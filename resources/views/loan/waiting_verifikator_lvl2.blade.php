@extends('layouts.app')

@section('breadcrumb')
<div class="left-content">
    <h4 class="content-title mb-1">Queue Team Verifikator Level 2</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Team Verifikator</a></li>
            <li class="breadcrumb-item active" aria-current="page">Queue Level 2</li>
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
                            <select class="form-control" name="rekomendasi_lvl1">
                                <option value="">Semua Rekomendasi</option>
                                <option value="5" {{ request('rekomendasi_lvl1') == '5' ? 'selected' : '' }}>Approve</option>
                                <option value="6" {{ request('rekomendasi_lvl1') == '6' ? 'selected' : '' }}>Not Approve</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <span class="input-group-btn">
                                <button type="submit" class="btn ripple btn-primary br-tl-0 br-bl-0"
                                    type="button">Search</button>
                            </span>
                        </div>
                        <div class="col-md-1">
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
                    Dokumen Menunggu Keputusan Final (Level 2)
                    <span class="badge badge-info ml-2">{{ $datafile->total() }}</span>
                </h3>
            </div>
            <div class="card-body">
                {{-- NEW FLOW INFO --}}
                <div class="alert alert-info mb-3">
                    <i class="fa fa-info-circle"></i> <strong>Flow Baru:</strong>
                    Anda hanya perlu memberikan keputusan (Approve/Not Approve).
                    <strong>File bukti pemeriksaan akan diupload oleh Team Verifikator Level 1 setelah Anda submit.</strong>
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
                                                    <th>Rekomendasi Lvl 1</th>
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
                                                        @if($loan->final_status_verif1 == 5)
                                                            <span class="badge badge-success">
                                                                <i class="fa fa-thumbs-up"></i> Approve (Rekomendasi)
                                                            </span>
                                                        @elseif($loan->final_status_verif1 == 6)
                                                            <span class="badge badge-warning">
                                                                <i class="fa fa-thumbs-down"></i> Not Approve (Rekomendasi)
                                                            </span>
                                                        @endif
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $loan->user_verif1 ?? '-' }} | {{ $loan->date_flag_verif1 ?? '-' }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('loans.show', $loan->loan_app_no) }}"
                                                           class="btn btn-sm btn-success">
                                                            <i class="fa fa-gavel"></i> Show
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
