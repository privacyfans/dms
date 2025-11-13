@extends('layouts.app')

@section('breadcrumb')
<div class="left-content">
    <h4 class="content-title mb-1">Pending Disbursement</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Disbursement</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pending</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')

{{-- Flash Messages --}}
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
                            <select class="form-control" name="final_status">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('final_status') == '1' ? 'selected' : '' }}>Verify</option>
                                <option value="3" {{ request('final_status') == '3' ? 'selected' : '' }}>TBO</option>
                                <option value="6" {{ request('final_status') == '6' ? 'selected' : '' }}>Not Approve</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <span class="input-group-btn">
                                <button type="submit" class="btn ripple btn-primary br-tl-0 br-bl-0"
                                    type="button">Search</button>
                            </span>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ url()->current() }}" class="btn ripple btn-secondary">Reset</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Main Card --}}
<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mg-b-0">List Loan Pending Disbursement</h4>
                </div>
                <p class="tx-12 tx-gray-500 mb-2">
                    Loan yang sudah upload file bukti verifikator dan siap untuk di-disburse.
                    Total: <strong>{{ $loans->total() }}</strong> loan(s)
                </p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-nowrap w-100" id="responsive-datatable">
                        <thead>
                            <tr>
                                <th class="wd-5p">No</th>
                                <th class="wd-15p">Loan App No</th>
                                <th class="wd-15p">Nama Debitur</th>
                                <th class="wd-10p">Branch</th>
                                <th class="wd-10p">Produk</th>
                                <th class="wd-10p">Date Input</th>
                                <th class="wd-10p">Status</th>
                                <th class="wd-15p">File Bukti</th>
                                <th class="wd-10p">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = ($loans->currentPage() - 1) * $loans->perPage() + 1; @endphp
                            @forelse ($loans as $loan)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    <a href="{{ route('datafile.show_new', $loan->loan_app_no) }}"
                                       class="text-primary font-weight-bold"
                                       target="_blank">
                                        {{ $loan->loan_app_no }}
                                        <i class="fas fa-external-link-alt fa-xs"></i>
                                    </a>
                                </td>
                                <td>{{ $loan->nama_debitur ?? '-' }}</td>
                                <td>
                                    @if($loan->mastercabang && $loan->mastercabang->first())
                                        {{ $loan->mastercabang->first()->branch_name }}
                                    @else
                                        {{ $loan->kode_cabang ?? '-' }}
                                    @endif
                                </td>
                                <td>{{ $loan->produk ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($loan->date_input)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($loan->final_status == 1)
                                        <span class="badge badge-success">Verify</span>
                                    @elseif($loan->final_status == 3)
                                        <span class="badge badge-warning">TBO</span>
                                    @elseif($loan->final_status == 6)
                                        <span class="badge badge-danger">Not Approve</span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($loan->file_bukti_verifikator))
                                        <a href="{{ asset('indexed' . $loan->file_bukti_verifikator) }}"
                                           target="_blank"
                                           class="text-primary">
                                            <i class="fas fa-file-pdf"></i> Lihat Bukti
                                            <i class="fas fa-external-link-alt fa-xs"></i>
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            By: {{ $loan->user_verif1 ?? '-' }}
                                        </small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('datafile.show_new', $loan->loan_app_no) }}?lock=1"
                                       class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye"></i> Show
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> Tidak ada loan yang pending disbursement
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $loans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
