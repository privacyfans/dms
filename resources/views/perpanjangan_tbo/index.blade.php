@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<!-- <div class="left-content">
						<h4 class="content-title mb-1">Branches List</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Master Branch</a></li>
								<li class="breadcrumb-item active" aria-current="page">Index</li>
							</ol>
						</nav>
					</div> -->

@endsection('breadcrumb')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>List Request Perpanjangan Tanggal TBO</h2>
            </div>
            @if((Session('role')=='spv1' && Session('branch_type')==1) || (Session('role')=='pcp' && Session('branch_type')==2) )
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('perpanjangan_tbo.create') }}"> Request Perpanjangan Tanggal TBO</a>
            </div>
            @endif
        </div>
    </div>
    <br>
    
    @if(Session('role')=='spv2' || Session('role')=='spv3'  || Session('role')=='spv4'  || Session('role')=='spv5')
    <form action="{{ url()->current() }}"
        method="get">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="pd-5 pd-sm-10 bg-gray-100">
                    <div class="input-group">
                        
                        <div class="col-md-4">
                            <input type="text"
                            class="form-control"
                            name="keyword"
                            value="{{ request('keyword') }}"
                            placeholder="Search Branch Code ...">
                        </div>
                        <span class="input-group-btn">
                            <button type="submit" class="btn ripple btn-primary br-tl-0 br-bl-0" type="button">Submit</button>
                            
                        </span>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</form>
@endif
  
@if (count($perpanjangan_tbo) == 0)
<div class="alert alert-danger mg-b-0" role="alert">
    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <p>Data Tidak Ditemukan</p>
</div>
@endif
@if ($message = Session::get('success'))
<div class="alert alert-success" role="alert">
    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
       <span aria-hidden="true">&times;</span>
  </button>
  <p>{{ $message }}</p>
</div>
@endif
@if ($message = Session::get('error'))

<div class="alert alert-danger mg-b-0" role="alert">
    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <p>{{ $message }}</p>
</div>
@endif
   
    <table class="table table-bordered table-hover  mb-0 text-md-nowrap border">
        <tr>
            <th>No</th>
            <th>Kantor Cabang</th>
            <th>User Input</th>
            <th>Input Date</th>
            <th>Status</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($perpanjangan_tbo as $br)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $br->branch_input." - ".$br->branchlist->branch_name }}</td>
            <td>{{ $br->user_input }}</td>
            <td>{{ $br->date_input }}</td>
            <td>
                 @if($br->final_flag == 0)
                    @if($br->flag_spv==0)
                    <span class="badge badge-pill badge-dark">Wait Appv. PC</span>
                    @elseif($br->flag_spv==1 && $br->flag_spv1==0)
                    <span class="badge badge-pill badge-dark">Wait Appv. KaDept</span>
                    @elseif($br->flag_spv1==1 && $br->flag_spv2==0)
                    <span class="badge badge-pill badge-dark">Wait Appv. KaDiv</span>
                    @endif
                @elseif($br->final_flag == 1)
                    <span class="badge badge-pill badge-success">Approve</span>
                @else
                    <span class="badge badge-pill badge-danger">Reject</span>
                @endif
            </td>
            
            <td>
                <form action="{{ route('perpanjangan_tbo.destroy',$br->id ) }}" method="POST">
   
                    <a class="btn btn-info" href="{{ route('perpanjangan_tbo.show',$br->id) }}">Show</a>
    
                    <!-- <a class="btn btn-primary" href="{{ route('branch.edit', $br->id) }}">Edit</a> -->
                    @if($br->flag_spv == 0 && (Session('role')=='spv1'  || Session('role')=='pc' || Session('role')=='pcp') )
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger" >Delete</button>
                    @endif
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    <br>
    {!! $perpanjangan_tbo->links() !!}
      
@endsection