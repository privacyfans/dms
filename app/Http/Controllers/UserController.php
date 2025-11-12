<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branchlist;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class UserController extends Controller
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
        $users    = User::with('userrolename')->when($request->keyword, function ($query) use ($request) {
            $query
            ->where('nik', 'like', "%{$request->keyword}%")
            ->orWhere('name', 'like', "%{$request->keyword}%");
        })->orderBy('created_at', 'desc')->paginate($pagination);
    
        $users->appends($request->only('keyword'));
    
       
        return view('users.index', [
            'nik'    => 'NIK',
            'users' => $users,
        ])->with('i', ($request->input('page', 1) - 1) * $pagination);

        /*$users = User::latest()->paginate(5);
    
        return view('users.index',compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);*/
    }

    public function branchSearch(Request $request)
    {
    	$branch = [];

        if($request->has('q')){
            $search = $request->q;
            $branch =Branchlist::select("branch_code", "branch_name")
            		->where('branch_name', 'LIKE', "%$search%")
                    ->orderBy('branch_code','asc')
            		->get();
        }else{
            $search = $request->q;
            $branch =Branchlist::select("branch_code", "branch_name")
                    ->orderBy('branch_code','asc')
            		->get();
        }
        return response()->json($branch);
    }

    public function roleSearch(Request $request)
    {
    	$role = [];

        if($request->has('q')){
            $search = $request->q;
            $role =Role::select("id", "name")
            		->where('name', 'LIKE', "%$search%")
            		->get();
        }else{
            $search = $request->q;
            $role =Role::select("id", "name")
            		->get();
        }
        return response()->json($role);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        //try {
        $request->validate([
            'role' => 'required',
            'email' => 'required|unique:users,email',
            'name' => 'required',
            'cabang' => 'required',
            'nik' => 'required|unique:users,nik',
        ]);
    
        if($request->blocked_at=="blocked"){
            $request->blocked_at=date('Y-m-d H:i:s');
        }else{
            $request->blocked_at=null;
        }

        User::create($request->all());
     
        return redirect()->route('users.index')
                        ->with('success','User created successfully.');
        /*}catch (QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return redirect()->route('users.index')->with('error','NIK : '.$request->nik.' already exist');
            }
        }*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $branchall=[];
        $branchlist=Branchlist::select("branch_code", "branch_name")
        ->where('branch_code',$user->cabang)
        ->orderBy('branch_code','asc')
        ->get();
        foreach($branchlist as $branch)
        {
            $branchall[] = $branch->branch_code;
        }  


        $roleall=[];
        $rolelist=Role::select("id", "name")
        ->where('id',$user->role)
        ->orderBy('id','asc')
        ->get();
        foreach($rolelist as $role)
        {
            $roleall[] = $role->id;
        }  
        //dd($branchall);
        //return view('users.create',compact('branchlist'));
        return view('users.edit',compact('user','branchlist','branchall','rolelist','roleall'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required',
            'email' => 'required',
            'name' => 'required',
            'cabang' => 'required',
            'nik' => 'required',
        ]);
        
        if($request->blocked_at=="blocked"){
            $request->merge([ 'blocked_at' => date('Y-m-d H:i:s') ]);
        }else{
            $request->merge([ 'blocked_at' => null ]);
        }
        
        $user->update($request->all());
    
        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
    
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
}

