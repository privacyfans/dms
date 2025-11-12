<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerpanjanganJam;
use App\Models\PerpanjanganJamDetail;
use App\Models\CutOff;
use App\Models\SettingTime;
use Redirect;
use App\Exports\WaktuLayanan;
use Maatwebsite\Excel\Facades\Excel;

class PerpanjanganJamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function waktu_layanan(Request $request)
    {
        return view('perpanjangan_jam.report');
    }

    public function waktu_layananSubmit(Request $request)
    {
        $dari= $request->from_date;
        $sampai=$request->end_date;
        
        $str_dari=strtotime($dari);
        $str_sampai=strtotime($sampai);
        if($str_sampai < $str_dari){
            return redirect()->route('perpanjangan_jam.waktu_layanan')
            ->with('error','Tanggal End Date harus lebih besar dari Tanggal From Date');
        }
      
            
            return Excel::download(new WaktuLayanan($dari,$sampai), 'Perpanjang_Waktu_Layanan_'.$dari.'_'.$sampai.'.xlsx');
    
           
    }

    public function create()
    {
        //date_default_timezone_set('America/Chicago'); // CDT

        $currentTime = time();
        $setting_time = SettingTime::first();
        //dd($setting_time->waktu);
        $waktu = $setting_time ? $setting_time->waktu : '17:00';
        $deadlineTime = strtotime('today ' . $waktu);
        //$deadlineTime = strtotime('today 17:00');
        
        if ($currentTime > $deadlineTime) {
            return view('perpanjangan_jam.create')->with('error','Cut Off Request Perpanjangan Jam Layanan maksimal Pukul '.$setting_time->waktu.' WIB');
        } else {
            return view('perpanjangan_jam.create')->with('error','');
        }

        //return view('perpanjangan_jam.create');
    }

    public function store(Request $request)
    {
        $messages = [
            'required'  => 'The :attribute field is required.',
            'unique'    => ':attribute is already used',
            'after'    => 'Tanggal Sesudah Perubahan harus lebih besar dari Tanggal Sebelum Perubahan',
          ];
        
        if($request->jam_layanan==""){
            $rules['jam_layanan'] = 'required';
        }else{
            foreach($request->nama_debitur as $key => $val)
            {
                $rules['jam_layanan'] = 'required';
                $rules['loan_app_no.'.$key] = 'required';
                $rules['nama_debitur.'.$key] = 'required';
                $rules['note.'.$key] = 'required';
            }
        }
        
        
        $request->validate($rules,$messages);

        $jmldata=count($request->loan_app_no);
        $request->cabang=Session('branch_code');
        $post_head = new PerpanjanganJam;
        $post_head->branch_input = $request->cabang;
        $post_head->jam_layanan = $request->jam_layanan;
        $post_head->user_input = Session('nik');
        $post_head->date_input = date('Y-m-d H:i:s');
        $post_head->flag_spv = 1;
        $post_head->save();

        for($x=0;$x<$jmldata;$x++){
            $post = new PerpanjanganJamDetail;
            //$post->branch_input = Session('branch_code');
            $post->id_perubahan = $post_head->id;
            $post->loan_app_no = $request->loan_app_no[$x];
            $post->nama_debitur = $request->nama_debitur[$x];
            $post->note = $request->note[$x];
            $post->save();
        }

        $cut_off= CutOff::where('branch_code','=',$request->cabang)->firstOrFail();
        $cut_off->purpose_cut_off=$request->jam_layanan;
        $cut_off->updated_at= date('Y-m-d H:i:s');
        $cut_off->save();
       // dd($post_head->id);

       $cabang=Session("branch_code");
       $jam=$request->jam_layanan;
       $link="https://dms.bankwoorisaudara.com/perpanjangan_jam/".$post_head->id;
       $role="spv4";
      
       
       sendEmailRequestJam($cabang,$jam,$link,$role);

        return Redirect::to('perpanjangan_jam/'.$post_head->id)
        ->with(['succes' => 'Created Successfully']);
       
    }

    public function show($id)
    {
        $perpanjangan_jam= PerpanjanganJam::findOrFail($id);
        $perpanjangan_jam_detail= PerpanjanganJamDetail::where('id_perubahan','=',$perpanjangan_jam->id)->get();
       // dd($perpanjangan_jam_detail);
        return view('perpanjangan_jam.show',with([
            'perpanjangan_jam'=>$perpanjangan_jam,
            'perpanjangan_jam_detail'=>$perpanjangan_jam_detail,
        ]));
    }

    public function approveSubmit(Request $request)
    {
        $perpanjangan_jam= PerpanjanganJam::findOrFail($request->id_perubahan);

        // if(Session('role')=='pc'){

        //     $perpanjangan_jam->user_spv=Session('nik');
        //     $perpanjangan_jam->flag_spv=$request->approve_flag;
        //     $perpanjangan_jam->note_spv=$request->note_spv;
        //     $perpanjangan_jam->date_flag_spv=date('Y-m-d H:i:s');
        //     $perpanjangan_jam->save();
        // }else if(Session('role')=='spv5'){
        //     $perpanjangan_jam->user_spv1=Session('nik');
        //     $perpanjangan_jam->flag_spv1=$request->approve_flag;
        //     $perpanjangan_jam->approve_time=$request->approve_time;
        //     $perpanjangan_jam->note_spv1=$request->note_spv;
        //     $perpanjangan_jam->date_flag_spv1=date('Y-m-d H:i:s');
        //     $perpanjangan_jam->save();
        // }

        if(Session('role')=='spv4'){
            $perpanjangan_jam->user_spv1=Session('nik');
            $perpanjangan_jam->flag_spv1=$request->approve_flag;
            $perpanjangan_jam->approve_time=$request->approve_time;
            $perpanjangan_jam->note_spv1=$request->note_spv;
            $perpanjangan_jam->date_flag_spv1=date('Y-m-d H:i:s');
            $perpanjangan_jam->save();
        }
        $cut_off= CutOff::where('branch_code','=',$perpanjangan_jam->branch_input)->firstOrFail();
        $cut_off->approve_flag=$request->approve_flag;

       

        if($request->approve_flag == 1){
            $perpanjangan_jam_final= PerpanjanganJam::findOrFail($request->id_perubahan);
            if($perpanjangan_jam_final->flag_spv == 1 && $perpanjangan_jam_final->flag_spv1 == 1){
                $perpanjangan_jam_final->final_flag=1;
                $perpanjangan_jam_final->save();
                $cut_off->cut_off=$perpanjangan_jam->approve_time;  
                $cut_off->user_approve=Session('nik');
                $cut_off->approve_date= date('Y-m-d H:i:s');
                $cut_off->updated_at= date('Y-m-d H:i:s');
                $cut_off->save();
        
        
               
            }  
            $cabang=$perpanjangan_jam->branch_input;
            $jam=$perpanjangan_jam->jam_layanan;
            $link="https://dms.bankwoorisaudara.com/perpanjangan_jam/".$perpanjangan_jam->id;
            $role="spv4";
          
           if(Session("role")=='pc'){
                sendEmailRequestJam($cabang,$jam,$link,$role);
           }
            $msg="Request Approved";
            
        }else{
            $perpanjangan_jam_final= PerpanjanganJam::findOrFail($request->id_perubahan);
            if($perpanjangan_jam_final->flag_spv == 2 || $perpanjangan_jam_final->flag_spv1 == 2){
                $perpanjangan_jam_final->flag_spv1=2;
                $perpanjangan_jam_final->final_flag=2;
                $perpanjangan_jam_final->save();
                //$cut_off->cut_off=$perpanjangan_jam->jam_layanan;  
            }  
            
            $msg="Request Rejected";
            
        }
       
       

        return redirect()->route('perpanjangan_jam.index')
                        ->with('success',$msg);
    }

    public function index(Request $request){

        $pagination  = 10;

        //dd(Session('branch_code'));
           
        if(Session('role')=='staff' || Session('role')=='spv1'  || Session('role')=='pc' || Session('role')=='pcp' ){
            $request->keyword = Session('branch_code');
            if(Session('role')=='pc'){
                $perpanjangan_jam    = PerpanjanganJam::join('branchlist', 'branch_code', '=', 'branch_input')->when($request->keyword, function ($query) use ($request) {
                    $query
                    ->where('parent_branch', 'like', "%{$request->keyword}%");
                })->orderBy('id', 'desc')->paginate($pagination);
               
            } else{
                $perpanjangan_jam    = PerpanjanganJam::join('branchlist', 'branch_code', '=', 'branch_input')->when($request->keyword, function ($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->where('branch_input', 'like', "%{$request->keyword}%")
                          ->orWhere('branch_name', 'like', "%{$request->keyword}%");
                    });
                })->orderBy('id', 'desc')->paginate($pagination);
            }
            
        }else{
            if(Session('role')=='spv5'){
                $perpanjangan_jam    = PerpanjanganJam::join('branchlist', 'branch_code', '=', 'branch_input')->where('flag_spv', '=', 1)->when($request->keyword, function ($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->where('branch_input', 'like', "%{$request->keyword}%")
                          ->orWhere('branch_name', 'like', "%{$request->keyword}%");
                    });
                })->orderBy('flag_spv1', 'asc')->orderBy('date_input', 'desc')->paginate($pagination);
                //$perpanjangan_jam    = PerpanjanganJam::where('flag_spv', '=', 1)->orderBy('id', 'desc')->paginate($pagination);  

            }else{
                $perpanjangan_jam    = PerpanjanganJam::join('branchlist', 'branch_code', '=', 'branch_input')->when($request->keyword, function ($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->where('branch_input', 'like', "%{$request->keyword}%")
                          ->orWhere('branch_name', 'like', "%{$request->keyword}%");
                    });
                })->orderBy('id', 'desc')->paginate($pagination);
            }
           
        }
        
    
        $perpanjangan_jam->appends($request->only('keyword'));
    
        return view('perpanjangan_jam.index', [
            'perpanjangan_jam' => $perpanjangan_jam,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);
    }

    public function destroy(PerpanjanganJam $perpanjangan_jam)
    {
        $perpanjangan_jam->delete();

        $perpanjangan_jam_detail= PerpanjanganJamDetail::where('id_perubahan','=',$perpanjangan_jam->id)->delete();
    
        return redirect()->route('perpanjangan_jam.index')
                        ->with('success','Perpanjangan Jam Layanan Deleted Successfully');
    }

   

}

