@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">{{$produk_title}}</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">{{$produk_title}}</a></li>
								<li class="breadcrumb-item active" aria-current="page">Index</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
    <div class="row">
        {{-- <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Data Loan </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('datafile.searchLoan') }}"> New Request</a>
            </div>
        </div> --}}
    </div>
    {{-- 
    <form action="{{ url()->current() }}"
        method="get">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="pd-5 pd-sm-10 bg-gray-100">
                    <div class="input-group">
                        
                        <div class="col-md-2">
                            <input type="text"
                            class="form-control"
                            name="keyword_loan"
                            value="{{ request('keyword_loan') }}"
                            placeholder="Search Loan App No ...">
                        </div>
                        <div class="col-md-2">
                            <input type="text"
                            class="form-control"
                            name="keyword_branch"
                            value="{{ request('keyword_branch') }}"
                            placeholder="Search Branch ...">
                        </div>
                        <div class="col-md-2">
                            <input type="text"
                            class="form-control"
                            name="keyword_cif"
                            value="{{ request('keyword_cif') }}"
                            placeholder="Search No CIF ...">
                        </div>
                        <div class="col-md-2">
                            <input type="text"
                            class="form-control"
                            name="keyword_ktp"
                            value="{{ request('keyword_ktp') }}"
                            placeholder="Search No KTP ...">
                        </div>
                        <div class="col-md-2">
                            <input type="text"
                            class="form-control"
                            name="keyword_name"
                            value="{{ request('keyword_name') }}"
                            placeholder="Search Name ...">
                        </div>
                        <div class="col-md-2">
                            <span class="input-group-btn">
                                <button type="submit" class="btn ripple btn-primary br-tl-0 br-bl-0" type="button">Search</button>
                            </span>
                        </div>
                        
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</form>
--}}

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
<div class="table-responsive">

<table class="table table-bordered table-hover mb-0 text-md-nowrap border">
        <tr>
            <th>No</th>
            <th>Loan App No</th>
            <th>Nama Cabang</th>
            <th><b>Estimasi Selesai</b></th>
        </tr>
       
        @foreach ($datafile as $index => $br)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $br->loan_app_no }}</td>
                <td>{{ $br->branch_name }}</td>
                <td><b>{{ $waitingTimes[$index] }}</b></td>
            </tr>
        @endforeach
    </table>
</div>
    <br>
    {!! $datafile->appends(request()->all())->links() !!}
      
@endsection
