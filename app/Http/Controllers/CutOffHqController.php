<?php

namespace App\Http\Controllers;

use App\Models\CutOffHq;
use App\Models\CutOffHqHistory;
use App\Models\CutOff;
use Illuminate\Http\Request;
use DB;

class CutOffHqController extends Controller
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
    public function index(Request $request)
    {
        $pagination  = 200;
        $cut_off_hq    = CutOffHq::when($request->keyword, function ($query) use ($request) {
            $query
            ->where('cut_off', 'like', "%{$request->keyword}%");
        })->orderBy('cut_off', 'desc')->paginate($pagination);
    
        $cut_off_hq->appends($request->only('keyword'));
    
        return view('cut_off_hq.index', [
            'cut_off' => $cut_off_hq,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);
    }

    public function create()
    {
        return view('cut_off_hq.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      

        $request->validate([
            'cut_off' => 'required',
        ]);
        
    
        CutOffHq::create($request->all());
     
        return redirect()->route('cut_off_hq.index')
                        ->with('success','CutOffHq created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CutOffHq  $cut_off_hq
     * @return \Illuminate\Http\Response
     */
    public function show(CutOffHq $cut_off_hq)
    {
        return view('cut_off_hq.show',compact('cut_off_hq'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CutOffHq  $cut_off_hq
     * @return \Illuminate\Http\Response
     */
    public function edit(CutOffHq $cut_off_hq)
    {
        return view('cut_off_hq.edit',compact('cut_off_hq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CutOffHq  $cut_off_hq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CutOffHq $cut_off_hq)
    {
        // $request->validate([
        //     'cut_off' => 'required',
        // ]);
        //dd($request->all());
        $link="https://dms.bankwoorisaudara.com/cut_off_hq";
        $jam=$request->purpose_cut_off;
        $role=Session('role');
        if($role=='spv4'){
            sendEmailRequestJamHQ($jam,$link,'spv5');
        }
        $cut_off_hq->user_input=Session('nik');
        $cut_off_hq->date_input=date('Y-m-d H:i:s');
        $cut_off_hq->approve_flag=0;
        $cut_off_hq->update($request->all());


    
        return redirect()->route('cut_off_hq.index')
                        ->with('success','To Be Cut off Time Already Changed');
        
    }

    public function approve($id)
    {
        

        $cut_off_hq= CutOffHq::findOrFail($id);
        return view('cut_off_hq.approve',compact('cut_off_hq'));
    }
    public function approveSubmit(Request $request)
    {
        $request->validate([
            //'cut_off' => 'required',
            'purpose_cut_off'=> 'required',
            //'user_approve'=> 'required',
            //'approve_date'=> 'required',
        ]);
        $cut_off_hq= CutOffHq::findOrFail($request->id);
        
//dd($request);
        if($request->approve_flag == 1){
            $cut_off_hq->cut_off=$cut_off_hq->purpose_cut_off;    
            
            DB::table('cut_off')
            ->update(['cut_off' => $cut_off_hq->purpose_cut_off]);
        }
        $cut_off_hq->approve_flag=$request->approve_flag;
        $cut_off_hq->user_approve=Session('nik');
        $cut_off_hq->approve_date=date('Y-m-d H:i:s');
        $cut_off_hq->save();
        
        
        $cut_off_hq_history= new CutOffHqHistory();
        $cut_off_hq_history->cut_off=$cut_off_hq->cut_off;
        $cut_off_hq_history->approve_flag=$cut_off_hq->approve_flag;
        $cut_off_hq_history->user_input=$cut_off_hq->user_input;
        $cut_off_hq_history->date_input=$cut_off_hq->date_input;
        $cut_off_hq_history->purpose_cut_off=$cut_off_hq->purpose_cut_off;
        $cut_off_hq_history->user_approve=$cut_off_hq->user_approve;
        $cut_off_hq_history->approve_date=$cut_off_hq->approve_date;
        $cut_off_hq_history->save();

        if($request->approve_flag == 1){
            return redirect()->route('cut_off_hq.index')
            ->with('success','Cut off Time Already Changed');
        }else{
            return redirect()->route('cut_off_hq.index')
                        ->with('error','Cut off Time Not Changed');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CutOffHq  $cut_off_hq
     * @return \Illuminate\Http\Response
     */
    public function destroy(CutOffHq $cut_off_hq)
    {
        $cut_off_hq->delete();
    
        return redirect()->route('cut_off_hq.index')
                        ->with('success','Cut Off deleted successfully');
    }
}
