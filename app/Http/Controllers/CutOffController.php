<?php

namespace App\Http\Controllers;

use App\Models\CutOff;
use Illuminate\Http\Request;

class CutOffController extends Controller
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
        $cut_off    = CutOff::join('branchlist','branchlist.branch_code','cut_off.branch_code')
            ->when($request->keyword, function ($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('cut_off.branch_code', 'like', "%{$request->keyword}%")
                      ->orWhere('branchlist.branch_name', 'like', "%{$request->keyword}%")
                      ->orWhere('cut_off', 'like', "%{$request->keyword}%");
                });
            })
            ->orderBy('cut_off', 'desc')
            ->paginate($pagination);
    
        $cut_off->appends($request->only('keyword'));
    
        return view('cut_off.index', [
            'cut_off' => $cut_off,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);
    }

    public function create()
    {
        return view('cut_off.create');
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
    
        CutOff::create($request->all());
     
        return redirect()->route('cut_off.index')
                        ->with('success','CutOff created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CutOff  $cut_off
     * @return \Illuminate\Http\Response
     */
    public function show(CutOff $cut_off)
    {
        return view('cut_off.show',compact('cut_off'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CutOff  $cut_off
     * @return \Illuminate\Http\Response
     */
    public function edit(CutOff $cut_off)
    {
        return view('cut_off.edit',compact('cut_off'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CutOff  $cut_off
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CutOff $cut_off)
    {
        // $request->validate([
        //     'cut_off' => 'required',
        // ]);
        $cut_off->approve_flag=0;
        $cut_off->update($request->all());
    
        return redirect()->route('cut_off.index')
                        ->with('success','To Be Cut off Time Already Changed');
    }

    public function approve($id)
    {
        

        $cut_off= CutOff::findOrFail($id);
        return view('cut_off.approve',compact('cut_off'));
    }

    public function approveSubmitList(Request $request)
    {
        $request->validate([
            //'cut_off' => 'required',
            'purpose_cut_off'=> 'required',
            //'user_approve'=> 'required',
            //'approve_date'=> 'required',
        ]);
        
        $cut_off= CutOff::findOrFail($request->id);
        
        if($request->approve_flag == 1){
            $cut_off->cut_off=$cut_off->purpose_cut_off;    
        }
        $cut_off->approve_flag=$request->approve_flag;
        $cut_off->user_approve=Session('nik');
        $cut_off->approve_date=date('Y-m-d H:i:s');
        $cut_off->save();
        if($request->approve_flag == 1){
            return redirect()->route('cut_off.index')->with('success','Cut off Time Already Changed');
        }else{
            return redirect()->route('cut_off.index')->with('error','Cut off Time Not Change');
        }
    }
    public function approveSubmit(Request $request)
    {
        $request->validate([
            //'cut_off' => 'required',
            'purpose_cut_off'=> 'required',
            //'user_approve'=> 'required',
            //'approve_date'=> 'required',
        ]);
        $cut_off= CutOff::findOrFail($request->id);
//dd($request);
        if($request->approve_flag == 1){
            $cut_off->cut_off=$cut_off->purpose_cut_off;    
        }
        $cut_off->approve_flag=$request->approve_flag;
        $cut_off->user_approve=Session('nik');
        $cut_off->approve_date=date('Y-m-d H:i:s');
        $cut_off->save();
        if($request->approve_flag == 1){
            return redirect()->route('cut_off.index')->with('success','Cut off Time Already Changed');
        }else{
            return redirect()->route('cut_off.index')->with('error','Cut off Time Not Change');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CutOff  $cut_off
     * @return \Illuminate\Http\Response
     */
    public function destroy(CutOff $cut_off)
    {
        $cut_off->delete();
    
        return redirect()->route('cut_off.index')
                        ->with('success','Cut Off deleted successfully');
    }
}
