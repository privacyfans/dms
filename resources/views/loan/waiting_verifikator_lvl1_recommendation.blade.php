@extends('layouts.app')

@section('breadcrumb')
<div class="left-content">
    <h4 class="content-title mb-1">Queue - Pending Recommendation</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Team Verifikator</a></li>
            <li class="breadcrumb-item"><a href="#">Level 1</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pending Recommendation</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')

{{-- Search Filter --}}
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
                            <input type="text" class="form-control" name="keyword_name"
                                value="{{ request('keyword_name') }}" placeholder="Search Nama Debitur ...">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="keyword_branch"
                                value="{{ request('keyword_branch') }}" placeholder="Search Cabang ...">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="keyword_produk"
                                value="{{ request('keyword_produk') }}" placeholder="Search Produk ...">
                        </div>
                        <div class="col-md-2">
                            <span class="input-group-btn">
                                <button type="submit" class="btn ripple btn-primary br-tl-0 br-bl-0"
                                    type="button">Search</button>
                            </span>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ url()->current() }}" class="btn btn-secondary">Clear</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fa fa-file-text text-warning"></i> Dokumen Menunggu Rekomendasi
                    <span class="badge badge-warning ml-2">{{ $datafile->total() }}</span>
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-3">
                    <i class="fa fa-info-circle"></i> <strong>Task:</strong>
                    Berikan rekomendasi (Approve/Not Approve) untuk dokumen-dokumen yang sudah di-verify/TBO oleh SPV.
                    Rekomendasi Anda akan menjadi pertimbangan untuk Team Verifikator Level 2.
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="datatable">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Loan App No</th>
                                <th>Nama Debitur</th>
                                <th>Produk</th>
                                <th>Cabang</th>
                                <th>Tanggal Input</th>
                                <th>Status SPV</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datafile as $key => $loan)
                            <tr>
                                <td>{{ $datafile->firstItem() + $key }}</td>
                                <td>{{ $loan->loan_app_no }}</td>
                                <td>{{ $loan->nama_debitur }}</td>
                                <td>{{ $loan->produk }}</td>
                                <td>{{ $loan->kode_cabang }}</td>
                                <td>{{ $loan->date_input }}</td>
                                <td>
                                    @if($loan->final_status_spv2 == 1)
                                        <span class="badge badge-success">Verify</span>
                                    @elseif($loan->final_status_spv2 == 3)
                                        <span class="badge badge-warning">TBO</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('loans.show', $loan->loan_app_no) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fa fa-file-text"></i> Show
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                <div class="mt-3">
                    {{ $datafile->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
