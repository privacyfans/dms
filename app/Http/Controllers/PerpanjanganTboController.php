<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerpanjanganTbo;
use App\Models\PerpanjanganTboDetail;
use App\Models\CutOff;
use App\Models\Comment;
use App\Models\DataFileModel;
use Validator, Input, Redirect, DB; 
use App\Exports\TanggalTbo;
use Maatwebsite\Excel\Facades\Excel;

class PerpanjanganTboController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function tanggal_tbo(Request $request)
    {
        return view('perpanjangan_tbo.report');
    }

    public function tanggal_tboSubmit(Request $request)
    {
        $dari= $request->from_date;
        $sampai=$request->end_date;
        
        $str_dari=strtotime($dari);
        $str_sampai=strtotime($sampai);
        if($str_sampai < $str_dari){
            return redirect()->route('perpanjangan_tbo.tanggal_tbo')
            ->with('error','Tanggal End Date harus lebih besar dari Tanggal From Date');
        }
      
            
            return Excel::download(new TanggalTbo($dari,$sampai), 'Perpanjang_Tanggal_Tbo_'.$dari.'_'.$sampai.'.xlsx');
    
           
    }

    public function create()
    {
        return view('perpanjangan_tbo.create');
    }

    public function store(Request $request)
    {
       
//dd($request);


$messages = [
    'required'  => 'The :attribute field is required.',
    'unique'    => ':attribute is already used',
    'after'    => 'Tanggal Sesudah Perubahan harus lebih besar dari Tanggal Sebelum Perubahan',
  ];

foreach($request->tgl_sebelum_perubahan as $key => $val)
{
    $rules['loan_app_no.'.$key] = 'required';
    $rules['nama_debitur.'.$key] = 'required';
    $rules['dokumen_tbo.'.$key] = 'required';
    $rules['note.'.$key] = 'required';
    $rules['tgl_sebelum_perubahan.'.$key] = 'required|date';
    $rules['tgl_sesudah_perubahan.'.$key] = 'required|date|after:tgl_sebelum_perubahan.'.$key;
    
    
}
//dd($rules);
        $request->validate($rules,$messages);

       // dd($request);
        $jmldata=count($request->loan_app_no);
        $request->cabang=Session('branch_code');
        $post_head = new PerpanjanganTbo;
        $post_head->branch_input = $request->cabang;
        $post_head->user_input = Session('nik');
        $post_head->date_input = date('Y-m-d H:i:s');
        $post_head->flag_spv = 0;
        $post_head->final_flag= 0;
        $post_head->save();

        for($x=0;$x<$jmldata;$x++){
            $post = new PerpanjanganTboDetail;
            //$post->branch_input = Session('branch_code');
            $post->id_perubahan = $post_head->id;
            $post->loan_app_no = $request->loan_app_no[$x];
            $post->nama_debitur = $request->nama_debitur[$x];
            $post->dokumen_tbo = $request->dokumen_tbo[$x];
            $post->tgl_sebelum_perubahan = $request->tgl_sebelum_perubahan[$x];
            $post->tgl_sesudah_perubahan = $request->tgl_sesudah_perubahan[$x];
            $post->perubahan_ke = $request->perubahan_ke[$x];
            $post->note = $request->note[$x];
            $post->save();
        }

        $cabang=Session("branch_code");
        $id=$post_head->id;
        $link="https://dms.bankwoorisaudara.com/perpanjangan_tbo/".$post_head->id;
        $role="pc";
       
        
        sendEmailRequestTbo($cabang,$id,$link,$role);

        return Redirect::to('perpanjangan_tbo/'.$post_head->id)
        ->with(['succes' => 'Created Successfully']);
       
    }

    public function show($id)
    {
        $perpanjangan_tbo= PerpanjanganTbo::findOrFail($id);
        $perpanjangan_tbo_detail= PerpanjanganTboDetail::where('id_perubahan','=',$perpanjangan_tbo->id)
        ->get();
      
        return view('perpanjangan_tbo.show',with([
            'perpanjangan_tbo'=>$perpanjangan_tbo,
            'perpanjangan_tbo_detail'=>$perpanjangan_tbo_detail,
        ]));
    }

    public function approveSubmit(Request $request)
    {
        
        $perpanjangan_tbo= PerpanjanganTbo::findOrFail($request->id_perubahan);

        if(Session('role')=='pc'){

            $chkData = $request->chkData;
            $dataArray = json_decode($chkData, true);

            $resumeValue='';
            $val=0;
            // Iterasi setiap elemen dan lakukan update pada tabel perubahan_tgl_tbo_detail
            foreach ($dataArray as $data) {
                $loanAppNo = $data['loanAppNo'];
                $value = $data['value'];
                $resumeValue .= $data['value'];
                if($value){
                    $val=1;
                }else{
                    $val=2;
                }

                // Lakukan update pada tabel perubahan_tgl_tbo_detail
                DB::table('perubahan_tgl_tbo_detail')
                    ->where('loan_app_no', $loanAppNo)
                    ->update(['approve' => $val]);
            }

            $perpanjangan_tbo->user_spv=Session('nik');
            $perpanjangan_tbo->flag_spv=$request->approve_flag;
            if($perpanjangan_tbo->flag_spv == 2){
                $perpanjangan_tbo->final_flag=2;
            }

            if(str_contains($resumeValue,"1")==false){
                $perpanjangan_tbo->flag_spv=2;
                $perpanjangan_tbo->flag_spv1=2;
                $perpanjangan_tbo->flag_spv2=2;
                $perpanjangan_tbo->final_flag=2;
            }
           
            $perpanjangan_tbo->date_flag_spv=date('Y-m-d H:i:s');
            $perpanjangan_tbo->note_spv=$request->note_spv;
            $perpanjangan_tbo->save();
        }else if(Session('role')=='spv4'){
            $chkData = $request->chkData;
            $dataArray = json_decode($chkData, true);
            $resumeValue='';
            $val=0;
            // Iterasi setiap elemen dan lakukan update pada tabel perubahan_tgl_tbo_detail
            foreach ($dataArray as $data) {
                $loanAppNo = $data['loanAppNo'];
                $value = $data['value'];
                $resumeValue .= $data['value'];
                if($value){
                    $val=1;
                }else{
                    $val=2;
                }
                // Lakukan update pada tabel perubahan_tgl_tbo_detail
                DB::table('perubahan_tgl_tbo_detail')
                    ->where('loan_app_no', $loanAppNo)
                    ->update(['approve' => $val]);
            }
            
            $perpanjangan_tbo->user_spv1=Session('nik');
            $perpanjangan_tbo->flag_spv1=$request->approve_flag;
            if($perpanjangan_tbo->flag_spv1 == 2){
                $perpanjangan_tbo->final_flag=2;
            }

            if(str_contains($resumeValue,"1")==false){
                $perpanjangan_tbo->flag_spv2=2;
                $perpanjangan_tbo->final_flag=2;
            }
            
            $perpanjangan_tbo->date_flag_spv1=date('Y-m-d H:i:s');
            $perpanjangan_tbo->note_spv1=$request->note_spv;
            $perpanjangan_tbo->save();
        }else if(Session('role')=='spv5'){
            $chkData = $request->chkData;
            $dataArray = json_decode($chkData, true);
            $val=0;
            $resumeValue='';
            // Iterasi setiap elemen dan lakukan update pada tabel perubahan_tgl_tbo_detail
            foreach ($dataArray as $data) {
                $loanAppNo = $data['loanAppNo'];
                $value = $data['value'];
                $resumeValue .= $data['value'];
                if($value){
                    $val=1;
                }else{
                    $val=2;
                }
                // Lakukan update pada tabel perubahan_tgl_tbo_detail
                DB::table('perubahan_tgl_tbo_detail')
                    ->where('loan_app_no', $loanAppNo)
                    ->update(['approve' => $val]);
            }
            
            $perpanjangan_tbo->user_spv2=Session('nik');
            $perpanjangan_tbo->flag_spv2=$request->approve_flag;
            if($perpanjangan_tbo->flag_spv2 == 2){
                $perpanjangan_tbo->final_flag=2;
            }

            if(str_contains($resumeValue,"1")==false){
                $perpanjangan_tbo->flag_spv2=2;
                $perpanjangan_tbo->final_flag=2;
            }
            
            $perpanjangan_tbo->date_flag_spv2=date('Y-m-d H:i:s');
            $perpanjangan_tbo->note_spv2=$request->note_spv;
            $perpanjangan_tbo->save();
        }
        
        
        
        $perpanjangan_tbo_detail= PerpanjanganTboDetail::where('id_perubahan','=',$perpanjangan_tbo->id)
        ->where('approve','=',1)->get();

        if($request->approve_flag == 1){
            $perpanjangan_tbo_final= PerpanjanganTbo::findOrFail($request->id_perubahan);
            if($perpanjangan_tbo_final->flag_spv == 1 && $perpanjangan_tbo_final->flag_spv1 == 1 && $perpanjangan_tbo_final->flag_spv2 == 1){
                $perpanjangan_tbo_final->final_flag=1;
                $perpanjangan_tbo_final->save();
                foreach($perpanjangan_tbo_detail as $tbo){
                    $loan_app_no=$tbo->loan_app_no;
    
                    $sql="SELECT
                                max(id) as id
                            FROM
                                `comment` 
                            WHERE
                                flag_spv = 3 and loan_app_no ='".$loan_app_no."'
                            GROUP BY
                                loan_app_no";
    
                    $id_tbo = DB::select($sql);
    
                    $data_file_model= DataFileModel::findOrFail($loan_app_no);
                    $data_file_model->user_spv3='system';
                    $data_file_model->final_status_spv3=3;
                    $data_file_model->date_flag_spv3=date('Y-m-d H:i:s');;
                    $data_file_model->save();

                    $data_comment= Comment::where('id','=',$id_tbo[0]->id)->firstOrFail();
                    // $data_comment->tbo_date=$tbo->tgl_sesudah_perubahan;
                    // $data_comment->updated_at= date('Y-m-d H:i:s');
                    // $data_comment->save();
                    $data_comm = New Comment;
                    $data_comm->loan_app_no = $data_comment->loan_app_no;
                    $data_comm->comment = "Perubahan Tanggal TBO Ke-".$tbo->perubahan_ke;
                    $data_comm->user_name = 'system';
                    $data_comm->level_spv = 'spv3';
                    $data_comm->comment_date =date('Y-m-d H:i:s');
                    $data_comm->flag_spv =$data_comment->flag_spv;
                    $data_comm->tbo_date =$tbo->tgl_sesudah_perubahan;
                    $data_comm->reason =$data_comment->reason;
                    $data_comm->flag_process =$data_comment->flag_process;
                    $data_comm->created_at =date('Y-m-d H:i:s');
                    $data_comm->updated_at =date('Y-m-d H:i:s');
                    $data_comm->save();
                    /*$data_comment->tbo_date=$tbo->tgl_sesudah_perubahan;
                    $data_comment->updated_at= date('Y-m-d H:i:s');
                    $data_comment->save();*/
                }
               
        
            }
            $cabang=$perpanjangan_tbo->branch_input;
            $id=$perpanjangan_tbo->id;
            $link="https://dms.bankwoorisaudara.com/perpanjangan_tbo/".$perpanjangan_tbo->id;
            
          
           if(Session("role")=='pc'){
                $role="spv4";
                sendEmailRequestTbo($cabang,$id,$link,$role);
           }elseif(Session("role")=='spv4'){
                $role="spv5";
                sendEmailRequestTbo($cabang,$id,$link,$role);
           }
            $msg="Request Approved";
        }else{
            $perpanjangan_tbo_final= PerpanjanganTbo::findOrFail($request->id_perubahan);
            if($perpanjangan_tbo_final->flag_spv == 2 || $perpanjangan_tbo_final->flag_spv1 == 2){
                $perpanjangan_tbo_final->flag_spv1=2;
                $perpanjangan_tbo_final->final_flag=2;
                $perpanjangan_tbo_final->save();
            }
            $msg="Request Rejected";
        }
        
       
        return redirect()->route('perpanjangan_tbo.index')
                        ->with('success',$msg);
    }

    public function index(Request $request){

        $pagination  = 10;
        
        //dd(Session('branch_code'));
       // dd(Session('role'));
        if(Session('role')=='staff' || Session('role')=='spv1' || Session('role')=='pcp' || Session('role')=='pc'){
            $request->keyword = Session('branch_code');
            if(Session('role')=='pc'){
                $perpanjangan_tbo    = PerpanjanganTbo::join('branchlist', 'branch_code', '=', 'branch_input')->when($request->keyword, function ($query) use ($request) {
                    $query
                    ->where('parent_branch', 'like', "%{$request->keyword}%");
                })->orderBy('id', 'desc')->paginate($pagination);
               
            }else {
                $perpanjangan_tbo    = PerpanjanganTbo::when($request->keyword, function ($query) use ($request) {
                    $query
                    ->where('branch_input', 'like', "%{$request->keyword}%");
                })->orderBy('id', 'desc')->paginate($pagination);
            }
        }else{
           
            if(Session('role')=='spv4'){
                $perpanjangan_tbo    = PerpanjanganTbo::where('flag_spv', '=', 1)->when($request->keyword, function ($query) use ($request) {
                    $query
                    ->where('branch_input', 'like', "%{$request->keyword}%");
                })->orderBy('id', 'desc')->paginate($pagination);

                //$perpanjangan_tbo    = PerpanjanganTbo::where('flag_spv', '=', 1)->orderBy('id', 'desc')->paginate($pagination);    
            }else if(Session('role')=='spv5'){
                $perpanjangan_tbo    = PerpanjanganTbo::where('flag_spv1', '=', 1)->when($request->keyword, function ($query) use ($request) {
                    $query
                    ->where('branch_input', 'like', "%{$request->keyword}%");
                })->orderBy('id', 'desc')->paginate($pagination);

                //$perpanjangan_tbo    = PerpanjanganTbo::where('flag_spv', '=', 1)->orderBy('id', 'desc')->paginate($pagination);    
            }else{
                $perpanjangan_tbo    = PerpanjanganTbo::when($request->keyword, function ($query) use ($request) {
                    $query
                    ->where('branch_input', 'like', "%{$request->keyword}%");
                })->orderBy('id', 'desc')->paginate($pagination);
            }
            
        }
        
    
        $perpanjangan_tbo->appends($request->only('keyword'));
    
        return view('perpanjangan_tbo.index', [
            'perpanjangan_tbo' => $perpanjangan_tbo,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);
    }

    public function destroy(PerpanjanganTbo $perpanjangan_tbo)
    {
        $perpanjangan_tbo->delete();

        $perpanjangan_tbo_detail= PerpanjanganTboDetail::where('id_perubahan','=',$perpanjangan_tbo->id)->delete();
    
        return redirect()->route('perpanjangan_tbo.index')
                        ->with('success','Perpanjangan Jam Layanan Deleted Successfully');
    }

   

}

