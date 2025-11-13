<?php

namespace App\Http\Controllers;

use App\Models\DataFileModel;
use App\Models\DetailFileModel;
use App\Models\DokumenEarly;
use App\Models\MasterProduct;
use App\Models\JenisProduct;
use App\Models\MapProduct;
use App\Models\FlagSpv;
use App\Models\Reason;
use App\Models\Comment;
use App\Models\SlaBpr;
use App\Models\Registration;
use App\Models\Branchlist;
use App\Models\LogRequest;
use App\Models\SettingAi;
use App\Models\Dokumen;
use App\Models\DokumenKategori;
use App\Models\SubDokumen;
use App\Models\MasterInstansi;
use App\Services\LoanApiService;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use File;
use DB;
use DateTime;
use App\Exports\Sla;
use App\Exports\SlaNew;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;
use GuzzleHttp\Client;

class DataFilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function send_documents_batch()
    {
        // Array ID yang akan diproses
        $ids = [
            'ID025011800',
            'ID025011836',
            'ID025011632',
            'ID025011779',
            'ID025011570',
            'ID025011650',
            'ID025011551',
            'ID025011589',
            'ID025011561',
            'ID025011433',
        ];
    
        $results = [];
        $successCount = 0;
        $failCount = 0;
    
        // Loop melalui setiap ID
        foreach ($ids as $id) {
            $result = $this->send_document_single($id);
            
            // Jika berhasil
            if (isset($result['status_code']) && ($result['status_code'] == 200 || $result['status_code'] == 201)) {
                $successCount++;
            } else {
                $failCount++;
            }
            
            $results[$id] = $result;
        }
    
        return response()->json([
            'message' => 'Batch processing completed',
            'total' => count($ids),
            'success' => $successCount,
            'failed' => $failCount,
            'results' => $results
        ]);
    }
    
    /**
     * Mengirimkan dokumen untuk satu ID
     * 
     * @param string $id ID loan application
     * @return array Hasil pengiriman
     */
    private function send_document_single($id)
    {
        $setting_ai = SettingAi::findOrFail(1);
        
        if (!$setting_ai) {
            return [
                'status_code' => 200,
                'message' => 'AI Validator Disable',
                'response' => ''
            ];
        }
        
        $sql = "SELECT
                data_file.loan_app_no AS loan_id, 
                data_file.nama_debitur AS `name`, 
                COALESCE(s_dokumen.id, s_sub_dokumen.id) AS document_code,
                detail_file.alias AS document_name, 
                CONCAT('https://dms.bankwoorisaudara.com/indexed', detail_file.file) AS path
            FROM
                data_file
                INNER JOIN detail_file ON data_file.loan_app_no = detail_file.loan_app_no
                left JOIN s_dokumen ON detail_file.alias = s_dokumen.nama_dokumen
                left JOIN s_sub_dokumen ON detail_file.alias = s_sub_dokumen.nama_sub_dokumen
            WHERE
                data_file.loan_app_no = ?
                AND detail_file.alias IN (
                    '[Salinan] KTP Debitur / Calon Debitur',
                    '[Salinan] Kartu Keluarga',
                    'E Form Fronting Agent',
                    'Hasil SLIK Checking atas nama Calon Debitur',
                    'Memorandum Credit Committee (MCC)',
                    'Memo Permohonan Pelaksanaan Take Over',
                    '[Salinan] Mutasi Rekening Debitur/Calon Debitur',
                    'Perjanjian Kredit',
                    'Surat Keterangan Usaha (SKU) / Legalitas Usaha',
                    '[Asli] Surat Keputusan Pensiun',
                    'Slip/Ledger Gaji',
                    'Surat Kuasa Pemasangan Flagging',
                    '[Asli] Surat Keputusan Pegawai (Swasta)'
                )";
    
        $data = DB::select($sql, [$id]);
    
        // Jika tidak ada data, kembalikan error
        if (count($data) == 0) {
            $errorResult = [
                'status_code' => 404,
                'message' => 'Data not found',
                'id' => $id
            ];
            
            // Log error
            $datarequest = new LogRequest;
            $datarequest->loan_app_no = $id;
            $datarequest->request = json_encode(['error' => 'Data not found']);
            $datarequest->request_date = date('Y-m-d H:i:s');
            $datarequest->response = json_encode(['error' => 'Data not found']);
            $datarequest->response_code = '404';
            $datarequest->save();
            
            return $errorResult;
        }
    
        // Format data sesuai kebutuhan API
        $formattedData = [
            'loan_id' => $data[0]->loan_id,
            'name' => $data[0]->name,
            'document_path' => array_map(function ($item) {
                return [
                    'document_code' => $item->document_code,
                    'document_name' => $item->document_name,
                    'path' => $item->path
                ];
            }, $data)
        ];
    
        // Kirim data ke API menggunakan Guzzle
        $client = new Client();
    
        try {
            $response = $client->post('http://172.28.100.136/save_documents', [
                'headers' => [
                    'x-api-key' => 'bozOkuNQqgJyHP1ZzP4z6zuD0',
                    'Content-Type' => 'application/json'
                ],
                'json' => $formattedData
            ]);
            
            // Log request dan response
            $datarequest = new LogRequest;
            $datarequest->loan_app_no = $id;
            $datarequest->request = json_encode($formattedData);
            $datarequest->request_date = date('Y-m-d H:i:s');
            $datarequest->response = json_encode(json_decode($response->getBody()));
            $datarequest->response_code = $response->getStatusCode();
            $datarequest->save();
            
            return [
                'status_code' => $response->getStatusCode(),
                'message' => 'Data sent successfully',
                'response' => json_decode($response->getBody()),
                'id' => $id
            ];
        } catch (\Exception $e) {
            // Log error
            $datarequest = new LogRequest;
            $datarequest->loan_app_no = $id;
            $datarequest->request = json_encode($formattedData);
            $datarequest->request_date = date('Y-m-d H:i:s');
            $datarequest->response = json_encode($e->getMessage());
            $datarequest->response_code = '500';
            $datarequest->save();
            
            return [
                'status_code' => 500,
                'message' => 'Error sending data',
                'error' => $e->getMessage(),
                'id' => $id
            ];
        }
    }

    public function send_document($id)
    {
        $setting_ai = SettingAi::findOrFail(1);
        //$setting_ai=true;
        if ($setting_ai) {
            $sql = "SELECT
                data_file.loan_app_no AS loan_id, 
                data_file.nama_debitur AS `name`, 
                COALESCE(s_dokumen.id, s_sub_dokumen.id) AS document_code,
                detail_file.alias AS document_name, 
                CONCAT('https://dms.bankwoorisaudara.com/indexed', detail_file.file) AS path
            FROM
                data_file
                INNER JOIN detail_file ON data_file.loan_app_no = detail_file.loan_app_no
                left JOIN s_dokumen ON detail_file.alias = s_dokumen.nama_dokumen
                left JOIN s_sub_dokumen ON detail_file.alias = s_sub_dokumen.nama_sub_dokumen
            WHERE
                data_file.loan_app_no = ?
                AND detail_file.alias IN (
                    '[Salinan] KTP Debitur / Calon Debitur',
                    '[Salinan] Kartu Keluarga',
                    'E Form Fronting Agent',
                    'Hasil SLIK Checking atas nama Calon Debitur',
                    'Memorandum Credit Committee (MCC)',
                    'Memo Permohonan Pelaksanaan Take Over',
                    '[Salinan] Mutasi Rekening Debitur/Calon Debitur',
                    'Perjanjian Kredit',
                    'Surat Keterangan Usaha (SKU) / Legalitas Usaha',
                    '[Asli] Surat Keputusan Pensiun',
                    'Slip/Ledger Gaji',
                    'Surat Kuasa Pemasangan Flagging',
                    '[Asli] Surat Keputusan Pegawai (Swasta)'
                )";
            //detail_file.alias IN ('Copy KTP', 'KK', 'MCC','SK Pensiun','Perjanjian Kredit yang telah ditandatangani oleh pihak Bank dan Nasabah')";

            $data = DB::select($sql, [$id]);

            // Format data sesuai dengan kebutuhan API
            if (count($data) > 0) {
                $formattedData = [
                    'loan_id' => $data[0]->loan_id,
                    'name' => $data[0]->name,
                    'document_path' => array_map(function ($item) {
                        return [
                            'document_code' => $item->document_code,
                            'document_name' => $item->document_name,
                            'path' => $item->path
                        ];
                    }, $data)
                ];
            } else {
                return response()->json(['error' => 'Data not found'], 404);
            }

            // Kirim data ke API menggunakan Guzzle
            $client = new Client();


            //dd(json_encode($formattedData));


            try {
                $response = $client->post('http://172.28.100.136/save_documents', [
                    'headers' => [
                        'x-api-key' => 'bozOkuNQqgJyHP1ZzP4z6zuD0',
                        'Content-Type' => 'application/json'
                    ],
                    'json' => $formattedData
                ]);
                $datarequest = new LogRequest;
                $datarequest->loan_app_no = $id;
                $datarequest->request = json_encode($formattedData); // Ensure this is a string
                $datarequest->request_date = date('Y-m-d h:i:s');
                $datarequest->response = json_encode(json_decode($response->getBody())); // Convert to string
                $datarequest->response_code = $response->getStatusCode(); // This should already be an integer or string
                $datarequest->save();
                //return response()->json(['message' => 'Data sent successfully', 'response' => json_decode($response->getBody())], $response->getStatusCode());
                return true;
            } catch (\Exception $e) {
                $datarequest = new LogRequest;
                $datarequest->loan_app_no = $id;
                $datarequest->request = json_encode($formattedData); // Ensure this is a string
                $datarequest->request_date = date('Y-m-d h:i:s');
                $datarequest->response = json_encode($e->getMessage()); // Convert to string
                $datarequest->response_code = '500'; // This should already be an integer or string
                $datarequest->save();
                return true;
                //return response()->json(['error' => $e->getMessage()], 500);
            }

        } else {
            //return response()->json(['message' => 'AI Validator Disable', 'response' => ''], 200);
            return true;
        }

    }

    public function send_document_test($id)
    {
        //sendEmailNotifikasiTest($id, "Verify");
        $setting_ai = SettingAi::findOrFail(1);
        //$setting_ai=true;
        if ($setting_ai) {
            $sql = "SELECT
                data_file.loan_app_no AS loan_id, 
                data_file.nama_debitur AS `name`, 
                COALESCE(s_dokumen.id, s_sub_dokumen.id) AS document_code,
                detail_file.alias AS document_name, 
                CONCAT('https://dms.bankwoorisaudara.com/indexed', detail_file.file) AS path
            FROM
                data_file
                INNER JOIN detail_file ON data_file.loan_app_no = detail_file.loan_app_no
                left JOIN s_dokumen ON detail_file.alias = s_dokumen.nama_dokumen
                left JOIN s_sub_dokumen ON detail_file.alias = s_sub_dokumen.nama_sub_dokumen
            WHERE
                data_file.loan_app_no = ?
                AND detail_file.alias IN (
                    '[Salinan] KTP Debitur / Calon Debitur',
                    '[Salinan] Kartu Keluarga',
                    'E Form Fronting Agent',
                    'Hasil SLIK Checking atas nama Calon Debitur',
                    'Memorandum Credit Committee (MCC)',
                    'Memo Permohonan Pelaksanaan Take Over',
                    '[Salinan] Mutasi Rekening Debitur/Calon Debitur',
                    'Perjanjian Kredit',
                    'Surat Keterangan Usaha (SKU) / Legalitas Usaha',
                    '[Asli] Surat Keputusan Pensiun',
                    'Slip/Ledger Gaji',
                    'Surat Kuasa Pemasangan Flagging',
                    '[Asli] Surat Keputusan Pegawai (Swasta)'
                )";
            //detail_file.alias IN ('Copy KTP', 'KK', 'MCC','SK Pensiun','Perjanjian Kredit yang telah ditandatangani oleh pihak Bank dan Nasabah')";
            //dd($sql);
            $data = DB::select($sql, [$id]);

            // Format data sesuai dengan kebutuhan API
            if (count($data) > 0) {
                $formattedData = [
                    'loan_id' => $data[0]->loan_id,
                    'name' => $data[0]->name,
                    'document_path' => array_map(function ($item) {
                        return [
                            'document_code' => $item->document_code,
                            'document_name' => $item->document_name,
                            'path' => $item->path
                        ];
                    }, $data)
                ];
            } else {
                return response()->json(['error' => 'Data not found'], 404);
            }

            // Kirim data ke API menggunakan Guzzle
            $client = new Client();


            //dd(json_encode($formattedData));


            try {
                $response = $client->post('http://172.28.100.136/save_documents', [
                    'headers' => [
                        'x-api-key' => 'bozOkuNQqgJyHP1ZzP4z6zuD0',
                        'Content-Type' => 'application/json'
                    ],
                    'json' => $formattedData
                ]);
                $datarequest = new LogRequest;
                $datarequest->loan_app_no = $id;
                $datarequest->request = json_encode($formattedData); // Ensure this is a string
                $datarequest->request_date = date('Y-m-d h:i:s');
                $datarequest->response = json_encode(json_decode($response->getBody())); // Convert to string
                $datarequest->response_code = $response->getStatusCode(); // This should already be an integer or string
                $datarequest->save();
                return response()->json(['message' => 'Data sent successfully', 'response' => json_decode($response->getBody())], $response->getStatusCode());
                //return true;
            } catch (\Exception $e) {
                //return true;
                
                $datarequest = new LogRequest;
                $datarequest->loan_app_no = $id;
                $datarequest->request = json_encode($formattedData); // Ensure this is a string
                $datarequest->request_date = date('Y-m-d h:i:s');
                $datarequest->response = json_encode($e->getMessage()); // Convert to string
                $datarequest->response_code = '500'; // This should already be an integer or string
                $datarequest->save();
                
                return response()->json(['error' => $e->getMessage()], 500);
                //return response()->json(['message' => 'Data sent successfully', 'response' => json_decode($response->getBody())], $response->getStatusCode());
                
            }

        } else {
            return response()->json(['message' => 'AI Validator Disable', 'response' => ''], 200);
            //return true;
        }

    }

    public function queue_reviewer(Request $request)
    {
        $pagination = 10;
        $filter = true;
        $filterx = false;
        $produk_title = 'Queue Reviewer';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        $role = Session('role');
        $branch = Session('branch_code');

        // Get the AI setting
        $ai_enabled = DB::table('setting_ai')->value('enable');

        $common_conditions = function ($query) use ($ai_enabled) {
            $query->whereNotIn('data_file.loan_app_no', function ($subQuery) {
                $subQuery->select('loan_app_no')
                    ->from('list_pickup')
                    ->where('status', 0);
            })
                ->where(function ($dateQuery) {
                    $dateQuery->whereIn(DB::raw('date(data_file.date_input)'), [DB::raw('date(now())'), DB::raw('date_sub(date(now()), interval 1 day)')])
                        ->orWhere(function ($mondayQuery) {
                            $mondayQuery->where(DB::raw('DAYOFWEEK(NOW())'), 2)
                                ->where(DB::raw('DATE(data_file.date_input)'), DB::raw('DATE(SUBDATE(NOW(), 3))'))
                                ->where(DB::raw('DATE(data_file.date_flag_spv1)'), DB::raw('CURDATE()'));
                        });
                })
                ->where('data_file.final_status', '<>', 4);

            if ($ai_enabled == 1) {
                $query->whereExists(function ($existsQuery) {
                    $existsQuery->select(DB::raw(1))
                        ->from('detail_file as dtf')
                        ->whereRaw('dtf.loan_app_no = data_file.loan_app_no')
                        ->where(function ($aiQuery) {
                            $aiQuery->whereNotNull('dtf.score')
                                ->orWhere(DB::raw('TIMESTAMPDIFF(MINUTE, data_file.date_flag_spv1, NOW())'), '>', 5);
                        });
                });
            }
        };

        if ($role == 'staff' || $role == 'spv1' || $role == 'spv2') {
            $datafile = DataFileModel::query()
                ->join('branchlist AS br', 'data_file.kode_cabang', '=', 'br.branch_code')
                ->where(function ($query) {
                    $query->where('data_file.final_status_spv1', 1)
                        ->orWhere('data_file.final_status_spv1', 3);
                })
                ->where('data_file.final_status_spv2', 0)
                ->tap($common_conditions)
                ->where('data_file.date_flag_spv1', '>=', DB::raw('(CASE WHEN DAYOFWEEK(now()) = 2 THEN date_sub(date(now()), interval 3 day) ELSE date_sub(date(now()), interval 1 day) END)'))
                ->orderBy('data_file.take_over_hari_ini', 'desc')
                ->orderByRaw("FIELD(data_file.modul, 'KPH', 'KPKB-WNI', 'KPKB-WNA') DESC")
                ->orderByRaw("IF(FIELD(data_file.modul, 'KPKB-WNI', 'KPKB-WNA', 'KPH') = 0, data_file.date_flag_spv1, NULL)")
                ->orderBy('data_file.date_flag_spv1', 'asc')
                ->paginate($pagination);
        } else if ($role == 'spv3' || $role == 'spv4') {
            $datafile = DataFileModel::query()
                ->join('branchlist AS br', 'data_file.kode_cabang', '=', 'br.branch_code')
                ->where(function ($query) {
                    $query->where('data_file.final_status_spv2', 1)
                        ->orWhere('data_file.final_status_spv2', 3);
                })
                ->where('data_file.final_status_spv3', 0)
                ->tap($common_conditions)
                ->where('data_file.date_flag_spv2', '>=', DB::raw('(CASE WHEN DAYOFWEEK(now()) = 2 THEN date_sub(date(now()), interval 3 day) ELSE date_sub(date(now()), interval 1 day) END)'))
                ->orderBy('data_file.take_over_hari_ini', 'desc')
                ->orderByRaw("FIELD(data_file.modul, 'KPH', 'KPKB-WNI', 'KPKB-WNA') DESC")
                ->orderByRaw("IF(FIELD(data_file.modul, 'KPKB-WNI', 'KPKB-WNA', 'KPH') = 0, data_file.date_flag_spv2, NULL)")
                ->orderBy('data_file.date_flag_spv2', 'asc')
                ->paginate($pagination);
        } else {
            $datafile = collect(); // Empty collection if role doesn't match any condition
        }

        $intervalMinutes = 10; // Interval dalam menit
        $groupSize = 3; // Jumlah baris dalam satu grup
        $currentPage = $request->input('page', 1);
        $rowsPerPage = $pagination; // Jumlah baris per halaman
        $totalRows = $datafile->total(); // Jumlah total baris
        $waitingTimes = generateWaitingTimes($totalRows, $intervalMinutes, $groupSize, $currentPage, $pagination);

        return view('loan.queue_reviewer', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
            'waitingTimes' => $waitingTimes,
            'currentPage' => $currentPage,
            'rowsPerPage' => $pagination,
        ])->with('i', ($currentPage - 1) * $pagination);
    }
    public function queue_team_leader(Request $request)
    {
        $pagination = 10;
        $filter = true;
        $filterx = false;
        $produk_title = 'Queue DCRM';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        $role = Session('role');
        $branch = Session('branch_code');

        // Get the AI setting
        $ai_enabled = DB::table('setting_ai')->value('enable');

        $common_conditions = function ($query) use ($ai_enabled) {
            $query->whereNotIn('data_file.loan_app_no', function ($subQuery) {
                $subQuery->select('loan_app_no')
                    ->from('list_pickup')
                    ->where('status', 0);
            })
                ->where(function ($dateQuery) {
                    $dateQuery->whereIn(DB::raw('date(data_file.date_input)'), [DB::raw('date(now())'), DB::raw('date_sub(date(now()), interval 1 day)')])
                        ->orWhere(function ($mondayQuery) {
                            $mondayQuery->where(DB::raw('DAYOFWEEK(NOW())'), 2)
                                ->where(DB::raw('DATE(data_file.date_input)'), DB::raw('DATE(SUBDATE(NOW(), 3))'))
                                ->where(DB::raw('DATE(data_file.date_flag_spv2)'), DB::raw('CURDATE()'));
                        });
                })
                ->where('data_file.final_status', '<>', 4);

            if ($ai_enabled == 1) {
                $query->whereExists(function ($existsQuery) {
                    $existsQuery->select(DB::raw(1))
                        ->from('detail_file as dtf')
                        ->whereRaw('dtf.loan_app_no = data_file.loan_app_no')
                        ->where(function ($aiQuery) {
                            $aiQuery->whereNotNull('dtf.score')
                                ->orWhere(DB::raw('TIMESTAMPDIFF(MINUTE, data_file.date_flag_spv1, NOW())'), '>', 5);
                        });
                });
            }
        };

        if ($role == 'staff' || $role == 'spv1' || $role == 'spv2' || $role == 'pcp'  || $role == 'pc') {
            $datafile = DataFileModel::query()
                ->join('branchlist AS br', 'data_file.kode_cabang', '=', 'br.branch_code')
                ->where(function ($query) {
                    $query->where('data_file.final_status_spv1', 1)
                        ->orWhere('data_file.final_status_spv1', 3);
                })
                ->where('data_file.final_status_spv3', 0)
                ->tap($common_conditions)
                ->where('data_file.date_flag_spv1', '>=', DB::raw('(CASE WHEN DAYOFWEEK(now()) = 2 THEN date_sub(date(now()), interval 3 day) ELSE date_sub(date(now()), interval 1 day) END)'))
                ->orderBy('data_file.take_over_hari_ini', 'desc')
                ->orderByRaw("FIELD(data_file.modul, 'KPH', 'KPKB-WNI', 'KPKB-WNA') DESC")
                ->orderByRaw("IF(FIELD(data_file.modul, 'KPKB-WNI', 'KPKB-WNA', 'KPH') = 0, data_file.date_flag_spv2, NULL)")
                ->orderBy('data_file.date_flag_spv2', 'asc')
                ->paginate($pagination);
        } else if ($role == 'spv3' || $role == 'spv4') {
            $datafile = DataFileModel::query()
                ->join('branchlist AS br', 'data_file.kode_cabang', '=', 'br.branch_code')
                ->where(function ($query) {
                    $query->where('data_file.final_status_spv1', 1)
                        ->orWhere('data_file.final_status_spv1', 3);
                })
                ->where('data_file.final_status_spv3', 0)
                ->tap($common_conditions)
                ->where('data_file.date_flag_spv1', '>=', DB::raw('(CASE WHEN DAYOFWEEK(now()) = 2 THEN date_sub(date(now()), interval 3 day) ELSE date_sub(date(now()), interval 1 day) END)'))
                ->orderBy('data_file.take_over_hari_ini', 'desc')
                ->orderByRaw("FIELD(data_file.modul, 'KPH', 'KPKB-WNI', 'KPKB-WNA') DESC")
                ->orderByRaw("IF(FIELD(data_file.modul, 'KPKB-WNI', 'KPKB-WNA', 'KPH') = 0, data_file.date_flag_spv2, NULL)")
                ->orderBy('data_file.date_flag_spv2', 'asc')
                ->paginate($pagination);
        } else {
            $datafile = collect(); // Empty collection if role doesn't match any condition
        }

        $intervalMinutes = 10; // Interval dalam menit
        $groupSize = 7; // Jumlah baris dalam satu grup
        $currentPage = $request->input('page', 1);
        $rowsPerPage = $pagination; // Jumlah baris per halaman
        //dd($datafile);
        $totalRows = $datafile->total(); // Jumlah total baris
        $waitingTimes = generateWaitingTimes($totalRows, $intervalMinutes, $groupSize, $currentPage, $pagination);

        return view('loan.queue_reviewer', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
            'waitingTimes' => $waitingTimes,
            'currentPage' => $currentPage,
            'rowsPerPage' => $pagination,
        ])->with('i', ($currentPage - 1) * $pagination);
    }

    public function pickup_count()
    {
        // Get the AI setting
        $ai_enabled = DB::table('setting_ai')->value('enable');

        $common_conditions = "
            AND df.loan_app_no NOT IN (SELECT loan_app_no FROM list_pickup WHERE status = 0)
            AND (
                -- Data hari ini dan kemarin
                (DATE(df.date_input) IN (CURDATE(), CURDATE() - INTERVAL 1 DAY))
                OR
                -- Data Jumat jika hari ini Senin
                (
                    DAYOFWEEK(NOW()) = 2 -- Hari Senin
                    AND DATE(df.date_input) = DATE(SUBDATE(NOW(), 3)) -- Jumat
                    AND DATE(df.date_flag_spv1) = CURDATE()
                )
                AND df.final_status <> 4
            )
        ";

        if ($ai_enabled == 1) {
            $common_conditions .= "
                AND EXISTS (
                    SELECT 1 FROM detail_file dtf
                    WHERE dtf.loan_app_no = df.loan_app_no
                    AND (dtf.score IS NOT NULL OR TIMESTAMPDIFF(MINUTE, df.date_flag_spv1, NOW()) > 5)
                )
            ";
        }



        if (Session('role') == 'spv2' || Session('role') == 'spv3' || Session('role') == 'spv4') {
            $sql = "SELECT COUNT(DISTINCT df.loan_app_no) as jml
                FROM data_file df
                INNER JOIN branchlist AS br ON df.kode_cabang = br.branch_code
                WHERE (df.final_status_spv1 = 1 OR df.final_status_spv1 = 3)
                    AND df.final_status_spv2 = 0
                    $common_conditions";
        } else {
            return response()->json(['total' => 0]);
        }
        //dd($sql);
        $result = DB::select($sql);
        $total = $result[0]->jml ?? 0;

        return response()->json(['total' => $total]);
    }

    public function pickup(Request $request)
    {
        if (!empty($request->nik) || !empty($request->role)) {
            $nik = $request->nik;
            $role = $request->role;
            $loan_no_result = null;
            DB::statement('CALL insert_pickup_new(?, ?, @loan_no_result)', [$nik, $role]);
            $loan_no_result = DB::select('SELECT @loan_no_result as loan_no')[0]->loan_no;
            return redirect('/show-new/' . $loan_no_result);
        }

        // Get the AI setting
        $ai_enabled = DB::table('setting_ai')->value('enable');

        $common_conditions = "
            AND df.loan_app_no NOT IN (SELECT loan_app_no FROM list_pickup WHERE status = 0)
            AND (
                -- Data hari ini dan kemarin
                (DATE(df.date_input) IN (CURDATE(), CURDATE() - INTERVAL 1 DAY))
                OR
                -- Data Jumat jika hari ini Senin
                (
                    DAYOFWEEK(NOW()) = 2 -- Hari Senin
                    AND DATE(df.date_input) = DATE(SUBDATE(NOW(), 3)) -- Jumat
                    AND DATE(df.date_flag_spv1) = CURDATE()
                )
                AND df.final_status <> 4
            )
        ";

        if ($ai_enabled == 1) {
            $common_conditions .= "
                AND EXISTS (
                    SELECT 1 FROM detail_file dtf
                    WHERE dtf.loan_app_no = df.loan_app_no
                    AND (dtf.score IS NOT NULL OR TIMESTAMPDIFF(MINUTE,  df.date_flag_spv1, NOW()) > 5)
                )
            ";
        }

        // if (Session('role') == 'spv2') {
        //     $sql = "SELECT COUNT(DISTINCT df.loan_app_no) as jml
        //         FROM data_file df
        //         INNER JOIN branchlist AS br ON df.kode_cabang = br.branch_code
        //         WHERE (df.final_status_spv1 = 1 OR df.final_status_spv1 = 3)
        //             AND df.final_status_spv2 = 0
        //             $common_conditions";
        // } else if (Session('role') == 'spv3' || Session('role') == 'spv4') {
        //     $sql = "SELECT COUNT(DISTINCT df.loan_app_no) as jml
        //         FROM data_file df
        //         INNER JOIN branchlist AS br ON df.kode_cabang = br.branch_code
        //         WHERE (df.final_status_spv2 = 1 OR df.final_status_spv2 = 3)
        //             AND df.final_status_spv3 = 0
        //             $common_conditions";
        // } else {
        //     return view('loan.pickup', ['total' => 0]);
        // }
        if (Session('role') == 'spv2' || Session('role') == 'spv3' || Session('role') == 'spv4') {
            $sql = "SELECT COUNT(DISTINCT df.loan_app_no) as jml
                FROM data_file df
                INNER JOIN branchlist AS br ON df.kode_cabang = br.branch_code
                WHERE (df.final_status_spv1 = 1 OR df.final_status_spv1 = 3)
                    AND df.final_status_spv2 = 0
                    $common_conditions";
        } else {
            return view('loan.pickup', ['total' => 0]);
        }
        //dd($sql);
        $result = DB::select($sql);
        $total = $result[0]->jml ?? 0;

        return view('loan.pickup', [
            'total' => $total,
        ]);
    }

    public function list_pickup(Request $request)
    {
        $pagination = 10;
        $filter = true;
        $filterx = false;
        $produk_title = 'List Pickup';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        $role = Session('role');
        $branch = Session('branch_code');

        // Get the AI setting
        $ai_enabled = DB::table('setting_ai')->value('enable');

        $common_conditions = function ($query) use ($ai_enabled, $request, $filterx) {
            $query->whereNotIn('data_file.loan_app_no', function ($subQuery) {
                $subQuery->select('loan_app_no')
                    ->from('list_pickup')
                    ->where('status', 0);
            })
                ->where(function ($dateQuery) {
                    $dateQuery->whereIn(DB::raw('date(data_file.date_input)'), [DB::raw('date(now())'), DB::raw('date_sub(date(now()), interval 1 day)')])
                        ->orWhere(function ($mondayQuery) {
                            $mondayQuery->where(DB::raw('DAYOFWEEK(NOW())'), 2)
                                ->where(DB::raw('DATE(data_file.date_input)'), DB::raw('DATE(SUBDATE(NOW(), 3))'))
                                ->where(DB::raw('DATE(data_file.date_flag_spv1)'), DB::raw('CURDATE()'));
                        });
                })
                ->where('data_file.final_status', '<>', 4);

            if ($ai_enabled == 1) {
                $query->whereExists(function ($existsQuery) {
                    $existsQuery->select(DB::raw(1))
                        ->from('detail_file as dtf')
                        ->whereRaw('dtf.loan_app_no = data_file.loan_app_no')
                        ->where(function ($aiQuery) {
                            $aiQuery->whereNotNull('dtf.score')
                                ->orWhere(DB::raw('TIMESTAMPDIFF(MINUTE,  data_file.date_flag_spv1, NOW())'), '>', 5);
                        });
                });
            }

            if ($filterx) {
                $query->where(function ($q) use ($request) {
                    if (!empty($request->keyword_loan)) {
                        $q->where('data_file.loan_app_no', 'like', "%{$request->keyword_loan}%");
                    }
                    if (!empty($request->keyword_branch)) {
                        $q->orWhere('data_file.kode_cabang', 'like', "%{$request->keyword_branch}%");
                    }
                    if (!empty($request->keyword_cif)) {
                        $q->orWhere('data_file.no_cif', 'like', "%{$request->keyword_cif}%");
                    }
                    if (!empty($request->keyword_ktp)) {
                        $q->orWhere('data_file.no_ktp', 'like', "%{$request->keyword_ktp}%");
                    }
                    if (!empty($request->keyword_name)) {
                        $q->orWhere('data_file.nama_debitur', 'like', "%{$request->keyword_name}%");
                    }
                });
            }
        };

        $query = DataFileModel::query()
            ->join('branchlist AS br', 'data_file.kode_cabang', '=', 'br.branch_code');

        if ($role == 'spv2' || $role == 'spv3' || $role == 'spv4') {
            $query->where(function ($q) {
                $q->where('data_file.final_status_spv1', 1)
                    ->orWhere('data_file.final_status_spv1', 3);
            })
                ->where('data_file.final_status_spv2', 0)
                ->where('data_file.date_flag_spv1', '>=', DB::raw('(CASE WHEN DAYOFWEEK(now()) = 2 THEN date_sub(date(now()), interval 3 day) ELSE date_sub(date(now()), interval 1 day) END)'));
            // } else if ($role == 'spv3' || $role == 'spv4') {
            //     $query->where(function ($q) {
            //         $q->where('data_file.final_status_spv2', 1)
            //           ->orWhere('data_file.final_status_spv2', 3);
            //     })
            //     ->where('data_file.final_status_spv3', 0)
            //     ->where('data_file.date_flag_spv2', '>=', DB::raw('(CASE WHEN DAYOFWEEK(now()) = 2 THEN date_sub(date(now()), interval 3 day) ELSE date_sub(date(now()), interval 1 day) END)'));
            // } 
        } else {
            return view('loan.list-pickup', [
                'datafile' => collect(),
                'produk_title' => $produk_title,
            ])->with('i', 0);
        }

        $query->tap($common_conditions)
            ->orderBy('data_file.take_over_hari_ini', 'desc')
            ->orderByRaw("FIELD(data_file.modul, 'KPH', 'KPKB-WNI', 'KPKB-WNA') DESC")
            ->orderByRaw("IF(FIELD(data_file.modul, 'KPKB-WNI', 'KPKB-WNA', 'KPH') = 0, data_file.date_flag_spv1, NULL)")
            ->orderBy('data_file.date_flag_spv1', 'asc');

        $datafile = $query->paginate($pagination);
        $datafile->appends($request->only(['keyword_loan', 'keyword_branch', 'keyword_cif', 'keyword_ktp', 'keyword_name', 'action']));

        // For debugging
        //dd($query->toSql(), $query->getBindings(), $datafile);

        return view('loan.list-pickup', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);
    }
    public function show_pickup($id)
    {

        $data = DataFileModel::where('loan_app_no', '=', $id)->first();
        $log_sla = SlaBpr::where('loan_app_no', '=', $id)->where('isLocked', '=', 1)->first();


        $datafile = DataFileModel::findOrFail($id);
        $detailfile = DetailFileModel::where('loan_app_no', $id)->get();
        $products = MasterProduct::where('produk', $datafile->produk)->first();

        //$map_product = MapProduct::where('id_jenis_produk', $products->jenis_produk)->get();
        if($data->fasilitas == 3){
            $map_product = MapProduct::where('id_jenis_produk', 9)->get();
        }else{
            //$map_product = MapProduct::where('id_jenis_produk', $products->jenis_produk)->get();
            if($data->modul=="TAWON"){
                $map_product = MapProduct::where('id_jenis_produk', 10)->get();
            }else{
                $map_product = MapProduct::where('id_jenis_produk', $products->jenis_produk)->get();
            }
        }

        $flag_spv = FlagSpv::All();

        $comment = Comment::where('loan_app_no', $id)->sortable()->get();

        //dd($datafile);


        return view('loan.show-pickup', compact('datafile', 'detailfile', 'products', 'map_product', 'flag_spv', 'comment'));
    }

    public function show_pickup_new($id)
    {

        $data = DataFileModel::where('loan_app_no', '=', $id)->first();
        $log_sla = SlaBpr::where('loan_app_no', '=', $id)->where('isLocked', '=', 1)->first();


        $datafile = DataFileModel::findOrFail($id);
        $detailfile = DetailFileModel::where('loan_app_no', $id)->get();
        $products = MasterProduct::where('produk', $datafile->produk)->first();
        $kategori = DokumenKategori::with(['dokumen.subDokumen'])->get();
        $map_product = MapProduct::where('id_jenis_produk', $products->jenis_produk)->get();

        $flag_spv = FlagSpv::All();

        $comment = Comment::where('loan_app_no', $id)->sortable()->get();

        //dd($datafile);


        return view('loan.show-pickup-new', compact('kategori', 'datafile', 'detailfile', 'products', 'map_product', 'flag_spv', 'comment'));
    }

    public function downloadZip($id)
    {
        $zip = new ZipArchive;

        $fileName = $id . '_' . date('Ymd') . '.zip';

        $sql = "Select file from detail_file where loan_app_no = '" . $id . "'";
        $dokumen = DB::select($sql);

        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {

            // $files = File::files(public_path('myFiles'));

            foreach ($dokumen as $key => $value) {
                $relativeNameInZipFile = basename($value->file);
                $file = public_path('indexed' . $value->file);
                //echo $file."<br>";
                // $relativeNameInZipFile."<br>";
                $zip->addFile($file, $relativeNameInZipFile);
            }

            $zip->close();
        }

        return response()->download(public_path($fileName));
    }

    public function daily_transaction(Request $request)
    {

        $pagination = 10;
        $filter = false;
        $filterx = false;
        $produk_title = '';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4') {
            $filter = true;
        }

        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                $query
                    ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                    ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                    ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                    ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                    ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
            })->when($filter, function ($query) use ($request) {
                $role = Session('role');
                if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                    $field_status = 'final_status_spv1';
                    $query->where($field_status, '=', $request->action);
                } else if ($role == 'spv2') {
                    $field_status = 'final_status_spv2';
                    $query->where($field_status, '=', $request->action);
                } else if ($role == 'spv3') {
                    $field_status = 'final_status_spv3';
                    $query->where($field_status, '=', $request->action);
                }

            })->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));
        } else {

            $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                $query
                    ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                    ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                    ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                    ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                    ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
            })->when($filter, function ($query) use ($request) {
                $role = Session('role');
                if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                    $field_status = 'final_status_spv1';
                    $query->where($field_status, '=', $request->action);
                } else if ($role == 'spv2') {
                    $field_status = 'final_status_spv2';
                    $query->where('final_status_spv1', '<>', 0);
                    $query->where($field_status, '=', $request->action);

                } else if ($role == 'spv3' || $role == 'spv4') {
                    $field_status = 'final_status_spv3';
                    $query->where('final_status_spv2', '<>', 0);
                    $query->where($field_status, '=', $request->action);
                }

            })->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))->orderBy('date_input', 'desc')->paginate($pagination);

            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));
        }

        //



        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function daily_spv1(Request $request)
    {


        $pagination = 10;
        $filter = false;
        $filterx = false;
        $produk_title = '';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4') {
            $filter = true;
        }

        $role = Session('role');
        $branch = Session('branch_code');
        // dd($role);
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {

            $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                $query
                    ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                    ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                    ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                    ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                    ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
            })->when($filter, function ($query) use ($request) {
                $role = Session('role');
                /*if($role=='spv1' || $role=='pc' || $role=='pcp'){
                    $field_status='final_status_spv1';
                    $query->where($field_status, '=', $request->action);
                }else if($role=='spv2'){
                    $field_status='final_status_spv2';
                    $query->where($field_status, '=', $request->action);
                }else if($role=='spv3'){
                    $field_status='final_status_spv3';
                    $query->where($field_status, '=', $request->action);
                }*/


            })->where(function ($query) {
                $query->where('final_status_spv1', '=', 0)
                    ->orWhere('final_status_spv1', '=', 2);
            })->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));


        } else {

            $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                $query
                    ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                    ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                    ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                    ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                    ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
            })->when($filter, function ($query) use ($request) {
                $role = Session('role');
                if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                    $field_status = 'final_status_spv1';
                    $query->where($field_status, '=', $request->action);
                } else if ($role == 'spv2') {
                    $field_status = 'final_status_spv2';
                    $query->where('final_status_spv1', '<>', 0);
                    $query->where($field_status, '=', $request->action);

                } else if ($role == 'spv3' || $role == 'spv4') {
                    $field_status = 'final_status_spv3';
                    $query->where('final_status_spv2', '<>', 0);
                    $query->where($field_status, '=', $request->action);
                }

            })->where(function ($query) {
                $query->where('final_status_spv1', '=', 0)
                    ->orWhere('final_status_spv1', '=', 2);
            })->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))->orderBy('date_input', 'desc')->paginate($pagination);

            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));
        }

        //



        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function daily_bpr1(Request $request)
    {

        $pagination = 10;
        $filter = false;
        $filterx = false;
        $produk_title = '';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4') {
            $filter = true;
        }

        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                $query
                    ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                    ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                    ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                    ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                    ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
            })->when($filter, function ($query) use ($request) {
                $role = Session('role');
                if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                    $field_status = 'final_status_spv1';
                    $query->where($field_status, '=', $request->action);
                } else if ($role == 'spv2') {
                    $field_status = 'final_status_spv2';
                    $query->where($field_status, '=', $request->action);
                } else if ($role == 'spv3') {
                    $field_status = 'final_status_spv3';
                    $query->where($field_status, '=', $request->action);
                }

            })
                ->where('data_file.final_status_spv1', '<>', 0)
                ->where('data_file.final_status_spv2', '=', 0)
                ->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))
                ->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));
        } else {

            $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                $query
                    ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                    ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                    ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                    ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                    ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
            })->when($filter, function ($query) use ($request) {
                $role = Session('role');
                if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                    $field_status = 'final_status_spv1';
                    $query->where($field_status, '=', $request->action);
                } else if ($role == 'spv2') {
                    $field_status = 'final_status_spv2';
                    $query->where('final_status_spv1', '<>', 0);
                    $query->where($field_status, '=', $request->action);

                } else if ($role == 'spv3' || $role == 'spv4') {
                    $field_status = 'final_status_spv3';
                    $query->where('final_status_spv2', '<>', 0);
                    $query->where($field_status, '=', $request->action);
                }

            })
                ->leftJoin('registration', 'data_file.loan_app_no', '=', 'registration.loan_app_no')
                ->where('data_file.final_status_spv1', '<>', 0)
                ->where('data_file.final_status_spv2', '=', 0)
                ->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))
                ->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))
                ->select('data_file.*', 'registration.no_register') // Pilih kolom lain yang Anda inginkan dari registration
                ->selectRaw('MAX(CAST(SUBSTRING_INDEX(registration.no_register, \'-\', -1) AS UNSIGNED)) as max_register_number')
                ->groupBy('data_file.loan_app_no')
                ->orderByRaw('max_register_number ASC')
                ->paginate($pagination);

            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));
        }

        //



        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }
/*
    public function daily_bpr2(Request $request)
    {

        $pagination = 10;
        $filter = false;
        $filterx = false;
        $produk_title = '';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4') {
            $filter = true;
        }

        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                $query
                    ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                    ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                    ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                    ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                    ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
            })->when($filter, function ($query) use ($request) {
                $role = Session('role');
               
            })
                ->where(function ($query) {
                    $query->where('data_file.final_status_spv2', '=', 5)
                        ->orWhere('data_file.final_status_spv2', '=', 5);
                })
                ->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))
                ->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));
        } else {

         
            $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                $query
                    ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                    ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                    ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                    ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                    ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
            })->when($filter, function ($query) use ($request) {
                $query->where(function ($query) {
                    $query->where('data_file.final_status_spv1', '=', 1)
                        ->orWhere('data_file.final_status_spv1', '=', 3);
                });
            })
                ->where(function ($query) {
                    $query->where('data_file.final_status_spv1', '=', 1)
                        ->orWhere('data_file.final_status_spv1', '=', 3);
                })
                ->leftJoin('registration', 'data_file.loan_app_no', '=', 'registration.loan_app_no')
                ->where('data_file.final_status_spv3', '=', 0)
                ->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))
                ->select('data_file.*', 'registration.no_register') // Pilih kolom lain yang Anda inginkan dari registration
                ->selectRaw('MAX(CAST(SUBSTRING_INDEX(registration.no_register, \'-\', -1) AS UNSIGNED)) as max_register_number')
                ->groupBy('data_file.loan_app_no')
                ->orderByRaw('max_register_number ASC')
                ->paginate($pagination);

            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));

        }



        //



        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }
*/

public function daily_bpr2(Request $request)
{
    // Get the AI setting
    $ai_enabled = DB::table('setting_ai')->value('enable');
    $role = Session('role');
    $branch = Session('branch_code');
    $pagination = 10;
    $produk_title = '';
    $filterx = !empty($request->keyword_loan) || 
               !empty($request->keyword_branch) || 
               !empty($request->keyword_cif) || 
               !empty($request->keyword_ktp) || 
               !empty($request->keyword_name);
    
    $filter = in_array($request->action, ['0', '1', '2', '3', '4']);

    // Base query conditions
    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
        $datafile = DB::table('data_file as df')
            ->when($filterx, function ($query) use ($request) {
                $query->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                      ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                      ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                      ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                      ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
            })
            ->where(function ($query) {
                $query->where('df.final_status_spv2', '=', 5)
                      ->orWhere('df.final_status_spv2', '=', 5);
            })
            ->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))
            ->where('kode_cabang', '=', $branch)
            ->orderBy('date_input', 'desc')
            ->paginate($pagination);
            
    } else if ($role == 'spv2' || $role == 'spv3' || $role == 'spv4') {
        $datafile = DB::table('data_file as df')
            ->leftJoin('registration', 'df.loan_app_no', '=', 'registration.loan_app_no')
            ->when($filterx, function ($query) use ($request) {
                $query->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                      ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                      ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                      ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                      ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
            })
            ->when($filter, function ($query) {
                $query->where(function ($q) {
                    $q->where('df.final_status_spv1', '=', 1)
                      ->orWhere('df.final_status_spv1', '=', 3);
                });
            })
            ->where(function ($query) {
                $query->where('df.final_status_spv1', '=', 1)
                      ->orWhere('df.final_status_spv1', '=', 3);
            })
            ->where('df.final_status_spv3', '=', 0)
            ->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'))
            ->whereNotIn('df.loan_app_no', function($query) {
                $query->select('loan_app_no')
                      ->from('list_pickup')
                      ->where('status', '=', 0);
            });

        if ($ai_enabled == 1) {
            $datafile = $datafile->whereExists(function($query) {
                $query->select(DB::raw(1))
                      ->from('detail_file as dtf')
                      ->whereColumn('dtf.loan_app_no', 'df.loan_app_no')
                      ->where(function($q) {
                          $q->whereNotNull('dtf.score')
                            ->orWhere(DB::raw('TIMESTAMPDIFF(MINUTE, df.date_flag_spv1, NOW())'), '>', 5);
                      });
            });
        }

        $datafile = $datafile->select('df.*', 'registration.no_register')
            ->selectRaw('MAX(CAST(SUBSTRING_INDEX(registration.no_register, \'-\', -1) AS UNSIGNED)) as max_register_number')
            ->where(function($query) {
                $query->where(function($q) {
                    // Data hari ini dan kemarin
                    $q->whereIn(DB::raw('DATE(df.date_input)'), [
                        DB::raw('CURDATE()'),
                        DB::raw('CURDATE() - INTERVAL 1 DAY')
                    ]);
                })
                ->orWhere(function($q) {
                    // Data Jumat jika hari ini Senin
                    $q->where(DB::raw('DAYOFWEEK(NOW())'), '=', 2)
                       ->where(DB::raw('DATE(df.date_input)'), '=', DB::raw('DATE(SUBDATE(NOW(), 3))'))
                       ->where(DB::raw('DATE(df.date_flag_spv1)'), '=', DB::raw('CURDATE()'));
                });
            })
            ->where('df.final_status', '!=', 4)
            ->groupBy('df.loan_app_no')
            ->orderByRaw('max_register_number ASC')
            ->paginate($pagination);

        // Append query parameters to pagination links
        $datafile->appends($request->input('keyword_loan'));
        $datafile->appends($request->input('keyword_branch'));
        $datafile->appends($request->input('keyword_cif'));
        $datafile->appends($request->input('keyword_ktp'));
        $datafile->appends($request->input('keyword_name'));
    } else {
        $datafile = collect([])->paginate($pagination);
    }

    return view('loan.index', [
        'datafile' => $datafile,
        'produk_title' => $produk_title,
    ])->with('i', ($request->input('page', 1) - 1) * $pagination);
}
    
    public function dataloanSearch($id)
    {


        $sql = "SELECT
                    d1.loan_app_no,
                    d1.nama_debitur,
                    date( d2.tbo_date ) AS tbo_date,
                    (
                    SELECT
                        count( 1 )+ 1 
                    FROM
                        perubahan_tgl_tbo_detail
                        INNER JOIN perubahan_tgl_tbo ON perubahan_tgl_tbo_detail.id_perubahan = perubahan_tgl_tbo.id 
                    WHERE
                        perubahan_tgl_tbo.flag_spv = 1 and loan_app_no = d1.loan_app_no 
                    ) AS perubahan_ke 
                FROM
                    data_file d1
                    INNER JOIN (
                    SELECT
                        p1.loan_app_no,
                        p1.tbo_date 
                    FROM
                        `comment` p1
                        INNER JOIN ( SELECT max( comment_date ) MaxPostDate, loan_app_no, tbo_date FROM `comment` WHERE flag_spv = 3 GROUP BY loan_app_no ) p2 ON p1.loan_app_no = p2.loan_app_no 
                        AND p1.comment_date = p2.MaxPostDate 
                    ) d2 ON d1.loan_app_no = d2.loan_app_no 
                WHERE
                    d1.final_status = 3 
                    -- AND date( d2.tbo_date ) < date(now()) 
                    AND d1.loan_app_no = '" . $id . "' 
                    AND d1.kode_cabang = '" . Session('branch_code') . "' 
                GROUP BY
                    d1.loan_app_no
                    
                        
                    ";
        $tbo = DB::select($sql);


        return response()->json($tbo);
    }


    public function dataloanSearchAll($id)
    {


        $sql = "SELECT
                    d1.loan_app_no,
                    d1.nama_debitur
                    from data_file d1
                WHERE
                   
                    d1.loan_app_no = '" . $id . "' 
                    AND d1.kode_cabang = '" . Session('branch_code') . "' 
                GROUP BY
                    d1.loan_app_no
                    
                        
                    ";
        $tbo = DB::select($sql);


        return response()->json($tbo);
    }


    public function loanappnoSearch(Request $request)
    {
        $branch = [];

        if ($request->has('q')) {
            $search = $request->q;
            $branch = DataFileModel::select("loan_app_no", "nama_debitur")
                ->where('loan_app_no', 'LIKE', "%$search%")
                ->where('final_status', '=', 3)
                ->where('kode_cabang', '=', Session('branch_code'))
                ->orderBy('loan_app_no', 'asc')
                ->get();
        } else {
            $search = $request->q;
            $branch = DataFileModel::select("loan_app_no", "nama_debitur")
                ->where('final_status', '=', 3)
                ->where('kode_cabang', '=', Session('branch_code'))
                ->orderBy('loan_app_no', 'asc')
                ->get();
        }
        return response()->json($branch);
    }

    public function loanappnoSearchAll(Request $request)
    {
        $branch = [];

        if ($request->has('q')) {
            $search = $request->q;
            $branch = DataFileModel::select("loan_app_no", "nama_debitur")
                ->where('loan_app_no', 'LIKE', "%$search%")
                //->where('final_status', '=', 3)
                ->where('kode_cabang', '=', Session('branch_code'))
                //->orderBy('loan_app_no','asc')
                ->get();
        } else {
            $search = $request->q;
            $branch = DataFileModel::select("loan_app_no", "nama_debitur")
                //->where('final_status', '=', 3)
                ->where('kode_cabang', '=', Session('branch_code'))
                //->orderBy('loan_app_no','asc')
                ->get();
        }
        return response()->json($branch);
    }

    public function emptylabel(Request $request)
    {   //dd($id);
        $x = $request->no;
        $loan = $request->loan_app_no;
        foreach ($x as $item) {
            $data = explode("-", $item);
            $no_id = $data[0];
            $no_alias = $data[1];
            echo $no_id . ' ' . $no_alias;

            DB::table('detail_file')
                ->where('id', $no_id)
                ->update(['alias' => $no_alias]);
        }

        return redirect()->route('loans.show', ['loan' => $loan]);
    }

    public function showpdf($id)
    {

        //dd($id);
        return view('loan.showpdf')->with("urlfile", $id);
    }

    public function download_tbo(Request $request)
    {
        $wherebranch = '';
        if (Session('role') == 'staff' || Session('role') == 'spv1' || Session('role') == 'pc' || Session('role') == 'pcp') {
            $wherebranch = " AND `kode_cabang` = '" . Session('branch_code') . "'";
        }

        $sql = 'SELECT
        	kode_cabang,
	data_file.loan_app_no,
	no_cif,
	no_ktp,
	nama_debitur,
	date_input,
	produk,
	tbo_date,
	(select a.comment from comment a where a.loan_app_no = data_file.loan_app_no ORDER BY a.comment_date desc limit 1) as last_note,
	(select
	CASE
    WHEN  a.level_spv = "spv1" THEN "SPV BRANCH"
    WHEN a.level_spv = "spv2" THEN "BPR1"
		WHEN a.level_spv = "spv3" THEN "BPR2"
    END
		  from comment a where a.loan_app_no = data_file.loan_app_no ORDER BY a.comment_date desc limit 1) as last_level_reviewer,
			CASE
    WHEN final_status_spv1 = "0" THEN "Pending"            
    WHEN final_status_spv1 = "1" THEN "Verify"
    WHEN final_status_spv1 = "2" THEN "Not Verify"
		WHEN final_status_spv1 = "3" THEN "TBO"
		WHEN final_status_spv1 = "4" THEN "Reject"
    END as branch_lvl1,
		CASE
    WHEN final_status_spv2 = "0" THEN "Pending"            
    WHEN final_status_spv2 = "1" THEN "Verify"
    WHEN final_status_spv2 = "2" THEN "Not Verify"
		WHEN final_status_spv2 = "3" THEN "TBO"
		WHEN final_status_spv2 = "4" THEN "Reject"
    END as bpr_lvl1,
		CASE
    WHEN final_status_spv3 = "0" THEN "Pending"            
    WHEN final_status_spv3 = "1" THEN "Verify"
    WHEN final_status_spv3 = "2" THEN "Not Verify"
		WHEN final_status_spv3 = "3" THEN "TBO"
		WHEN final_status_spv3 = "4" THEN "Reject"
    END as bpr_lvl2
    FROM
        `data_file`
        INNER JOIN `comment` ON `comment`.`loan_app_no` = `data_file`.`loan_app_no` 
    WHERE
        -- date(`comment`.`tbo_date`) < date(now()) AND
        `data_file`.`final_status` = 3 
        ' . $wherebranch . '
    GROUP BY
        `data_file`.`loan_app_no` 
    ORDER BY
        `comment`.`tbo_date` DESC;';
        //dd($sql);
        $fileName = 'download_tbo_' . date('Ymd') . '.csv';
        $tasks = DB::select($sql);



        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array(
            'Kode Cabang',
            'Loan App No',
            'No CIF',
            'No KTP',
            'Nama Debitur',
            'Date Input',
            'Product',
            'TBO Date',
            'UNIT BISNIS SPV',
            'DCRM REVIEWER',
            'DCRM TEAM LEADER',
            'Last Level Approval',
            'Last Note',
        );


        $callback = function () use ($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tasks as $task) {
                $row["kode_cabang"] = $task->kode_cabang;
                $row["loan_app_no"] = $task->loan_app_no;
                $row["no_cif"] = $task->no_cif;
                $row["no_ktp"] = $task->no_ktp;
                $row["nama_debitur"] = $task->nama_debitur;
                $row["date_input"] = $task->date_input;
                $row["produk"] = $task->produk;
                $row["tbo_date"] = $task->tbo_date;
                $row["last_note"] = $task->last_note;
                $row["last_level_reviewer"] = $task->last_level_reviewer;
                $row["branch_lvl1"] = $task->branch_lvl1;
                $row["bpr_lvl1"] = $task->bpr_lvl1;
                $row["bpr_lvl2"] = $task->bpr_lvl2;



                fputcsv($file, array(
                    $row["kode_cabang"],
                    $row["loan_app_no"],
                    $row["no_cif"],
                    $row["no_ktp"],
                    $row["nama_debitur"],
                    $row["date_input"],
                    $row["produk"],
                    $row["tbo_date"],
                    $row["branch_lvl1"],
                    $row["bpr_lvl1"],
                    $row["bpr_lvl2"],
                    $row["last_level_reviewer"],
                    $row["last_note"],
                ));
            }

            fclose($file);

        };

        return response()->stream($callback, 200, $headers);
    }

    public function download_tbo_overdue(Request $request)
    {
        $wherebranch = '';
        if (Session('role') == 'staff' || Session('role') == 'spv1' || Session('role') == 'pc' || Session('role') == 'pcp') {
            $wherebranch = " AND kode_cabang = '" . Session('branch_code') . "'";
        }

        $sql = 'SELECT
        d1.kode_cabang,
d1.loan_app_no,
d1.no_cif,
d1.no_ktp,
d1.nama_debitur,
d1.date_input,
d1.produk,
max(d2.tbo_date) as tbo_date,
(select a.comment from comment a where a.loan_app_no = d1.loan_app_no ORDER BY a.comment_date desc limit 1) as last_note,
(select
CASE
WHEN  a.level_spv = "spv1" THEN "SPV BRANCH"
WHEN a.level_spv = "spv2" THEN "DCRM Reviewer"
    WHEN a.level_spv = "spv3" OR a.level_spv = "spv4" THEN "DCRM TEAM LEADER"
    
END
      from comment a where a.loan_app_no = d1.loan_app_no ORDER BY a.comment_date desc limit 1) as last_level_reviewer,
        CASE
WHEN final_status_spv1 = "0" THEN "Pending"            
WHEN final_status_spv1 = "1" THEN "Verify"
WHEN final_status_spv1 = "2" THEN "Not Verify"
    WHEN final_status_spv1 = "3" THEN "TBO"
    WHEN final_status_spv1 = "4" THEN "Reject"
END as branch_lvl1,
    CASE
WHEN final_status_spv2 = "0" THEN "Pending"            
WHEN final_status_spv2 = "1" THEN "Verify"
WHEN final_status_spv2 = "2" THEN "Not Verify"
    WHEN final_status_spv2 = "3" THEN "TBO"
    WHEN final_status_spv2 = "4" THEN "Reject"
END as bpr_lvl1,
    CASE
WHEN final_status_spv3 = "0" THEN "Pending"            
WHEN final_status_spv3 = "1" THEN "Verify"
WHEN final_status_spv3 = "2" THEN "Not Verify"
    WHEN final_status_spv3 = "3" THEN "TBO"
    WHEN final_status_spv3 = "4" THEN "Reject"
END as bpr_lvl2
from
 COMMENT d2
 INNER JOIN data_file d1 on d1.loan_app_no = d2.loan_app_no
WHERE
	d2.loan_app_no IN ( SELECT loan_app_no FROM data_file WHERE final_status = 3 ' . $wherebranch . ' ) 
GROUP BY
	d2.loan_app_no 
HAVING
	date(
	max( d2.tbo_date )) < date(now())';
        //dd($sql);
        $fileName = 'download_tbo_overdue_' . date('Ymd') . '.csv';
        $tasks = DB::select($sql);



        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array(
            'Kode Cabang',
            'Loan App No',
            'No CIF',
            'No KTP',
            'Nama Debitur',
            'Date Input',
            'Product',
            'TBO Date',
            'UNIT BISNIS SPV',
            'DCRM REVIEWER',
            'DCRM TEAM LEADER',
            'Last Level Approval',
            'Last Note',
        );


        $callback = function () use ($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tasks as $task) {
                $row["kode_cabang"] = $task->kode_cabang;
                $row["loan_app_no"] = $task->loan_app_no;
                $row["no_cif"] = $task->no_cif;
                $row["no_ktp"] = $task->no_ktp;
                $row["nama_debitur"] = $task->nama_debitur;
                $row["date_input"] = $task->date_input;
                $row["produk"] = $task->produk;
                $row["tbo_date"] = $task->tbo_date;
                $row["last_note"] = $task->last_note;
                $row["last_level_reviewer"] = $task->last_level_reviewer;
                $row["branch_lvl1"] = $task->branch_lvl1;
                $row["bpr_lvl1"] = $task->bpr_lvl1;
                $row["bpr_lvl2"] = $task->bpr_lvl2;



                fputcsv($file, array(
                    $row["kode_cabang"],
                    $row["loan_app_no"],
                    $row["no_cif"],
                    $row["no_ktp"],
                    $row["nama_debitur"],
                    $row["date_input"],
                    $row["produk"],
                    $row["tbo_date"],
                    $row["branch_lvl1"],
                    $row["bpr_lvl1"],
                    $row["bpr_lvl2"],
                    $row["last_level_reviewer"],
                    $row["last_note"],
                ));
            }

            fclose($file);

        };

        return response()->stream($callback, 200, $headers);
    }

    public function download_tbo_pending(Request $request)
    {
        $wherebranch = '';
        if (Session('role') == 'staff' || Session('role') == 'spv1' || Session('role') == 'pc' || Session('role') == 'pcp') {
            $wherebranch = " AND `kode_cabang` = '" . Session('branch_code') . "'";
        }

        $sql = 'SELECT
        d1.kode_cabang,
d1.loan_app_no,
d1.no_cif,
d1.no_ktp,
d1.nama_debitur,
d1.date_input,
d1.produk,
max(d2.tbo_date) as tbo_date,
(select a.comment from comment a where a.loan_app_no = d1.loan_app_no ORDER BY a.comment_date desc limit 1) as last_note,
(select
CASE
WHEN  a.level_spv = "spv1" THEN "SPV BRANCH"
WHEN a.level_spv = "spv2" THEN "DCRM REVIEWER"
    WHEN a.level_spv = "spv3" THEN "DCRM TEAM LEADER"
END
      from comment a where a.loan_app_no = d1.loan_app_no ORDER BY a.comment_date desc limit 1) as last_level_reviewer,
        CASE
WHEN final_status_spv1 = "0" THEN "Pending"            
WHEN final_status_spv1 = "1" THEN "Verify"
WHEN final_status_spv1 = "2" THEN "Not Verify"
    WHEN final_status_spv1 = "3" THEN "TBO"
    WHEN final_status_spv1 = "4" THEN "Reject"
END as branch_lvl1,
    CASE
WHEN final_status_spv2 = "0" THEN "Pending"            
WHEN final_status_spv2 = "1" THEN "Verify"
WHEN final_status_spv2 = "2" THEN "Not Verify"
    WHEN final_status_spv2 = "3" THEN "TBO"
    WHEN final_status_spv2 = "4" THEN "Reject"
END as bpr_lvl1,
    CASE
WHEN final_status_spv3 = "0" THEN "Pending"            
WHEN final_status_spv3 = "1" THEN "Verify"
WHEN final_status_spv3 = "2" THEN "Not Verify"
    WHEN final_status_spv3 = "3" THEN "TBO"
    WHEN final_status_spv3 = "4" THEN "Reject"
END as bpr_lvl2
from
COMMENT d2
 INNER JOIN data_file d1 on d1.loan_app_no = d2.loan_app_no
WHERE
	d2.loan_app_no IN ( SELECT loan_app_no FROM data_file WHERE final_status = 3 ' . $wherebranch . ' ) 
GROUP BY
	d2.loan_app_no 
HAVING
	date(
	max(d2.tbo_date)) >= date(now())';
        //dd($sql);
        $fileName = 'download_tbo_pending_' . date('Ymd') . '.csv';
        $tasks = DB::select($sql);



        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array(
            'Kode Cabang',
            'Loan App No',
            'No CIF',
            'No KTP',
            'Nama Debitur',
            'Date Input',
            'Product',
            'TBO Date',
            'UNIT BISNIS SPV',
            'DCRM REVIEWER',
            'DCRM TEAM LEADER',
            'Last Level Approval',
            'Last Note',
        );


        $callback = function () use ($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tasks as $task) {
                $row["kode_cabang"] = $task->kode_cabang;
                $row["loan_app_no"] = $task->loan_app_no;
                $row["no_cif"] = $task->no_cif;
                $row["no_ktp"] = $task->no_ktp;
                $row["nama_debitur"] = $task->nama_debitur;
                $row["date_input"] = $task->date_input;
                $row["produk"] = $task->produk;
                $row["tbo_date"] = $task->tbo_date;
                $row["last_note"] = $task->last_note;
                $row["last_level_reviewer"] = $task->last_level_reviewer;
                $row["branch_lvl1"] = $task->branch_lvl1;
                $row["bpr_lvl1"] = $task->bpr_lvl1;
                $row["bpr_lvl2"] = $task->bpr_lvl2;



                fputcsv($file, array(
                    $row["kode_cabang"],
                    $row["loan_app_no"],
                    $row["no_cif"],
                    $row["no_ktp"],
                    $row["nama_debitur"],
                    $row["date_input"],
                    $row["produk"],
                    $row["tbo_date"],
                    $row["branch_lvl1"],
                    $row["bpr_lvl1"],
                    $row["bpr_lvl2"],
                    $row["last_level_reviewer"],
                    $row["last_note"],
                ));
            }

            fclose($file);

        };

        return response()->stream($callback, 200, $headers);
    }


    public function report(Request $request)
    {
        return view('loan.report');
    }

    public function reportSubmit(Request $request)
    {
        $dari = $request->from_date;
        $sampai = $request->end_date;

        $str_dari = strtotime($dari);
        $str_sampai = strtotime($sampai);
        if ($str_sampai < $str_dari) {
            return redirect()->route('datafile.report')
                ->with('error', 'Tanggal End Date harus lebih besar dari Tanggal From Date');
        }
        //dd($str_sampai);
        $where_branch = '';
        $where_status = '';
        $where_fasilitas = '';

        if ($request->cabang) {
            $where_branch = ' and data_file.kode_cabang = "' . $request->cabang . '" ';
        }
        if ($request->final_status) {
            $where_status = ' and data_file.final_status = "' . $request->final_status . '" ';
        }
        if ($request->fasilitas) {
            $where_fasilitas = ' and data_file.fasilitas = "' . $request->fasilitas . '" ';
        }


        $sql = 'SELECT
        data_file.kode_cabang,
        branchlist.branch_name,
        data_file.loan_app_no,
        data_file.produk,
        data_file.user_input,
        input_users.`name`,
        data_file.date_input,
        data_file.no_cif,
        data_file.nama_debitur,
        data_file.plafond,
        user_spv1 AS nik_spv_cabang,
        approval_users_spv1.`name` AS nama_spv_cabang,
        user_spv2 AS nik_dcrm_reviewer,
        approval_users_spv2.`name` AS nama_dcrm_reviewer,
        user_spv3 AS nik_dcrm_teamleader,
        approval_users_spv3.`name` AS nama_dcrm_teamleader,
        (select 
            
            CASE
            WHEN
            `comment`.level_spv = "spv1" or `comment`.level_spv ="pcp" or `comment`.level_spv = "pc" THEN
                    "SPV BRANCH" 
                    WHEN `comment`.level_spv = "spv2" THEN
                    "DCRM REVIEWER" 
                    WHEN `comment`.level_spv = "spv3" OR `comment`.level_spv = "spv4" THEN
                    "DCRM TEAM LEADER" 
                END 
            from `comment` where `comment`.loan_app_no = data_file.loan_app_no ORDER BY loan_app_no,comment_date DESC limit 1
            ) as last_review_level,
        ( SELECT `comment` FROM `comment` WHERE `comment`.loan_app_no = data_file.loan_app_no ORDER BY loan_app_no, comment_date DESC LIMIT 1 ) AS last_note,
        ( SELECT `reason` FROM `comment` WHERE `comment`.loan_app_no = data_file.loan_app_no ORDER BY loan_app_no, comment_date DESC LIMIT 1 ) AS last_reason,
            ( SELECT `comment`.comment_date FROM `comment` WHERE `comment`.loan_app_no = data_file.loan_app_no ORDER BY loan_app_no, comment_date DESC LIMIT 1 ) AS approval_date,
    IF
        (
            final_status = "0",
            "Pending",
        IF
        ( final_status = "1", "Verify", IF ( final_status = "2", "Not Verify", IF ( final_status = "3", "TBO", "Rejected" )) )) AS final_status,
        fasilitas 
    FROM
        data_file
        INNER JOIN branchlist ON branchlist.branch_code = data_file.kode_cabang
        LEFT JOIN `users` AS input_users ON data_file.user_input = input_users.nik
        LEFT JOIN `users` AS approval_users_spv1 ON data_file.user_spv1 = approval_users_spv1.nik
        LEFT JOIN `users` AS approval_users_spv2 ON data_file.user_spv2 = approval_users_spv2.nik
        LEFT JOIN `users` AS approval_users_spv3 ON data_file.user_spv3 = approval_users_spv3.nik 
    WHERE
        date(data_file.date_input) >= "' . $dari . '" AND date(data_file.date_input) <= "' . $sampai . '" ' . $where_branch . ' ' . $where_status . ' ' . $where_fasilitas . '
            ORDER BY
            data_file.date_input ASC';

        $fileName = 'report_' . $dari . '_' . $sampai . '.csv';
        $tasks = DB::select($sql);



        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array(
            'BRANCH CODE',
            'BRANCH NAME',
            'ID APPROVAL NO',
            'PRODUCT CODE',
            'USER INPUT ID',
            'USER INPUT NAME',
            'INPUT DATE',
            'CIF DEBITUR',
            'DEBITUR NAME',
            'TOTAL PLAFOND',
            'NIK SPV',
            'SPV NAME',
            'NIK DCRM REVIEWER',
            'DCRM REVIEWER NAME',
            'NIK DCRM TEAMLEADER',
            'DCRM TEAMLEADER NAME',
            'LAST REVIEW LEVEL',
            'LAST NOTE',
            'LAST REASON',
            'APPROVAL DATE',
            'FINAL STATUS',
            'FASILITAS',
        );


        $callback = function () use ($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tasks as $task) {

                $row["BRANCH_CODE"] = $task->kode_cabang;
                $row["BRANCH_NAME"] = $task->branch_name;
                $row["ID_APPROVAL_NO"] = $task->loan_app_no;
                $row["PRODUCT_CODE"] = $task->produk;
                $row["USER_INPUT_ID"] = $task->user_input;
                $row["USER_INPUT_NAME"] = $task->name;
                $row["INPUT_DATE"] = $task->date_input;
                $row["CIF_DEBITUR"] = $task->no_cif;
                $row["DEBITUR_NAME"] = $task->nama_debitur;
                $row["TOTAL_PLAFOND"] = $task->plafond;

                $row["NIK_SPV"] = $task->nik_spv_cabang;
                $row["SPV_NAME"] = $task->nama_spv_cabang;
                $row["NIK_DCRM_REVIEWER"] = $task->nik_dcrm_reviewer;
                $row["DCRM_REVIEWER_NAME"] = $task->nama_dcrm_reviewer;
                $row["NIK_DCRM_TEAMLEADER"] = $task->nik_dcrm_teamleader;
                $row["DCRM_TEAMLEADER_NAME"] = $task->nama_dcrm_teamleader;

                $row["LAST_REVIEW_LEVEL"] = $task->last_review_level;
                $row["LAST_NOTE"] = $task->last_note;
                $row["LAST_REASON"] = $task->last_reason;
                $row["APPROVAL_DATE"] = $task->approval_date;
                $row["FINAL_STATUS"] = $task->final_status;
                $row["FASILITAS"] = $task->fasilitas;

                fputcsv($file, array(
                    $row["BRANCH_CODE"],
                    $row["BRANCH_NAME"],
                    $row["ID_APPROVAL_NO"],
                    $row["PRODUCT_CODE"],
                    $row["USER_INPUT_ID"],
                    $row["USER_INPUT_NAME"],
                    $row["INPUT_DATE"],
                    $row["CIF_DEBITUR"],
                    $row["DEBITUR_NAME"],
                    $row["TOTAL_PLAFOND"],
                    $row["NIK_SPV"],
                    $row["SPV_NAME"],
                    $row["NIK_DCRM_REVIEWER"],
                    $row["DCRM_REVIEWER_NAME"],
                    $row["NIK_DCRM_TEAMLEADER"],
                    $row["DCRM_TEAMLEADER_NAME"],
                    $row["LAST_REVIEW_LEVEL"],
                    $row["LAST_NOTE"],
                    $row["LAST_REASON"],
                    $row["APPROVAL_DATE"],
                    $row["FINAL_STATUS"],
                    $row["FASILITAS"],
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function report_detail(Request $request)
    {
        return view('loan.report-detail');
    }

    public function reportDetailSubmit(Request $request)
    {
        $dari = $request->from_date;
        $sampai = $request->end_date;

        $str_dari = strtotime($dari);
        $str_sampai = strtotime($sampai);
        if ($str_sampai < $str_dari) {
            return redirect()->route('datafile.report')
                ->with('error', 'Tanggal End Date harus lebih besar dari Tanggal From Date');
        }
        //dd($str_sampai);
        $where_branch = '';
        $where_status = '';
        $where_nik_bpr_1 = '';
        $where_nik_bpr_2 = '';
        if ($request->cabang) {
            $where_branch = ' and data_file.kode_cabang = "' . $request->cabang . '" ';
        }
        if ($request->final_status) {
            $where_status = ' and data_file.final_status = "' . $request->final_status . '" ';
        }
        if ($request->nik_brp1) {
            $where_nik_bpr_1 = ' and data_file.user_spv2 = "' . $request->nik_brp1 . '" ';
        }
        if ($request->nik_brp2) {
            $where_nik_bpr_2 = ' and data_file.user_spv3 = "' . $request->nik_brp2 . '" ';
        }


        $sql = 'SELECT
        data_file.kode_cabang as "BRANCH_CODE",
        branchlist.branch_name as "BRANCH_NAME",
        data_file.loan_app_no as "ID_APPROVAL_NO",
        data_file.produk as "PRODUCT_CODE",
        data_file.instansi as "INSTITUTIONS_COMPANIES",
        data_file.user_input as "USER_INPUT_ID",
        input_users.name AS "USER_INPUT_NAME",
        data_file.date_input as "INPUT_DATE",
        data_file.no_cif as "CIF_DEBITUR",
        data_file.nama_debitur as "DEBITUR_NAME",
        data_file.plafond as "TOTAL_PLAFOND",
        IF(`comment`.level_spv = "spv1", "1", "") AS "APPROVAL_BRANCH",
        IF(`comment`.level_spv = "spv2", "1", "") AS "APPROVAL_BPR_1",
        IF(`comment`.level_spv = "spv3", "1", "") AS "APPROVAL_BPR_2",
        `comment`.user_name as "APPROVAL_USER_ID",
        approval_users.`name` as "APPROVAL_USER_NAME",
        IF(flag_spv = "1", "1","" ) AS "VERIFY",
        IF(flag_spv = "2", "1","" ) AS "NOT_VERIFY",
        IF(flag_spv = "3", "1","" ) AS "TBO",
        IF(flag_spv = "4", "1","" ) AS "REJECT",
        `comment`.comment_date as "APPROVAL_DATE",
        `comment`.`comment` as "NOTE",
        `comment`.`reason` as "REASON",
        `comment`.tbo_date as "TBO_DATE",
        data_file.final_status as "FINAL_STATUS",
        data_file.jangka_waktu as "LOAN_PERIOD",
        data_file.tanggal_jatuh_tempo as "MATURITY_DATE",
        data_file.rate as "INTEREST_RATE"
        FROM
        data_file
        INNER JOIN `comment` ON data_file.loan_app_no = `comment`.loan_app_no
        INNER JOIN branchlist ON data_file.kode_cabang = branchlist.branch_code
                    LEFT JOIN `users` AS input_users ON data_file.user_input = input_users.nik
                    LEFT JOIN `users` AS approval_users ON `comment`.user_name = approval_users.nik
        
            WHERE
            date(data_file.date_input) >= "' . $dari . '" AND date(data_file.date_input) <= "' . $sampai . '" ' . $where_branch . ' ' . $where_status . ' ' . $where_nik_bpr_1 . ' ' . $where_nik_bpr_2 . '
            ORDER BY
            data_file.date_input ASC';
        //dd($sql);
        $fileName = 'report_detail_' . $dari . '_' . $sampai . '.csv';
        $tasks = DB::select($sql);



        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array(
            'BRANCH CODE',
            'BRANCH NAME',
            'ID APPROVAL NO',
            'PRODUCT CODE',
            'INSTITUTIONS / COMPANIES',
            'USER INPUT ID',
            'USER INPUT NAME',
            'INPUT DATE',
            'CIF DEBITUR',
            'DEBITUR NAME',
            'TOTAL PLAFOND',
            'APPROVAL BRANCH',
            'APPROVAL BPR 1',
            'APPROVAL BPR 2',
            'APPROVAL USER ID',
            'APPROVAL USER NAME',
            'VERIFY',
            'NOT VERIFY',
            'TBO',
            'REJECT',
            'APPROVAL DATE',
            'NOTE',
            'REASON',
            'TBO DATE',
            'FINAL STATUS',
            'LOAN PERIOD',
            'MATURITY DATE',
            'INTEREST RATE'
        );


        $callback = function () use ($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tasks as $task) {
                $row["BRANCH_CODE"] = $task->BRANCH_CODE;
                $row["BRANCH_NAME"] = $task->BRANCH_NAME;
                $row["ID_APPROVAL_NO"] = $task->ID_APPROVAL_NO;
                $row["PRODUCT_CODE"] = $task->PRODUCT_CODE;
                $row["INSTITUTIONS_COMPANIES"] = $task->INSTITUTIONS_COMPANIES;
                $row["USER_INPUT_ID"] = $task->USER_INPUT_ID;
                $row["USER_INPUT_NAME"] = $task->USER_INPUT_NAME;
                $row["INPUT_DATE"] = $task->INPUT_DATE;
                $row["CIF_DEBITUR"] = $task->CIF_DEBITUR;
                $row["DEBITUR_NAME"] = $task->DEBITUR_NAME;
                $row["TOTAL_PLAFOND"] = $task->TOTAL_PLAFOND;
                $row["APPROVAL_BRANCH"] = $task->APPROVAL_BRANCH;
                $row["APPROVAL_BPR_1"] = $task->APPROVAL_BPR_1;
                $row["APPROVAL_BPR_2"] = $task->APPROVAL_BPR_2;
                $row["APPROVAL_USER_ID"] = $task->APPROVAL_USER_ID;
                $row["APPROVAL_USER_NAME"] = $task->APPROVAL_USER_NAME;
                $row["VERIFY"] = $task->VERIFY;
                $row["NOT_VERIFY"] = $task->NOT_VERIFY;
                $row["TBO"] = $task->TBO;
                $row["REJECT"] = $task->REJECT;
                $row["APPROVAL_DATE"] = $task->APPROVAL_DATE;
                $row["NOTE"] = $task->NOTE;
                $row["REASON"] = $task->REASON;
                $row["TBO_DATE"] = $task->TBO_DATE;
                $row["FINAL_STATUS"] = $task->FINAL_STATUS;
                $row["LOAN_PERIOD"] = $task->LOAN_PERIOD;
                $row["MATURITY_DATE"] = $task->MATURITY_DATE;
                $row["INTEREST_RATE"] = $task->INTEREST_RATE;






                fputcsv($file, array(
                    $row["BRANCH_CODE"],
                    $row["BRANCH_NAME"],
                    $row["ID_APPROVAL_NO"],
                    $row["PRODUCT_CODE"],
                    $row["INSTITUTIONS_COMPANIES"],
                    $row["USER_INPUT_ID"],
                    $row["USER_INPUT_NAME"],
                    $row["INPUT_DATE"],
                    $row["CIF_DEBITUR"],
                    $row["DEBITUR_NAME"],
                    $row["TOTAL_PLAFOND"],
                    $row["APPROVAL_BRANCH"],
                    $row["APPROVAL_BPR_1"],
                    $row["APPROVAL_BPR_2"],
                    $row["APPROVAL_USER_ID"],
                    $row["APPROVAL_USER_NAME"],
                    $row["VERIFY"],
                    $row["NOT_VERIFY"],
                    $row["TBO"],
                    $row["REJECT"],
                    $row["APPROVAL_DATE"],
                    $row["NOTE"],
                    $row["REASON"],
                    $row["TBO_DATE"],
                    $row["FINAL_STATUS"],
                    $row["LOAN_PERIOD"],
                    $row["MATURITY_DATE"],
                    $row["INTEREST_RATE"],
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function sla(Request $request)
    {
        return view('loan.sla');
    }

    public function slaSubmit(Request $request)
    {
        $dari = $request->from_date;
        $sampai = $request->end_date;

        $str_dari = strtotime($dari);
        $str_sampai = strtotime($sampai);
        if ($str_sampai < $str_dari) {
            return redirect()->route('datafile.sla')
                ->with('error', 'Tanggal End Date harus lebih besar dari Tanggal From Date');
        }
        //dd($str_sampai);
        $where_branch = '';
        $where_status = '';
        $where_nik_bpr_1 = '';
        $where_nik_bpr_2 = '';
        if ($request->cabang) {
            $where_branch = ' and data_file.kode_cabang = "' . $request->cabang . '" ';
        }
        if ($request->final_status) {
            $where_status = ' and data_file.final_status = "' . $request->final_status . '" ';
        }
        if ($request->nik_brp1) {
            $where_nik_bpr_1 = ' and data_file.user_spv2 = "' . $request->nik_brp1 . '" ';
        }
        if ($request->nik_brp2) {
            $where_nik_bpr_2 = ' and data_file.user_spv3 = "' . $request->nik_brp2 . '" ';
        }


        $sql = "call staging_resume_dcrm('" . $dari . "','" . $sampai . "');";

        $tasks = DB::select($sql);

        return Excel::download(new SlaNew, 'sla_' . $dari . '_' . $sampai . '.xlsx');


    }

    public function searchLoan()
    {

        return view('loan.search');
    }

    public function testLoanApi($loanAppNo = 'ID025022686')
    {
        try {
            $loanApiService = new LoanApiService();
            $loanData = $loanApiService->getLoanData($loanAppNo);
            
            if ($loanData) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'API call successful',
                    'loan_app_no' => $loanAppNo,
                    'data' => $loanData
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No data found or API error',
                    'loan_app_no' => $loanAppNo,
                    'data' => null
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exception: ' . $e->getMessage(),
                'loan_app_no' => $loanAppNo,
                'data' => null
            ]);
        }
    }

    public function clearApiCache()
    {
        $loanApiService = new LoanApiService();
        $loanApiService->clearTokenCache();
        
        return response()->json([
            'status' => 'success',
            'message' => 'API token cache cleared'
        ]);
    }

    public function testDebtorFields($loanAppNo = 'ID025022686')
    {
        try {
            // Test the API data retrieval
            $loanApiService = new LoanApiService();
            $loanData = $loanApiService->getLoanData($loanAppNo);
            
            if ($loanData) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Debtor classification fields test',
                    'loan_app_no' => $loanData->loan_app_no,
                    'debtor_classification_code' => $loanData->debtor_classification_code,
                    'debtor_classification_name' => $loanData->debtor_classification_name,
                    'full_data' => $loanData
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No data found'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }

    public function updateBaseData($id)
    {
        // Use new API service to get loan data
        $loanApiService = new LoanApiService();
        $loanData = $loanApiService->getLoanData($id);

        if (empty($loanData)) {
            return redirect()->route('loans.show', ['loan' => $id]);
        } else {
            // Map the loan data from the new API
            $loan_app_no = $loanData->loan_app_no;
            $no_cif = $loanData->no_cif;
            $kode_cabang = $loanData->kode_cabang;
            $nama_cabang = $loanData->nama_cabang;
            $nama_debitur = $loanData->nama_debitur;
            $no_ktp = $loanData->no_ktp;
            $tgl_lahir = $loanData->tgl_lahir;
            $alamat_rumah = $loanData->alamat_rumah;
            $instansi = $loanData->instansi;
            $alamat_kantor = $loanData->alamat_kantor;
            $no_tlp_kantor = $loanData->no_tlp_kantor;
            $produk = $loanData->produk;
            $plafond = $loanData->plafond;
            $jangka_waktu = $loanData->jangka_waktu;
            $rate = $loanData->rate;
            $angsuran = $loanData->angsuran;
            $tgl_jatuh_tempo = $loanData->tgl_jatuh_tempo;
            $kd_status_pernikahan = $loanData->kd_status_pernikahan;
            $status_pernikahan = $loanData->status_pernikahan;


            $datafile = DataFileModel::findOrFail($id);
            $datafile->loan_app_no = $loan_app_no;
            $datafile->no_cif = $no_cif;
            $datafile->kode_cabang = $kode_cabang;
            $datafile->nama_debitur = $nama_debitur;
            $datafile->no_ktp = $no_ktp;
            $datafile->ttl = $tgl_lahir;
            $datafile->alamat_rumah = $alamat_rumah;
            $datafile->instansi = $instansi;
            $datafile->debtor_classification_code = $loanData->debtor_classification_code;
            $datafile->debtor_classification_name = $loanData->debtor_classification_name;
            $datafile->alamat_kantor = $alamat_kantor;
            $datafile->no_tlp_kantor = $no_tlp_kantor;
            //$datafile->produk = $produk;
            $datafile->plafond = $plafond;
            $datafile->jangka_waktu = $jangka_waktu;
            $datafile->rate = $rate;
            $datafile->angsuran = $angsuran;
            $datafile->tanggal_jatuh_tempo = $tgl_jatuh_tempo;
            $datafile->status_pernikahan = $kd_status_pernikahan;

            $datafile->save();
            //return "berhasil update data";
          
                $date_input = new DateTime($datafile->date_input);
                $compare_date = new DateTime('2024-11-06');

            if ($date_input > $compare_date){
                return redirect()->route('datafile.show_new', ['id' => $id]);
            }else{
                return redirect()->route('loans.show', ['loan' => $id]);
            }
                

            
        }
        //$datafile->modul = $ob['modul'];


    }
    public function submitsearchLoan(Request $request)
    {
        $data = $request->all();
        $search_option = $data['search_option'];
        $nomor = $data['nomor'];
        $modul = $data['produk'];
        $fasilitas = $data['fasilitas'];
        $early = $data['early'];
        $take_over = $data['take_over'];
        $take_over_hari_ini = $data['take_over_hari_ini'];
        $status_deviasi = $data['status_deviasi'];
        $fronting_agent = $data['fronting_agent'];
        $status_pegawai = $data['status_pegawai'];
        $pekerjaan = '';

        // if(checkTboCount()){
        //     return redirect()->route('datafile.searchLoan')
        //              ->with('error','TBO Quota Exceeded - Silankan seleasaikan terlebih dahulu dokumen yang masih TBO.');
        // }


        if (checkCutOff($fasilitas)) {

            return redirect()->route('datafile.searchLoan')
                ->with('error', 'Proses upload dokumen dapat dilakukan pada Jam Layanan yang telah ditentukan');


        }




        // Use new API service to get loan data
        $loanApiService = new LoanApiService();
        $loanData = $loanApiService->getLoanData($nomor);

        if (empty($loanData)) {
            return redirect()->route('datafile.searchLoan')
                ->with('error', 'Data tidak ditemukan');
        } else {
            // Map the loan data from the new API
            $loan_app_no = $loanData->loan_app_no;
            $no_cif = $loanData->no_cif;
            $kode_cabang = $loanData->kode_cabang;
            $nama_cabang = $loanData->nama_cabang;
            $nama_debitur = $loanData->nama_debitur;
            $no_ktp = $loanData->no_ktp;
            $tgl_lahir = $loanData->tgl_lahir;
            $alamat_rumah = $loanData->alamat_rumah;
            $instansi = $loanData->instansi;
            $debtor_classification_code = $loanData->debtor_classification_code;
            $debtor_classification_name = $loanData->debtor_classification_name;
            $alamat_kantor = $loanData->alamat_kantor;
            $no_tlp_kantor = $loanData->no_tlp_kantor;
            $produk = $loanData->produk;
            $plafond = $loanData->plafond;
            $jangka_waktu = $loanData->jangka_waktu;
            $rate = $loanData->rate;
            $angsuran = $loanData->angsuran;
            $tgl_jatuh_tempo = $loanData->tgl_jatuh_tempo;
            $kd_status_pernikahan = $loanData->kd_status_pernikahan;
            $status_pernikahan = $loanData->status_pernikahan;
            if ($modul == "KPKB-WNI") {
                $produk = "KREDIT PEMILIKAN KENDARAAN BERMOTOR (WNI)";
            } else if ($modul == "KPKB-WNA") {
                $produk = "KREDIT PEMILIKAN KENDARAAN BERMOTOR (WNA)";
            }
            if ($modul == "KPKB-WNI") {
                $pekerjaan = $data['pekerjaan'];
            }
            //dd(changeDate($loanData->tgl_lahir));
            $lahir = new DateTime($loanData->tgl_lahir);
            $today = new DateTime('today');
            $usia = $lahir->diff($today)->y; // Menghitung selisih tahun

            /*if ($usia > 63) {
                $status_usia_debitur = ">63";
            } else {
                $status_usia_debitur = "<=63";
            }*/
            $status_usia_debitur_kategori=calculateAgeCategory($loanData->tgl_lahir);

            $status_usia_debitur=$status_usia_debitur_kategori['category'];

            // Validasi Instansi untuk modul KUPEN
            $validationWarning = null;
            $availableInstansi = [];
            $isValidInstansi = true; // Default valid

            if (str_contains($modul, 'KUPEN') &&
                !empty($debtor_classification_name) &&
                !empty($instansi)) {

                // Ambil semua instansi yang tersedia untuk klasifikasi ini
                $availableInstansiList = MasterInstansi::where('klasifikasi_debitur', $debtor_classification_name)
                    ->where('status', 'active')
                    ->orderBy('instansi', 'asc')
                    ->pluck('instansi')
                    ->toArray();

                // Cek apakah instansi input dimulai dengan salah satu instansi yang valid
                $isValidInstansi = false;
                foreach ($availableInstansiList as $validInstansi) {
                    // Cek apakah instansi input dimulai dengan instansi yang ada di database
                    if (str_starts_with($instansi, $validInstansi)) {
                        $isValidInstansi = true;
                        break;
                    }
                }

                if (!$isValidInstansi) {
                    $availableInstansi = $availableInstansiList;
                    // Buat pesan warning
                    $validationWarning = 'Instansi "' . $instansi . '" tidak sesuai dengan Klasifikasi Debitur "' . $debtor_classification_name . '".';
                }
            }

            return view('loan.search-data', compact(
                'loan_app_no',
                'no_cif',
                'kode_cabang',
                'nama_cabang',
                'nama_debitur',
                'no_ktp',
                'tgl_lahir',
                'alamat_rumah',
                'instansi',
                'debtor_classification_code',
                'debtor_classification_name',
                'alamat_kantor',
                'no_tlp_kantor',
                'produk',
                'plafond',
                'jangka_waktu',
                'rate',
                'angsuran',
                'tgl_jatuh_tempo',
                'modul',
                'kd_status_pernikahan',
                'status_pernikahan',
                'pekerjaan',
                'fasilitas',
                'early',
                'take_over',
                'take_over_hari_ini',
                'status_deviasi',
                'fronting_agent',
                'status_pegawai',
                'status_usia_debitur',
                'validationWarning',
                'availableInstansi',
                'isValidInstansi'
            ));
        }
    }

    public function savesearchLoan(Request $request)
    {
        $ob = $request->all();

        //dd(checkJenisProduk($ob['produk']));
        try {
            $datafile = new DataFileModel;

            $datafile->modul = $ob['modul'];
            $datafile->loan_app_no = $ob['loan_app_no'];
            $datafile->no_cif = $ob['no_cif'];
            $datafile->kode_cabang = $ob['kode_cabang'];
            $datafile->nama_debitur = $ob['nama_debitur'];
            $datafile->no_ktp = $ob['no_ktp'];
            $datafile->ttl = $ob['tgl_lahir'];
            $datafile->alamat_rumah = $ob['alamat_rumah'];
            $datafile->instansi = $ob['instansi'];
            $datafile->debtor_classification_code = array_key_exists('debtor_classification_code', $ob) 
            ? $ob['debtor_classification_code'] 
            : null;
            
            $datafile->debtor_classification_name = array_key_exists('debtor_classification_name', $ob) 
            ? $ob['debtor_classification_name'] 
            : null;
            $datafile->alamat_kantor = $ob['alamat_kantor'];
            $datafile->no_tlp_kantor = $ob['no_tlp_kantor'];
            $datafile->produk = $ob['produk'];
            $datafile->plafond = $ob['plafond'];
            $datafile->jangka_waktu = $ob['jangka_waktu'];
            $datafile->rate = $ob['rate'];
            $datafile->angsuran = $ob['angsuran'];
            $datafile->tanggal_jatuh_tempo = $ob['tgl_jatuh_tempo'];
            $datafile->user_input = Session('nik');
            $datafile->branch_input = Session('branch_code');


            //$date_input=getWilayah($ob['kode_cabang']);
            $date_input = date('Y-m-d H:i:s');
            $datafile->date_input = $date_input;
            $datafile->status_pernikahan = $ob['kd_status_pernikahan'];
            $datafile->pekerjaan = $ob['pekerjaan'];
            $datafile->fasilitas = $ob['fasilitas'];
            $datafile->early = $ob['early'];
            $datafile->take_over = $ob['take_over'];
            $datafile->take_over_hari_ini = $ob['take_over_hari_ini'];
            $datafile->status_deviasi = $ob['status_deviasi'];
            $datafile->status_fronting_agent = $ob['fronting_agent'];
            $datafile->status_pegawai = $ob['status_pegawai'];
            $datafile->status_usia_debitur = $ob['status_usia_debitur'];


            $datafile->save();


            //$produk_title='';
            return redirect()->route(checkJenisProduk($ob['produk']))
                ->with('success', 'Data Loan created successfully')
            ;
        } catch (QueryException $e) {
            //dd($e);
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return redirect()->route('datafile.searchLoan')->with('error', 'Loan App No : ' . $ob['loan_app_no'] . ' already exist');
            }
        }

    }


    public function index(Request $request)
    {

        $pagination = 10;
        $filter = false;
        $filterx = false;
        $produk_title = '';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4' || $request->action == '5') {
            $filter = true;
        }

        $role = Session('role');
        $branch = Session('branch_code');

        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                $query
                    ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                    ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                    ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                    ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                    ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
            })->when($filter, function ($query) use ($request) {
                $role = Session('role');
                if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                    $field_status = 'final_status_spv1';
                    if ($request->action == 0) {

                        $query->where(function ($query) {
                            $query->where('final_status_spv1', '=', 0)
                                ->orWhere('final_status_spv1', '=', 2);
                        });
                    } else if ($request->action == 5) {
                        $query->where(function ($query) {
                            $query->where('final_status_spv1', '=', 0)
                                ->orWhere('final_status_spv1', '=', 2);
                        });
                        $query->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'));
                    } else {

                        $query->where($field_status, '=', $request->action);
                    }
                } else if ($role == 'spv2') {
                    $field_status = 'final_status_spv2';
                    if ($request->action == 5) {
                        $query->where($field_status, '=', 0);
                        $query->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'));
                    } else {
                        $query->where($field_status, '=', $request->action);
                    }
                } else if ($role == 'spv3') {
                    $field_status = 'final_status_spv3';
                    if ($request->action == 0) {
                        $query->where(function ($query) {
                            $query->where('final_status_spv2', '=', 1)
                                ->orWhere('final_status_spv2', '=', 3);
                        });
                        $query->where('final_status', '<>', 4);
                    } else if ($request->action == 5) {
                        $query->where(function ($query) {
                            $query->where('final_status_spv2', '=', 1)
                                ->orWhere('final_status_spv2', '=', 3);
                        });
                        $query->where('final_status', '<>', 4);
                        $query->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'));
                    } else {
                        $query->where($field_status, '=', $request->action);
                    }
                }
                if ($request->action != 4) {
                    $query->where('final_status', '<>', 4);
                }

                if ($request->action == 5) {
                    $query->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'));
                }

            })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));
        } else {

            $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                $query
                    ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                    ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                    ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                    ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                    ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
            })->when($filter, function ($query) use ($request) {
                $role = Session('role');
                if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                    $field_status = 'final_status_spv1';
                    if ($request->action == 5) {
                        $query->where('final_status_spv1', '=', 0)->orWhere('final_status_spv1', '=', 2);
                        //$query->where($field_status, '=', 0);
                        $query->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'));
                    } else {
                        $query->where($field_status, '=', $request->action);
                    }
                } else if ($role == 'spv2') {
                    $field_status = 'final_status_spv2';
                    $query->where('final_status_spv1', '<>', 0);
                    if ($request->action == 5) {
                        $query->where($field_status, '=', 0);
                        $query->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'));
                    } else {
                        $query->where($field_status, '=', $request->action);
                        $query->where(DB::raw('date(date_input)'), '<', DB::raw('date(now())'));
                    }

                } else if ($role == 'spv3' || $role == 'spv4') {

                    $field_status = 'final_status_spv3';
                    //$query->where('final_status_spv2', '=', 1)->orWhere('final_status_spv2', '=', 3);

                    if ($request->action == 0) {

                        $query->where(function ($query) {
                            $query->where('final_status_spv2', '=', 1)
                                ->orWhere('final_status_spv2', '=', 3)
                            ;
                        });
                        $query->where('final_status_spv3', '=', 0);
                        //$query->where('final_status', '<>', 4);
                    } else if ($request->action == 5) {
                        $query->where(function ($query) {
                            $query->where('final_status_spv2', '=', 1)
                                ->orWhere('final_status_spv2', '=', 3);
                        });
                        $query->where('final_status_spv3', '=', 0);
                        $query->where('final_status', '<>', 4);
                        $query->where(DB::raw('date(date_input)'), '=', DB::raw('date(now())'));
                    } else {
                        $query->where($field_status, '=', $request->action);
                    }
                }
                if ($request->action != 4) {
                    $query->where('final_status', '<>', 4);
                }


            })->orderBy('date_input', 'desc')->paginate($pagination);

            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));
        }

        //



        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function waiting_spv1(Request $request)
    {
        // Cleanup expired locks before loading page
        cleanExpiredLocks();

        $pagination = 10;
        $filter = true;
        $filterx = false;
        $produk_title = 'Waiting Verify Supervisor';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        // if($request->action=='0' || $request->action=='1' || $request->action=='2' || $request->action=='3' || $request->action=='4'){
        //     $filter=true;    
        // }

        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);


        $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
            $query
                ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
        })->when($filter, function ($query) use ($request) {
            $field_status = 'final_status_spv1';
            $query->where(function ($query) {
                $query->where('data_file.final_status_spv1', '=', 0)
                    ->orWhere('data_file.final_status_spv1', '=', 2);
            });
            //$query->where($field_status, '=', 0);
        })->orderBy('date_input', 'desc')->paginate($pagination);

        $datafile->appends($request->input('keyword_loan'));
        $datafile->appends($request->input('keyword_branch'));
        $datafile->appends($request->input('keyword_cif'));
        $datafile->appends($request->input('keyword_ktp'));
        $datafile->appends($request->input('keyword_name'));


        //



        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function waiting_spv2(Request $request)
    {
        // Cleanup expired locks before loading page
        cleanExpiredLocks();

        $pagination = 10;
        $filter = true;
        $filterx = false;
        $produk_title = 'Waiting Verify Level 2';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        // if($request->action=='0' || $request->action=='1' || $request->action=='2' || $request->action=='3' || $request->action=='4'){
        //     $filter=true;    
        // }

        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);


        $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
            $query
                ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
        })->when($filter, function ($query) use ($request) {
            $query->where('final_status_spv1', '=', 1);
            $query->where('final_status_spv2', '=', 0);
        })->orderBy('date_input', 'desc')->paginate($pagination);

        $datafile->appends($request->input('keyword_loan'));
        $datafile->appends($request->input('keyword_branch'));
        $datafile->appends($request->input('keyword_cif'));
        $datafile->appends($request->input('keyword_ktp'));
        $datafile->appends($request->input('keyword_name'));


        //



        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function waiting_tbo_spv2(Request $request)
    {

        $pagination = 10;
        $filter = true;
        $filterx = false;
        $produk_title = 'Waiting TBO Level 1';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        // if($request->action=='0' || $request->action=='1' || $request->action=='2' || $request->action=='3' || $request->action=='4'){
        //     $filter=true;    
        // }

        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);


        $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
            $query
                ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
        })->when($filter, function ($query) use ($request) {
            $query->where('final_status_spv1', '=', 3);
            $query->where('final_status_spv2', '=', 0);
        })->orderBy('date_input', 'desc')->paginate($pagination);

        $datafile->appends($request->input('keyword_loan'));
        $datafile->appends($request->input('keyword_branch'));
        $datafile->appends($request->input('keyword_cif'));
        $datafile->appends($request->input('keyword_ktp'));
        $datafile->appends($request->input('keyword_name'));


        //



        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function waiting_spv3(Request $request)
    {
        // Cleanup expired locks before loading page
        cleanExpiredLocks();

        $pagination = 10;
        $filter = true;
        $filterx = false;
        $produk_title = 'Waiting Verify Level 2';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        // if($request->action=='0' || $request->action=='1' || $request->action=='2' || $request->action=='3' || $request->action=='4'){
        //     $filter=true;    
        // }

        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);


        $datafile = DataFileModel::whereDate('date_input', '!=', now()->toDateString())
    ->when($filterx, function ($query) use ($request) {
        $query
            ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
            ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
            ->where('no_cif', 'like', "%{$request->keyword_cif}%")
            ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
            ->where('nama_debitur', 'like', "%{$request->keyword_name}%");

        })->when($filter, function ($query) use ($request) {

            /* $query->where(function ($query) {
                 $query->where('data_file.final_status_spv2','=',1)
                       ->orWhere('data_file.final_status_spv2', '=', 3);
               })*/
            $query->where('final_status_spv1', '=', 1)
                ->where('final_status_spv2', '=', 0);
        })->orderBy('date_input', 'desc')->paginate($pagination);

        $datafile->appends($request->input('keyword_loan'));
        $datafile->appends($request->input('keyword_branch'));
        $datafile->appends($request->input('keyword_cif'));
        $datafile->appends($request->input('keyword_ktp'));
        $datafile->appends($request->input('keyword_name'));


        //



        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }


    public function waiting_tbo_spv3(Request $request)
    {

        $pagination = 10;
        $filter = true;
        $filterx = false;
        $produk_title = 'Waiting TBO Level 2';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        // if($request->action=='0' || $request->action=='1' || $request->action=='2' || $request->action=='3' || $request->action=='4'){
        //     $filter=true;    
        // }

        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);


        $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
            $query
                ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
        })->when($filter, function ($query) use ($request) {
            $query->where('final_status_spv1', '=', 3);
            $query->where('final_status_spv2', '=', 0);
        })->orderBy('date_input', 'desc')->paginate($pagination);

        $datafile->appends($request->input('keyword_loan'));
        $datafile->appends($request->input('keyword_branch'));
        $datafile->appends($request->input('keyword_cif'));
        $datafile->appends($request->input('keyword_ktp'));
        $datafile->appends($request->input('keyword_name'));


        //



        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function pending_tbo_overdue(Request $request)
    {

        $pagination = 10;
        $filter = true;
        $filterx = false;
        $produk_title = 'Pending TBO Overdue';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        // if($request->action=='0' || $request->action=='1' || $request->action=='2' || $request->action=='3' || $request->action=='4'){
        //     $filter=true;    
        // }

        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);


        $datafile = DataFileModel::whereRelation('comment', 'tbo_date', '<=', 'now()')->when($filterx, function ($query) use ($request) {
            $query
                ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
        })->when($filter, function ($query) use ($request) {
            $role = Session('role');
            if ($role == 'spv2') {
                $field_status = 'final_status_spv2';
                $query->where('final_status_spv1', '=', 1);
                $query->where('final_status', '=', 3);


            } else if ($role == 'spv3') {
                $field_status = 'final_status_spv3';
                $query->where('final_status_spv2', '=', 1);
                $query->where('final_status', '=', 3);

            }

        })->orderBy('date_input', 'desc')->paginate($pagination);

        $datafile->appends($request->input('keyword_loan'));
        $datafile->appends($request->input('keyword_branch'));
        $datafile->appends($request->input('keyword_cif'));
        $datafile->appends($request->input('keyword_ktp'));
        $datafile->appends($request->input('keyword_name'));


        //



        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }
    public function pending_tbo(Request $request)
    {

        $pagination = 10;
        $filter = true;
        $filterx = false;
        $produk_title = 'Pending TBO';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        // if($request->action=='0' || $request->action=='1' || $request->action=='2' || $request->action=='3' || $request->action=='4'){
        //     $filter=true;    
        // }

        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);


        $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
            $query
                ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
        })->when($filter, function ($query) use ($request) {
            $role = Session('role');
            if ($role == 'spv2') {
                $field_status = 'final_status_spv2';
                $query->where('final_status_spv1', '<>', 3);
                $query->where('final_status', '=', 3);


            } else if ($role == 'spv3') {
                $field_status = 'final_status_spv3';
                $query->where('final_status_spv2', '<>', 0);
                $query->where('final_status', '=', 3);

            }

        })->orderBy('date_input', 'desc')->paginate($pagination);

        $datafile->appends($request->input('keyword_loan'));
        $datafile->appends($request->input('keyword_branch'));
        $datafile->appends($request->input('keyword_cif'));
        $datafile->appends($request->input('keyword_ktp'));
        $datafile->appends($request->input('keyword_name'));


        //



        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function tbo(Request $request)
    {

        $pagination = 10;
        $filter = true;
        $filterx = false;
        $produk_title = 'All TBO';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        /* if($request->action=='0' || $request->action=='1' || $request->action=='2' || $request->action=='3' || $request->action=='4'){
             $filter=true;    
         }*/

        $role = Session('role');
        $branch = Session('branch_code');
        $branch_parent = Session('branch_parent');
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {

            if ($branch == $branch_parent) {
                $query = DataFileModel::select('data_file.*')
                ->selectRaw('comment.comment, comment.user_name, comment.level_spv, comment.comment_date, comment.flag_spv, comment.reason, MAX(comment.tbo_date) as tbo_date')
                ->join('comment', 'comment.loan_app_no', '=', 'data_file.loan_app_no')
                ->join('branchlist', 'branchlist.branch_code', '=', 'data_file.kode_cabang')
                ->where('data_file.final_status', 3)
                ->whereIn('data_file.kode_cabang', function ($query) {
                    $query->select('branch_code')
                        ->from('branchlist')
                        ->whereIn('parent_branch', function ($subquery) {
                            $subquery->select('parent_branch')
                                ->from('branchlist')
                                ->where('branch_code', Session('branch_code'));
                        });
                })
                ->when($filterx, function ($query) use ($request) {
                    $query->where('data_file.loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('data_file.kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('data_file.no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('data_file.no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('data_file.nama_debitur', 'like', "%{$request->keyword_name}%");
                })
                ->groupBy('data_file.loan_app_no')
                ->orderByDesc('tbo_date');

            $datafile = $query->paginate($pagination);

            }else{
                $datafile = DataFileModel::select(
                    'data_file.modul',
                    'data_file.kode_cabang',
                    'data_file.loan_app_no',
                    'data_file.no_cif',
                    'data_file.no_ktp',
                    'data_file.nama_debitur',
                    'data_file.ttl',
                    'data_file.alamat_rumah',
                    'data_file.no_tlp_rumah',
                    'data_file.instansi',
                    'data_file.alamat_kantor',
                    'data_file.no_tlp_kantor',
                    'data_file.plafond',
                    'data_file.jangka_waktu',
                    'data_file.rate',
                    'data_file.angsuran',
                    'data_file.tanggal_jatuh_tempo',
                    'data_file.produk',
                    'data_file.user_input',
                    'data_file.branch_input',
                    'data_file.date_input',
                    'data_file.user_spv1',
                    'data_file.final_status_spv1',
                    'data_file.date_flag_spv1',
                    'data_file.user_spv2',
                    'data_file.final_status_spv2',
                    'data_file.date_flag_spv2',
                    'data_file.user_spv3',
                    'data_file.final_status_spv3',
                    'data_file.date_flag_spv3',
                    'data_file.final_status',
                    'data_file.processed',
                    'data_file.updated_at',
                    'data_file.created_at',
                    'data_file.status_pernikahan',
                    'data_file.pekerjaan',
                    'data_file.fasilitas',
                    'data_file.flag_process',
                    'comment.comment',
                    'comment.user_name',
                    'comment.level_spv',
                    'comment.comment_date',
                    'comment.flag_spv',
                    DB::raw('MAX(comment.tbo_date) as tbo_date'),
                    'comment.reason',
                )->join('comment', 'comment.loan_app_no', 'data_file.loan_app_no')->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('data_file.loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    $query->where('final_status', '=', 3);
    
                })->where('kode_cabang', "=", $branch)->groupBy('data_file.loan_app_no')->orderBy('comment.tbo_date', 'desc')->paginate($pagination);

            }
            

           

            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));
        } else {

            $datafile = DataFileModel::select(
                'data_file.modul',
                'data_file.kode_cabang',
                'data_file.loan_app_no',
                'data_file.no_cif',
                'data_file.no_ktp',
                'data_file.nama_debitur',
                'data_file.ttl',
                'data_file.alamat_rumah',
                'data_file.no_tlp_rumah',
                'data_file.instansi',
                'data_file.alamat_kantor',
                'data_file.no_tlp_kantor',
                'data_file.plafond',
                'data_file.jangka_waktu',
                'data_file.rate',
                'data_file.angsuran',
                'data_file.tanggal_jatuh_tempo',
                'data_file.produk',
                'data_file.user_input',
                'data_file.branch_input',
                'data_file.date_input',
                'data_file.user_spv1',
                'data_file.final_status_spv1',
                'data_file.date_flag_spv1',
                'data_file.user_spv2',
                'data_file.final_status_spv2',
                'data_file.date_flag_spv2',
                'data_file.user_spv3',
                'data_file.final_status_spv3',
                'data_file.date_flag_spv3',
                'data_file.final_status',
                'data_file.processed',
                'data_file.updated_at',
                'data_file.created_at',
                'data_file.status_pernikahan',
                'data_file.pekerjaan',
                'data_file.fasilitas',
                'data_file.flag_process',
                'comment.comment',
                'comment.user_name',
                'comment.level_spv',
                'comment.comment_date',
                'comment.flag_spv',
                DB::raw('MAX(comment.tbo_date) as tbo_date'),
                'comment.reason',
            )->join('comment', 'comment.loan_app_no', 'data_file.loan_app_no')->when($filterx, function ($query) use ($request) {
                $query
                    ->where('data_file.loan_app_no', 'like', "%{$request->keyword_loan}%")
                    ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                    ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                    ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                    ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
            })->when($filter, function ($query) use ($request) {
                $role = Session('role');
                $query->where('final_status', '=', 3);

            })->groupBy('data_file.loan_app_no')->orderBy('comment.tbo_date', 'desc')->paginate($pagination);

            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));
        }

        //



        return view('loan.index-tbo', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }
    
    public function tbo_overdue(Request $request)
    {

        $pagination = 10;
        $filter = true;
        $filterx = false;
        $produk_title = 'All TBO Overdue';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        

        $role = Session('role');
        $branch = Session('branch_code');
        $branch_parent = Session('branch_parent');
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {

            if ($branch == $branch_parent) {
                $query = DataFileModel::select('data_file.*')
                ->selectRaw('comment.comment, comment.user_name, comment.level_spv, comment.comment_date, comment.flag_spv, comment.reason, MAX(comment.tbo_date) as tbo_date')
                ->join('comment', 'comment.loan_app_no', '=', 'data_file.loan_app_no')
                ->join('branchlist', 'branchlist.branch_code', '=', 'data_file.kode_cabang')
                ->where('data_file.final_status', 3)
                ->whereIn('data_file.kode_cabang', function ($query) {
                    $query->select('branch_code')
                        ->from('branchlist')
                        ->whereIn('parent_branch', function ($subquery) {
                            $subquery->select('parent_branch')
                                ->from('branchlist')
                                ->where('branch_code', Session('branch_code'));
                        });
                })
                ->when($filterx, function ($query) use ($request) {
                    $query->where('data_file.loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('data_file.kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('data_file.no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('data_file.no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('data_file.nama_debitur', 'like', "%{$request->keyword_name}%");
                })
                ->groupBy('data_file.loan_app_no')
                ->havingRaw('DATE(MAX(comment.tbo_date)) < CURDATE()')
                ->orderByDesc('tbo_date');

            $datafile = $query->paginate($pagination);
            }else{
                $datafile = DataFileModel::select(
                    'data_file.modul',
                    'data_file.kode_cabang',
                    'data_file.loan_app_no',
                    'data_file.no_cif',
                    'data_file.no_ktp',
                    'data_file.nama_debitur',
                    'data_file.ttl',
                    'data_file.alamat_rumah',
                    'data_file.no_tlp_rumah',
                    'data_file.instansi',
                    'data_file.alamat_kantor',
                    'data_file.no_tlp_kantor',
                    'data_file.plafond',
                    'data_file.jangka_waktu',
                    'data_file.rate',
                    'data_file.angsuran',
                    'data_file.tanggal_jatuh_tempo',
                    'data_file.produk',
                    'data_file.user_input',
                    'data_file.branch_input',
                    'data_file.date_input',
                    'data_file.user_spv1',
                    'data_file.final_status_spv1',
                    'data_file.date_flag_spv1',
                    'data_file.user_spv2',
                    'data_file.final_status_spv2',
                    'data_file.date_flag_spv2',
                    'data_file.user_spv3',
                    'data_file.final_status_spv3',
                    'data_file.date_flag_spv3',
                    'data_file.final_status',
                    'data_file.processed',
                    'data_file.updated_at',
                    'data_file.created_at',
                    'data_file.status_pernikahan',
                    'data_file.pekerjaan',
                    'data_file.fasilitas',
                    'data_file.flag_process',
                    'comment.comment',
                    'comment.user_name',
                    'comment.level_spv',
                    'comment.comment_date',
                    'comment.flag_spv',
                    DB::raw('MAX(comment.tbo_date) as tbo_date'),
                    'comment.reason'
                )->join('comment', 'comment.loan_app_no', 'data_file.loan_app_no')
                    ->when($filterx, function ($query) use ($request) {
                        $query
                            ->where('data_file.loan_app_no', 'like', "%{$request->keyword_loan}%")
                            ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                            ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                            ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                            ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                    })->when($filter, function ($query) use ($request) {
                        //$role=Session('role');
                        //$query->where(DB::raw('DATE(comment.tbo_date )'), '<', DB::raw('DATE(now())'));
                        $query->whereRaw("comment.loan_app_no IN (SELECT loan_app_no FROM data_file WHERE final_status = 3 and kode_cabang = '" . Session('branch_code') . "')");
                    })
                    ->where('kode_cabang', "=", $branch)
                    ->groupBy('data_file.loan_app_no')
                    ->having(DB::raw('date(max( comment.tbo_date ))'), "<", DB::raw('date(now())'))
                    ->orderBy('comment.tbo_date', 'desc')
                    ->paginate($pagination);
            }
            

            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));
        } else {
            
            $datafile = DataFileModel::select(
                'data_file.modul',
                'data_file.kode_cabang',
                'data_file.loan_app_no',
                'data_file.no_cif',
                'data_file.no_ktp',
                'data_file.nama_debitur',
                'data_file.ttl',
                'data_file.alamat_rumah',
                'data_file.no_tlp_rumah',
                'data_file.instansi',
                'data_file.alamat_kantor',
                'data_file.no_tlp_kantor',
                'data_file.plafond',
                'data_file.jangka_waktu',
                'data_file.rate',
                'data_file.angsuran',
                'data_file.tanggal_jatuh_tempo',
                'data_file.produk',
                'data_file.user_input',
                'data_file.branch_input',
                'data_file.date_input',
                'data_file.user_spv1',
                'data_file.final_status_spv1',
                'data_file.date_flag_spv1',
                'data_file.user_spv2',
                'data_file.final_status_spv2',
                'data_file.date_flag_spv2',
                'data_file.user_spv3',
                'data_file.final_status_spv3',
                'data_file.date_flag_spv3',
                'data_file.final_status',
                'data_file.processed',
                'data_file.updated_at',
                'data_file.created_at',
                'data_file.status_pernikahan',
                'data_file.pekerjaan',
                'data_file.fasilitas',
                'data_file.flag_process',
                'comment.comment',
                'comment.user_name',
                'comment.level_spv',
                'comment.comment_date',
                'comment.flag_spv',
                DB::raw('MAX(comment.tbo_date) as tbo_date'),
                'comment.reason'
            )->join('comment', 'comment.loan_app_no', 'data_file.loan_app_no')
                ->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('data_file.loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    //$role=Session('role');
                    //$query->where(DB::raw('DATE(comment.tbo_date )'), '<', DB::raw('DATE(now())'));
                    $query->whereRaw("comment.loan_app_no IN (SELECT loan_app_no FROM data_file WHERE final_status = 3)");
                })
                //->where('kode_cabang',"=",$branch)
                ->groupBy('data_file.loan_app_no')
                ->having(DB::raw('date(max( comment.tbo_date ))'), "<", DB::raw('date(now())'))
                ->orderBy('comment.tbo_date', 'desc')
                ->paginate($pagination);


            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));
        }

        //



        return view('loan.index-tbo', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }
    

    public function tbo_overdue_pending(Request $request)
    {

        $pagination = 10;
        $filter = true;
        $filterx = false;
        $produk_title = 'All TBO Pending';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        /* if($request->action=='0' || $request->action=='1' || $request->action=='2' || $request->action=='3' || $request->action=='4'){
             $filter=true;    
         }*/

        $role = Session('role');
        $branch = Session('branch_code');
        $branch_parent = Session('branch_parent');
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            /* $datafile    = DataFileModel::join('comment','comment.loan_app_no','data_file.loan_app_no')
             ->join(DB::raw('(
                 SELECT max(comment_date) MaxPostDate, loan_app_no ,tbo_date
                 FROM `comment`
                 WHERE flag_spv = 3
                 GROUP BY loan_app_no
             ) p2'), 
             function($join)
             {
                 $join->on('data_file.loan_app_no', '=', 'p2.loan_app_no');
                 $join->on('comment.comment_date', '=', 'p2.MaxPostDate');
             })->when($filterx, function ($query) use ($request) {
                 $query
                 ->where('data_file.loan_app_no', 'like', "%{$request->keyword_loan}%")
                 ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                 ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                 ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                 ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                 })->when($filter, function ($query) use ($request) {
                     $role=Session('role');
                     $query->where(DB::raw('DATE(comment.tbo_date )'), '>=', DB::raw('DATE(now())'));
                     $query->where('data_file.final_status', '=', 3);
                     
                 })->where('kode_cabang',"=",$branch)->groupBy('data_file.loan_app_no')->orderBy('comment.tbo_date', 'desc')->paginate($pagination);
              
                 $datafile->appends($request->input('keyword_loan'));
                 $datafile->appends($request->input('keyword_branch'));
                 $datafile->appends($request->input('keyword_cif'));
                 $datafile->appends($request->input('keyword_ktp'));
                 $datafile->appends($request->input('keyword_name'));
                 */
            if ($branch == $branch_parent) {
                $query = DataFileModel::select('data_file.*')
                ->selectRaw('comment.comment, comment.user_name, comment.level_spv, comment.comment_date, comment.flag_spv, comment.reason, MAX(comment.tbo_date) as tbo_date')
                ->join('comment', 'comment.loan_app_no', '=', 'data_file.loan_app_no')
                ->join('branchlist', 'branchlist.branch_code', '=', 'data_file.kode_cabang')
                ->where('data_file.final_status', 3)
                ->whereIn('data_file.kode_cabang', function ($query) {
                    $query->select('branch_code')
                        ->from('branchlist')
                        ->whereIn('parent_branch', function ($subquery) {
                            $subquery->select('parent_branch')
                                ->from('branchlist')
                                ->where('branch_code', Session('branch_code'));
                        });
                })
                ->when($filterx, function ($query) use ($request) {
                    $query->where('data_file.loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('data_file.kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('data_file.no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('data_file.no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('data_file.nama_debitur', 'like', "%{$request->keyword_name}%");
                })
                ->groupBy('data_file.loan_app_no')
                ->havingRaw('DATE(MAX(comment.tbo_date)) >= CURDATE()')
                ->orderByDesc('tbo_date');

            $datafile = $query->paginate($pagination);
            }else{
                $datafile = DataFileModel::select(
                'data_file.modul',
                'data_file.kode_cabang',
                'data_file.loan_app_no',
                'data_file.no_cif',
                'data_file.no_ktp',
                'data_file.nama_debitur',
                'data_file.ttl',
                'data_file.alamat_rumah',
                'data_file.no_tlp_rumah',
                'data_file.instansi',
                'data_file.alamat_kantor',
                'data_file.no_tlp_kantor',
                'data_file.plafond',
                'data_file.jangka_waktu',
                'data_file.rate',
                'data_file.angsuran',
                'data_file.tanggal_jatuh_tempo',
                'data_file.produk',
                'data_file.user_input',
                'data_file.branch_input',
                'data_file.date_input',
                'data_file.user_spv1',
                'data_file.final_status_spv1',
                'data_file.date_flag_spv1',
                'data_file.user_spv2',
                'data_file.final_status_spv2',
                'data_file.date_flag_spv2',
                'data_file.user_spv3',
                'data_file.final_status_spv3',
                'data_file.date_flag_spv3',
                'data_file.final_status',
                'data_file.processed',
                'data_file.updated_at',
                'data_file.created_at',
                'data_file.status_pernikahan',
                'data_file.pekerjaan',
                'data_file.fasilitas',
                'data_file.flag_process',
                'comment.comment',
                'comment.user_name',
                'comment.level_spv',
                'comment.comment_date',
                'comment.flag_spv',
                DB::raw('MAX(comment.tbo_date) as tbo_date'),
                'comment.reason'
            )->join('comment', 'comment.loan_app_no', 'data_file.loan_app_no')
                ->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('data_file.loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    //$role=Session('role');
                    //$query->where(DB::raw('DATE(comment.tbo_date )'), '<', DB::raw('DATE(now())'));
                    $query->whereRaw("comment.loan_app_no IN (SELECT loan_app_no FROM data_file WHERE final_status = 3 and kode_cabang = '" . Session('branch_code') . "')");
                })
                ->where('kode_cabang', "=", $branch)
                ->groupBy('data_file.loan_app_no')
                ->having(DB::raw('date(max( comment.tbo_date ))'), ">=", DB::raw('date(now())'))
                ->orderBy('comment.tbo_date', 'desc')
                ->paginate($pagination);
            }
            

            


            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));
        } else {

            /* $datafile    = DataFileModel::join('comment','comment.loan_app_no','data_file.loan_app_no')
             ->join(DB::raw('(
                 SELECT max(comment_date) MaxPostDate, loan_app_no ,tbo_date
                 FROM `comment`
                 WHERE flag_spv = 3
                 GROUP BY loan_app_no
             ) p2'), 
             function($join)
             {
                 $join->on('data_file.loan_app_no', '=', 'p2.loan_app_no');
                 $join->on('comment.comment_date', '=', 'p2.MaxPostDate');
             })->when($filterx, function ($query) use ($request) {
                 $query
                 ->where('data_file.loan_app_no', 'like', "%{$request->keyword_loan}%")
                 ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                 ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                 ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                 ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                 })->when($filter, function ($query) use ($request) {
                     $role=Session('role');
                     $query->where(DB::raw('DATE(comment.tbo_date )'), '>=', DB::raw('DATE(now())'));
                     $query->where('data_file.final_status', '=', 3);
                     
                 })->groupBy('data_file.loan_app_no')->orderBy('comment.tbo_date', 'desc')->paginate($pagination);
              
                 $datafile->appends($request->input('keyword_loan'));
                 $datafile->appends($request->input('keyword_branch'));
                 $datafile->appends($request->input('keyword_cif'));
                 $datafile->appends($request->input('keyword_ktp'));
                 $datafile->appends($request->input('keyword_name'));
                 */
            $datafile = DataFileModel::select(
                'data_file.modul',
                'data_file.kode_cabang',
                'data_file.loan_app_no',
                'data_file.no_cif',
                'data_file.no_ktp',
                'data_file.nama_debitur',
                'data_file.ttl',
                'data_file.alamat_rumah',
                'data_file.no_tlp_rumah',
                'data_file.instansi',
                'data_file.alamat_kantor',
                'data_file.no_tlp_kantor',
                'data_file.plafond',
                'data_file.jangka_waktu',
                'data_file.rate',
                'data_file.angsuran',
                'data_file.tanggal_jatuh_tempo',
                'data_file.produk',
                'data_file.user_input',
                'data_file.branch_input',
                'data_file.date_input',
                'data_file.user_spv1',
                'data_file.final_status_spv1',
                'data_file.date_flag_spv1',
                'data_file.user_spv2',
                'data_file.final_status_spv2',
                'data_file.date_flag_spv2',
                'data_file.user_spv3',
                'data_file.final_status_spv3',
                'data_file.date_flag_spv3',
                'data_file.final_status',
                'data_file.processed',
                'data_file.updated_at',
                'data_file.created_at',
                'data_file.status_pernikahan',
                'data_file.pekerjaan',
                'data_file.fasilitas',
                'data_file.flag_process',
                'comment.comment',
                'comment.user_name',
                'comment.level_spv',
                'comment.comment_date',
                'comment.flag_spv',
                DB::raw('MAX(comment.tbo_date) as tbo_date'),
                'comment.reason'
            )->join('comment', 'comment.loan_app_no', 'data_file.loan_app_no')
                ->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('data_file.loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    //$role=Session('role');
                    //$query->where(DB::raw('DATE(comment.tbo_date )'), '<', DB::raw('DATE(now())'));
                    $query->whereRaw("comment.loan_app_no IN (SELECT loan_app_no FROM data_file WHERE final_status = 3)");
                })
                //->where('kode_cabang',"=",$branch)
                ->groupBy('data_file.loan_app_no')
                ->having(DB::raw('date(max( comment.tbo_date ))'), ">=", DB::raw('date(now())'))
                ->orderBy('comment.tbo_date', 'desc')
                ->paginate($pagination);

            //dd($datafile);

            $datafile->appends($request->input('keyword_loan'));
            $datafile->appends($request->input('keyword_branch'));
            $datafile->appends($request->input('keyword_cif'));
            $datafile->appends($request->input('keyword_ktp'));
            $datafile->appends($request->input('keyword_name'));
        }

        //



        return view('loan.index-tbo', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }


    public function kupen(Request $request)
    {
        $pagination = 10;
        $filterx = false;
        $filter = false;

        $produk_title = "Kupen Umum";

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4') {
            $filter = true;
        }



        $typeproduct = 1;
        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);

        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            if ($typeproduct == 1) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                // $datafile->appends($request->only('keyword'));
                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                //$datafile->appends($request->only('keyword'));
                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        } else {
            if ($typeproduct == 1) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                // $datafile->appends($request->only('keyword'));


                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                //$datafile->appends($request->only('keyword'));

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        }


        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function kupenJandaDuda(Request $request)
    {
        $pagination = 10;
        $filterx = false;
        $filter = false;

        $produk_title = "Kupen Janda";

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4') {
            $filter = true;
        }



        $typeproduct = 9;
        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);

        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            if ($typeproduct == 9) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                // $datafile->appends($request->only('keyword'));
                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                //$datafile->appends($request->only('keyword'));
                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        } else {
            //dd($typeproduct);
            $role = Session('role');
            //dd($role);
            if ($typeproduct == 9) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                   
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3' || $role == 'spv4')   {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                    
                })->orderBy('date_input', 'desc')->paginate($pagination);

                // $datafile->appends($request->only('keyword'));


                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                //$datafile->appends($request->only('keyword'));

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        }


        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }
    public function kupenHybrid(Request $request)
    {
        $pagination = 10;
        $filter = false;
        $filterx = false;
        $produk_title = 'Kupen Hybrid';
        //dd($produk_title);
        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4') {
            $filter = true;
        }
        $typeproduct = 2;
        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            if ($typeproduct == 2) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                //$datafile->appends($request->only('keyword'));
                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        } else {
            if ($typeproduct == 2) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        }


        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function kupengo(Request $request)
    {
        $pagination = 10;
        $filter = false;
        $filterx = false;
        $produk_title = 'Kupen Hybrid GO';
        // dd($produk_title);
        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4') {
            $filter = true;
        }
        $typeproduct = 8;
        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            if ($typeproduct == 8) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                //$datafile->appends($request->only('keyword'));
                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        } else {
            if ($typeproduct == 8) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        }


        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function tht(Request $request)
    {
        $pagination = 10;
        $filter = false;
        $filterx = false;
        $produk_title = 'THT';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4') {
            $filter = true;
        }
        $typeproduct = 7;
        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            if ($typeproduct == 7) {
                //$datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $datafile = DataFileModel::whereRelation('masterproduk', 'modul', $produk_title)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                //$datafile->appends($request->only('keyword'));
                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        } else {
            if ($typeproduct == 7) {
                //$datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $datafile = DataFileModel::whereRelation('masterproduk', 'modul', $produk_title)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        }


        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function tawon(Request $request)
    {
        $pagination = 10;
        $filter = false;
        $filterx = false;
        $produk_title = 'TAWON';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4') {
            $filter = true;
        }
        $typeproduct = 10;
        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            if ($typeproduct == 10) {
                //$datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $datafile = DataFileModel::whereRelation('masterproduk', 'modul', $produk_title)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                //$datafile->appends($request->only('keyword'));
                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        } else {
            if ($typeproduct == 10) {
                //$datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'modul', $produk_title)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        }


        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function kupeg(Request $request)
    {
        $pagination = 10;
        $filter = false;
        $filterx = false;
        $produk_title = "Kupeg";
        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4') {
            $filter = true;
        }
        $typeproduct = 3;
        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            if ($typeproduct == 3) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        } else {
            if ($typeproduct == 3) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        }


        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function kph(Request $request)
    {
        $pagination = 10;
        $filter = false;
        $filterx = false;
        $produk_title = 'KPH';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4') {
            $filter = true;
        }
        $typeproduct = 4;
        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            if ($typeproduct == 4) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        } else {
            if ($typeproduct == 4) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        }


        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }


    public function kpkb_wni(Request $request)
    {
        $pagination = 10;
        $filter = false;
        $filterx = false;
        $produk_title = 'KPKB WNI';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }

        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4') {
            $filter = true;
        }
        $typeproduct = 5;
        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            if ($typeproduct == 5) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        } else {
            if ($typeproduct == 5) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        }


        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function kpkb_wna(Request $request)
    {
        $pagination = 10;
        $filter = false;
        $filterx = false;
        $produk_title = 'KPKB WNA';

        if (!empty($request->keyword_loan) || !empty($request->keyword_branch) || !empty($request->keyword_cif) || !empty($request->keyword_ktp) || !empty($request->keyword_name)) {
            $filterx = true;
        }
        if ($request->action == '0' || $request->action == '1' || $request->action == '2' || $request->action == '3' || $request->action == '4') {
            $filter = true;
        }
        $typeproduct = 6;
        $role = Session('role');
        $branch = Session('branch_code');
        //dd($branch);
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            if ($typeproduct == 6) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        } else {
            if ($typeproduct == 6) {
                $datafile = DataFileModel::whereRelation('masterproduk', 'jenis_produk', $typeproduct)->when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            } else {
                $datafile = DataFileModel::when($filterx, function ($query) use ($request) {
                    $query
                        ->where('loan_app_no', 'like', "%{$request->keyword_loan}%")
                        ->where('kode_cabang', 'like', "%{$request->keyword_branch}%")
                        ->where('no_cif', 'like', "%{$request->keyword_cif}%")
                        ->where('no_ktp', 'like', "%{$request->keyword_ktp}%")
                        ->where('nama_debitur', 'like', "%{$request->keyword_name}%");
                })->when($filter, function ($query) use ($request) {
                    $role = Session('role');
                    if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                        $field_status = 'final_status_spv1';
                    } else if ($role == 'spv2') {
                        $field_status = 'final_status_spv2';
                    } else if ($role == 'spv3') {
                        $field_status = 'final_status_spv3';
                    }
                    $query->where($field_status, '=', $request->action)
                    ;
                })->orderBy('date_input', 'desc')->paginate($pagination);

                $datafile->appends($request->input('keyword_loan'));
                $datafile->appends($request->input('keyword_branch'));
                $datafile->appends($request->input('keyword_cif'));
                $datafile->appends($request->input('keyword_ktp'));
                $datafile->appends($request->input('keyword_name'));
            }
        }


        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function verify(Request $request)
    {
        $pagination = 10;

        $role = Session('role');
        $branch = Session('branch_code');
        $filter = true;
        //dd($branch);
        $datafile = DataFileModel::with('masterproduk')->when($request->keyword, function ($query) use ($request) {
            $query
                ->where('loan_app_no', 'like', "%{$request->keyword}%");
        })->when($filter, function ($query) use ($request) {
            $query->where('processed', '=', 1)
            ;
        })->where('kode_cabang', "=", $branch)->orderBy('date_input', 'desc')->paginate($pagination);

        $datafile->appends($request->only('keyword'));


        $produk_title = '';
        return view('loan.index', [
            'datafile' => $datafile,
            'produk_title' => $produk_title,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    public function processverify($id)
    {

        $model = DataFileModel::findOrFail($id);
        $model->processed = 2;
        $model->save();

        $date_input = new DateTime($model->date_input);
        $compare_date = new DateTime('2024-11-06');

    if ($date_input > $compare_date){
        return redirect()->route('datafile.show_new', ['id' => $id]);
    }else{
        return redirect()->route('loans.show', ['loan' => $id]);
    }

        //return redirect()->route('loans.show', ['loan' => $id]);
    }



    public function pending(Request $request)
    {
        $pagination = 10;
        $pending = true;
        $role = Session('role');
        if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
            $field_status = 'final_status_spv1';
        } else if ($role == 'spv2') {
            $field_status = 'final_status_spv2';
        } else if ($role == 'spv3') {
            $field_status = 'final_status_spv3';
        } else {
            $field_status = '';
        }
        $datafile = DataFileModel::when($request->keyword, function ($query) use ($request) {
            $query
                ->where('loan_app_no', 'like', "%{$request->keyword}%");
        })->when($pending, function ($query) use ($request) {
            $role = Session('role');
            if ($role == 'spv1' || $role == 'pc' || $role == 'pcp' || $role == 'staff') {
                $field_status = 'final_status_spv1';
            } else if ($role == 'spv2') {
                $field_status = 'final_status_spv2';
            } else if ($role == 'spv3') {
                $field_status = 'final_status_spv3';
            }
            $query->where($field_status, '=', "0")
            ;
        })->orderBy('date_input', 'desc')->paginate($pagination);

        $datafile->appends($request->only('keyword'));

        return view('loan.index', [
            'datafile' => $datafile,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DataFileModel  $dataFile
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $data = DataFileModel::where('loan_app_no', '=', $id)->first();

        // === LOAN LOCKING IMPLEMENTATION ===
        // Cleanup expired locks terlebih dahulu
        cleanExpiredLocks();

        // Tentukan level SPV yang sedang mengakses
        $currentRole = Session('role');
        $currentNik = Session('nik');

        //Cek apakah user ini perlu lock (hanya SPV2, SPV3, SPV4, team_verifikator_lvl1, dan team_verifikator_lvl2) dan diakses dengan parameter lock
        $needsLock = in_array($currentRole, ['spv2', 'spv3', 'spv4', 'team_verifikator_lvl1', 'team_verifikator_lvl2']) && request()->has('lock');

        if ($needsLock) {
            // Coba untuk lock loan ini
            $lockResult = lockLoan($id, $currentNik, $currentRole);

            // Jika lock gagal karena sudah di-lock oleh user lain
            if (is_array($lockResult) && !$lockResult['success']) {
                return redirect()->back()
                    ->with('error', "Loan App No {$id} sedang direview oleh {$lockResult['locked_by_name']} ({$lockResult['locked_by']}). Silakan review dokumen yang lain.");
            }

            // Jika lock gagal karena error
            if ($lockResult === false) {
                return redirect()->back()
                    ->with('error', "Terjadi kesalahan saat mengakses loan. Silakan coba lagi.");
            }
        }
        //=== END LOAN LOCKING ===

        $datafile = DataFileModel::findOrFail($id);
        $detailfile = DetailFileModel::where('loan_app_no', $id)->get();
        $products = MasterProduct::where('produk', $datafile->produk)->first();

        if ($data->fasilitas == 3) {
            $map_product = MapProduct::where('id_jenis_produk', 9)->get();
        } else {
            if($data->modul=="TAWON"){
                $map_product = MapProduct::where('id_jenis_produk', 10)->get();
            }else{
                $map_product = MapProduct::where('id_jenis_produk', $products->jenis_produk)->get();
            }
        }
        $dokumen_early = DokumenEarly::All();

        $flag_spv = FlagSpv::All();

        $comment = Comment::where('loan_app_no', $id)->sortable()->get();

        //dd($datafile);


        return view('loan.show', compact('datafile', 'detailfile', 'products', 'map_product', 'flag_spv', 'comment', 'dokumen_early'));
    }

    public function show_new($id)
    {

        $data = DataFileModel::where('loan_app_no', '=', $id)->first();

        // === LOAN LOCKING IMPLEMENTATION ===
        // Cleanup expired locks terlebih dahulu
        cleanExpiredLocks();

        // Tentukan level SPV yang sedang mengakses
        $currentRole = Session('role');
        $currentNik = Session('nik');

        // Cek apakah user ini perlu lock (hanya SPV2, SPV3, SPV4, team_verifikator_lvl1, dan team_verifikator_lvl2) dan diakses dengan parameter lock
        $needsLock = in_array($currentRole, ['spv2', 'spv3', 'spv4', 'team_verifikator_lvl1', 'team_verifikator_lvl2']) && request()->has('lock');

        if ($needsLock) {
            // Coba untuk lock loan ini
            $lockResult = lockLoan($id, $currentNik, $currentRole);

            // Jika lock gagal karena sudah di-lock oleh user lain
            if (is_array($lockResult) && !$lockResult['success']) {
                return redirect()->back()
                    ->with('error', "Loan App No {$id} sedang direview oleh {$lockResult['locked_by_name']} ({$lockResult['locked_by']}). Silakan review dokumen yang lain.");
            }

            // Jika lock gagal karena error
            if ($lockResult === false) {
                return redirect()->back()
                    ->with('error', "Terjadi kesalahan saat mengakses loan. Silakan coba lagi.");
            }
        }
        // === END LOAN LOCKING ===

        $datafile = DataFileModel::findOrFail($id);
        $detailfile = DetailFileModel::where('loan_app_no', $id)->get();
        $products = MasterProduct::where('produk', $datafile->produk)->first();
        $kategori = DokumenKategori::with(['dokumen.subDokumen'])->get();
        $dokumen_early = DokumenEarly::All();

        $flag_spv = FlagSpv::All();

        $comment = Comment::where('loan_app_no', $id)->sortable()->get();
        
        if ($data->fasilitas == 3) {
            $map_product = MapProduct::where('id_jenis_produk', 9)->get();
           
            return view('loan.show', compact('datafile', 'detailfile', 'products', 'map_product', 'flag_spv', 'comment', 'dokumen_early'));
        } else {
            if($data->modul=="TAWON"){
                $map_product = MapProduct::where('id_jenis_produk', 10)->get();
            }else{
                $map_product = MapProduct::where('id_jenis_produk', $products->jenis_produk)->get();
            }
            
        }
        
        if ($data->modul == 'KPH' ||
            $data->modul == 'KPKB-WNA' ||
            $data->modul == 'KPKB-WNI' ||
            $data->modul == 'THT' ||
            $data->modul == 'TAWON') {
            return view('loan.show', compact('datafile', 'detailfile', 'products', 'map_product', 'flag_spv', 'comment', 'dokumen_early'));
        }

        $map_product = MapProduct::where('id_jenis_produk', $products->jenis_produk)->get();

        //dd($datafile);


        return view('loan.show-new', compact('kategori', 'datafile', 'detailfile', 'products', 'map_product', 'flag_spv', 'comment', 'dokumen_early'));
    }

    public function dokumen_store(Request $request)
    {
        //dd($request);
        // Check cut off time (berdasarkan logika Anda sebelumnya)
        if (checkCutOff(checkFasilitas($request->loan_app_no))) {
            return response()->json([
                'message' => 'Cut Off Time',
                'errors' => ['file' => 'Lakukan Proses Upload Dokumen Pada Jam Layanan yang telah ditentukan.']
            ], 400);
        }

        // Validasi file
        $request->validate([
            'file' => 'required|mimes:pdf,jpg,jpeg|max:2000', // Batasi tipe file dan ukuran
            'type' => 'required|string', // Validasi type dokumen (wajib ada)
            'name' => 'required|string', // Validasi nama dokumen (wajib ada)
            'id' => 'required|integer' // Validasi ID dari dokumen/subdokumen
        ]);

        // Ambil nomor aplikasi pinjaman dan tentukan direktori penyimpanan
        $loan_app_no = $request->loan_app_no;
        $dir = '/' . Session('branch_code') . '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
        $name = $dir . $loan_app_no . '-' . str_replace('/', '-', $request->name) . '-' . time() . '.' . $request->file->getClientOriginalExtension();

        // Buat path ke directory file yang akan disimpan
        $path = public_path('indexed/' . Session('branch_code') . '/' . date('Y') . '/' . date('m') . '/' . date('d'));
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true); // Buat folder jika belum ada
        }
        //dd($request->all());
        // Pindahkan file ke lokasi penyimpanan yang ditentukan
        $request->file->move($path, $name);

        // Simpan informasi file ke database
        $file = new DetailFileModel;
        $file->loan_app_no = $loan_app_no;
        $file->file = $name;
        $file->branch_dir = Session('branch_code');
        $file->alias = $request->name;
        $file->created_at = now(); // Fungsi Laravel untuk timestamp sekarang
        $file->save();

        //dd($file);
        $filename = basename($name);
        // Kembalikan respons JSON yang sukses
        return response()->json([
            'success' => 'File berhasil diupload.',
            'file_path' => $name,
            'file' => [
                'name' => $file->file, // Nama file
                'alias' => $filename // Alias dari tabel detail_file
            ]
        ]);

    }



    public function listdoc($id)
    {
        // $id='ID021116866';
        $datafile = DataFileModel::findOrFail($id);
        $detailfile = DetailFileModel::where('loan_app_no', $id)->get();

        //$jenis_produk = JenisProduct::findOrFail($id);
        $products = MasterProduct::where('produk', $datafile->produk)->first();


        $map_product = MapProduct::where('id_jenis_produk', $products->jenis_produk)->get();
        //dd($map_product);

        return view('loan.list-document', compact('datafile', 'detailfile', 'products', 'map_product'));
    }

    public function uploadToServer(Request $request)
    {

        //if(checkFinalStatus($request->loan_app_no) == 0){
        if (checkCutOff(checkFasilitas($request->loan_app_no))) {
            return response()->json(['message' => 'Cut Off Time', 'errors' => ['file' => 'Lakukan Proses Upload Dokumen Pada Jam Layanan yang telah ditentukan.']]);
        }
        //}


        $request->validate([
            'file' => 'required|mimes:pdf,jpg,jpeg|max:2000',
        ]);


        $loan_app_no = $request->loan_app_no;
        $dir = '/' . Session('branch_code') . '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
        $name = $dir . $loan_app_no . '-' . str_replace('/', '-', $request->type) . '-' . time() . '.' . request()->file->getClientOriginalExtension();

        $path = public_path('indexed/' . Session('branch_code') . '/' . date('Y') . '/' . date('m') . '/' . date('d'));
        if (!File::isDirectory($path)) {

            File::makeDirectory($path, 0777, true, true);

        }
        $request->file->move($path, $name);

        $file = new DetailFileModel;
        $file->loan_app_no = $loan_app_no;
        $file->file = $name;
        $file->branch_dir = Session('branch_code');
        $file->alias = $request->type;
        $file->created_at = date('Y-m-d H:i:s');
        $file->save();

        return response()->json(['success' => 'Successfully uploaded.']);
    }

    public function copyToServer(Request $request)
    {
        if (checkCutOff(checkFasilitas($request->loan_app_no))) {
            return response()->json(['message' => 'Cut Off Time', 'errors' => ['file' => 'Lakukan Proses Upload Dokumen Pada Jam Layanan yang telah ditentukan.']]);
        }

        $request->validate([
            'loan_app_no' => 'required',
            'loan_app_no_src' => 'required',
        ]);


        $loan_app_no = $request->loan_app_no;
        $loan_app_no_src = $request->loan_app_no_src;
        $detailfile = DetailFileModel::where('loan_app_no', $loan_app_no_src)->get();

        foreach ($detailfile as $dt) {
            $file = new DetailFileModel;
            $file->loan_app_no = $loan_app_no;
            $file->file = $dt->file;
            $file->branch_dir = $dt->branch_dir;
            $file->alias = $dt->alias;
            $file->save();
        }


        return response()->json(['success' => 'Successfully Copy.']);
    }


    public function updateFlag(Request $request)
    {
        //if(checkFinalStatus($request->loan_app_no) == 0){
        if (checkCutOff(checkFasilitas($request->loan_app_no)) && (Session('role') == "staff" || Session('role') == "spv1" || Session('role') == "pcp" || Session('role') == "pc")) {
            return response()->json(['message' => 'Cut Off Time', 'errors' => ['file' => 'Lakukan Proses Upload Dokumen Pada Jam Layanan yang telah ditentukan.']]);
        }
        //}

        if ($request->flag_spv == 1) {
            $request->validate(
                [
                    'loan_app_no' => 'required',
                    'flag_spv' => 'required',
                    'comment' => 'required',
                ],
                [
                    'flag_spv.required' => 'Status Document field is required.',
                    'comment.required' => 'Add Note field is required',
                ]
            );
        } else if ($request->flag_spv == 2) {
            $request->validate(
                [
                    'loan_app_no' => 'required',
                    'flag_spv' => 'required',
                    'comment' => 'required',
                    'reason' => 'required',
                ],
                [
                    'flag_spv.required' => 'Status Document field is required.',
                    'comment.required' => 'Add Note field is required',
                    'reason.required' => 'Reason field is required.',
                ]
            );
        } else if ($request->flag_spv == 3) {
            $request->validate(
                [
                    'loan_app_no' => 'required',
                    'flag_spv' => 'required',
                    'comment' => 'required',
                    'reason' => 'required',
                    'tbo_date' => 'required',
                ],
                [
                    'flag_spv.required' => 'Status Document field is required.',
                    'comment.required' => 'Add Note field is required',
                    'reason.required' => 'Reason field is required.',
                    'tbo_date.required' => 'TBO Date field is required.'
                ]
            );
        } else if ($request->flag_spv == 4) {
            $request->validate(
                [
                    'loan_app_no' => 'required',
                    'flag_spv' => 'required',
                    'comment' => 'required',
                    'reason' => 'required',
                ],
                [
                    'flag_spv.required' => 'Status Document field is required.',
                    'comment.required' => 'Add Note field is required.',
                    'reason.required' => 'Reason field is required.'
                ]
            );
        } else if ($request->flag_spv == 5) {
            // Validation for Team Verifikator status: Approve
            $request->validate(
                [
                    'loan_app_no' => 'required',
                    'flag_spv' => 'required',
                    'comment' => 'required',
                ],
                [
                    'flag_spv.required' => 'Status Document field is required.',
                    'comment.required' => 'Add Note field is required',
                ]
            );
        } else if ($request->flag_spv == 6) {
            // Validation for Team Verifikator status: Not Approve
            $request->validate(
                [
                    'loan_app_no' => 'required',
                    'flag_spv' => 'required',
                    'comment' => 'required',
                    'reason' => 'required',
                ],
                [
                    'flag_spv.required' => 'Status Document field is required.',
                    'comment.required' => 'Add Note field is required',
                    'reason.required' => 'Reason field is required.',
                ]
            );
        } else {
            $request->validate(
                [
                    'loan_app_no' => 'required',
                    'flag_spv' => 'required',
                ],
                [
                    'flag_spv.required' => 'Status Document field is required.',
                ]
            );

        }


        $nik = Session('nik');
        $level_spv = Session('role');

        if (empty($nik)) {
            return redirect('login')->with('error', 'Expired Session.');
        }

        if (checkTboCountIdLoan($request->loan_app_no)) {

            $model = DataFileModel::findOrFail($request->loan_app_no);
            $com = new Comment;
            $com->loan_app_no = $request->loan_app_no;
            $com->comment = $request->comment;
            $com->user_name = $nik;
            $com->level_spv = $level_spv;
            //$comment_date=getWilayah($model->kode_cabang);
            $comment_date = date('Y-m-d H:i:s');
            $com->comment_date = $comment_date;
            $com->flag_spv = $request->flag_spv;
            $com->tbo_date = $request->tbo_date;

            if ($request->reason != null) {
                $request->reason = implode(",", $request->reason);
            } else {
                $request->reason = "";
            }
            $com->reason = $request->reason;
            $flag = $request->flag_spv;


            if ($level_spv == "staff" || $level_spv == "spv1" || $level_spv == "pc" || $level_spv == "pcp") {
                $model->final_status_spv1 = $flag;
                $model->final_status_spv2 = 0;
                $model->final_status_spv3 = 0;
                $model->user_spv1 = $nik;
                $model->date_flag_spv1 = date("Y-m-d H:i:s");


                $date_input = $model->date_input;
                $currentDay = date("N");
                $dateInputDay = date("N", strtotime($date_input));
                $dateInputDate = date("Y-m-d", strtotime($date_input));

                if (
                    ($flag != 4) &&
                    (

                        $dateInputDate == date("Y-m-d") ||
                        $dateInputDate == date('Y-m-d', strtotime("-1 days")) ||
                        ($currentDay == 1 && $dateInputDay == 5 && strtotime($dateInputDate) == strtotime("last Friday"))
                    )
                ) {
                    // if(checkLastComment($request->loan_app_no,'spv1')){
                    $result = DB::select('CALL insert_registration(?)', [$request->loan_app_no]);
                    // }

                }


                if ($flag == 4) {
                    DB::table('registration')
                        ->where('loan_app_no', $request->loan_app_no)
                        ->where('processed', 0)
                        ->update(['processed' => 1]);
                }
                $this->send_document($request->loan_app_no);
            } else if ($level_spv == "spv2" || $level_spv == "spv3" || $level_spv == "spv4") {
                // Check if product needs team verifikator
                $jenis_produk = $model->masterproduk()->first()->jenis_produk ?? null;
                $need_verifikator = in_array($jenis_produk, [2, 3, 8]); // KUPEN Hybrid, KUPEG, KUPEN Hybrid GO

                $model->final_status_spv2 = $flag;
                $model->final_status_spv3 = $flag;
                if ($flag == 2 || $flag == 3 || $flag == 4) {
                    $model->final_status_spv1 = $flag;
                    $model->final_status_spv2 = $flag;
                    $model->final_status_spv3 = $flag;

                    // Reset verifikator jika reject/not verify
                    if ($need_verifikator) {
                        $model->final_status_verif1 = 0;
                        $model->final_status_verif2 = 0;
                    }
                }

                // Jika verify/TBO untuk produk dengan verifikator, set pending
                if ($need_verifikator && ($flag == 1 || $flag == 3)) {
                    $model->final_status_verif1 = 0; // Set pending untuk verifikator
                    $model->final_status_verif2 = 0;
                }

                $model->user_spv2 = $nik;
                $model->user_spv3 = $nik;
                $model->date_flag_spv2 = date("Y-m-d H:i:s");
                $model->date_flag_spv3 = date("Y-m-d H:i:s");
                DB::table('registration')
                    ->where('loan_app_no', $request->loan_app_no)
                    ->where('processed', 0)
                    ->update(['processed' => 1]);
            } else if ($level_spv == "team_verifikator_lvl1") {
                // Team Verifikator Level 1 - Rekomendasi only
                // Status 5 = Approve (rekomendasi), 6 = Not Approve (rekomendasi)
                $model->final_status_verif1 = $flag;
                $model->user_verif1 = $nik;
                $model->date_flag_verif1 = date("Y-m-d H:i:s");

                // Reset verifikator level 2
                $model->final_status_verif2 = 0;

                // Tidak ada perubahan pada status SPV atau final_status
                // Hanya rekomendasi, tetap lanjut ke team_verifikator_lvl2
            } else if ($level_spv == "team_verifikator_lvl2") {
                // Team Verifikator Level 2 - Decision Only (NEW FLOW)
                // Status 5 = Approve, 6 = Not Approve
                // TIDAK set final_status atau processed di sini
                // Menunggu user_verif1 upload file_bukti_verifikator terlebih dahulu

                $model->final_status_verif2 = $flag;
                $model->user_verif2 = $nik;
                $model->date_flag_verif2 = date("Y-m-d H:i:s");

                // Loan akan kembali ke queue verifikator level 1 untuk upload file bukti
                // final_status dan processed akan di-set saat upload file
                // TIDAK update registration table di sini
            }
            // else if($level_spv=="spv3" || $level_spv=="spv4"){
            //    $model->final_status_spv3=$flag;
            //    if($flag==2 || $flag==3 || $flag==4){
            //        $model->final_status_spv1=$flag;
            //        $model->final_status_spv2=$flag;
            //    }
            //    $model->user_spv3=$nik;
            //    $model->date_flag_spv3=date("Y-m-d H:i:s");
            //    DB::table('registration')
            //     ->where('loan_app_no', $request->loan_app_no)
            //     ->where('processed', 0)
            //     ->update(['processed' => 1]);
            // }


            // Determine final_status
            // Check if product needs team verifikator
            $jenis_produk = $model->masterproduk()->first()->jenis_produk ?? null;
            $need_verifikator = in_array($jenis_produk, [2, 3, 8]);

            // Skip final_status setting if role is team verifikator (sudah dihandle di block verifikator)
            if ($level_spv != "team_verifikator_lvl1" && $level_spv != "team_verifikator_lvl2") {
                if ($need_verifikator) {
                    // Untuk produk dengan verifikator, hanya set final_status jika reject atau not verify
                    if ($model->final_status_spv1 == 2 || $model->final_status_spv2 == 2 || $model->final_status_spv3 == 2) {
                        $model->final_status = 2;
                        //sendEmailNotifikasi($request->loan_app_no, "Not Verify");
                    } else if ($model->final_status_spv1 == 4 || $model->final_status_spv2 == 4 || $model->final_status_spv3 == 4) {
                        $model->final_status = 4;
                        $model->final_status_spv1 = 4;
                        $model->final_status_spv2 = 4;
                        $model->final_status_spv3 = 4;
                        $model->processed = 4;
                        //sendEmailNotifikasi($request->loan_app_no, "Rejected");
                    }
                    // Jika verify (1) atau TBO (3), tunggu verifikator - tidak set final_status
                } else {
                    // Untuk produk tanpa verifikator, logic existing
                    if ($model->final_status_spv1 == 1 && $model->final_status_spv2 == 1 && $model->final_status_spv3 == 1) {
                        $model->final_status = 1;
                        $model->processed = 1;
                        //sendEmailNotifikasi($request->loan_app_no, "Verify");
                    } else if ($model->final_status_spv1 == 2 || $model->final_status_spv2 == 2 || $model->final_status_spv3 == 2) {
                        $model->final_status = 2;
                        //sendEmailNotifikasi($request->loan_app_no, "Not Verify");
                    } else if ($model->final_status_spv1 == 3 || $model->final_status_spv2 == 3 || $model->final_status_spv3 == 3) {
                        $model->final_status = 3;
                        $model->processed = 1;
                        //sendEmailNotifikasi($request->loan_app_no, "TBO");
                    }

                    if ($model->final_status_spv1 == 4 || $model->final_status_spv2 == 4 || $model->final_status_spv3 == 4) {
                        $model->final_status = 4;
                        $model->final_status_spv1 = 4;
                        $model->final_status_spv2 = 4;
                        $model->final_status_spv3 = 4;
                        $model->processed = 4;
                        //sendEmailNotifikasi($request->loan_app_no, "Rejected");
                    }
                }
            }



            DB::table('list_pickup')
                ->where('loan_app_no', $request->loan_app_no)
                ->where('status', 0)
                ->update(['status' => 1]);



            //die;
            $model->save();
            //dd($com);
            $com->save();

            

            $log_sla = SlaBpr::where('loan_app_no', '=', $request->loan_app_no)->where('isLocked', '=', 1)->first();
            if ($log_sla <> null) {
                $log_sla->isLocked = 0;
                $log_sla->save();
            }


        } else {

            $model = DataFileModel::findOrFail($request->loan_app_no);
            $com = new Comment;
            $com->loan_app_no = $request->loan_app_no;
            $com->comment = $request->comment;
            $com->user_name = $nik;
            $com->level_spv = $level_spv;
            //$comment_date=getWilayah($model->kode_cabang);
            $comment_date = date('Y-m-d H:i:s');
            $com->comment_date = $comment_date;
            $com->flag_spv = $request->flag_spv;
            $com->tbo_date = $request->tbo_date;

            if ($request->reason != null) {
                $request->reason = implode(",", $request->reason);
            } else {
                $request->reason = "";
            }


            $com->reason = $request->reason;
            $flag = $request->flag_spv;


            if ($level_spv == "staff" || $level_spv == "spv1" || $level_spv == "pc" || $level_spv == "pcp") {
                $model->final_status_spv1 = $flag;
                $model->final_status_spv2 = 0;
                $model->final_status_spv3 = 0;
                $model->user_spv1 = $nik;
                $model->date_flag_spv1 = date("Y-m-d H:i:s");

                // if( (($flag != 4) && date("Y-m-d",strtotime($model->date_input))==date("Y-m-d") || date($model->date_input)==date('Y-m-d',strtotime("-1 days"))) ) {

                //     $result = DB::select('CALL insert_registration(?)', [$request->loan_app_no]);
                // }
                $date_input = $model->date_input;
                $currentDay = date("N");
                $dateInputDay = date("N", strtotime($date_input));
                $dateInputDate = date("Y-m-d", strtotime($date_input));

                if (
                    ($flag != 4) &&
                    (

                        $dateInputDate == date("Y-m-d") ||
                        $dateInputDate == date('Y-m-d', strtotime("-1 days")) ||
                        ($currentDay == 1 && $dateInputDay == 5 && strtotime($dateInputDate) == strtotime("last Friday"))
                    )
                ) {
                    //dd('input registrasi');
                    //if(checkLastComment($request->loan_app_no,'spv1')){
                    $result = DB::select('CALL insert_registration(?)', [$request->loan_app_no]);
                    //}
                }

                //dd($currentDay.'-'.$dateInputDay.'-'.strtotime($dateInputDate));

                if ($flag == 4) {
                    DB::table('registration')
                        ->where('loan_app_no', $request->loan_app_no)
                        ->where('processed', 0)
                        ->update(['processed' => 1]);
                }
                $this->send_document($request->loan_app_no);

            } else if ($level_spv == "spv2" || $level_spv == "spv3" || $level_spv == "spv4") {
                // Check if product needs team verifikator
                $jenis_produk = $model->masterproduk()->first()->jenis_produk ?? null;
                $need_verifikator = in_array($jenis_produk, [2, 3, 8]); // KUPEN Hybrid, KUPEG, KUPEN Hybrid GO

                $model->final_status_spv2 = $flag;
                $model->final_status_spv3 = $flag;
                if ($flag == 2 || $flag == 3 || $flag == 4) {
                    $model->final_status_spv1 = $flag;
                    $model->final_status_spv2 = $flag;
                    $model->final_status_spv3 = $flag;

                    // Reset verifikator jika reject/not verify
                    if ($need_verifikator) {
                        $model->final_status_verif1 = 0;
                        $model->final_status_verif2 = 0;
                    }
                }

                // Jika verify/TBO untuk produk dengan verifikator, set pending
                if ($need_verifikator && ($flag == 1 || $flag == 3)) {
                    $model->final_status_verif1 = 0; // Set pending untuk verifikator
                    $model->final_status_verif2 = 0;
                }

                $model->user_spv2 = $nik;
                $model->user_spv3 = $nik;
                $model->date_flag_spv2 = date("Y-m-d H:i:s");
                $model->date_flag_spv3 = date("Y-m-d H:i:s");
                DB::table('registration')
                    ->where('loan_app_no', $request->loan_app_no)
                    ->where('processed', 0)
                    ->update(['processed' => 1]);

            } else if ($level_spv == "team_verifikator_lvl1") {
                // Team Verifikator Level 1 - Rekomendasi only
                $model->final_status_verif1 = $flag;
                $model->user_verif1 = $nik;
                $model->date_flag_verif1 = date("Y-m-d H:i:s");
                $model->final_status_verif2 = 0;
            } else if ($level_spv == "team_verifikator_lvl2") {
                // Team Verifikator Level 2 - Final Decision
                $model->final_status_verif2 = $flag;
                $model->user_verif2 = $nik;
                $model->date_flag_verif2 = date("Y-m-d H:i:s");

                if ($flag == 5) {
                    // APPROVE
                    if ($model->final_status_spv2 == 1 || $model->final_status_spv3 == 1) {
                        $model->final_status = 1;
                        $model->processed = 1;
                        //sendEmailNotifikasi($request->loan_app_no, "Approved by Team Verifikator");
                    } else if ($model->final_status_spv2 == 3 || $model->final_status_spv3 == 3) {
                        $model->final_status = 3;
                        $model->processed = 1;
                        //sendEmailNotifikasi($request->loan_app_no, "TBO - Approved by Team Verifikator");
                    }
                } else if ($flag == 6) {
                    // NOT APPROVE
                    $model->final_status = 6;
                    $model->final_status_spv1 = 6;
                    $model->final_status_spv2 = 6;
                    $model->final_status_spv3 = 6;
                    $model->final_status_verif1 = 6;
                    $model->processed = 6;
                    //sendEmailNotifikasi($request->loan_app_no, "Not Approved by Team Verifikator");
                }

                DB::table('registration')
                    ->where('loan_app_no', $request->loan_app_no)
                    ->where('processed', 0)
                    ->update(['processed' => 1]);
            }
            //  else if($level_spv=="spv3" || $level_spv=="spv4"){
            //     $model->final_status_spv3=$flag;
            //     if($flag==2 || $flag==3 || $flag==4){
            //         $model->final_status_spv1=$flag;
            //         $model->final_status_spv2=$flag;
            //     }
            //     $model->user_spv3=$nik;
            //     $model->date_flag_spv3=date("Y-m-d H:i:s");
            //     DB::table('registration')
            //     ->where('loan_app_no', $request->loan_app_no)
            //     ->where('processed', 0)
            //     ->update(['processed' => 1]);

            //  }


            // Determine final_status
            $jenis_produk = $model->masterproduk()->first()->jenis_produk ?? null;
            $need_verifikator = in_array($jenis_produk, [2, 3, 8]);

            if ($level_spv != "team_verifikator_lvl1" && $level_spv != "team_verifikator_lvl2") {
                if ($need_verifikator) {
                    // Untuk produk dengan verifikator, hanya set final_status jika reject atau not verify
                    if ($model->final_status_spv1 == 2 || $model->final_status_spv2 == 2 || $model->final_status_spv3 == 2) {
                        $model->final_status = 2;
                        //sendEmailNotifikasi($request->loan_app_no, "Not Verify");
                    } else if ($model->final_status_spv1 == 4 || $model->final_status_spv2 == 4 || $model->final_status_spv3 == 4) {
                        $model->final_status = 4;
                        $model->final_status_spv1 = 4;
                        $model->final_status_spv2 = 4;
                        $model->final_status_spv3 = 4;
                        $model->processed = 4;
                        //sendEmailNotifikasi($request->loan_app_no, "Rejected");
                    }
                    // Jika verify (1) atau TBO (3), tunggu verifikator
                } else {
                    // Untuk produk tanpa verifikator, logic existing
                    if ($model->final_status_spv1 == 1 && $model->final_status_spv2 == 1 && $model->final_status_spv3 == 1) {
                        $model->final_status = 1;
                        $model->processed = 1;
                        //sendEmailNotifikasi($request->loan_app_no, "Verify");
                    } else if ($model->final_status_spv1 == 2 || $model->final_status_spv2 == 2 || $model->final_status_spv3 == 2) {
                        $model->final_status = 2;
                        //sendEmailNotifikasi($request->loan_app_no, "Not Verify");
                    } else if ($model->final_status_spv1 == 3 || $model->final_status_spv2 == 3 || $model->final_status_spv3 == 3) {
                        $model->final_status = 3;
                        $model->processed = 1;
                        //sendEmailNotifikasi($request->loan_app_no, "TBO");
                    }

                    if ($model->final_status_spv1 == 4 || $model->final_status_spv2 == 4 || $model->final_status_spv3 == 4) {
                        $model->final_status = 4;
                        $model->final_status_spv1 = 4;
                        $model->final_status_spv2 = 4;
                        $model->final_status_spv3 = 4;
                        $model->processed = 4;
                        //sendEmailNotifikasi($request->loan_app_no, "Rejected");
                    }
                }
            }



            DB::table('list_pickup')
                ->where('loan_app_no', $request->loan_app_no)
                ->where('status', 0)
                ->update(['status' => 1]);



            //die;
            $model->save();
            //dd($com);
            $com->save();
            
            $log_sla = SlaBpr::where('loan_app_no', '=', $request->loan_app_no)->where('isLocked', '=', 1)->first();
            if ($log_sla <> null) {
                $log_sla->isLocked = 0;
                $log_sla->save();
            }

        }





        // Prepare detailed response based on role and action
        $level_spv = Session('role');
        $flag = $request->flag_spv;

        $response = [
            'success' => true,
            'loan_app_no' => $request->loan_app_no,
            'role' => $level_spv,
            'action' => $flag,
        ];

        // Add specific message based on role
        if ($level_spv == 'team_verifikator_lvl1') {
            if ($flag == 5) {
                $response['message'] = 'Rekomendasi APPROVE berhasil disimpan. Dokumen diteruskan ke Team Verifikator Level 2.';
                $response['status_text'] = 'Approve (Rekomendasi)';
            } else if ($flag == 6) {
                $response['message'] = 'Rekomendasi NOT APPROVE berhasil disimpan. Dokumen diteruskan ke Team Verifikator Level 2.';
                $response['status_text'] = 'Not Approve (Rekomendasi)';
            }
        } else if ($level_spv == 'team_verifikator_lvl2') {
            if ($flag == 5) {
                $response['message'] = 'Keputusan APPROVE berhasil disimpan. Dokumen telah disetujui dan diproses.';
                $response['status_text'] = 'Approve (Final)';
                $response['final_status'] = $model->final_status;
            } else if ($flag == 6) {
                $response['message'] = 'Keputusan NOT APPROVE berhasil disimpan. Semua status telah diupdate.';
                $response['status_text'] = 'Not Approve (Final)';
                $response['final_status'] = 6;
            }
        } else {
            // For other roles (SPV, staff, etc.)
            $statusLabels = [
                1 => 'Verify',
                2 => 'Not Verify',
                3 => 'TBO',
                4 => 'Reject'
            ];
            $response['message'] = 'Review berhasil disimpan dengan status: ' . ($statusLabels[$flag] ?? 'Unknown');
            $response['status_text'] = $statusLabels[$flag] ?? 'Unknown';
        }

        return response()->json($response);
    }

    /**
     * Queue untuk Team Verifikator Level 1 - Pending Recommendation (State 1)
     * Menampilkan loan yang sudah di-approve SPV dan menunggu rekomendasi dari verif1
     */
    public function waiting_verifikator_lvl1_recommendation(Request $request)
    {
        $role = Session('role');

        if ($role != 'team_verifikator_lvl1') {
            return redirect()->back()->with('error', 'Access denied');
        }

        $nik = Session('nik');

        // Query loan yang menunggu rekomendasi verifikator level 1 (STATE 1 ONLY)
        // Hanya untuk produk KUPEN Hybrid (2), KUPEG (3), KUPEN Hybrid GO (8)
        $query = DataFileModel::whereHas('masterproduk', function($query) {
                $query->whereIn('jenis_produk', [2, 3, 8]);
            })
            ->where(function($query) {
                // SPV kasih verify (1) atau TBO (3)
                $query->where(function($q) {
                    $q->where('final_status_spv2', 1)
                      ->where('final_status_spv3', 1);
                })
                ->orWhere(function($q) {
                    $q->where('final_status_spv2', 3)
                      ->where('final_status_spv3', 3);
                });
            })
            ->where('date_input','>=', '2025-08-01')
            // STATE 1: Belum direview verif1 (pending recommendation)
            ->where('final_status_verif1', 0)
            ->where('final_status_verif2', 0);

        // Search filters
        if ($request->filled('keyword_loan')) {
            $query->where('loan_app_no', 'like', '%' . $request->keyword_loan . '%');
        }
        if ($request->filled('keyword_name')) {
            $query->where('nama_debitur', 'like', '%' . $request->keyword_name . '%');
        }
        if ($request->filled('keyword_branch')) {
            $query->where('kode_cabang', 'like', '%' . $request->keyword_branch . '%');
        }
        if ($request->filled('keyword_produk')) {
            $query->where('produk', 'like', '%' . $request->keyword_produk . '%');
        }

        $datafile = $query->orderBy('date_input', 'asc')->paginate(25);

        return view('loan.waiting_verifikator_lvl1_recommendation', compact('datafile'));
    }

    /**
     * Queue untuk Team Verifikator Level 1 - Pending Upload File (State 3)
     * Menampilkan loan yang sudah diputuskan verif2 dan menunggu upload file bukti dari verif1
     */
    public function waiting_verifikator_lvl1_upload(Request $request)
    {
        $role = Session('role');

        if ($role != 'team_verifikator_lvl1') {
            return redirect()->back()->with('error', 'Access denied');
        }

        $nik = Session('nik');

        // Query loan yang menunggu upload file bukti (STATE 3 ONLY)
        // Hanya untuk produk KUPEN Hybrid (2), KUPEG (3), KUPEN Hybrid GO (8)
        $query = DataFileModel::whereHas('masterproduk', function($query) {
                $query->whereIn('jenis_produk', [2, 3, 8]);
            })
            ->where(function($query) {
                // SPV kasih verify (1) atau TBO (3)
                $query->where(function($q) {
                    $q->where('final_status_spv2', 1)
                      ->where('final_status_spv3', 1);
                })
                ->orWhere(function($q) {
                    $q->where('final_status_spv2', 3)
                      ->where('final_status_spv3', 3);
                });
            })
            ->where('date_input','>=', '2025-08-01')
            // STATE 3: Verif2 sudah kasih keputusan, tapi belum upload file bukti
            ->whereIn('final_status_verif1', [5, 6])
            ->whereIn('final_status_verif2', [5, 6])
            ->whereNull('file_bukti_verifikator');

        // Search filters
        if ($request->filled('keyword_loan')) {
            $query->where('loan_app_no', 'like', '%' . $request->keyword_loan . '%');
        }
        if ($request->filled('keyword_name')) {
            $query->where('nama_debitur', 'like', '%' . $request->keyword_name . '%');
        }
        if ($request->filled('keyword_branch')) {
            $query->where('kode_cabang', 'like', '%' . $request->keyword_branch . '%');
        }
        if ($request->filled('keyword_produk')) {
            $query->where('produk', 'like', '%' . $request->keyword_produk . '%');
        }

        $datafile = $query->orderBy('date_input', 'asc')->paginate(25);

        return view('loan.waiting_verifikator_lvl1_upload', compact('datafile'));
    }

    /**
     * Queue untuk Team Verifikator Level 2
     * Menampilkan loan yang sudah direview verifikator level 1 tapi belum level 2
     */
    public function waiting_verifikator_lvl2(Request $request)
    {
        $role = Session('role');

        if ($role != 'team_verifikator_lvl2') {
            return redirect()->back()->with('error', 'Access denied');
        }

        $nik = Session('nik');

        // Query loan yang sudah direview verif1 tapi belum verif2
        $query = DataFileModel::whereHas('masterproduk', function($query) {
                $query->whereIn('jenis_produk', [2, 3, 8]);
            })
            ->whereIn('final_status_verif1', [5, 6]) // Sudah direview verif1 (approve/not approve)
            ->where('final_status_verif2', 0); // Belum direview verif2

        // Search filters
        if ($request->filled('keyword_loan')) {
            $query->where('loan_app_no', 'like', '%' . $request->keyword_loan . '%');
        }
        if ($request->filled('keyword_name')) {
            $query->where('nama_debitur', 'like', '%' . $request->keyword_name . '%');
        }
        if ($request->filled('keyword_branch')) {
            $query->where('kode_cabang', 'like', '%' . $request->keyword_branch . '%');
        }
        if ($request->filled('keyword_produk')) {
            $query->where('produk', 'like', '%' . $request->keyword_produk . '%');
        }
        if ($request->filled('rekomendasi_lvl1')) {
            $query->where('final_status_verif1', $request->rekomendasi_lvl1);
        }

        $datafile = $query->orderBy('date_input', 'asc')->paginate(25);

        return view('loan.waiting_verifikator_lvl2', compact('datafile'));
    }

    /**
     * Upload file bukti hasil pemeriksaan oleh Team Verifikator Level 1 (NEW FLOW)
     * Diupload SETELAH verifikator level 2 memberikan keputusan
     */
    public function uploadBuktiVerifikator(Request $request)
    {
        // Validation
        $request->validate([
            'loan_app_no' => 'required',
            'file_bukti' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2000', // 2MB max (consistent with dokumen_store)
        ]);

        $loan_app_no = $request->loan_app_no;
        $role = Session('role');
        $nik = Session('nik');

        // NEW FLOW: Verify user is verifikator level 1
        if ($role != 'team_verifikator_lvl1') {
            return response()->json(['success' => false, 'message' => 'Access denied. Only verifikator level 1 can upload file.'], 403);
        }

        $model = DataFileModel::find($loan_app_no);

        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Loan not found'], 404);
        }

        // NEW FLOW: Validate verif2 sudah memberikan keputusan
        if ($model->final_status_verif2 == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Verifikator Level 2 belum memberikan keputusan. File hanya bisa diupload setelah Verif2 submit.'
            ], 400);
        }

        // Validate file belum pernah diupload
        if (!empty($model->file_bukti_verifikator)) {
            return response()->json([
                'success' => false,
                'message' => 'File bukti sudah pernah diupload sebelumnya.'
            ], 400);
        }

        // Upload to LOCAL STORAGE (pattern dokumen_store)
        $file = $request->file('file_bukti');
        $dir = '/' . Session('branch_code') . '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
        $filename = $loan_app_no . '-BUKTI_VERIFIKATOR-' . time() . '.' . $file->getClientOriginalExtension();
        $full_path = $dir . $filename;

        // Create directory if not exists
        $path = public_path('indexed/' . Session('branch_code') . '/' . date('Y') . '/' . date('m') . '/' . date('d'));
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        // Move file to storage
        $file->move($path, $full_path);

        // Save to data_file table
        $model->file_bukti_verifikator = $full_path;

        // NEW FLOW: Set final_status and processed based on verif2 decision
        if ($model->final_status_verif2 == 5) {
            // APPROVE - final_status mengikuti SPV (1=Verify atau 3=TBO)
            if ($model->final_status_spv2 == 1 || $model->final_status_spv3 == 1) {
                $model->final_status = 1; // Verify
                $model->processed = 1;
            } else if ($model->final_status_spv2 == 3 || $model->final_status_spv3 == 3) {
                $model->final_status = 3; // TBO
                $model->processed = 1;
            }
        } else if ($model->final_status_verif2 == 6) {
            // NOT APPROVE - Cascade semua status jadi 6
            $model->final_status = 6;
            $model->final_status_spv1 = 6;
            $model->final_status_spv2 = 6;
            $model->final_status_spv3 = 6;
            $model->final_status_verif1 = 6;
            $model->processed = 6;
        }

        $model->save();

        // Save to detail_file table
        $detail_file = new DetailFileModel;
        $detail_file->loan_app_no = $loan_app_no;
        $detail_file->file = $full_path;
        $detail_file->branch_dir = Session('branch_code');
        $detail_file->alias = 'BUKTI_VERIFIKATOR';
        $detail_file->created_at = now();
        $detail_file->save();

        // Update registration table
        DB::table('registration')
            ->where('loan_app_no', $loan_app_no)
            ->where('processed', 0)
            ->update(['processed' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'File bukti berhasil diupload. Loan telah diproses dengan status: ' . ($model->final_status == 1 ? 'Verify' : ($model->final_status == 3 ? 'TBO' : 'Not Approve')),
            'file_path' => $full_path,
            'final_status' => $model->final_status
        ]);
    }

    /**
     * Display list of loans pending disbursement (for SPV2, SPV3, SPV4)
     * Loans that have file_bukti_verifikator uploaded but ready_to_disburs = 0
     */
    public function pendingDisbursement(Request $request)
    {
        $role = Session('role');
        $nik = Session('nik');

        // Only SPV2, SPV3, SPV4 can access
        if (!in_array($role, ['spv2', 'spv3', 'spv4'])) {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get loans with file_bukti_verifikator uploaded and ready_to_disburs = 0
        $query = DataFileModel::where('file_bukti_verifikator', '!=', '')
                              ->whereNotNull('file_bukti_verifikator')
                              ->where('ready_to_disburs', 0)
                              ->where('processed', 1); // Only processed loans

        // Branch filtering based on role
        if ($role == 'spv2') {
            // SPV2 sees only their branch
            $query->where('branch_code', Session('branch_code'));
        } elseif ($role == 'spv3') {
            // SPV3 sees their region
            $branchlist = Session('branchlist');
            if ($branchlist && $branchlist != 'all') {
                $branches = explode(',', $branchlist);
                $query->whereIn('branch_code', $branches);
            }
        }
        // SPV4 sees all

        // Search filters
        if ($request->has('keyword_loan') && $request->keyword_loan != '') {
            $query->where('loan_app_no', 'like', '%' . $request->keyword_loan . '%');
        }

        if ($request->has('keyword_name') && $request->keyword_name != '') {
            $query->where('nama_debitur', 'like', '%' . $request->keyword_name . '%');
        }

        if ($request->has('keyword_branch') && $request->keyword_branch != '') {
            $query->where('kode_cabang', 'like', '%' . $request->keyword_branch . '%');
        }

        if ($request->has('keyword_produk') && $request->keyword_produk != '') {
            $query->where('produk', 'like', '%' . $request->keyword_produk . '%');
        }

        if ($request->has('final_status') && $request->final_status != '') {
            $query->where('final_status', $request->final_status);
        }

        // Sorting with eager loading
        $loans = $query->with('mastercabang')
                      ->orderBy('date_input', 'desc')
                      ->paginate(50)
                      ->appends($request->except('page')); // Maintain query params in pagination

        return view('loan.pending_disbursement', compact('loans'));
    }

    /**
     * Flag loan as ready to disburse
     */
    public function flagReadyToDisburs(Request $request, $loan_app_no)
    {
        $role = Session('role');
        $nik = Session('nik');

        // Only SPV2, SPV3, SPV4 can flag
        if (!in_array($role, ['spv2', 'spv3', 'spv4'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        $loan = DataFileModel::find($loan_app_no);

        if (!$loan) {
            return response()->json(['success' => false, 'message' => 'Loan not found'], 404);
        }

        // Verify file_bukti_verifikator exists
        if (empty($loan->file_bukti_verifikator)) {
            return response()->json([
                'success' => false,
                'message' => 'File bukti verifikator belum diupload'
            ], 400);
        }

        // Verify not already flagged
        if ($loan->ready_to_disburs == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Loan sudah di-flag ready to disburse sebelumnya'
            ], 400);
        }

        // Update flag
        $loan->ready_to_disburs = 1;
        $loan->ready_to_disburs_by = $nik;
        $loan->ready_to_disburs_at = now();
        $loan->save();

        return response()->json([
            'success' => true,
            'message' => 'Loan berhasil di-flag ready to disburse'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DataFileModel  $dataFile
     * @return \Illuminate\Http\Response
     */
    public function edit(DataFileModel $datafile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DataFileModel  $dataFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DataFileModel $dataFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DataFileModel  $dataFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataFileModel $datafile)
    {
        $datafile->delete();

        return redirect()->route('loan.index')
            ->with('success', 'Data Loan deleted successfully');
    }

    public function deletedetailfile($loan, $id)
    {
        $detailfile = DetailFileModel::find($id);
        //dd($detailfile);
        $detailfile->delete();


        return redirect()->route('loans.show', ['loan' => $loan])->with('success', 'Detail file deleted successfully');
    }
    public function deletedetailfilenew($loan, $id)
    {
        $detailfile = DetailFileModel::find($id);
        //dd($detailfile);
        $detailfile->delete();


        return redirect()->route('datafile.show_new', ['id' => $loan])->with('success', 'Detail file deleted successfully');
    }

    /**
     * ========================================
     * ADMIN LOAN LOCK MANAGEMENT
     * ========================================
     */

    /**
     * Display all currently locked loans (for admin/supervisor)
     */
    public function admin_locked_loans()
    {
        // Check if user has permission (only admin, PC, PCP, SPV3, SPV4 can access)
        $role = Session('role');
        if (!in_array($role, ['pc', 'pcp', 'spv3', 'spv4', 'spv5'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Cleanup expired locks first
        cleanExpiredLocks();

        // Get all currently locked loans
        $lockedLoans = getAllLockedLoans();

        return view('admin.locked-loans', compact('lockedLoans'));
    }

    /**
     * Force unlock a specific loan (for admin/supervisor)
     */
    public function admin_force_unlock($loan_app_no)
    {
        // Check if user has permission (only admin, PC, PCP, SPV3, SPV4 can access)
        $role = Session('role');
        $nik = Session('nik');

        if (!in_array($role, ['pc', 'pcp', 'spv3', 'spv4', 'spv5'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk force unlock.');
        }

        // Get lock info before unlocking
        $lockInfo = isLoanLocked($loan_app_no);

        if ($lockInfo === null) {
            return redirect()->back()->with('error', 'Loan tidak sedang di-lock atau sudah expired.');
        }

        // Force unlock
        $success = forceUnlockLoan($loan_app_no, $nik);

        if ($success) {
            return redirect()->back()->with('success', "Loan {$loan_app_no} berhasil di-unlock. (Was locked by {$lockInfo['name']})");
        } else {
            return redirect()->back()->with('error', 'Gagal melakukan force unlock. Silakan coba lagi.');
        }
    }

    /**
     * Cleanup all legacy locks (locks without proper timestamps)
     */
    public function admin_cleanup_legacy_locks()
    {
        // Check if user has permission
        $role = Session('role');

        if (!in_array($role, ['pc', 'pcp', 'spv3', 'spv4', 'spv5'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk cleanup legacy locks.');
        }

        // Run cleanup
        $count = cleanupLegacyLocks();

        if ($count > 0) {
            return redirect()->back()->with('success', "Berhasil cleanup {$count} legacy locks.");
        } else {
            return redirect()->back()->with('info', 'Tidak ada legacy locks yang perlu di-cleanup.');
        }
    }
}
