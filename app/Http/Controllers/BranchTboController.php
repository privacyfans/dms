<?php

namespace App\Http\Controllers;

use App\Models\BranchTbo;
use Illuminate\Http\Request;
use DB;
class BranchTboController extends Controller
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

        $pagination  = 10;
        /*$branch    = BranchTbo::has('branchlist')->when($request->keyword, function ($query) use ($request) {
            $query
            ->where('branch_code', 'like', "%{$request->keyword}%");
        })->orderBy('branch_code', 'asc')->paginate($pagination);
    
        $branch->appends($request->only('keyword'));*/

        $branch = BranchTbo::has('branchlist')
        ->when($request->keyword, function ($query) use ($request) {
            $query->where('branch_code', 'like', "%{$request->keyword}%");
        })
        ->when($request->branch_name, function ($query) use ($request) {
            $query->whereHas('branchlist', function ($subQuery) use ($request) {
                $subQuery->where('branch_name', 'like', "%{$request->branch_name}%");
            });
        })
        ->orderBy('branch_code', 'asc')
        ->paginate($pagination);

        $branch->appends($request->only('keyword', 'branch_name'));

        //dd($branch);
        return view('branch_tbo.index', [
            'branch_tbo' => $branch,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

        /*$branch = BranchTbo::orderBy('branch_code','asc')->paginate(10);
    
        return view('branch.index',compact('branch'))
            ->with('i', (request()->input('page', 1) - 1) * 10);*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('branch_tbo.create');
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
            'branch_code' => 'required',
            'jml' => 'required',
            
            
        ]);
    
        BranchTbo::create($request->all());
     
        return redirect()->route('branch_tbo.index')
                        ->with('success','BranchTbo created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BranchTbo  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(BranchTbo $branch_tbo)
    {
        return view('branch_tbo.show',compact('branch_tbo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BranchTbo  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(BranchTbo $branch_tbo)
    {
        return view('branch_tbo.edit',compact('branch_tbo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BranchTbo  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BranchTbo $branch_tbo)
    {
       /* $request->validate([
            'branch_code' => 'required',
            //'jml' => 'required',
        ]);*/
        $branch_tbo->approve_flag=0;
        $branch_tbo->update($request->all());
    
        $branch_tbo->update($request->all());
    
        return redirect()->route('branch_tbo.index')
                        ->with('success','Max TBO Overdue Already Changed');
    }

     public function approve($id)
    {
        

        $branch_tbo= BranchTbo::findOrFail($id);
        return view('branch_tbo.approve',compact('branch_tbo'));
    }
    public function approveSubmit(Request $request)
    {
        $request->validate([
            //'cut_off' => 'required',
            'purpose_jml'=> 'required',
            //'user_approve'=> 'required',
            //'approve_date'=> 'required',
        ]);
        $branch_tbo= BranchTbo::findOrFail($request->branch_code);

        if($request->approve_flag == 1){
            $branch_tbo->jml=$branch_tbo->purpose_jml;    
        }
        $branch_tbo->approve_flag=$request->approve_flag;
        $branch_tbo->user_approve=Session('nik');
        $branch_tbo->approve_date=date('Y-m-d H:i:s');
        $branch_tbo->save();
        $sql="call get_limit_tbo_overdue();";
        $tasks = DB::select($sql);
        return redirect()->route('branch_tbo.index')
                        ->with('success','Approve Max TBO Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BranchTbo  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(BranchTbo $branch)
    {
        $branch->delete();
    
        return redirect()->route('branch_tbo.index')
                        ->with('success','BranchTbo deleted successfully');
    }
}
