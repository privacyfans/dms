@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Setting Max TBO Overdue</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Setting Max TBO Overdue</a></li>
								<li class="breadcrumb-item active" aria-current="page">Index</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
    {{-- <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Setting Max TBO Overdue</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('branch_tbo.create') }}"> Create Branch</a>
            </div>
        </div>
    </div> --}}
    <br>
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

                        <div class="col-md-4">
                            <input type="text"
                            class="form-control"
                            name="branch_name"
                            value="{{ request('branch_name') }}"
                            placeholder="Search Branch Name ...">
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
            <th>Branch Code</th>
            <th>Branch Name</th>
            <th>Total Pengajuan</th>
            <th>Total Droping</th>
            <th>Tbo Overdue</th>
            <th>Limit Tbo Overdue</th>
            <th>Limit TBO Overdue (%) </th>
            <th>To Be Limit TBO Overdue (%)</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($branch_tbo as $br)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $br->branch_code }}</td>
            <td>{{ $br->branchlist->branch_name }}</td>
            <td>{{ $br->total_pengajuan }}</td>
            <td>{{ $br->total_pencairan }}</td>
            <td>{{ $br->tbo_overdue }}</td>
            <td>{{ $br->total_limit_overdue }}</td>
            <td>{{ $br->jml }}</td>
            <td>{{ $br->purpose_jml }}</td>

            
            
            <td>
                {{-- <form action="{{ route('branch_tbo.destroy',$br->branch_code ) }}" method="POST"> --}}
   
                    {{-- <a class="btn btn-info" href="{{ route('branch_tbo.show',$br->branch_code) }}">Show</a>
    
                    <a class="btn btn-primary" href="{{ route('branch_tbo.edit', $br->branch_code) }}">Edit</a> --}}

                    @if(Session("role")=="spv3" || Session("role")=="spv4")
                    {{--<a class="btn btn-primary" href="{{ route('branch_tbo.edit', $br->branch_code) }}">Purpose</a>--}}
                    <form action="{{ route('branch_tbo.update',$br->branch_code) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col">
                            <input type="text" name="purpose_jml" value="" class="form-control" placeholder="TBO (%)">
                        </div>
                        <div class="col">
                        <button type="submit" class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">Change</button>
                        </div>
                    </div>
                    </form>


					@endif
					@if(Session("role")=="spv5")
                    {{--<a class="btn btn-secondary" href="{{ route('branch_tbo.approve', $br->branch_code) }}">Approve</a>--}}
                    <form action="{{ route('branch_tbo.approveSubmit') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col">
                        <input type="hidden" name="branch_code" value="{{ $br->branch_code }}" class="form-control" placeholder="Jumlah">
                        <input type="hidden" name="purpose_jml" value="{{ $br->purpose_jml }}" class="form-control" placeholder="Jumlah">
                        <select name="approve_flag" class="form-control SlectBox SumoUnder">
										<!--placeholder-->
										<option value="1">Approve</option>
										<option value="2">Not Approve</option>
									</select>
                        </div>
                        <div class="col">
                        <button type="submit" class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">Submit</button>
                        </div>
                    </div>
                    </form>


					@endif
   
                    {{-- @csrf
                    @method('DELETE')
      
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form> --}}
            </td>
        </tr>
        @endforeach
    </table>
    <br>
    {!! $branch_tbo->links() !!}
      
@endsection