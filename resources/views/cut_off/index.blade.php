@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">List Cut Off</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">List Cut Off</a></li>
								<li class="breadcrumb-item active" aria-current="page">Index</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
    {{-- <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Master Branch</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('branch.create') }}"> Create Branch</a>
            </div>
        </div>
    </div> --}}
    <br>
    <!-- Search Form -->
    <form action="{{ url()->current() }}" method="get">
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
                                    placeholder="Search Branch Code / Branch Name / Cut Off Time ...">
                            </div>
                            <span class="input-group-btn">
                                <button type="submit" class="btn ripple btn-primary br-tl-0 br-bl-0" type="button">Search</button>
                            </span>
                            @if(request('keyword'))
                                <span class="input-group-btn ml-2">
                                    <a href="{{ route('cut_off.index') }}" class="btn ripple btn-secondary">Clear</a>
                                </span>
                            @endif
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
            <th>Branch Code</th>
            <th>Branch Name</th>
            <th>Cut Off Time</th>
            <th>To Be</th>
            <th width="280px">Action</th>
        </tr>
        
        @foreach ($cut_off as $br)
        <tr>
            
            <td>{!! $br->branch_code !!}</td>
            <td>{!! $br->branch_name !!}</td>
            <td>{!! $br->cut_off !!}</td>
            <td>{!! $br->purpose_cut_off !!}</td>
           
            <td>
                {{-- <form action="{{ route('branch.destroy',$br->branch_code ) }}" method="POST"> --}}
                
                    {{-- <a class="btn btn-info" href="{{ route('cut_off.show',$br->id) }}">Show</a> --}}
					@if(Session("role")=="spv3" || Session("role")=="spv4")
                    <form action="{{ route('cut_off.update',$br->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col">
                            <input type="text" name="purpose_cut_off" value="" class="form-control" placeholder="HH:MM:SS" maxlength=8 size=8>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Change</button>
                        </div>
                    </div>
                    </form>
                    {{-- <a class="btn btn-primary" href="{{ route('cut_off.edit', $br->id) }}">Change</a> --}}
					@endif
					@if(Session("role")=="spv5")
                    {{--<a class="btn btn-secondary" href="{{ route('cut_off.approve', $br->id) }}">Approve</a>--}}
                    <form id="{{$br->id}}" name="{{$br->id}}" action="{{ route('cut_off.approveSubmitList') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <input type="hidden" name="id" value="{{$br->id}}" class="form-control" placeholder="HH:MM:SS" maxlength=8 size=8>
                            <input type="hidden" name="purpose_cut_off" value="{{$br->purpose_cut_off}}" class="form-control" placeholder="HH:MM:SS" maxlength=8 size=8>
                            <select name="approve_flag" class="form-control SlectBox SumoUnder">
										<!--placeholder-->
										<option value="1">Approve</option>
										<option value="2">Not Approve</option>
									</select>
                            
                        </div>
                        <div class="col">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    </div>
                    </form>
					@endif
                    {{-- @csrf
                    @method('DELETE')
      
                    <button type="submit" class="btn btn-danger">Delete</button> --}}
                    {{--</form>--}}
            </td>
           
        </tr>
        @endforeach
    </table>
    <br>
    {!! $cut_off->links() !!}
      
@endsection