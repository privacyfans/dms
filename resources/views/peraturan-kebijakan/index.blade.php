@extends('layouts.app')

@section('styles')
<style>
    .section-divider {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 30px;
        padding-bottom: 15px;
    }
    .memo-card {
        transition: transform 0.2s;
    }
    .memo-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .search-box {
        position: sticky;
        top: 0;
        z-index: 0;
        background: white;
        padding: 10px;
        border-bottom: 1px solid #dee2e6;
    }
    .no-results {
        text-align: center;
        padding: 20px;
        color: #6c757d;
    }
</style>
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Peraturan dan Kebijakan Produk</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Peraturan dan Kebijakan</a></li>
								<li class="breadcrumb-item active" aria-current="page">List</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')

<div class="row">
    <!-- Section 1: Kebijakan SOP (Left Side) -->
    <div class="col-lg-6 col-md-12">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title text-white mb-0" style="color:white !important;"><i class="fa fa-book"></i> Kebijakan dan SOP</h3>
            </div>
            <div class="search-box">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" id="searchSOP" class="form-control" placeholder="🔍 Cari Kebijakan SOP...">
                    </div>
                    <div class="col-md-4">
                        <select id="filterYearSOP" class="form-control">
                            <option value="">Semua Tahun</option>
                            @foreach($availableYearsSop as $year)
                                <option value="{{ $year }}" {{ $selectedYearSop == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Menampilkan <span id="sopCount">{{ $kebijakanSop->count() }}</span> dari <span id="sopTotal">{{ $kebijakanSop->count() }}</span> data</small>
                </div>
            </div>
            <div class="card-body" id="sopContainer" style="max-height: 800px; overflow-y: auto;">
                @if($kebijakanSop->count() > 0)
                    @foreach($kebijakanSop as $index => $item)
                    <div class="card memo-card mb-3 sop-item" data-title="{{ strtolower($item->title) }}" data-desc="{{ strtolower(strip_tags($item->deskripsi)) }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $item->title }}</h5>
                                <span class="badge badge-primary">{{ $index + 1 }}</span>
                            </div>
                            <div class="card-text mb-3">
                                {!! $item->deskripsi !!}
                            </div>
                            <a href="{{ $item->url }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fa fa-external-link"></i> Buka E-Sisdur
                            </a>
                            <div class="text-muted mt-2">
                                <small><i class="fa fa-clock-o"></i> {{ $item->created_at->format('d M Y') }}</small>
                            </div>
                        </div>
                        
                    </div>
                    
                    @endforeach
                    <div class="no-results" id="noResultsSOP" style="display: none;">
                        <i class="fa fa-search fa-3x mb-3"></i>
                        <p>Tidak ada hasil yang ditemukan</p>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fa fa-info-circle"></i> Belum ada Kebijakan dan SOP yang tersedia.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Section 2: Internal Memo (Right Side) -->
    <div class="col-lg-6 col-md-12">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h3 class="card-title text-white mb-0" style="color:white !important;"><i class="fa fa-file-text"></i> Internal Memo</h3>
            </div>
            <div class="search-box">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" id="searchMemo" class="form-control" placeholder="🔍 Cari Internal Memo...">
                    </div>
                    <div class="col-md-4">
                        <select id="filterYearMemo" class="form-control">
                            <option value="">Semua Tahun</option>
                            @foreach($availableYearsMemo as $year)
                                <option value="{{ $year }}" {{ $selectedYearMemo == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Menampilkan <span id="memoCount">{{ $internalMemo->count() }}</span> dari <span id="memoTotal">{{ $internalMemo->count() }}</span> data</small>
                </div>
            </div>
            <div class="card-body" id="memoContainer" style="max-height: 800px; overflow-y: auto;">
                @if($internalMemo->count() > 0)
                    @foreach($internalMemo as $item)
                    <div class="card memo-card mb-3 memo-item" data-title="{{ strtolower($item->title) }}" data-desc="{{ strtolower(strip_tags($item->deskripsi)) }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->title }}</h5>
                            <div class="card-text mb-3">
                                {!! $item->deskripsi !!}
                            </div>

                            @if($item->files->count() > 0)
                            <div class="border-top pt-3">
                                <h6 class="text-muted mb-2"><i class="fa fa-file"></i> Files:</h6>
                                <ul class="list-unstyled mb-0">
                                    @foreach($item->files as $file)
                                    <li class="mb-2">
                                        <a href="/uploads/{{ $file->file_path }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="fa fa-download"></i> {{ $file->file_name }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="text-muted mt-2">
                                <small><i class="fa fa-clock-o"></i> {{ $item->created_at->format('d M Y') }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="no-results" id="noResultsMemo" style="display: none;">
                        <i class="fa fa-search fa-3x mb-3"></i>
                        <p>Tidak ada hasil yang ditemukan</p>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fa fa-info-circle"></i> Belum ada Internal Memo yang tersedia.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Store initial counts
        var sopTotal = $('.sop-item').length;
        var memoTotal = $('.memo-item').length;
        $('#sopTotal').text(sopTotal);
        $('#memoTotal').text(memoTotal);

        // Function to update SOP counter
        function updateSopCount() {
            var count = $('.sop-item:visible').length;
            $('#sopCount').text(count);
        }

        // Function to update Memo counter
        function updateMemoCount() {
            var count = $('.memo-item:visible').length;
            $('#memoCount').text(count);
        }

        // Filter Year for Kebijakan SOP
        $('#filterYearSOP').on('change', function() {
            var selectedYear = $(this).val();
            var currentUrl = new URL(window.location.href);

            if (selectedYear) {
                currentUrl.searchParams.set('year_sop', selectedYear);
            } else {
                currentUrl.searchParams.delete('year_sop');
            }

            window.location.href = currentUrl.toString();
        });

        // Filter Year for Internal Memo
        $('#filterYearMemo').on('change', function() {
            var selectedYear = $(this).val();
            var currentUrl = new URL(window.location.href);

            if (selectedYear) {
                currentUrl.searchParams.set('year_memo', selectedYear);
            } else {
                currentUrl.searchParams.delete('year_memo');
            }

            window.location.href = currentUrl.toString();
        });

        // Search for Kebijakan SOP
        $('#searchSOP').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();
            var visibleCount = 0;

            $('.sop-item').each(function() {
                var title = $(this).data('title');
                var desc = $(this).data('desc');

                if (title.includes(searchText) || desc.includes(searchText)) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });

            // Update counter
            updateSopCount();

            // Show/hide no results message
            if (visibleCount === 0 && searchText !== '') {
                $('#noResultsSOP').show();
            } else {
                $('#noResultsSOP').hide();
            }
        });

        // Search for Internal Memo
        $('#searchMemo').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();
            var visibleCount = 0;

            $('.memo-item').each(function() {
                var title = $(this).data('title');
                var desc = $(this).data('desc');

                if (title.includes(searchText) || desc.includes(searchText)) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });

            // Update counter
            updateMemoCount();

            // Show/hide no results message
            if (visibleCount === 0 && searchText !== '') {
                $('#noResultsMemo').show();
            } else {
                $('#noResultsMemo').hide();
            }
        });
    });
</script>
@endsection
