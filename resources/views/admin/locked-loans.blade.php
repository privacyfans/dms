@extends('layouts.app')

@section('styles')
@endsection

@section('breadcrumb')

<div class="left-content">
    <h4 class="content-title mb-1">Locked Loans Management</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Locked Loans</li>
        </ol>
    </nav>
</div>

@endsection('breadcrumb')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title">Currently Locked Loans</h3>
                        <p class="text-muted mb-0">Daftar loan application yang sedang di-lock oleh user. Lock akan otomatis expire setelah 15 menit.</p>
                    </div>
                    <div>
                        <form action="{{ route('admin.cleanup_legacy_locks') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Cleanup semua legacy locks (locks tanpa timestamp)? Ini akan merelease lock yang tidak valid.')">
                                <i class="fa fa-broom"></i> Cleanup Legacy Locks
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
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

                @if(count($lockedLoans) == 0)
                    <div class="alert alert-info">
                        <p>Tidak ada loan yang sedang di-lock saat ini.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0 text-md-nowrap border">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Loan App No</th>
                                    <th>Locked By (NIK)</th>
                                    <th>Locked By (Name)</th>
                                    <th>Level</th>
                                    <th>Locked At</th>
                                    <th>Expires At</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lockedLoans as $index => $lock)
                                    <tr class="{{ $lock['is_expired'] ? 'table-warning' : '' }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $lock['loan_app_no'] }}</td>
                                        <td>{{ $lock['nik'] ?? '-' }}</td>
                                        <td>{{ $lock['name'] ?: '-' }}</td>
                                        <td>
                                            @if($lock['level'])
                                                <span class="badge badge-info">{{ strtoupper($lock['level']) }}</span>
                                                @if($lock['source'] == 'pickup')
                                                    <br><span class="badge badge-warning mt-1">Pickup</span>
                                                @endif
                                            @else
                                                <span class="badge badge-secondary">Legacy</span>
                                            @endif
                                        </td>
                                        <td>{{ $lock['locked_at'] ? $lock['locked_at']->format('Y-m-d H:i:s') : '-' }}</td>
                                        <td>{{ $lock['expired_at'] ? $lock['expired_at']->format('Y-m-d H:i:s') : '-' }}</td>
                                        <td>
                                            @if($lock['locked_at'] === null || $lock['expired_at'] === null)
                                                <span class="badge badge-secondary">Legacy Lock</span>
                                            @elseif($lock['is_expired'])
                                                <span class="badge badge-warning">Expired</span>
                                            @else
                                                @php
                                                    $remaining = now()->diffInMinutes($lock['expired_at'], false);
                                                @endphp
                                                <span class="badge badge-success">Active ({{ $remaining }} min remaining)</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.force_unlock', $lock['loan_app_no']) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin force unlock loan ini?')">
                                                    Force Unlock
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <p class="text-muted">
                            <strong>Total Locked Loans:</strong> {{ count($lockedLoans) }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Auto refresh page every 30 seconds to update lock status
    setTimeout(function(){
        location.reload();
    }, 30000);
</script>
@endsection
