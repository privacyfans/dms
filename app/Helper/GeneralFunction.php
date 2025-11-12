<?php

use App\Mail\Notif;
use App\Models\DokumenEarly;
use Illuminate\Support\Facades\DB;
use App\Models\DataFileModel;
use App\Models\DetailFileModel;
use App\Models\MasterProduct;
use App\Models\JenisProduct;
use App\Models\MapProduct;
use App\Models\FlagSpv;
use App\Models\SlaBpr;
use App\Models\User;
use App\Models\CutOff;
use App\Models\Branchlist;
use App\Models\BranchTbo;
use App\Models\DokumenKategori;
use App\Mail\requestJam;
use App\Mail\requestJamHQ;
use App\Mail\requestTbo;
use App\Models\ListPickup;
use Carbon\Carbon;
/**
 * [total_warga_rt description]
 * @param  [type] $rt [description]
 * @return [type]     [description]
 */


 function calculateAgeCategory($date) {
    // Get current date
    $y=substr($date,0,4);
    $m=substr($date,4,2);
    $d=substr($date,6,2);
    $birthDate=$y."-".$m."-".$d;

    $currentDate = new DateTime();
    
    // Convert birth date string to DateTime object
    $birthDateTime = new DateTime($birthDate);
    
    // Calculate difference between dates
    $difference = $currentDate->diff($birthDateTime);
    
    // Extract years, months, and days
    $years = $difference->y;
    $months = $difference->m;
    $days = $difference->d;
    
    // Base comparison age (63 years)
    $baseAge = 63;
    
    // Function to format age for display
    $ageString = sprintf("%d tahun %d bulan %d hari", $years, $months, $days);
    
    // Check conditions
    if ($years < $baseAge) {
        return array(
            'category' => '<63',
            'age' => $ageString
        );
    } elseif ($years == $baseAge) {
        if ($months < 6) {
            return array(
                'category' => '<63',
                'age' => $ageString
            );
        } elseif ($months == 5 && $days <= 29) {
            return array(
                'category' => '<63',
                'age' => $ageString
            );
        } else {
            return array(
                'category' => '>=63',
                'age' => $ageString
            );
        }
    } else {
        return array(
            'category' => '>=63',
            'age' => $ageString
        );
    }
}

function isKupeg($jenis_produk) {
    return isset($jenis_produk) && $jenis_produk == '3';
}

function isKupen($jenis_produk) {
    return isset($jenis_produk) && in_array($jenis_produk, ['1',  '9']);
}

function isKupenHybrid($jenis_produk) {
    return isset($jenis_produk) && in_array($jenis_produk, ['2', '8']);
}

function getBadge($isMandatory, $namefield, $labelonly, $status_pernikahan,$status_deviasi,$early,$usia,$frontingagent,$take_over,$fasilitas,$status_pegawai, $loan_date_input = null) {
    if($labelonly){
        return '';
    }

    // Logika khusus untuk dokumen "Akseptasi Persetujuan Mitra Asuransi"
    // Dokumen ini hanya mandatory untuk loan yang dibuat pada atau setelah 2025-10-28 14:00:00 WIB
    if($namefield == 'Akseptasi Persetujuan Mitra Asuransi' && $loan_date_input) {
        $cutoffDate = \Carbon\Carbon::parse('2025-10-27 17:00:00');
        $loanDate = \Carbon\Carbon::parse($loan_date_input);

        if($loanDate->lt($cutoffDate)) {
            // Loan dibuat sebelum dokumen ini ditambahkan, jadi opsional
            return '<span class="badge badge-secondary">Opsional</span>';
        }
        // Jika loan dibuat setelah cutoff, lanjutkan ke logika normal (akan return mandatory di akhir)
    }

    if($status_pernikahan==10 &&
       ($namefield=='[Salinan] Buku Nikah/Akta Nikah' ||
        $namefield=='[Salinan] Surat Cerai/Salinan Putusan Pengadilan' ||
        $namefield=='[Salinan] KTP Pasangan Debitur/Calon Debitur'))
    {
        return '<span class="badge badge-secondary">Opsional</span>';
     }else if($status_pernikahan==20 &&
        ($namefield=='[Salinan] Buku Nikah/Akta Nikah' ||
        $namefield=='[Salinan] KTP Pasangan Debitur/Calon Debitur'))
     {
        return '<span class="badge badge-danger">Wajib</span>';
     }else if($status_pernikahan==40 &&
        $namefield=='[Salinan] Surat Cerai/Salinan Putusan Pengadilan')

    {
        return '<span class="badge badge-danger">Wajib</span>';
    }

     if($status_deviasi && $namefield=='Surat Rekomendasi/ Deviasi Ketentuan'){
        return '<span class="badge badge-danger">Wajib</span>';
     }


     if($early && $namefield=='Memo Permohonan Pelaksanaan Take Over'){
        return '<span class="badge badge-secondary">Opsional</span>';
     }

     if($usia=='<63' && ($namefield=='Simple screening health' ||$namefield=='Medical chek up (Darah & Urine)' ) ){
        return '<span class="badge badge-secondary">Opsional</span>';
     }

     if($frontingagent && ($namefield=='E Form Fronting Agent' )){
        return '<span class="badge badge-danger">Wajib</span>';
     }
     if($take_over && ($namefield=='Dokumen Mutasi Kantor Bayar Gaji' )){
        return '<span class="badge badge-danger">Wajib</span>';
     }

     if($fasilitas=='2' && ($namefield=='Surat Keterangan Usaha (SKU) / Legalitas Usaha' )){
        return '<span class="badge badge-danger">Wajib</span>';
     }
     if($status_pegawai=='TNI/ Polri' &&
       ($namefield=='[Asli] Surat Keputusan Pengangkatan Menjadi Pegawai (SK Awal) / Prajurit/Anggota (TNI/POLRI)' ||
        $namefield=='[Asli] Surat Keputusan Pangkat Pertama/Terakhir (TNI/POLRI)'
       )){
        return '<span class="badge badge-danger">Wajib</span>';
     }

     if($status_pegawai=='ASN' &&
       ($namefield=='[Asli] Surat Keputusan Pegawai (Pangkat, Jabatan dan/atau Golongan Terakhir (PNS/Swasta)' ||
        $namefield=='[Asli] Surat Keputusan Calon Pegawai Negeri Sipil/ CPNS (ASN)'
       )){
        return '<span class="badge badge-danger">Wajib</span>';
     }

     if($status_pegawai=='SWASTA' &&
     ($namefield=='[Asli] Surat Keputusan Pegawai (Swasta)'
     )){
      return '<span class="badge badge-danger">Wajib</span>';
   }


    return $isMandatory ? '<span class="badge badge-danger">Wajib</span>' :
                          '<span class="badge badge-secondary">Opsional</span>';
}


function generateWaitingTimes($totalRows, $intervalMinutes, $groupSize, $currentPage, $rowsPerPage) {
    $waitingTimes = [];
    $intervalSeconds = $intervalMinutes * 60;
    $startRow = ($currentPage - 1) * $rowsPerPage;
    $initialInterval = $intervalSeconds; // Memulai dari intervalMinutes pertama
    $currentInterval = $initialInterval + $intervalSeconds * floor($startRow / $groupSize); // Menghitung interval mulai berdasarkan halaman dan grup

    for ($i = 0; $i < $rowsPerPage; $i++) {
        $rowIndex = $startRow + $i;
        if ($rowIndex >= $totalRows) {
            break; // Jika melebihi jumlah total baris, berhenti
        }

        if ($rowIndex != 0 && $rowIndex % $groupSize == 0) {
            $currentInterval += $intervalSeconds;
        }

        $hours = floor($currentInterval / 3600);
        $minutes = floor(($currentInterval % 3600) / 60);
        $seconds = $currentInterval % 60;

        $waitingTimes[] = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    return $waitingTimes;
}

function getWilayah($id){
    $branch = Branchlist::where('branch_code', $id)->first();
    $date_input=date('Y-m-d H:i:s');
    if (!$branch) {
        return response()->json(['error' => 'Branch not found'], 404);
    }else{
        if($branch->wilayah==1){
            $date_input=date('Y-m-d H:i:s');
        }else{
            $date_input = date('Y-m-d H:i:s');
            $date = new DateTime($date_input);
            $date->modify('-1 hour');
            $date_input = $date->format('Y-m-d H:i:s');

        }
    }
    return $date_input;
}


function checkLastComment($id, $level) {
    // Menggunakan query builder Laravel untuk mencegah SQL injection
    $result = DB::table('comment')
                ->where('loan_app_no', $id)
                ->orderBy('comment_date', 'desc')
                ->limit(1)
                ->get();

                if ($result->isEmpty()) { // Jika $result kosong (null)
                    return true;
                } else {
                    // Jika level_spv ada dalam hasil dan sama dengan $level, maka return false; selain itu, return true
                    //return isset($result[0]->level_spv) && $result[0]->level_spv == $level ? false : true;
                    return false;
                }
}
 function getUserPickupLoan($id,$user){
    $sql="select count(1) as jml from list_pickup where loan_app_no='".$id."' and user_spv='".$user."' and status = 0  order by id limit 1";
    $result = DB::select($sql);
    
    $hasil=false;
    if(!$result == null){
        
        $pickup=$result[0]->jml;
        if($pickup>0){
            $hasil=true;
        }else{
            $hasil=false;
        }
    }else{
        $hasil=false;
    }
    return $hasil;
}
function getWaitingNoRegistration(){
    $sql="select no_register from registration where processed = 0  order by id limit 1";

   /* $sql="	SELECT
	registration.no_register
FROM
	data_file AS df
	INNER JOIN
	registration
	ON 
		df.loan_app_no = registration.loan_app_no
            WHERE (df.final_status_spv1 = 1 OR df.final_status_spv1 = 3) 
                AND df.final_status_spv2 = 0
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
            ORDER BY df.date_flag_spv1 limit 1";*/
            

    $result = DB::select($sql);
    if(!$result == null){
        $no_register=$result[0]->no_register;
    }else{
        $no_register='';
    }
    return $no_register;
}

function getWaitingNoRegistrationTeamLeader(){
    //$sql="select no_register from registration where processed = 0  order by id limit 1";

    $sql="SELECT
	registration.no_register
FROM
	data_file AS df
	INNER JOIN
	registration
	ON 
		df.loan_app_no = registration.loan_app_no
WHERE
	(
		df.final_status_spv2 = 1 OR
		df.final_status_spv2 = 3
	) AND
	df.final_status_spv3 = 0 AND
	df.loan_app_no NOT IN ((SELECT loan_app_no FROM list_pickup WHERE status = 0)) AND
	(
		(
			DATE(df.date_input) IN (CURDATE(),CURDATE() - INTERVAL 1 DAY)
		) OR
		(
			DAYOFWEEK(NOW()) = 2 AND
			DATE(df.date_input) = DATE(SUBDATE(NOW(), 3)) AND
			DATE(df.date_flag_spv2) = CURDATE()
		) AND
		df.final_status <> 4
	)
ORDER BY
	df.date_flag_spv2 ASC limit 1";

    $result = DB::select($sql);
    if(!$result == null){
        $no_register=$result[0]->no_register;
    }else{
        $no_register='';
    }
    return $no_register;
}
 function getNoRegistration($id){
    $sql="select no_register from registration where loan_app_no= '".$id."' order by SUBSTRING_INDEX(no_register, '-', 1) DESC, 
    CAST(SUBSTRING_INDEX(no_register, '-', -1) AS UNSIGNED) DESC limit 1";
    $result = DB::select($sql);
    if(!$result == null){
        $no_register=$result[0]->no_register;
    }else{
        $no_register='';
    }
    return $no_register;
}
function getLimitTBOOverdueHeader($id){
    
    $sql="select total_limit_overdue from branch_tbo where branch_code = '".$id."'";
    $result = DB::select($sql);
    if(!$result == null){
    $jml_limit=$result[0]->total_limit_overdue;
    }else{
        $jml_limit='-';
    }
    $sql="select count(1) as jml from(
        SELECT
            loan_app_no,
            max( tbo_date ) AS tbo_date 
        FROM
        COMMENT 
        WHERE
            loan_app_no IN ( SELECT loan_app_no FROM data_file WHERE final_status = 3 and kode_cabang  = '".$id."' ) 

        GROUP BY
            loan_app_no 
        HAVING
            date(
            max( tbo_date )) < date(
            now())
            ) x";
    $count_tbo = DB::select($sql);
    foreach($count_tbo as $c_total_tbo){
        $total_tbo=$c_total_tbo->jml;
    }
    return $total_tbo.' / '.$jml_limit;
}

function getLimitTBOOverdue($id){

    $sql="select total_limit_overdue from branch_tbo where branch_code = '".$id."'";
    $result = DB::select($sql);
    if(!$result == null){
        $jml_limit=$result[0]->total_limit_overdue;
        }else{
            $jml_limit='-';
        }
    return $jml_limit;

}

function sendEmailRequestTbo($cabang,$id,$link,$role){

    if($role=="pc"){
        $sql = "
        SELECT
        users.email
    FROM
        users
        INNER JOIN
        branchlist
        ON 
            users.cabang = branchlist.branch_code
        where branch_code = (select parent_branch from branchlist where branch_code = '".$cabang."')
        and role='".$role."'";

    }else{
        $sql= "SELECT
        users.email
    FROM users
        where role='".$role."'";
    }
   
    $email = DB::select($sql);
    $to="";
    if($email!=null){
        $to=$email[0]->email;
    }else{
        $to="";
    }

    $sqldetail="SELECT
	perubahan_tgl_tbo_detail.loan_app_no,
	perubahan_tgl_tbo_detail.nama_debitur,
	perubahan_tgl_tbo_detail.dokumen_tbo,
	perubahan_tgl_tbo_detail.tgl_sebelum_perubahan,
	perubahan_tgl_tbo_detail.tgl_sesudah_perubahan,
	perubahan_tgl_tbo_detail.perubahan_ke,
	perubahan_tgl_tbo_detail.note,
	perubahan_tgl_tbo.branch_input
	
FROM
	perubahan_tgl_tbo_detail
	INNER JOIN
	perubahan_tgl_tbo
	ON 
		perubahan_tgl_tbo_detail.id_perubahan = perubahan_tgl_tbo.id
WHERE
	id_perubahan = '".$id."'";
   
   $detailloan = DB::select($sqldetail);
    $details = [
        'cabang' => getBranchname($cabang),
        'link'=>$link,
        'role'=>$role,
        'detailloan'=>$detailloan
        ];
        //$to='wandi.giraldi@bankwoorisaudara.com';
      // dd($details);
        Mail::to($to)->cc(['dcrmdepartment@bankwoorisaudara.com'])->send(new requestTbo($details));
        //Mail::to($to)->send(new requestTbo($details));
       
        return true;
}

function sendEmailNotifikasiTest($loan_app_no, $result) {
    $sql = "SELECT DISTINCT
            u1.NAME AS user_input_name,
            u1.email AS user_input_email,
            u2.NAME AS user_spv_email_nama,
            u2.email AS user_spv_email 
        FROM
            data_file
            LEFT JOIN users AS u1 ON data_file.user_input = u1.nik
            LEFT JOIN users AS u2 ON data_file.user_spv1 = u2.nik 
        WHERE
            loan_app_no = '".$loan_app_no."';";

    $email = DB::select($sql);

    $to = "";
    $to_spv = "";
    $to_user_input_name = "";
    $to_user_spv_email_nama = "";

    if ($email != null && count($email) > 0) {
        $to = $email[0]->user_input_email ?? "";
        $to_spv = $email[0]->user_spv_email ?? "";
        $to_user_input_name = $email[0]->user_input_name ?? "";
        $to_user_spv_email_nama = $email[0]->user_spv_email_nama ?? "";
    }

    // Validasi alamat email untuk memastikan tidak ada yang kosong atau tidak valid
    $validEmails = array_filter([$to, $to_spv, 'dcrmdepartement@bankwoorisaudara.com'], function($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    });

    if (empty($validEmails)) {
        // Tidak ada email valid, return false atau lakukan penanganan error yang sesuai
        return true;
    }
	$to='irfan.luthfi@bankwoorisaudara.com';
	$to_spv='irfan.luthfi@bankwoorisaudara.com';
	
    $details = [
        'user_input_name' => $to_user_input_name,
        'user_spv_email_nama' => $to_user_spv_email_nama,
        'loan_app_no' => $loan_app_no,
        'result' => $result,
    ];
	//dd($to.$to_spv);
    // Kirim email hanya jika $to adalah email yang valid
    if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
        Mail::to($to)->cc(array_filter([$to_spv, 'dcrmdepartement@bankwoorisaudara.com'], function($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }))->send(new Notif($details));
        return true;
    }

    return true;
}

function sendEmailNotifikasi($loan_app_no, $result) {
    $sql = "SELECT DISTINCT
            u1.NAME AS user_input_name,
            u1.email AS user_input_email,
            u2.NAME AS user_spv_email_nama,
            u2.email AS user_spv_email 
        FROM
            data_file
            LEFT JOIN users AS u1 ON data_file.user_input = u1.nik
            LEFT JOIN users AS u2 ON data_file.user_spv1 = u2.nik 
        WHERE
            loan_app_no = '".$loan_app_no."';";

    $email = DB::select($sql);

    $to = "";
    $to_spv = "";
    $to_user_input_name = "";
    $to_user_spv_email_nama = "";

    if ($email != null && count($email) > 0) {
        $to = $email[0]->user_input_email ?? "";
        $to_spv = $email[0]->user_spv_email ?? "";
        $to_user_input_name = $email[0]->user_input_name ?? "";
        $to_user_spv_email_nama = $email[0]->user_spv_email_nama ?? "";
    }

    // Validasi alamat email untuk memastikan tidak ada yang kosong atau tidak valid
    $validEmails = array_filter([$to, $to_spv, 'dcrmdepartement@bankwoorisaudara.com'], function($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    });

    if (empty($validEmails)) {
        // Tidak ada email valid, return false atau lakukan penanganan error yang sesuai
        return true;
    }
	//$to='irfan.luthfi@bankwoorisaudara.com';
	//$to_spv='dedi.nurfalaq@bankwoorisaudara.com';
	
    $details = [
        'user_input_name' => $to_user_input_name,
        'user_spv_email_nama' => $to_user_spv_email_nama,
        'loan_app_no' => $loan_app_no,
        'result' => $result,
    ];
	//dd($to.$to_spv);
    // Kirim email hanya jika $to adalah email yang valid
    if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
        Mail::to($to)->cc(array_filter([$to_spv, 'dcrmdepartement@bankwoorisaudara.com'], function($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }))->send(new Notif($details));
        return true;
    }

    return true;
}


 function sendEmailRequestJam($cabang,$jam,$link,$role){

    if($role=="pc"){
        $sql = "
        SELECT
        users.email
    FROM
        users
        INNER JOIN
        branchlist
        ON 
            users.cabang = branchlist.branch_code
        where branch_code = (select parent_branch from branchlist where branch_code = '".$cabang."')
        and role='".$role."'";

    }elseif($role=="spv4"){
        $sql= "SELECT
        users.email
    FROM users
        where role='".$role."'  ORDER BY updated_at desc limit 1";
    }
   
    $email = DB::select($sql);
    $to="";
    if($email!=null){
        $to=$email[0]->email;
    }else{
        $to="";
    }
   
    $details = [
        'cabang' => getBranchname($cabang),
        'jam' => $jam,
        'link'=>$link,
        'role'=>$role
        ];
        //$to="wandi.giraldi@bankwoorisaudara.com";
        Mail::to($to)->cc(['dcrmdepartment@bankwoorisaudara.com'])->send(new requestJam($details));
        //Mail::to($to)->send(new requestJam($details));
        return true;
}

function sendEmailRequestJamHQ($jam,$link,$role){

    
        $sql= "SELECT
        users.email
    FROM users
        where role='spv5'";
    
   
    $email = DB::select($sql);
    $to="";
    if($email!=null){
        $to=$email[0]->email;
    }else{
        $to="";
    }
   
    $details = [
        'jam' => $jam,
        'link'=>$link,
        'role'=>$role
        ];
        //$to="wandi.giraldi@bankwoorisaudara.com";
        Mail::to($to)->cc(['dcrmdepartment@bankwoorisaudara.com'])->send(new requestJamHQ($details));
        //Mail::to($to)->send(new requestJamHQ($details));
        return true;
}
function getLoanInfo($id)
{
    $loan= DB::table('data_file')
            ->where('loan_app_no',$id)
            ->get();
   
    return $loan;
}

function changeDate($date)
{
    $y=substr($date,0,4);
    $m=substr($date,4,2);
    $d=substr($date,6,2);
    $newdate=$m."-".$d."-".$y;
    return $newdate;
}
function getFlagColor($flag_spv){
    if($flag_spv == 1){
        $flag_name="<div style='color:green; font-weight: bold;'>Verify</div>";
    }else if($flag_spv == 2){
        $flag_name="<div style='color:orange;font-weight: bold;'>Not Verify</div>";
    }else if($flag_spv == 3){
        $flag_name="<div style='color:blue;font-weight: bold;'>TBO</div>";
    }else if($flag_spv == 4){
        $flag_name="<div style='color:red;font-weight: bold;'>Reject</div>";
    }else if($flag_spv == 5){
        $flag_name="<div style='color:green;font-weight: bold;'>Approve (Verifikator)</div>";
    }else if($flag_spv == 6){
        $flag_name="<div style='color:red;font-weight: bold;'>Not Approve (Verifikator)</div>";
    }else{
        $flag_name="<div style='color:gray;font-weight: bold;'>Pending</div>";
    }
    return $flag_name;
 }

 function getLevel($id){
    $lbl_level_spv='';
    // if($id=='spv1'){
    //     $lbl_level_spv='Spv Branch';
    // }else if($id=='spv2'){
    //     $lbl_level_spv='DCRM REVIEWER';
    // }else if($id=='spv3' || $id=='spv4'){
    //     $lbl_level_spv='DCRM TEAM LEADER';
    // }else if($id=='pcp'){
    //     $lbl_level_spv='PCP';
    // }else if($id=='pc'){
    //     $lbl_level_spv='PC';
    // }
    if($id=='staff'){
        $lbl_level_spv='BRANCH';
    }else if($id=='spv3' || $id=='spv4'){
        $lbl_level_spv='DCRM';
    }else if($id=='team_verifikator_lvl1'){
        $lbl_level_spv='VERIFIKATOR LEVEL 1';
    }else if($id=='team_verifikator_lvl2'){
        $lbl_level_spv='VERIFIKATOR LEVEL 2';
    }else{
        $lbl_level_spv='';
    }
    return $lbl_level_spv;
 }

function getBranchname($id)
{
    $branch= DB::table('branchlist')
            ->where('branch_code',$id)
            ->get();
    $branch_name=$branch[0]->branch_name;
    return $branch_name;
}

function getBranchtype($id)
{
    $branch= DB::table('branchlist')
            ->where('branch_code',$id)
            ->get();
    $branch_type=$branch[0]->branch_type;
    return $branch_type;
}

function getBranchParent($id)
{
    $branch= DB::table('branchlist')
            ->where('branch_code',$id)
            ->get();
    $parent_branch=$branch[0]->parent_branch;
    return $parent_branch;
}


function checkCanDelete($id){
    $data = DataFileModel::where('loan_app_no','=',$id)->first();
    $isDeleted=false;
    if((Session('role')=='staff' && $data->final_status_spv1 == 0 )){
        $isDeleted=true;
    } 
    return $isDeleted;
} 
function log_sla_bpr($id){
    $data = DataFileModel::where('loan_app_no','=',$id)->first();

    
    $log_sla = SlaBpr::where('loan_app_no','=',$id)->where('isLocked','=',1)->first();
    return redirect()->back();
    /*if($log_sla<> null){
        return redirect()->back()
        ->with('error',"Loan App No ".$log_sla->loan_app_no." sedang direview oleh ".$log_sla->nik."-".getnameuser($log_sla->nik));
    }else{
        if((Session('role')=='spv2' && $data->final_status_spv1 != 0 ) ||
           ((Session('role')=='spv3' || Session('role')=='spv4') && $data->final_status_spv2 != 0 )
        )
        {
            $file = new SlaBpr;
            $file->loan_app_no = $id;
            $file->nik = Session('nik');
            $file->level_spv = Session('role'); 
            $file->created_at = date('Y-m-d H:i:s');
            $file->save();
        }
    }*/
    
    

     
        
    
    

} 
function checklevelReview($id){
    $data = DataFileModel::where('loan_app_no','=',$id)->first();
    
    $level=0;
    if($data->final_status_spv1 == 0){
        $level=1;
    }else if($data->final_status_spv1 !== 0 && $data->final_status_spv2 ==0){
        $level=2;
    }else if($data->final_status_spv2 !== 0 && $data->final_status_spv3 ==0){
        $level=3;
    }else if($data->final_status_spv1 !== 0 && $data->final_status_spv2 !== 0 && $data->final_status_spv3 !== 0){
        $level=4;
    }
    
    $role=Session('role');
    $review=false;
    if($level==1 && $role=='spv1' || $role=='pc' || $role=='pcp' ){
        $review=true;
    }else if($level==2 && $role=='spv2' ){
        $review=true;
    }else if($level==3 && ($role=='spv3' || $role=='spv4')){
        $review=true;
    }else if($level==4){
        $review=true;
    }
    //dd($review);
    return $review;
}

 function checkEmptyLabel($id){
    $count = DetailFileModel::where('alias','=',null)->where('loan_app_no','=',$id)->count();
    return $count;
}
function getStatusPernikahan($id){
    if($id==10){
       $data="Belum Menikah";
    }else if($id==20){
        $data="Menikah";
    }else if($id==40){
        $data="Janda/Duda";
    }else{
        $data="";
    }
    return $data;
}
function checkJenisProduk($id){
    $jenis_produk = DB::table('master_produk')
        ->where('produk','=',$id)
        ->first();
        if($jenis_produk->jenis_produk==1){
            $route='datafile.kupen';
        }else if($jenis_produk->jenis_produk==2){
            $route='datafile.kupenHybrid';
        }else if($jenis_produk->jenis_produk==3){
            $route='datafile.kupeg';
        }else if($jenis_produk->jenis_produk==4){
            $route='datafile.kph';
        }else if($jenis_produk->jenis_produk==5){
            $route='datafile.kpkb_wni';
        }else if($jenis_produk->jenis_produk==6){
            $route='datafile.kpkb_wna';
        }else{
            $route='loans.index';
        }
    return $route;
}

//  function checkTboCount(){
//      if(session("role")=="staff" || session("role")=="spv1" ){
    
//         $branch= Branchlist::findOrFail(Session("branch_code"));
//         $count_tbo= DataFileModel::where('final_status','=',3)->where('kode_cabang','=',$branch->branch_code)->count();
//         //var_dump($branch->branchtbo);
//         $jml_max_tbo=$branch->branchtbo[0]->jml;
//         //$jml_max_tbo=100;
//         if($count_tbo>$jml_max_tbo){
//             return true;
//         }else{
//             return false;
//         }
//      }else{
//         return false;
//      }
// }

function checkTboCount(){
    if(session("role")=="staff" || session("role")=="spv1" || session("role")=="pc" || session("role")=="pcp"){
   
       $branch= Branchlist::findOrFail(Session("branch_code"));
       //$count_tbo= DataFileModel::where('final_status','=',3)->where('kode_cabang','=',$branch->branch_code)->count();
       $sql="select count(1) as jml from(
        SELECT
            loan_app_no,
            max( tbo_date ) AS tbo_date 
        FROM
        COMMENT 
        WHERE
            loan_app_no IN ( SELECT loan_app_no FROM data_file WHERE final_status = 3 and kode_cabang  = '".Session("branch_code")."' ) 

        GROUP BY
            loan_app_no 
        HAVING
            date(
            max( tbo_date )) < date(
            now())
            ) x";
     
       $count_tbo = DB::select($sql);
       foreach($count_tbo as $c_total_tbo){
        $total_tbo=$c_total_tbo->jml;
      }
       //dd($total_tbo);
       //$jml_max_tbo=$branch->branchtbo[0]->total_limit_overdue;
       if (isset($branch->branchtbo[0])) {
             $jml_max_tbo = $branch->branchtbo[0]->total_limit_overdue;
        } else {
            $jml_max_tbo = 0; // Atau nilai default lainnya
        }
       //$jml_max_tbo=100;
       if($total_tbo>$jml_max_tbo){
           return true;
       }else{
           return false;
       }
    }else{
       return false;
    }
}
function checkTboCountIdLoan($id){
       $branchloan= DataFileModel::findOrFail($id);
       $branch= Branchlist::findOrFail($branchloan->kode_cabang);
       //$count_tbo= DataFileModel::where('final_status','=',3)->where('kode_cabang','=',$branch->branch_code)->count();
       $sql="select count(1) as jml from(
        SELECT
            loan_app_no,
            max( tbo_date ) AS tbo_date 
        FROM
        COMMENT 
        WHERE
            loan_app_no IN ( SELECT loan_app_no FROM data_file WHERE final_status = 3 and kode_cabang  = '".$branchloan->kode_cabang."' ) 

        GROUP BY
            loan_app_no 
        HAVING
            date(
            max( tbo_date )) < date(
            now())
            ) x";
     
       $count_tbo = DB::select($sql);
       foreach($count_tbo as $c_total_tbo){
        $total_tbo=$c_total_tbo->jml;
      }
       //dd($total_tbo);
       //$jml_max_tbo=$branch->branchtbo[0]->total_limit_overdue;
       if (isset($branch->branchtbo[0])) {
         $jml_max_tbo = $branch->branchtbo[0]->total_limit_overdue;
        } else {
            $jml_max_tbo = 0; // Atau nilai default lainnya
        }
       //$jml_max_tbo=100;
       if($total_tbo>$jml_max_tbo){
           return true;
       }else{
           return false;
       }
   
}

function checkFinalStatus($id){
    
    $data = DataFileModel::where('loan_app_no','=',$id)->first();

    $final_status = $data->final_status;
    return $final_status;
}

function checkFasilitas($id){
    
        $data = DataFileModel::where('loan_app_no','=',$id)->first();

        $fasilitas = $data->fasilitas;
        return $fasilitas;
}
function checkCutOff($fasilitas){
    if(session("role")=="staff" || session("role")=="spv1" || session("role")=="pc" || session("role")=="pcp" ){
        $cut_off= CutOff::where('branch_code','=',Session('branch_code'))->get();
        //$co = $cut_off->cut_off;
    // $ThatTime =$cut_off[0]->cut_off;

        if (isset($cut_off[0])) {
            $ThatTime =$cut_off[0]->cut_off;
        } else {
                $ThatTime = 0; // Atau nilai default lainnya
        }
        if (time() >= strtotime($ThatTime)) {
            if($fasilitas==1){
                $result=true;
            }else{
                $result=false;
            }
            
        }else{        
            $result=false;
        }
    }else{
        $result=false;
    }
    //dd($result);
    return $result;
    //return response()->json(['message'=>'Cut Off Time','errors'=>'Anda tidak diperbolehkan melakukan aktifitas apapun.']);
    
 }
function getStatusProcessed($id){
    if($id == 0 )
    {
        return '<span class="badge badge-dark">Waiting Final Status Verify</span>';
    }else if($id == 1 )
    {
        return '<span class="badge badge-warning">Waiting Processed</span>';
    }else if($id == 2 )
    {
        return '<span class="badge badge-success">Processed</span>';
    }else if($id == 4 )
    {
        return '<span class="badge badge-danger">Reject</span>';
    }
       
    

}

function getNotifPulseStaff($role){
    $verify=getVerifyNotif($role);
    if($verify>0){
        return '<span class=" pulse">';
    }else{
        return '';
    }

}

function getNotifPulse($role){
    $pending=getPendingNotif($role);
    $notverify=getNotVerifyNotif($role);
    $tbo=getTBONotif($role);
    $reject=getRejectNotif($role);
    $jml=$pending+$notverify+$tbo+$reject;
    if($jml>0){
        return '<span class=" pulse">';
    }else{
        return '';
    }

}

function getVerifyNotif($role){
        $stat = DataFileModel::where('processed', '=', 1)->where('kode_cabang', '=', Session('branch_code'))->count();
        //$stat_count = $stat->count();
        return $stat;
}



function getWaitingNotif($role){
    if($role=='spv1' || $role=='pc' || $role=='pcp'){
        $stat_count = DataFileModel::where(function ($query) {
            $query->where('data_file.final_status_spv1','=',0)
                  ->orWhere('data_file.final_status_spv1', '=', 2);})
                  ->where('kode_cabang', '=', Session('branch_code'))->where('final_status', '<>', 4)->where(DB::raw('date(date_input)'),'=',DB::raw('date(now())'))->count();
        //$stat_count = DataFileModel::where('final_status_spv1', '=', 0)->where('kode_cabang', '=', Session('branch_code'))->where('final_status', '<>', 4)->where(DB::raw('date(date_input)'),'=',DB::raw('date(now())'))->count();
        //$stat_count = $stat->count();
    }else if($role=='spv2'){
        $stat_count = DataFileModel::where('final_status_spv1', '<>', 0)->where('final_status_spv2', '=', 0)->where('final_status', '<>', 4)->where(DB::raw('date(date_input)'),'=',DB::raw('date(now())'))->count();
        //$stat_count = $stat->count();
    }else if($role=='spv3' || $role=='spv4'){
        $stat_count = DataFileModel::where(function ($query) {
            $query->where('data_file.final_status_spv2','=',1)
                  ->orWhere('data_file.final_status_spv2', '=', 3);
          })->where('final_status_spv3', '=', 0)->where('final_status', '<>', 4)->where(DB::raw('date(date_input)'),'=',DB::raw('date(now())'))->count();
        //$stat_count = DataFileModel::where('final_status_spv2', '<>', 0)->where('final_status_spv3', '=', 0)->where('final_status', '<>', 4)->where(DB::raw('date(date_input)'),'=',DB::raw('date(now())'))->count();
        //$stat_count = $stat->count();
    }else{
        $stat_count = 0;
    }
    
    return $stat_count;
}

function getPendingNotif($role){
    if($role=='spv1' || $role=='pc' || $role=='pcp'){
        $stat_count = DataFileModel::where(function ($query) {
            $query->where('data_file.final_status_spv1','=',0)
                  ->orWhere('data_file.final_status_spv1', '=', 2);})
                  ->where('kode_cabang', '=', Session('branch_code'))->where('final_status', '<>', 4)->count();
        //$stat_count = $stat->count();
    }else if($role=='spv2'){
        $stat_count = DataFileModel::where('final_status_spv1', '<>', 0)->where('final_status_spv2', '=', 0)->where('final_status', '<>', 4)->where(DB::raw('date(date_input)'),'<',DB::raw('date(now())'))->count();
        //$stat_count = $stat->count();
    }else if($role=='spv3' || $role=='spv4'){
        $stat_count = DataFileModel::where(function ($query) {
            $query->where('data_file.final_status_spv2','=',1)
                  ->orWhere('data_file.final_status_spv2', '=', 3);
          })->where('final_status_spv3', '=', 0)->where('final_status', '<>', 4)->count();
        //$stat_count = $stat->count();
    }else{
        $stat_count = 0;
    }
    
    return $stat_count;
}

function getNotVerifyNotif($role){
    if($role=='spv1' || $role=='pc' || $role=='pcp'){
        $stat_count = DataFileModel::where('final_status_spv1', '=', 2)->where('final_status', '<>', '4')->where('kode_cabang', '=', Session('branch_code'))->count();
        //$stat_count = $stat->count();
        
    }else if($role=='spv2'){
        $stat_count = DataFileModel::where('final_status_spv2', '=', 2)->where('final_status', '<>', '4')->count();
        //$stat_count = $stat->count();
    }else if($role=='spv3'  || $role=='spv4'){
        $stat_count = DataFileModel::where('final_status_spv3', '=', 2)->where('final_status', '<>', '4')->count();
        //$stat_count = $stat->count();
    }else{
        $stat_count = 0;
    }
    
    return $stat_count;
}

function getTBONotif($role){
    if($role=='spv1' || $role=='pc' || $role=='pcp'){
       // $stat_count = DataFileModel::where('final_status_spv1', '=', 3)->where('final_status', '=', '3')->where('kode_cabang', '=', Session('branch_code'))->count();
       $sql_total_tbo = 'select count(1) as jml from
       (SELECT
                 count(1)
           FROM
               `data_file`
               INNER JOIN `comment` ON `comment`.`loan_app_no` = `data_file`.`loan_app_no` 
           WHERE
               -- date(`comment`.`tbo_date`) < date(now()) AND
               `data_file`.`kode_cabang` = "'.Session('branch_code').'" and
               `data_file`.`final_status` = 3
               GROUP BY`data_file`.`loan_app_no` ) x';
              
     $count_total_tbo = DB::select($sql_total_tbo);
     $total_tbo =$count_total_tbo;
     foreach($count_total_tbo as $c_total_tbo){
       $stat_count=$c_total_tbo->jml;
     }
        //$stat_count = $stat->count();
    }else if($role=='spv2'){
        $sql_total_tbo = 'select count(1) as jml from
        (SELECT
                  count(1)
            FROM
                `data_file`
                INNER JOIN `comment` ON `comment`.`loan_app_no` = `data_file`.`loan_app_no` 
            WHERE
                `data_file`.`final_status` = 3
                GROUP BY`data_file`.`loan_app_no` ) x';
               
      $count_total_tbo = DB::select($sql_total_tbo);
      $total_tbo =$count_total_tbo;
      foreach($count_total_tbo as $c_total_tbo){
        $stat_count=$c_total_tbo->jml;
      }
        //$stat_count = DataFileModel::where('final_status_spv2', '=', 3)->where('final_status', '=', '3')->count();
        //$stat_count = $stat->count();
    }else if($role=='spv3'  || $role=='spv4'){
        $sql_total_tbo = 'select count(1) as jml from
        (SELECT
                  count(1)
            FROM
                `data_file`
                INNER JOIN `comment` ON `comment`.`loan_app_no` = `data_file`.`loan_app_no` 
            WHERE
                `data_file`.`final_status` = 3
                GROUP BY`data_file`.`loan_app_no` ) x';
               
      $count_total_tbo = DB::select($sql_total_tbo);
      $total_tbo =$count_total_tbo;
      foreach($count_total_tbo as $c_total_tbo){
        $stat_count=$c_total_tbo->jml;
      }
       // $stat_count = DataFileModel::where('final_status_spv3', '=', 3)->where('final_status', '=', '3')->count();
        //$stat_count = $stat->count();
    }else{
        $stat_count = 0;
    }
    
    return $stat_count;
}

function getRejectNotif($role){
    if($role=='spv1' || $role=='pc' || $role=='pcp'){
        $stat_count = DataFileModel::where('final_status', '=', '4')->where('kode_cabang', '=', Session('branch_code'))->count();
        //$stat_count = $stat->count();
    }else if($role=='spv2'){
        $stat_count = DataFileModel::where('final_status', '=', '4')->count();
        //$stat_count = $stat->count();
    }else if($role=='spv3'  || $role=='spv4'){
        $stat_count = DataFileModel::where('final_status', '=', '4')->count();
        //$stat_count = $stat->count();
    }else{
        $stat_count = 0;
    }
    
    return $stat_count;
}

 function getLabelStatus($id){
    if($id==0){
        $label='<span class="badge badge-dark">Pending</span>';
    }else if($id==1){
        $label='<span class="badge badge-success">Verify</span>';
    }else if($id==2){
        $label='<span class="badge badge-warning">Not Verify</span>';
    }else if($id==3){
        $label='<span class="badge badge-primary">TBO</span>';
    }else if($id==4){
        $label='<span class="badge badge-danger">Reject</span>';
    }else if($id==5){
        $label='<span class="badge badge-success">Approved</span>';
    }else if($id==6){
        $label='<span class="badge badge-danger">Not Approved</span>';
    }
    return $label;
}

function getBadgeMandatoryEarly($id){
    $label='<span class="badge badge-pill badge-danger">Mandatory</span>';
    return $label;
}
function getScore($loan_app_no, $dok, $namafile) {
    $label = "";
    //if ($dok == "Copy KTP" || $dok == "KK" || $dok == "MCC" || $dok == "SK Pensiun" || $dok == "Perjanjian Kredit yang telah ditandatangani oleh pihak Bank dan Nasabah") {
        if ($dok == "[Salinan] KTP Debitur / Calon Debitur" 
            || $dok == "[Salinan] Kartu Keluarga" 
            || $dok == "[Asli] Surat Keputusan Pensiun"
            || $dok == "E Form Fronting Agent"
            || $dok == "Hasil SLIK Checking atas nama Calon Debitur"
            || $dok == "Memorandum Credit Committee (MCC)"
            || $dok == "Memo Permohonan Pelaksanaan Take Over"
            || $dok == "[Salinan] Mutasi Rekening Debitur/Calon Debitur"
            || $dok == "Perjanjian Kredit"
            || $dok == "Surat Keterangan Usaha (SKU) / Legalitas Usaha"
            || $dok == "Slip/Ledger Gaji"
            || $dok == "Surat Kuasa Pemasangan Flagging"
            || $dok == "[Asli] Surat Keputusan Pegawai (Swasta)"
            ) {
            $datafile = DataFileModel::where('loan_app_no', $loan_app_no)->first();
            $detailfile = DetailFileModel::where('loan_app_no', $loan_app_no)
                ->where('alias', $dok)
                ->where('file', 'like', '%' . $namafile . '%')
                ->first();
               // dd($datafile->final_status_spv1);
            if($datafile->final_status_spv1 === 1 || $datafile->final_status_spv1 === 3){
                //dd($datafile->final_status_spv1);
                if ($detailfile) {
                    $now = now();
                    $date_flag_spv1 = \Carbon\Carbon::parse($datafile->date_flag_spv1);
                    $minutesPassed = $date_flag_spv1->diffInMinutes($now);
                    
                    if ($detailfile->score === null && $minutesPassed > 5) {
                        $label = '<span class="badge badge-pill badge-warning">NOT CHECKED</span>';
                    } else if ($detailfile->score === null) {
                        $label = '<span class="badge badge-pill badge-primary">WAITING</span>';
                    } else {
                        $score = $detailfile->score;
                        $scoreLabel = '';
                        
                        if ($score >= 0 && $score <= 25) {
                            $scoreLabel = 'Verification Failed';
                            $badgeColor = 'danger';
                        } elseif ($score > 25 && $score <= 80) {
                            $scoreLabel = 'Need Review';
                            $badgeColor = 'warning';
                        } elseif ($score > 80 && $score <= 100) {
                            $scoreLabel = 'Verification Success';
                            $badgeColor = 'success';
                        }
                        
                        $label = '<span class="badge badge-pill badge-' . $badgeColor . '">SCORE: ' . $score . ' - ' . $scoreLabel . '</span>';
                    }
                    return $label;
                }
            }else{
                return $label;
            }
    }
    
}
function getBadgeMandatory($id,$mandatory,$status_pernikahan,$pekerjaan,$fasilitas){
    //dd($status_pernikahan);
    $label='';
    if($mandatory == 1){
        


        if($status_pernikahan==20 && ($id=="Copy KTP Pasangan" || $id=="Copy Akta Nikah"|| $id=="Copy Akta Cerai/Akta Kematian")){
            if($id=="Copy KTP Pasangan"){
                $label='<span class="badge badge-pill badge-danger">Mandatory</span>';
            }
            if($id=="Copy Akta Nikah"){
                $label='<span class="badge badge-pill badge-danger">Mandatory</span>';
            }
            if($id=="Copy Akta Cerai/Akta Kematian"){
                $label='';
            }

        }else if(($status_pernikahan==40)  && ($id=="Copy KTP Pasangan" || $id=="Copy Akta Nikah"|| $id=="Copy Akta Cerai/Akta Kematian")){
            if($id=="Copy KTP Pasangan"){
                $label='';
            }
            if($id=="Copy Akta Nikah"){
                $label='';
            }
            if($id=="Copy Akta Cerai/Akta Kematian"){
                $label='<span class="badge badge-pill badge-danger">Mandatory</span>';
            }
        }else if($status_pernikahan==10  && ($id=="Copy KTP Pasangan" || $id=="Copy Akta Nikah"|| $id=="Copy Akta Cerai/Akta Kematian")){
            if($id=="Copy KTP Pasangan"){
                $label='';
            }
            if($id=="Copy Akta Nikah"){
                $label='';
            }
            if($id=="Copy Akta Cerai/Akta Kematian"){
                $label='';
            }
           
        }else{
            $label='<span class="badge badge-pill badge-danger">Mandatory</span>';
        }
       
        
        
    }else{
        if($pekerjaan == "KARYAWAN" && ($id=="Slip Gaji" || $id=="Form interview dan Kunjungan karyawan")){
            if($id=="Slip Gaji"){
                $label='<span class="badge badge-pill badge-danger">Mandatory</span>';
            }
            if($id=="Form interview dan Kunjungan karyawan"){
                $label='<span class="badge badge-pill badge-danger">Mandatory</span>';
            }
            if($id=="Form interview dan kunjungan pengusaha & professional (Khusus untuk pengusaha)"){
                $label='';
            }
            if($id=="Surat Ijin Praktek"){
                $label='';
            }
            if($id=="Legalitas Usaha"){
                $label='';
            }
        }else  if($pekerjaan == "PROFESIONAL" && ($id=="Form interview dan kunjungan pengusaha & professional (Khusus untuk pengusaha)" || $id=="Surat Ijin Praktek")){
            if($id=="Slip Gaji"){
                $label='';
            }
            if($id=="Form interview dan Kunjungan karyawan"){
                $label='';
            }
            if($id=="Form interview dan kunjungan pengusaha & professional (Khusus untuk pengusaha)"){
                $label='<span class="badge badge-pill badge-danger">Mandatory</span>';
            }
            if($id=="Surat Ijin Praktek"){
                $label='<span class="badge badge-pill badge-danger">Mandatory</span>';
            }
            if($id=="Legalitas Usaha"){
                $label='';
            }
        }else  if($pekerjaan == "WIRASWASTA" && ($id=="Form interview dan kunjungan pengusaha & professional (Khusus untuk pengusaha)" || $id=="Surat Ijin Praktek" || $id=="Legalitas Usaha")){
            if($id=="Slip Gaji"){
                $label='';
            }
            if($id=="Form interview dan Kunjungan karyawan"){
                $label='';
            }
            if($id=="Form interview dan kunjungan pengusaha & professional (Khusus untuk pengusaha)"){
                $label='<span class="badge badge-pill badge-danger">Mandatory</span>';
            }
            if($id=="Surat Ijin Praktek"){
                $label='';
            }
            if($id=="Legalitas Usaha"){
                $label='<span class="badge badge-pill badge-danger">Mandatory</span>';
            }
        }else{
            $label='';
        }
        if($fasilitas == 2 && $id=="Legalitas Usaha"){
            $label='<span class="badge badge-pill badge-danger">Mandatory</span>';
        }
        
    }
    return $label;
}
function getflagname($id){

        $flag = FlagSpv::find($id);   
        if($flag == null){
            return $id;
        }else{
            return $flag->name;
        }

    
    
    
}

function getnameuser($id){

    
    $user = User::find($id);
    $name="";
    if($user != null){
        $name=$user->name;
    }else{
        $name="";
    }
    if($id == 'system'){
        $name='System';
    }
    return $name;
}


 function getlastnamefile($name){
    preg_match("/[^\/]+$/", $name, $matches);
    $last_word = $matches[0]; 
    return $last_word;
}

// function get_warna_new($id, $name, $label_only) {
    
    
//     $detailfile = DetailFileModel::where('loan_app_no', $id)->where('alias', $name)->first();
//     if($label_only==1){
//         return 'dark';
//     }
//     if (empty($detailfile)) {
//         return 'error';
//     }

      
    
    
//     $kategori = DokumenKategori::with(['dokumen', 'dokumen.subDokumen'])
//         ->whereHas('dokumen', function($query) use ($detailfile) {
//             $query->where('nama_dokumen', 'like', '%' . trim($detailfile->alias) . '%');
//         })
//         ->orWhereHas('dokumen.subDokumen', function($query) use ($detailfile) {
//             $query->where('nama_sub_dokumen', 'like', '%' . trim($detailfile->alias) . '%');
//         })
//         ->get();
       
//     foreach ($kategori as $kat) {
       
//         if (
//             $kat->dokumen->contains('nama_dokumen', trim($detailfile->alias)) ||
//             $kat->dokumen->flatMap->subDokumen->contains('nama_sub_dokumen', trim($detailfile->alias))
//         ) {
//             return 'success';
//         }
//     }
  
//     return 'error';
// }

function get_warna_new($id, $name, $label_only) {
    // Early return untuk label_only
    if ($label_only == 1) {
        return 'dark';
    }
    
    // Mencari detail file
    $detailfile = DetailFileModel::where('loan_app_no', $id)
                                ->where('alias', $name)
                                ->first();
    
    // Early return jika detail file tidak ditemukan
    if (empty($detailfile)) {
        return 'error';
    }
    
    // Standardisasi nama file yang dicari
    $searchTerm = strtolower(trim($detailfile->alias));
    
    // Query untuk mencari di kategori dan dokumen terkait
    $kategori = DokumenKategori::with(['dokumen', 'dokumen.subDokumen'])
        ->whereHas('dokumen', function($query) use ($searchTerm) {
            $query->whereRaw('LOWER(nama_dokumen) LIKE ?', ['%' . $searchTerm . '%']);
        })
        ->orWhereHas('dokumen.subDokumen', function($query) use ($searchTerm) {
            $query->whereRaw('LOWER(nama_sub_dokumen) LIKE ?', ['%' . $searchTerm . '%']);
        })
        ->get();
    
    // Jika tidak ada kategori yang ditemukan
    if ($kategori->isEmpty()) {
        return 'error';
    }
    
    // Pengecekan kecocokan exact untuk setiap kategori
    foreach ($kategori as $kat) {
        // Cek di nama_dokumen
        $dokumenMatch = $kat->dokumen->contains(function($dokumen) use ($searchTerm) {
            return strtolower(trim($dokumen->nama_dokumen)) === $searchTerm;
        });
        
        // Cek di nama_sub_dokumen
        $subDokumenMatch = $kat->dokumen->flatMap->subDokumen->contains(function($subDokumen) use ($searchTerm) {
            return strtolower(trim($subDokumen->nama_sub_dokumen)) === $searchTerm;
        });
        
        if ($dokumenMatch || $subDokumenMatch) {
            return 'success';
        }
    }
    
    return 'error';
}


function get_warna($id,$name){
	
    $datafile = DataFileModel::findOrFail($id);
   
    $detailfile = DetailFileModel::where('loan_app_no', $id)->where('alias', $name)->first();
    
    //$jenis_produk = JenisProduct::findOrFail($id);
    $products = MasterProduct::where('produk', $datafile->produk)->first();
    if($datafile->fasilitas == 3){
        $map_product = MapProduct::where('id_jenis_produk', 9)->get();
    }else{
        if($datafile->modul=="TAWON"){
            $map_product = MapProduct::where('id_jenis_produk', 10)->get();
        }else{
            $map_product = MapProduct::where('id_jenis_produk', $products->jenis_produk)->get();
        }
       //dd($map_product);
    }
    if(!empty($detailfile)){
      foreach($map_product as $map){
       // echo($map->nama_dokumen.'-'.$detailfile->alias);
        // if(trim($map->nama_dokumen) == trim($detailfile->alias)){
        //    //echo($map->nama_dokumen.'-'.$detailfile->alias);
        //     return 'success';
        // }
            $mapDocName = strtolower(trim($map->nama_dokumen));
            $detailAlias = strtolower(trim($detailfile->alias));
            
            // Membandingkan string yang sudah distandarisasi
            if ($mapDocName === $detailAlias) {
                return 'success';
            }
      }
    }
    return 'error';
}

function get_warna_early($id,$name){
	
    $datafile = DataFileModel::findOrFail($id);
   
    $detailfile = DetailFileModel::where('loan_app_no', $id)->where('alias', $name)->first();
    //var_dump( empty($detailfile));
    //$jenis_produk = JenisProduct::findOrFail($id);
    $map_product = DokumenEarly::all();
    if(!empty($detailfile)){
      foreach($map_product as $map){
       // echo($map->nama_dokumen.'-'.$detailfile->alias);
        if(trim($map->nama_dokumen) == trim($detailfile->alias)){
           //echo($map->nama_dokumen.'-'.$detailfile->alias);
            return 'success';
        }
      }
    }
    return 'error';
}

 function format_date_view($date){
	
	if($date=='0000-00-00 00:00:00' || $date==null){
		$resultdate='';
	}else{
		$resultdate=date('Y-m-d',strtotime($date));
	}
	return $resultdate;
}
function get_name_ehr($id){
	$users = DB::connection('mysql_ehr')->select('select name from data_employee where id = :id', ['id' => $id]);
	$name='';
	foreach($users as $u){
		$name=$u->name;
	}
	//dd($name);
	return $name;
}

function get_cabang_ehr($id){
	$users = DB::connection('mysql_ehr')->select('SELECT
master_branch.`name`
FROM
data_employee
INNER JOIN master_branch ON data_employee.branch_id = master_branch.id where data_employee.id = :id', ['id' => $id]);
	$name='';
	foreach($users as $u){
		$name=$u->name;
	}
	//dd($name);
	return $name;
}

function log_aktifitas($ket)
{
        DB::insert('insert into t_log_aktifitas (
            nik,
            nama,
            ket,
            created_at,
            update_at
            ) values (?,?,?,?,?)', [
                session('nik'),
                session('name'),
                $ket,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s')
            ]);
    
    return true;
}

function get_jml_transaksi($id)
{
    if($id !== NULL ){
        $jml=\App\Models\Transaction::where('entity_reference','=', $id)->count();
		
        $data=$jml;
		
    }else{
        $data='';
    }
    
    return $data;
}

 function get_jenis_transaksi($id)
{
    if($id !== NULL ){
        $keterangan=\App\Models\JenisTransalksiMode::where('Kode','=', $id)->first();
		if($keterangan==null){
		$data='';
		}else{
        $data=$keterangan->Keterangan;
		}
    }else{
        $data='';
    }
    
    return $data;
}

function get_instrumen_transaksi($id)
{
    
    if($id !== NULL ){
        $keterangan=\App\Models\InstrumenTransaksi::where('Kode','=', $id)->first();
        if($keterangan==null){
		$data='';
		}else{
			$data=$keterangan->Keterangan;
		}
    }else{
        $data='';
    }
    
    return $data;
}


function get_negara($id)
{
    if($id !== NULL ){
        $keterangan=\App\Models\NamaNegara::where('Kode','=', $id)->first();
        if($keterangan==null){
		$data='';
		}else{
			$data=$keterangan->Keterangan;
		}
    }else{
        $data='';
    }
    
    return $data;
  
}

function get_jenis_rek($id)
{
    if($id !== NULL ){
        $keterangan=\App\Models\JenisRekening::where('Kode','=', $id)->first();
		if($keterangan==null){
		$data='';
		}else{
        $data=$keterangan->Keterangan;
		}
    }else{
        $data='';
    }
    
    return $data;
}

function get_status_rek($id)
{
    if($id !== NULL ){
        $keterangan=\App\Models\StatusRekening::where('Kode','=', $id)->first();
		if($keterangan==null){
		$data='';
		}else{
        $data=$keterangan->Keterangan;
		}
    }else{
        $data='';
    }
    
    return $data;
}

function get_mata_uang($id)
{
   
    
    if($id !== NULL ){
        $keterangan=\App\Models\MataUangDunia::where('Kode','=', $id)->first();
        if($keterangan==null){
		$data='';
		}else{
			$data=$keterangan->Keterangan;
		}
    }else{
        $data='';
    }
    
    return $data;
}

function get_gender($id)
{
    if($id !== NULL ){
        $keterangan=\App\Models\JenisKelamin::where('Kode','=', $id)->first();
        if($keterangan==null){
		$data='';
		}else{
			$data=$keterangan->Keterangan;
		}
    }else{
        $data='';
    }
    
    return $data;
}

function get_kategori_kontak($id)
{
    if($id !== NULL ){
        $keterangan=\App\Models\KategoriKontak::where('Kode','=', $id)->first();
        if($keterangan==null){
		$data='';
		}else{
			$data=$keterangan->Keterangan;
		}
    }else{
        $data='';
    }
    
    return $data;
}

function get_alat_komunikasi($id)
{
    if($id !== NULL ){
        $keterangan=\App\Models\JenisAlatkomunikasi::where('Kode','=', $id)->first();
        if($keterangan==null){
		$data='';
		}else{
			$data=$keterangan->Keterangan;
		}
    }else{
        $data='';
    }
    
    return $data;
}

function get_no_ref($report_code)
{
    
        $nomor=\App\Models\CounterLaporan::where('TahunLaporan','=', date('Y'))->where('report_code','=',$report_code)->orderBy('Nomax', 'DESC')->first();

        if($nomor === NULL){
            $tahun=date('Y');
            $nomax=1;
            $no=$report_code.$tahun.'-'.$nomax;
        }else{
            $tahun=$nomor->TahunLaporan;
            $nomax=$nomor->Nomax+1;
            $no=$report_code.$tahun.'-'.$nomax;
            
        }

    return $no;
}

function get_userid($id)
{
    
        $userid=\App\User::where('id','=',$id)->first();

        if($userid === NULL){
            $data='';
        }else{
            $data=$userid->name;
        }

    return $data;
}

function get_submission_code($id)
{
    
        $type=\App\Models\SubmissionType::where('Kode','=',$id)->first();

        if($type === NULL){
            $data='';
        }else{
            $data=$type->Keterangan;
        }

    return $data;
}

function get_peran($id)
{
    
        $type=\App\Models\PeranDalamRekening::where('Kode','=',$id)->first();

        if($type === NULL){
            $data='';
        }else{
            $data=$type->Keterangan;
        }

    return $data;
}


function get_no_max($report_code)
{
    
        $nomor=\App\Models\CounterLaporan::where('TahunLaporan','=', date('Y'))->where('report_code','=',$report_code)->orderBy('Nomax', 'DESC')->first();

        if($nomor === NULL){
            $tahun=date('Y');
            $nomax=1;
            $no=$report_code.$tahun.'-'.$nomax;
        }else{
            $tahun=$nomor->TahunLaporan;
            $nomax=$nomor->Nomax+1;
            $no=$report_code.$tahun.'-'.$nomax;
            
        }

        //dd($no);
    
    
    return $nomax;
}

function format_tanggal($id)
{
    if($id !== NULL ){
        $tanggal=date('d-M-Y',strtotime($id));
    }else{
        $tanggal='';
    }
    
    return $tanggal;
}

function format_nominal($id)
{
   $nominal=number_format($id,2,',','.');
    return $nominal;
}

function format_tanggal_xml($id)
{
    
    if($id !== NULL && $id!=='0000-00-00 00:00:00'){
        $tanggal=date('Y-m-d', strtotime($id)).'T'.date('H:i:s', strtotime($id));
    }else{
        $tanggal='';
    }
    
    return $tanggal;
}


function getfromto($id)
{
   if($id=='t_from_my_client'){
        $name='(From My Client)';
   }else if($id=='t_from'){
        $name='(From)';
   }else if($id=='t_to_my_client'){
        $name='(To My Client)';
   }else if($id=='t_to'){
        $name='(To)';
   }else{
        $name='';
   }
    return $name;
}

/**
 * ========================================
 * LOAN LOCKING MANAGEMENT FUNCTIONS
 * ========================================
 */

/**
 * Lock loan application untuk user tertentu
 *
 * @param string $loan_app_no
 * @param string $nik
 * @param string $level (spv1, spv2, spv3)
 * @return bool|array false jika gagal, array dengan info lock jika sukses atau sudah locked
 */
function lockLoan($loan_app_no, $nik, $level)
{
    try {
        // Cek apakah sudah ada lock yang aktif untuk loan ini
        $existingLock = ListPickup::where('loan_app_no', $loan_app_no)
                                  ->where('status', 0)
                                  ->first();

        if ($existingLock) {
            // Cek apakah lock sudah expired
            if ($existingLock->isExpired()) {
                // Update lock dengan user baru
                $existingLock->user_spv = $nik;
                $existingLock->spv_level = $level;
                $existingLock->nik = $nik;
                $existingLock->locked_by_level = $level;
                $existingLock->locked_at = Carbon::now();
                $existingLock->expired_at = Carbon::now()->addMinutes(15);
                $existingLock->save();

                return [
                    'success' => true,
                    'message' => 'Lock berhasil diambil (expired lock updated)',
                    'lock' => $existingLock
                ];
            } else {
                // Cek apakah lock dimiliki oleh user yang sama
                if ($existingLock->nik == $nik || $existingLock->user_spv == $nik) {
                    // Perpanjang lock
                    $existingLock->expired_at = Carbon::now()->addMinutes(15);
                    $existingLock->save();

                    return [
                        'success' => true,
                        'message' => 'Lock diperpanjang',
                        'lock' => $existingLock
                    ];
                } else {
                    // Lock masih aktif dan dimiliki user lain
                    return [
                        'success' => false,
                        'message' => 'Loan sedang diakses oleh ' . getnameuser($existingLock->nik) . ' (' . $existingLock->nik . ')',
                        'locked_by' => $existingLock->nik,
                        'locked_by_name' => getnameuser($existingLock->nik),
                        'locked_at' => $existingLock->locked_at,
                        'expired_at' => $existingLock->expired_at
                    ];
                }
            }
        } else {
            // Tidak ada lock, buat lock baru
            $newLock = ListPickup::create([
                'loan_app_no' => $loan_app_no,
                'status' => 0, // 0 = locked, 1 = released
                'user_spv' => $nik,
                'spv_level' => $level,
                'nik' => $nik,
                'locked_by_level' => $level,
                'locked_at' => Carbon::now(),
                'expired_at' => Carbon::now()->addMinutes(15)
            ]);

            return [
                'success' => true,
                'message' => 'Lock berhasil dibuat',
                'lock' => $newLock
            ];
        }
    } catch (\Exception $e) {
        \Log::error('Error locking loan: ' . $e->getMessage());
        return false;
    }
}

/**
 * Release lock untuk loan application
 *
 * @param string $loan_app_no
 * @param string|null $nik (optional, jika null maka release tanpa cek user)
 * @return bool
 */
function unlockLoan($loan_app_no, $nik = null)
{
    try {
        $query = ListPickup::where('loan_app_no', $loan_app_no)
                          ->where('status', 0);

        // Jika nik diberikan, pastikan hanya user yang bersangkutan yang bisa unlock
        if ($nik !== null) {
            $query->where('nik', $nik);
        }

        $updated = $query->update(['status' => 1]);

        return $updated > 0;
    } catch (\Exception $e) {
        \Log::error('Error unlocking loan: ' . $e->getMessage());
        return false;
    }
}

/**
 * Cek apakah loan sedang di-lock dan oleh siapa
 *
 * @param string $loan_app_no
 * @return array|null null jika tidak locked, array dengan info lock jika locked
 */
function isLoanLocked($loan_app_no)
{
    $lock = ListPickup::where('loan_app_no', $loan_app_no)
                      ->where('status', 0)
                      ->first();

    if (!$lock) {
        return null;
    }

    // Cek apakah sudah expired
    if ($lock->isExpired()) {
        // Auto cleanup expired lock
        unlockLoan($loan_app_no);
        return null;
    }

    return [
        'loan_app_no' => $lock->loan_app_no,
        'nik' => $lock->nik,
        'name' => getnameuser($lock->nik),
        'level' => $lock->locked_by_level,
        'locked_at' => $lock->locked_at,
        'expired_at' => $lock->expired_at,
        'is_expired' => $lock->isExpired()
    ];
}

/**
 * Cleanup semua expired locks
 *
 * @return int jumlah lock yang dibersihkan
 */
function cleanExpiredLocks()
{
    try {
        $expiredCount = ListPickup::where('status', 0)
                                  ->whereNotNull('expired_at')
                                  ->where('expired_at', '<=', Carbon::now())
                                  ->update(['status' => 1]);

        if ($expiredCount > 0) {
            \Log::info("Cleaned {$expiredCount} expired locks");
        }

        return $expiredCount;
    } catch (\Exception $e) {
        \Log::error('Error cleaning expired locks: ' . $e->getMessage());
        return 0;
    }
}

/**
 * Force unlock loan (untuk admin/supervisor)
 *
 * @param string $loan_app_no
 * @param string $admin_nik (NIK admin yang melakukan force unlock)
 * @return bool
 */
function forceUnlockLoan($loan_app_no, $admin_nik)
{
    try {
        $lock = ListPickup::where('loan_app_no', $loan_app_no)
                          ->where('status', 0)
                          ->first();

        if ($lock) {
            $lock->status = 1;
            $lock->save();

            // Log activity
            \Log::info("Force unlock by {$admin_nik} on loan {$loan_app_no} (was locked by {$lock->nik})");

            return true;
        }

        return false;
    } catch (\Exception $e) {
        \Log::error('Error force unlocking loan: ' . $e->getMessage());
        return false;
    }
}

/**
 * Get all currently locked loans
 *
 * @return \Illuminate\Support\Collection
 */
function getAllLockedLoans()
{
    return ListPickup::where('status', 0)
                     ->with('dataFile', 'user')
                     ->get()
                     ->filter(function($lock) {
                         // Filter out legacy locks without proper timestamps
                         // or auto-cleanup expired locks
                         if ($lock->locked_at === null || $lock->expired_at === null) {
                             // This is legacy data, optionally cleanup
                             // Uncomment below to auto-cleanup legacy data:
                             // $lock->status = 1;
                             // $lock->save();
                             return true; // Show in list so admin can clean it up
                         }

                         // Auto cleanup expired locks
                         if ($lock->isExpired()) {
                             $lock->status = 1;
                             $lock->save();
                             return false; // Don't show expired locks
                         }

                         return true; // Show active locks
                     })
                     ->map(function($lock) {
                         // Prioritas 1: Gunakan field baru (nik, locked_by_level) dari waiting_spv3
                         // Prioritas 2: Gunakan field lama (user_spv, spv_lvl) dari pickup
                         $userNik = $lock->nik ?: $lock->user_spv;
                         $userLevel = $lock->locked_by_level ?: $lock->spv_lvl;

                         return [
                             'loan_app_no' => $lock->loan_app_no,
                             'nik' => $userNik,
                             'name' => getnameuser($userNik),
                             'level' => $userLevel,
                             'locked_at' => $lock->locked_at,
                             'expired_at' => $lock->expired_at,
                             'is_expired' => $lock->isExpired(),
                             'loan_data' => $lock->dataFile,
                             'source' => ($lock->nik ? 'waiting_spv3' : 'pickup') // Informasi sumber lock
                         ];
                     })
                     ->values(); // Re-index array
}

/**
 * Cek apakah user boleh akses loan ini
 * Mengembalikan true jika boleh akses, false jika tidak
 *
 * @param string $loan_app_no
 * @param string $nik
 * @return bool
 */
function canAccessLoan($loan_app_no, $nik)
{
    $lockInfo = isLoanLocked($loan_app_no);

    // Jika tidak ada lock atau lock milik user ini, boleh akses
    if ($lockInfo === null || $lockInfo['nik'] === $nik) {
        return true;
    }

    return false;
}

/**
 * Cleanup legacy locks (locks without proper timestamps)
 *
 * @return int Number of legacy locks cleaned up
 */
function cleanupLegacyLocks()
{
    try {
        $count = ListPickup::where('status', 0)
                          ->where(function($query) {
                              $query->whereNull('locked_at')
                                    ->orWhereNull('expired_at')
                                    ->orWhereNull('nik')
                                    ->orWhereNull('locked_by_level');
                          })
                          ->update(['status' => 1]);

        if ($count > 0) {
            \Log::info("Cleaned up {$count} legacy locks from list_pickup table");
        }

        return $count;
    } catch (\Exception $e) {
        \Log::error('Error cleaning up legacy locks: ' . $e->getMessage());
        return 0;
    }
}

function getStatusBadge($status) {
    switch($status) {
        case 0: return '<span class="badge badge-secondary">Pending</span>';
        case 1: return '<span class="badge badge-success">Verify</span>';
        case 2: return '<span class="badge badge-warning">Not Verify</span>';
        case 3: return '<span class="badge badge-info">TBO</span>';
        case 4: return '<span class="badge badge-danger">Reject</span>';
        case 5: return '<span class="badge badge-success">Approve (Verifikator)</span>';
        case 6: return '<span class="badge badge-danger">Not Approve (Verifikator)</span>';
        default: return '<span class="badge badge-secondary">-</span>';
    }
}

?>