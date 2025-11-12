@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

<div class="left-content">
    <h4 class="content-title mb-1">Data Loan {{$produk_title}}</h4>
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

<form action="{{ url()->current() }}" method="get">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="pd-5 pd-sm-10 bg-gray-100">
                    <div class="input-group">

                        <div class="col-md-2">
                            <input type="text" class="form-control" name="keyword_loan"
                                value="{{ request('keyword_loan') }}" placeholder="Search Loan App No ...">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="keyword_branch"
                                value="{{ request('keyword_branch') }}" placeholder="Search Branch ...">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="keyword_cif"
                                value="{{ request('keyword_cif') }}" placeholder="Search No CIF ...">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="keyword_ktp"
                                value="{{ request('keyword_ktp') }}" placeholder="Search No KTP ...">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="keyword_name"
                                value="{{ request('keyword_name') }}" placeholder="Search Name ...">
                        </div>
                        <div class="col-md-1">
                            <span class="input-group-btn">
                                <button type="submit" class="btn ripple btn-primary br-tl-0 br-bl-0"
                                    type="button">Search</button>

                            </span>
                        </div>
                        <div class="col-md-1">
                            <span class="input-group-btn">

                                @if($produk_title == 'All TBO Overdue')
                                    <a class="btn btn-info" href="{{url('download_tbo_overdue')}}">Download</a>
                                @elseif($produk_title == 'All TBO')
                                    <a class="btn btn-info" href="{{url('download_tbo')}}">Download</a>
                                @elseif($produk_title == 'All TBO Pending')
                                    <a class="btn btn-info" href="{{url('download_tbo_pending')}}">Download</a>
                                @endif
                            </span>
                        </div>

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

<div class="table-responsive">

    <table class="table table-bordered table-hover  mb-0 text-md-nowrap border">
        <tr>
            <th>No</th>
            <th>No Registration</th>
            <th>Kode Cabang</th>
            <th>Loan App No</th>
            <th>No CIF</th>
            <th>No KTP</th>
            <th>Nama Debitur</th>
            <th>Date Input</th>
            <th>Product</th>
            <th>TBO Date</th>
            <th>UNIT BISNIS</th>
            <th>DCRM</th>
            <!-- <th>DCRM REVIEWER</th>
            <th>DCRM TEAM LEADER</th> -->
            <th>Date Submit</th>
            <th width="100px">Action</th>
        </tr>
        @foreach ($datafile as $br)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ getNoRegistration($br->loan_app_no) }}</td>
                        <td>{{ $br->kode_cabang }}</td>
                        <td>{{ $br->loan_app_no }}</td>
                        <td>{{ $br->no_cif }}</td>
                        <td>{{ $br->no_ktp }}</td>
                        <td>{{ $br->nama_debitur }}</td>
                        <td>{{ $br->date_input }}</td>
                        <td>{{ $br->produk }}</td>
                        <td>{{ date('Y-m-d', strtotime($br->tbo_date)) }}</td>
                        <td>{!! getLabelStatus($br->final_status_spv1) !!}</td>
                        <!-- <td>{!! getLabelStatus($br->final_status_spv2) !!}</td> -->
                        <td>{!! getLabelStatus($br->final_status_spv3) !!}</td>
                        <td>{{ $br->date_flag_spv1 }}</td>

                        <td>
                            {{-- <form action="{{ route('loans.destroy',$br->loan_app_no ) }}" method="POST"> --}}
                                
                                @php
                                    $date_input = new DateTime($br->date_input);
                                    $compare_date = new DateTime('2024-11-06');
                                @endphp

                                @if ($date_input > $compare_date && 
                                ($br->modul=='KUPEG' || 
                                $br->modul=='KUPEN HYBRID' || 
                                $br->modul=='KUPEN HYBRID GO' ||
                                $br->modul=='KUPEN JANDA' ||
                                $br->modul=='KUPEN UMUM')
                                )
                                    <a class="btn btn-info" href="{{ route('datafile.show_new', $br->loan_app_no) }}">Show</a>
                                @else
                                    <a class="btn btn-info" href="{{ route('loans.show', $br->loan_app_no) }}">Show</a>
                                @endif
                                
                                {{-- <a class="btn btn-info" href="{{ route('loans.show',$br->loan_app_no) }}">Show</a> --}}

                                {{-- <a class="btn btn-primary" href="{{ route('loans.edit', $br->loan_app_no) }}">Edit</a> --}}

                                {{-- @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger">Delete</button> --}}
                            </form>
                        </td>
                    </tr>
        @endforeach
    </table>
</div>
<br>
{!! $datafile->appends(request()->all())->links() !!}

@endsection