<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class LoanSearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('loan.searchhistory');
    }

    public function search(Request $request)
    {
        $request->validate([
            'loan_app_no' => 'required|string|max:255'
        ]);

        $loanAppNo = $request->input('loan_app_no');
        
        // Get loan data
        $loanData = $this->getLoanData($loanAppNo);
        
        // Get detail data with file processing
        $detailData = $this->getDetailData($loanAppNo);

        // Check if there are files available for ZIP download
        $hasFiles = $detailData->filter(function($detail) {
            return !is_null($detail->file_url);
        })->count() > 0;

        return view('loan.searchhistory', [
            'loan_app_no' => $loanAppNo,
            'loan_data' => $loanData,
            'detail_data' => $detailData,
            'has_files' => $hasFiles
        ]);
    }

    private function getLoanData($loanAppNo)
    {
        return DB::connection('mysql')->table('data_file')
            ->select([
                'modul', 
                'kode_cabang', 
                'loan_app_no', 
                'no_cif', 
                'no_ktp', 
                'nama_debitur', 
                'ttl', 
                'alamat_rumah', 
                'no_tlp_rumah', 
                'instansi', 
                'alamat_kantor', 
                'no_tlp_kantor', 
                'plafond', 
                'jangka_waktu', 
                'rate', 
                'angsuran', 
                'tanggal_jatuh_tempo', 
                'produk', 
                'user_input', 
                'branch_input', 
                'date_input'
            ])
            ->where('loan_app_no', $loanAppNo)
            ->get();
    }

    private function getDetailData($loanAppNo)
    {
        $details = DB::connection('mysql')->table('detail_file')
            ->select(['loan_app_no', 'file', 'branch_dir', 'alias'])
            ->where('loan_app_no', $loanAppNo)
            ->get();

        // Process each file for FTP download
        foreach ($details as $detail) {
            $detail->file_url = $this->downloadFromFtp($detail);
        }

        return $details;
    }

    private function downloadFromFtp($detail)
    {
        $ftpConfig = [
            'server' => '172.28.97.30',
            'port' => 21,
            'username' => 'irfan',
            'password' => 'bws@dipo28',
            'root_dir' => '/DMS_Backup'
        ];

        // Determine remote file path
        if (strpos($detail->file, '/') !== false) {
            $remoteFilePath = $ftpConfig['root_dir'] . $detail->file;
        } else {
            $remoteFilePath = $ftpConfig['root_dir'] . '/' . $detail->branch_dir . '/' . $detail->file;
        }

        // Create temp directory if it doesn't exist
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $fileName = basename($detail->file);
        $localFilePath = $tempDir . '/' . $fileName;

        try {
            // Connect to FTP
            $ftpConn = ftp_connect($ftpConfig['server'], $ftpConfig['port']);
            if (!$ftpConn) {
                throw new \Exception('Cannot connect to FTP server');
            }

            $login = ftp_login($ftpConn, $ftpConfig['username'], $ftpConfig['password']);
            if (!$login) {
                throw new \Exception('FTP login failed');
            }

            // Download file
            if (ftp_get($ftpConn, $localFilePath, $remoteFilePath, FTP_BINARY)) {
                // Generate URL for downloaded file
                $fileUrl = route('loan.download-history', ['filename' => $fileName]);
                ftp_close($ftpConn);
                return $fileUrl;
            } else {
                ftp_close($ftpConn);
                throw new \Exception('Failed to download file');
            }

        } catch (\Exception $e) {
            if (isset($ftpConn)) {
                ftp_close($ftpConn);
            }
            return null;
        }
    }

    public function downloadFile($filename)
    {
        $filePath = storage_path('app/temp/' . $filename);
        
        if (file_exists($filePath)) {
            return response()->download($filePath);
        }
        
        abort(404, 'File not found');
    }

    public function downloadAllFiles(Request $request)
    {
        $request->validate([
            'loan_app_no' => 'required|string|max:255'
        ]);

        $loanAppNo = $request->input('loan_app_no');
        
        // Get detail data
        $detailData = $this->getDetailData($loanAppNo);
        
        // Filter only files that were successfully downloaded
        $availableFiles = $detailData->filter(function($detail) {
            return !is_null($detail->file_url);
        });

        if ($availableFiles->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada file yang tersedia untuk diunduh.');
        }

        // Create ZIP file
        $zipFileName = "loan_files_{$loanAppNo}_" . date('Y-m-d_H-i-s') . '.zip';
        $zipFilePath = storage_path('app/temp/' . $zipFileName);
        
        $zip = new ZipArchive;
        
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ($availableFiles as $detail) {
                $tempDir = storage_path('app/temp');
                $fileName = basename($detail->file);
                $localFilePath = $tempDir . '/' . $fileName;
                
                // Check if file exists in temp directory
                if (file_exists($localFilePath)) {
                    // Add file to ZIP with original filename
                    $zip->addFile($localFilePath, $detail->file);
                }
            }
            
            $zip->close();
            
            // Download the ZIP file
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
            
        } else {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP.');
        }
    }

    private function downloadAllFilesFromFtp($loanAppNo)
    {
        $details = DB::connection('dms')->table('detail_file')
            ->select(['loan_app_no', 'file', 'branch_dir', 'alias'])
            ->where('loan_app_no', $loanAppNo)
            ->get();

        $ftpConfig = [
            'server' => '172.28.97.30',
            'port' => 21,
            'username' => 'irfan',
            'password' => 'bws@dipo28',
            'root_dir' => '/DMS_Backup'
        ];

        $downloadedFiles = [];
        $tempDir = storage_path('app/temp');
        
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        try {
            // Connect to FTP once for all downloads
            $ftpConn = ftp_connect($ftpConfig['server'], $ftpConfig['port']);
            if (!$ftpConn) {
                throw new \Exception('Cannot connect to FTP server');
            }

            $login = ftp_login($ftpConn, $ftpConfig['username'], $ftpConfig['password']);
            if (!$login) {
                throw new \Exception('FTP login failed');
            }

            foreach ($details as $detail) {
                // Determine remote file path
                if (strpos($detail->file, '/') !== false) {
                    $remoteFilePath = $ftpConfig['root_dir'] . $detail->file;
                } else {
                    $remoteFilePath = $ftpConfig['root_dir'] . '/' . $detail->branch_dir . '/' . $detail->file;
                }

                $fileName = basename($detail->file);
                $localFilePath = $tempDir . '/' . $fileName;

                // Download file
                if (ftp_get($ftpConn, $localFilePath, $remoteFilePath, FTP_BINARY)) {
                    $downloadedFiles[] = [
                        'local_path' => $localFilePath,
                        'original_name' => $detail->file,
                        'detail' => $detail
                    ];
                }
            }

            ftp_close($ftpConn);
            
        } catch (\Exception $e) {
            if (isset($ftpConn)) {
                ftp_close($ftpConn);
            }
        }

        return $downloadedFiles;
    }
}