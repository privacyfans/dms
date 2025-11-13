@extends('layouts.app')

@section('styles')
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />

    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        .uploaded-files-container {
            margin-top: 10px;
        }

        .uploaded-files-list {
            padding-left: 40px;
        }

        .uploaded-files-list li {
            margin-bottom: 5px;
        }

        .accordion-gray .card-header-dark a.collapsed,
        .accordion .card-header-dark a,
        .accordion .card-header-dark a.collapsed,
        .accordion .card-header-dark a.collapsed:focus,
        .accordion .card-header-dark a.collapsed:hover,
        .accordion .card-header-dark a:focus,
        .accordion .card-header-dark a:hover {
            color: #000;
            display: block;
            padding: 14px 20px;
            position: relative;
            font-weight: 500;
            font-size: 16px;
        }



        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background: #fff !important;
            background-clip: border-box;
            border-radius: 10px;
            border: 0 solid #edeef7 !important;
            margin-bottom: 5px !important;
            box-shadow: 0 5px 15px 5px rgba(80, 102, 224, 0.08);
            font-size: 16px !important;
        }

        .card-body {
            flex: 1 1 auto;
            padding: 0.5rem;
        }

        .form-group-fileupload {
            margin-bottom: 0.5rem;
            margin-top: 0.5rem;
        }

        /* Ensure textarea focus is visible and not overridden */
        textarea[name="comment"]:focus {
            outline: 2px solid #007bff !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
            border-color: #007bff !important;
        }

        /* Reset z-index for modal elements */
        .modal-backdrop.show {
            z-index: 1040;
        }

        .modal {
            z-index: 1050;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="left-content">
        <h4 class="content-title mb-1">Data Loan</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Data</a></li>
                <li class="breadcrumb-item active" aria-current="page">Loan</li>
            </ol>
        </nav>
    </div>
@endsection('breadcrumb')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Loan App No # {{ $datafile->loan_app_no }}</h2>
                <h4> No Registration # {{ getNoRegistration($datafile->loan_app_no) }}</h4>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route(checkJenisProduk($datafile->produk)) }}"> Back</a>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="panel panel-primary tabs-style-2">
                        <div class=" tab-menu-heading">
                            <div class="tabs-menu1">
                                <!-- Tabs -->
                                <ul class="nav panel-tabs main-nav-line" id="myTab">
                                    <li><a href="#tab1" class="nav-link" data-toggle="tab">Base Data</a></li>
                                    <li><a href="#tab2" class="nav-link active" data-toggle="tab">Document</a></li>
                                    <li><a href="#tab3" class="nav-link" data-toggle="tab">Review</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body main-content-body-right border">
                            <div class="tab-content">
                                <div class="tab-pane" id="tab1">
                                    <div class="row d-flex justify-content-end">
                                        <a class="btn btn-success"
                                            href="{{ url('updatebasedata') . '/' . $datafile->loan_app_no }}"><i
                                                class="la la-redo-alt text-white"></i>&nbsp;Update Base Data</a>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="card">

                                                <div class="card-body">
                                                    <!-- <a class="btn btn-success" href="{{ url('updatebasedata') . '/' . $datafile->loan_app_no }}"><i class="la la-redo-alt text-white"></i>&nbsp;Update Base Data</a> -->

                                                    <div class="pd-10 pd-sm-10 bg-gray-100">
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Loan App No</label>
                                                            </div>

                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->loan_app_no }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">No CIF </label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->no_cif }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Kode Cabang</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->kode_cabang }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Nama Debitur</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->nama_debitur }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">No KTP</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->no_ktp }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Tgl Lahir</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->ttl }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Status Pernikahan</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ getStatusPernikahan($datafile->status_pernikahan) }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Pekerjaan</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->pekerjaan }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Alamat Rumah</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->alamat_rumah }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Instansi</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->instansi }}
                                                            </div>
                                                        </div>
                                                        @if(str_contains($datafile->debtor_classification_name, 'KUPEN'))
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Klasifikasi Debitur</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->debtor_classification_code }} - {{ $datafile->debtor_classification_name }}
                                                            </div>
                                                        </div>
                                                        @endif
                                                        
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Alamat Kantor</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->alamat_kantor }}
                                                            </div>
                                                        </div>

                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">No Tlp Kantor</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->no_tlp_kantor }}
                                                            </div>
                                                        </div>

                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Produk</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->produk }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Plafond</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ number_format($datafile->plafond, 2, ',', '.') }}
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="pd-10 pd-sm-10 bg-gray-100">


                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Jangka Waktu</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->jangka_waktu }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Rate</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->rate . ' %' }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Angsuran</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ number_format($datafile->angsuran, 2, ',', '.') }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Tgl Jatuh Tempo</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->tanggal_jatuh_tempo }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Fasilitas</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->fasilitas }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Early</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                @php
                                                                    if ($datafile->early == 0) {
                                                                        $early_desc = 'TIDAK';
                                                                    } else {
                                                                        $early_desc = 'YA';
                                                                    }
                                                                @endphp
                                                                {{ $early_desc }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Take Over</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                @php
                                                                    if ($datafile->take_over == 0) {
                                                                        $take_over_desc = 'TIDAK';
                                                                    } else {
                                                                        $take_over_desc = 'YA';
                                                                    }
                                                                @endphp
                                                                {{ $take_over_desc }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Take Over Hari Ini</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                @php
                                                                    if ($datafile->take_over_hari_ini == 0) {
                                                                        $take_over_hari_ini_desc = 'TIDAK';
                                                                    } else {
                                                                        $take_over_hari_ini_desc = 'YA';
                                                                    }
                                                                @endphp
                                                                {{ $take_over_hari_ini_desc }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Status Deviasi</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                @php
                                                                    if ($datafile->status_deviasi == 0) {
                                                                        $status_deviasi_desc = 'TIDAK';
                                                                    } else {
                                                                        $status_deviasi_desc = 'YA';
                                                                    }
                                                                @endphp
                                                                {{ $status_deviasi_desc }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Fronting Agent</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                @php
                                                                    if ($datafile->status_fronting_agent == 0) {
                                                                        $status_fronting_agent_desc = 'TIDAK';
                                                                    } else {
                                                                        $status_fronting_agent_desc = 'YA';
                                                                    }
                                                                @endphp
                                                                {{ $status_fronting_agent_desc }}
                                                            </div>
                                                        </div>
                                                        @if ($datafile->modul == 'KUPEG' || $datafile->modul == 'KUPEN HYBRID' || $datafile->modul == 'KUPEN HYBRID GO')
                                                            <div class="row row-xs align-items-center mg-b-20">
                                                                <div class="col-md-3">
                                                                    <label class="form-label mg-b-0">Status Pegawai</label>
                                                                </div>
                                                                <div class="col-md-8 mg-t-5 mg-md-t-0">

                                                                    {{ $datafile->status_pegawai }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">User Input</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->user_input }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Branch Input</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->branch_input }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Date Input</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {{ $datafile->date_input }}
                                                            </div>
                                                        </div>
                                                        <div class="row row-xs align-items-center mg-b-20">
                                                            <div class="col-md-3">
                                                                <label class="form-label mg-b-0">Status Processed</label>
                                                            </div>
                                                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                                {!! getStatusProcessed($datafile->processed) !!}
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    @if ($datafile->processed == 1)
                                        <a class="btn btn-success"
                                            href="{{ url('processverify') . '/' . $datafile->loan_app_no }}"><i
                                                class="la la-check-circle text-white"></i>&nbsp;Processed</a>
                                    @endif
                                    <!-- /row -->
                                </div>

                                <div class="tab-pane active" id="tab2">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <h6 class="card-title mb-1">List Dokumen -
                                                                {{ $datafile->produk }}
                                                            </h6>
                                                        </div>
                                                        <div>
                                                            <a class="btn btn-primary"
                                                                href="{{ route('datafile.downloadZip', $datafile->loan_app_no) }}">
                                                                Download All Document</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php
                                                    $kategoriIdsByJenis = [
                                                        '1' => [1, 2], // Untuk jenis_kategori 1, tampilkan kategori 1 dan 2
                                                        '2' => [1, 2, 3], // Untuk jenis_kategori 2, tampilkan kategori 1, 2, dan 3
                                                        '3' => [1, 2, 3, 4], // Untuk jenis_kategori 3, tampilkan kategori 1, 2, dan
                                                        4,
                                                    ];

                                                    // Ambil ID kategori yang sesuai dengan $jenis_kategori
                                                    if ($datafile->take_over == 1 && $datafile->early == 1) {
                                                        $jenis_kategori = '3';
                                                    } elseif ($datafile->take_over == 1) {
                                                        $jenis_kategori = '2';
                                                    } else {
                                                        $jenis_kategori = '1';
                                                    }

                                                    $kategoriIdsToShow = $kategoriIdsByJenis[$jenis_kategori] ?? [];

                                                    $jumlahdokumen_kupeg = 0;
                                                    $jumlahdokumen_kupen = 0;
                                                    $jumlahdokumen_kupenhybrid = 0;
                                                    $jumlahdokumen_mandatory_kupeg = 0;
                                                    $jumlahdokumen_mandatory_kupen = 0;
                                                    $jumlahdokumen_mandatory_kupenhybrid = 0;

                                                    $jumlahdokumen_wajib_kupeg = 0;
                                                    $jumlahdokumen_opsional_kupeg = 0;
                                                    $jumlahdokumen_wajib_kupen = 0;
                                                    $jumlahdokumen_opsional_kupen = 0;
                                                    $jumlahdokumen_wajib_kupenhybrid = 0;
                                                    $jumlahdokumen_opsional_kupenhybrid = 0;
                                                    $kategoriCounter = 0;
                                                @endphp

                                                <div class="row pd-10 pd-sm-10 bg-gray-100">
                                                    <div class="col-lg-12 col-md-12">
                                                        @foreach ($kategori as $kat)
                                                            @if (in_array($kat->id, $kategoriIdsToShow))
                                                                @php
                                                                    $kategoriCounter++;
                                                                    $dokumenCounter = 0;

                                                                    // Filter visible documents
                                                                    $visibleDokumen = $kat->dokumen->filter(function (
                                                                        $dokumen,
                                                                    ) use ($products, $datafile) {
                                                                        // First check product type compatibility
                                                                        $productMatch = (isKupeg($products->jenis_produk) &&
                                                                            $dokumen->kupeg) ||
                                                                            (isKupen($products->jenis_produk) &&
                                                                                $dokumen->kupen) ||
                                                                            (isKupenHybrid($products->jenis_produk) &&
                                                                                $dokumen->kupen_hybrid);

                                                                        // If product doesn't match, hide document
                                                                        if (!$productMatch) {
                                                                            return false;
                                                                        }

                                                                        // Filter documents ID 30, 31, 32 by role
                                                                        $restrictedDocIds = [30, 31, 32];
                                                                        $allowedRoles = ['staff', 'team_verifikator_lvl1', 'team_verifikator_lvl2'];
                                                                        $currentRole = Session('role');

                                                                        // If document is restricted and user role is not allowed, hide it
                                                                        if (in_array($dokumen->id, $restrictedDocIds) && !in_array($currentRole, $allowedRoles)) {
                                                                            return false;
                                                                        }

                                                                        // Filter document ID 29 (Akseptasi Persetujuan Mitra Asuransi) by date_input
                                                                        // Hide this document if loan date_input is before 2025-10-27 17:00:00
                                                                        if ($dokumen->id == 29 && $datafile->date_input) {
                                                                            $cutoffDate = \Carbon\Carbon::parse('2025-10-27 17:00:00');
                                                                            $loanDate = \Carbon\Carbon::parse($datafile->date_input);

                                                                            // If loan created before cutoff date, hide this document
                                                                            if ($loanDate->lt($cutoffDate)) {
                                                                                return false;
                                                                            }
                                                                        }

                                                                        return true;
                                                                    });

                                                                    // Split into two columns
                                                                    $halfCount = ceil($visibleDokumen->count() / 2);
                                                                    $leftColumnDocs = $visibleDokumen->take($halfCount);
                                                                    $rightColumnDocs = $visibleDokumen->slice(
                                                                        $halfCount,
                                                                    );
                                                                @endphp

                                                                <h3>{{ $kat->nama_kategori }}</h3>
                                                                <div class="row">
                                                                    <!-- Left Column -->
                                                                    <div class="col-md-6">
                                                                        @foreach ($leftColumnDocs as $dokumen)
                                                                            @php
                                                                                $dokumenCounter++;
                                                                                $subDokumenCounter = 0;
                                                                                $warna = get_warna_new(
                                                                                    $datafile->loan_app_no,
                                                                                    $dokumen->nama_dokumen,
                                                                                    $dokumen->label_only,
                                                                                );

                                                                                if (isKupeg($products->jenis_produk)) {
                                                                                    if (
                                                                                        $warna == 'error' ||
                                                                                        $warna == 'success'
                                                                                    ) {
                                                                                        $jumlahdokumen_kupeg++;
                                                                                    }
                                                                                    if ($warna == 'error') {
                                                                                        $jumlahdokumen_mandatory_kupeg++;
                                                                                    }
                                                                                } elseif (
                                                                                    isKupen($products->jenis_produk)
                                                                                ) {
                                                                                    if (
                                                                                        $warna == 'error' ||
                                                                                        $warna == 'success'
                                                                                    ) {
                                                                                        $jumlahdokumen_kupen++;
                                                                                    }
                                                                                    if ($warna == 'error') {
                                                                                        $jumlahdokumen_mandatory_kupen++;
                                                                                    }
                                                                                } elseif (
                                                                                    isKupenHybrid(
                                                                                        $products->jenis_produk,
                                                                                    )
                                                                                ) {
                                                                                    if (
                                                                                        $warna == 'error' ||
                                                                                        $warna == 'success'
                                                                                    ) {
                                                                                        $jumlahdokumen_kupenhybrid++;
                                                                                    }
                                                                                    if ($warna == 'error') {
                                                                                        $jumlahdokumen_mandatory_kupenhybrid++;
                                                                                    }
                                                                                }
                                                                            @endphp

                                                                            <div class="card mb-2">
                                                                                <div class="card-body">
                                                                                    <div aria-multiselectable="true"
                                                                                        class="accordion"
                                                                                        id="accordion-left-{{ $dokumen->id }}"
                                                                                        role="tablist">
                                                                                        <div class="d-flex flex-row justify-content-between align-items-center bg-gray-100">
                                                                                            <div class="pd-1 bg-gray-100">
                                                                                                <div class="card-header-{{ $warna }}"
                                                                                                    id="headingOne"
                                                                                                    role="tab">
                                                                                                    <a aria-controls="collapseOne{{ $dokumen->id }}"
                                                                                                        aria-expanded="true"
                                                                                                        data-toggle="collapse"
                                                                                                        href="#collapseOne{{ $dokumen->id }}">
                                                                                                        <span
                                                                                                            class="font-weight-bold">{{ $dokumenCounter }}.</span>

                                                                                                        @if (isKupeg($products->jenis_produk))
                                                                                                            @php
                                                                                                                $badge = getBadge(
                                                                                                                    $dokumen->is_mandatory_kupeg,
                                                                                                                    $dokumen->nama_dokumen,
                                                                                                                    $dokumen->label_only,
                                                                                                                    $datafile->status_pernikahan,
                                                                                                                    $datafile->status_deviasi,
                                                                                                                    $datafile->early,
                                                                                                                    $datafile->status_usia_debitur,
                                                                                                                    $datafile->status_fronting_agent,
                                                                                                                    $datafile->take_over,
                                                                                                                    $datafile->fasilitas,
                                                                                                                    $datafile->status_pegawai,
                                                                                                                    $datafile->date_input
                                                                                                                );
                                                                                                                if (
                                                                                                                    $warna !==
                                                                                                                    'success'
                                                                                                                ) {
                                                                                                                    if (
                                                                                                                        strpos(
                                                                                                                            $badge,
                                                                                                                            'badge-danger',
                                                                                                                        ) !==
                                                                                                                        false
                                                                                                                    ) {
                                                                                                                        $jumlahdokumen_wajib_kupeg++;
                                                                                                                    } elseif (
                                                                                                                        strpos(
                                                                                                                            $badge,
                                                                                                                            'badge-secondary',
                                                                                                                        ) !==
                                                                                                                        false
                                                                                                                    ) {
                                                                                                                        $jumlahdokumen_opsional_kupeg++;
                                                                                                                    }
                                                                                                                }
                                                                                                            @endphp
                                                                                                            {!! $badge !!}
                                                                                                        @elseif(isKupen($products->jenis_produk))
                                                                                                                @php
                                                                                                                    $badge = getBadge(
                                                                                                                        $dokumen->is_mandatory_kupen,
                                                                                                                        $dokumen->nama_dokumen,
                                                                                                                        $dokumen->label_only,
                                                                                                                        $datafile->status_pernikahan,
                                                                                                                        $datafile->status_deviasi,
                                                                                                                        $datafile->early,
                                                                                                                        $datafile->status_usia_debitur,
                                                                                                                        $datafile->status_fronting_agent,
                                                                                                                        $datafile->take_over,
                                                                                                                        $datafile->fasilitas,
                                                                                                                        $datafile->status_pegawai,
                                                                                                                        $datafile->date_input
                                                                                                                    );
                                                                                                                    if (
                                                                                                                        $warna !==
                                                                                                                        'success'
                                                                                                                    ) {
                                                                                                                        if (
                                                                                                                            strpos(
                                                                                                                                $badge,
                                                                                                                                'badge-danger',
                                                                                                                            ) !==
                                                                                                                            false
                                                                                                                        ) {
                                                                                                                            $jumlahdokumen_wajib_kupen++;
                                                                                                                        } elseif (
                                                                                                                            strpos(
                                                                                                                                $badge,
                                                                                                                                'badge-secondary',
                                                                                                                            ) !==
                                                                                                                            false
                                                                                                                        ) {
                                                                                                                            $jumlahdokumen_opsional_kupen++;
                                                                                                                        }
                                                                                                                    }
                                                                                                                @endphp
                                                                                                                {!! $badge !!}
                                                                                                            @elseif(isKupenHybrid($products->jenis_produk))
                                                                                                                @php
                                                                                                                    $badge = getBadge(
                                                                                                                        $dokumen->is_mandatory_kupen_hybrid,
                                                                                                                        $dokumen->nama_dokumen,
                                                                                                                        $dokumen->label_only,
                                                                                                                        $datafile->status_pernikahan,
                                                                                                                        $datafile->status_deviasi,
                                                                                                                        $datafile->early,
                                                                                                                        $datafile->status_usia_debitur,
                                                                                                                        $datafile->status_fronting_agent,
                                                                                                                        $datafile->take_over,
                                                                                                                        $datafile->fasilitas,
                                                                                                                        $datafile->status_pegawai,
                                                                                                                        $datafile->date_input
                                                                                                                    );
                                                                                                                    if (
                                                                                                                        $warna !==
                                                                                                                        'success'
                                                                                                                    ) {
                                                                                                                        if (
                                                                                                                            strpos(
                                                                                                                                $badge,
                                                                                                                                'badge-danger',
                                                                                                                            ) !==
                                                                                                                            false
                                                                                                                        ) {
                                                                                                                            $jumlahdokumen_wajib_kupenhybrid++;
                                                                                                                        } elseif (
                                                                                                                            strpos(
                                                                                                                                $badge,
                                                                                                                                'badge-secondary',
                                                                                                                            ) !==
                                                                                                                            false
                                                                                                                        ) {
                                                                                                                            $jumlahdokumen_opsional_kupenhybrid++;
                                                                                                                        }
                                                                                                                    }
                                                                                                                @endphp
                                                                                                                {!! $badge !!}
                                                                                                            @endif

                                                                                                        {{ $dokumen->nama_dokumen }}
                                                                                                    </a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                            @if (Session('role') == 'staff')
                                                                                            <div class="d-flex flex-row justify-content-between align-items-center bg-gray-100">
                                                                                                <div
                                                                                                    class="pd-1 bg-gray-100">
                                                                                                    @if ($dokumen->subDokumen->isEmpty())
                                                                                                        <div
                                                                                                            class="form-group-fileupload">
                                                                                                            <div
                                                                                                                class="input-group">
                                                                                                                @if (isKupeg($products->jenis_produk))
                                                                                                                    <input
                                                                                                                        type="file"
                                                                                                                        name="{{ $dokumen->nama_dokumen }}"
                                                                                                                        id="{{ $dokumen->nama_dokumen }}"
                                                                                                                        class="form-control"
                                                                                                                        {{ $dokumen->is_mandatory_kupeg ? 'required' : '' }}>
                                                                                                                @elseif(isKupen($products->jenis_produk))
                                                                                                                    <input
                                                                                                                        type="file"
                                                                                                                        name="{{ $dokumen->nama_dokumen }}"
                                                                                                                        id="{{ $dokumen->nama_dokumen }}"
                                                                                                                        class="form-control"
                                                                                                                        {{ $dokumen->is_mandatory_kupen ? 'required' : '' }}>
                                                                                                                @elseif(isKupenHybrid($products->jenis_produk))
                                                                                                                    <input
                                                                                                                        type="file"
                                                                                                                        name="{{ $dokumen->nama_dokumen }}"
                                                                                                                        id="{{ $dokumen->nama_dokumen }}"
                                                                                                                        class="form-control"
                                                                                                                        {{ $dokumen->is_mandatory_kupen_hybrid ? 'required' : '' }}>
                                                                                                                @endif
                                                                                                                <div
                                                                                                                    class="input-group-append">
                                                                                                                    <button
                                                                                                                        class="btn btn-primary upload-btn"
                                                                                                                        data-type="dokumen"
                                                                                                                        data-id="{{ $dokumen->id }}">Upload</button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div
                                                                                                                class="progress mt-2 d-none">
                                                                                                                <div class="progress-bar"
                                                                                                                    role="progressbar"
                                                                                                                    style="width: 0%">
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="alert mt-2 d-none"
                                                                                                                role="alert">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                            @endif
                                                                                        

                                                                                        <!-- File List Section -->
                                                                                        <div aria-labelledby="headingOne"
                                                                                            class="collapse"
                                                                                            data-parent="#accordion-left-{{ $dokumen->id }}"
                                                                                            id="collapseOne{{ $dokumen->id }}"
                                                                                            role="tabpanel">
                                                                                            <div
                                                                                                class="uploaded-files-container">
                                                                                                <ul
                                                                                                    class="uploaded-files-list">
                                                                                                    @php
                                                                                                        $matchingFiles = $detailfile->filter(
                                                                                                            function (
                                                                                                                $file,
                                                                                                            ) use (
                                                                                                                $dokumen,
                                                                                                            ) {
                                                                                                                return $file->alias ===
                                                                                                                    $dokumen->nama_dokumen;
                                                                                                            },
                                                                                                        );
                                                                                                    @endphp

                                                                                                    @foreach ($matchingFiles as $file)
                                                                                                        <li>
                                                                                                            <div
                                                                                                                class="d-flex flex-row justify-content-between bg-gray-100">
                                                                                                                <div
                                                                                                                    class="pd-1">
                                                                                                                    <a class="text-secondary"
                                                                                                                        data-toggle="modal"
                                                                                                                        id="mediumButton"
                                                                                                                        data-target="#mediumModal"
                                                                                                                        data-attr="{{ route('datafile.showpdf', $file->file) }}">
                                                                                                                        {{ getlastnamefile($file->file) }}
                                                                                                                    </a>
                                                                                                                    <a href="{{ asset('indexed' . $file->file) }}"
                                                                                                                        target="_blank">
                                                                                                                        <i
                                                                                                                            class="fas fa-external-link-alt"></i>
                                                                                                                    </a>
                                                                                                                    {!! getScore($file->loan_app_no, $file->alias, $file->file) !!}
                                                                                                                </div>
                                                                                                                @if (checkCanDelete($datafile->loan_app_no))
                                                                                                                    <a href="deletedetailfile/{{ $datafile->loan_app_no }}/{{ $file->id }}"
                                                                                                                        target="_self">
                                                                                                                        <div
                                                                                                                            class="pd-1 bg-danger">
                                                                                                                            <span
                                                                                                                                style="color:white">X</span>
                                                                                                                        </div>
                                                                                                                    </a>
                                                                                                                @endif
                                                                                                            </div>
                                                                                                        </li>
                                                                                                    @endforeach
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- Sub Documents Section -->
                                                                                    @if ($dokumen->subDokumen->isNotEmpty())
                                                                                        @foreach ($dokumen->subDokumen as $subDokumen)
                                                                                            @php
                                                                                                $tampilkanSubDokumen =
                                                                                                    (isKupeg(
                                                                                                        $products->jenis_produk,
                                                                                                    ) &&
                                                                                                        $subDokumen->kupeg) ||
                                                                                                    (isKupen(
                                                                                                        $products->jenis_produk,
                                                                                                    ) &&
                                                                                                        $subDokumen->kupen) ||
                                                                                                    (isKupenHybrid(
                                                                                                        $products->jenis_produk,
                                                                                                    ) &&
                                                                                                        $subDokumen->kupen_hybrid);

                                                                                                if (
                                                                                                    $tampilkanSubDokumen
                                                                                                ) {
                                                                                                    $subDokumenCounter++;
                                                                                                    $warnasub = get_warna_new(
                                                                                                        $datafile->loan_app_no,
                                                                                                        $subDokumen->nama_sub_dokumen,
                                                                                                        '0',
                                                                                                    );
                                                                                                }
                                                                                            @endphp

                                                                                            @if ($tampilkanSubDokumen)
                                                                                                <div class="ml-4">
                                                                                                    <div
                                                                                                        class="form-group">
                                                                                                        <div aria-multiselectable="true"
                                                                                                            class="accordion"
                                                                                                            id="accordionSubdokumen-left-{{ $subDokumen->id }}"
                                                                                                            role="tablist">
                                                                                                            <div class="d-flex flex-row justify-content-between bg-gray-100">
                                                                                                                <div
                                                                                                                    class="pd-1 bg-gray-100">
                                                                                                                    <div class="card-header-{{ $warnasub }}"
                                                                                                                        id="headingSubDoc"
                                                                                                                        role="tab">
                                                                                                                        <a aria-controls="collapseTwo{{ $subDokumen->id }}"
                                                                                                                            aria-expanded="true"
                                                                                                                            data-toggle="collapse"
                                                                                                                            href="#collapseTwo{{ $subDokumen->id }}">
                                                                                                                            <span
                                                                                                                                class="font-weight-bold">{{ $dokumenCounter }}.{{ $subDokumenCounter }}.</span>
                                                                                                                            @if (isKupeg($products->jenis_produk))
                                                                                                                                @php
                                                                                                                                    $badge = getBadge(
                                                                                                                                        $subDokumen->is_mandatory_kupeg,
                                                                                                                                        $subDokumen->nama_sub_dokumen,
                                                                                                                                        0,
                                                                                                                                        $datafile->status_pernikahan,
                                                                                                                                        $datafile->status_deviasi,
                                                                                                                                        $datafile->early,
                                                                                                                                        $datafile->status_usia_debitur,
                                                                                                                                        $datafile->status_fronting_agent,
                                                                                                                                        $datafile->take_over,
                                                                                                                                        $datafile->fasilitas,
                                                                                                                                        $datafile->status_pegawai,
                                                                                                                                        $datafile->date_input
                                                                                                                                    );
                                                                                                                                    if (
                                                                                                                                        $warnasub !==
                                                                                                                                        'success'
                                                                                                                                    ) {
                                                                                                                                        if (
                                                                                                                                            strpos(
                                                                                                                                                $badge,
                                                                                                                                                'badge-danger',
                                                                                                                                            ) !==
                                                                                                                                            false
                                                                                                                                        ) {
                                                                                                                                            $jumlahdokumen_wajib_kupeg++;
                                                                                                                                        } elseif (
                                                                                                                                            strpos(
                                                                                                                                                $badge,
                                                                                                                                                'badge-secondary',
                                                                                                                                            ) !==
                                                                                                                                            false
                                                                                                                                        ) {
                                                                                                                                            $jumlahdokumen_opsional_kupeg++;
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                @endphp
                                                                                                                                {!! $badge !!}
                                                                                                                            @elseif(isKupen($products->jenis_produk))
                                                                                                                                @php
                                                                                                                                    $badge = getBadge(
                                                                                                                                        $subDokumen->is_mandatory_kupen,
                                                                                                                                        $subDokumen->nama_sub_dokumen,
                                                                                                                                        0,
                                                                                                                                        $datafile->status_pernikahan,
                                                                                                                                        $datafile->status_deviasi,
                                                                                                                                        $datafile->early,
                                                                                                                                        $datafile->status_usia_debitur,
                                                                                                                                        $datafile->status_fronting_agent,
                                                                                                                                        $datafile->take_over,
                                                                                                                                        $datafile->fasilitas,
                                                                                                                                        $datafile->status_pegawai,
                                                                                                                                        $datafile->date_input
                                                                                                                                    );
                                                                                                                                    if (
                                                                                                                                        $warnasub !==
                                                                                                                                        'success'
                                                                                                                                    ) {
                                                                                                                                        if (
                                                                                                                                            strpos(
                                                                                                                                                $badge,
                                                                                                                                                'badge-danger',
                                                                                                                                            ) !==
                                                                                                                                            false
                                                                                                                                        ) {
                                                                                                                                            $jumlahdokumen_wajib_kupen++;
                                                                                                                                        } elseif (
                                                                                                                                            strpos(
                                                                                                                                                $badge,
                                                                                                                                                'badge-secondary',
                                                                                                                                            ) !==
                                                                                                                                            false
                                                                                                                                        ) {
                                                                                                                                            $jumlahdokumen_opsional_kupen++;
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                @endphp
                                                                                                                                {!! $badge !!}
                                                                                                                            @elseif(isKupenHybrid($products->jenis_produk))
                                                                                                                                @php
                                                                                                                                    $badge = getBadge(
                                                                                                                                        $subDokumen->is_mandatory_kupen_hybrid,
                                                                                                                                        $subDokumen->nama_sub_dokumen,
                                                                                                                                        0,
                                                                                                                                        $datafile->status_pernikahan,
                                                                                                                                        $datafile->status_deviasi,
                                                                                                                                        $datafile->early,
                                                                                                                                        $datafile->status_usia_debitur,
                                                                                                                                        $datafile->status_fronting_agent,
                                                                                                                                        $datafile->take_over,
                                                                                                                                        $datafile->fasilitas,
                                                                                                                                        $datafile->status_pegawai,
                                                                                                                                        $datafile->date_input
                                                                                                                                    );
                                                                                                                                    if (
                                                                                                                                        $warnasub !==
                                                                                                                                        'success'
                                                                                                                                    ) {
                                                                                                                                        if (
                                                                                                                                            strpos(
                                                                                                                                                $badge,
                                                                                                                                                'badge-danger',
                                                                                                                                            ) !==
                                                                                                                                            false
                                                                                                                                        ) {
                                                                                                                                            $jumlahdokumen_wajib_kupenhybrid++;
                                                                                                                                        } elseif (
                                                                                                                                            strpos(
                                                                                                                                                $badge,
                                                                                                                                                'badge-secondary',
                                                                                                                                            ) !==
                                                                                                                                            false
                                                                                                                                        ) {
                                                                                                                                            $jumlahdokumen_opsional_kupenhybrid++;
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                @endphp
                                                                                                                                {!! $badge !!}
                                                                                                                            @endif

                                                                                                                            {{ $subDokumen->nama_sub_dokumen }}
                                                                                                                        </a>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                                @if (Session('role') == 'staff')
                                                                                                                <div class="d-flex flex-row justify-content-between bg-gray-100">
                                                                                                                    <div
                                                                                                                        class="pd-1 bg-gray-100">
                                                                                                                        
                                                                                                                            <div
                                                                                                                                class="form-group-fileupload">
                                                                                                                                <div
                                                                                                                                    class="input-group">
                                                                                                                                        @if(isKupeg($products->jenis_produk))
                                                                                                                                            
                                                                                                                                            <input type="file" name="{{ $subDokumen->nama_sub_dokumen }}" id="{{ $subDokumen->nama_sub_dokumen }}" class="form-control" {{ $subDokumen->is_mandatory_kupeg ? 'required' : '' }}>
                                                                                                                                        
                                                                                                                                        @elseif(isKupen($products->jenis_produk))
                                                                                                                                        
                                                                                                                                            <input type="file" name="{{ $subDokumen->nama_sub_dokumen }}" id="{{ $subDokumen->nama_sub_dokumen }}" class="form-control" {{ $subDokumen->is_mandatory_kupen ? 'required' : '' }}>
                                                                                                                                        @elseif(isKupenHybrid($products->jenis_produk))
                                                                                                                                        
                                                                                                                                        <input type="file" name="{{ $subDokumen->nama_sub_dokumen }}" id="{{ $subDokumen->nama_sub_dokumen }}" class="form-control" {{ $subDokumen->is_mandatory_kupen_hybrid ? 'required' : '' }}>
                                                                                                                                    
                                                                                                                                        @endif
                                                                                                                                        <div class="input-group-append">
                                                                                                                                            <button class="btn btn-primary upload-btn" data-type="sub_dokumen" data-id="{{ $subDokumen->id }}">Upload</button>
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div
                                                                                                                                    class="progress mt-2 d-none">
                                                                                                                                    <div class="progress-bar"
                                                                                                                                        role="progressbar"
                                                                                                                                        style="width: 0%">
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div class="alert mt-2 d-none"
                                                                                                                                    role="alert">
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                @endif
                                                                                                            

                                                                                                            <div aria-labelledby="headingSubDoc"
                                                                                                                class="collapse"
                                                                                                                data-parent="#accordionSubdokumen-left-{{ $subDokumen->id }}"
                                                                                                                id="collapseTwo{{ $subDokumen->id }}"
                                                                                                                role="tabpanel">
                                                                                                                <div
                                                                                                                    class="uploaded-files-container">
                                                                                                                    <ul
                                                                                                                        class="uploaded-files-list">
                                                                                                                        @php
                                                                                                                            $matchingFiles = $detailfile->filter(
                                                                                                                                function (
                                                                                                                                    $file,
                                                                                                                                ) use (
                                                                                                                                    $subDokumen,
                                                                                                                                ) {
                                                                                                                                    return $file->alias ===
                                                                                                                                        $subDokumen->nama_sub_dokumen;
                                                                                                                                },
                                                                                                                            );
                                                                                                                        @endphp

                                                                                                                        @foreach ($matchingFiles as $file)
                                                                                                                            <li>
                                                                                                                                <div
                                                                                                                                    class="d-flex flex-row justify-content-between bg-gray-100">
                                                                                                                                    <div
                                                                                                                                        class="pd-1">
                                                                                                                                        <a class="text-secondary"
                                                                                                                                            data-toggle="modal"
                                                                                                                                            id="mediumButton"
                                                                                                                                            data-target="#mediumModal"
                                                                                                                                            data-attr="{{ route('datafile.showpdf', $file->file) }}">
                                                                                                                                            {{ getlastnamefile($file->file) }}
                                                                                                                                        </a>
                                                                                                                                        <a href="{{ asset('indexed' . $file->file) }}"
                                                                                                                                            target="_blank">
                                                                                                                                            <i
                                                                                                                                                class="fas fa-external-link-alt"></i>
                                                                                                                                        </a>
                                                                                                                                        {!! getScore($file->loan_app_no, $file->alias, $file->file) !!}
                                                                                                                                    </div>
                                                                                                                                    @if (checkCanDelete($datafile->loan_app_no))
                                                                                                                                        <a href="deletedetailfile/{{ $datafile->loan_app_no }}/{{ $file->id }}"
                                                                                                                                            target="_self">
                                                                                                                                            <div
                                                                                                                                                class="pd-1 bg-danger">
                                                                                                                                                <span
                                                                                                                                                    style="color:white">X</span>
                                                                                                                                            </div>
                                                                                                                                        </a>
                                                                                                                                    @endif
                                                                                                                                </div>
                                                                                                                            </li>
                                                                                                                        @endforeach
                                                                                                                    </ul>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>

                                                                    <!-- Right Column -->
                                                                    <div class="col-md-6">
                                                                        @foreach ($rightColumnDocs as $dokumen)
                                                                            @php
                                                                                $dokumenCounter++;
                                                                                $subDokumenCounter = 0;
                                                                                $warna = get_warna_new(
                                                                                    $datafile->loan_app_no,
                                                                                    $dokumen->nama_dokumen,
                                                                                    $dokumen->label_only,
                                                                                );

                                                                                if (isKupeg($products->jenis_produk)) {
                                                                                    if (
                                                                                        $warna == 'error' ||
                                                                                        $warna == 'success'
                                                                                    ) {
                                                                                        $jumlahdokumen_kupeg++;
                                                                                    }
                                                                                    if ($warna == 'error') {
                                                                                        $jumlahdokumen_mandatory_kupeg++;
                                                                                    }
                                                                                } elseif (
                                                                                    isKupen($products->jenis_produk)
                                                                                ) {
                                                                                    if (
                                                                                        $warna == 'error' ||
                                                                                        $warna == 'success'
                                                                                    ) {
                                                                                        $jumlahdokumen_kupen++;
                                                                                    }
                                                                                    if ($warna == 'error') {
                                                                                        $jumlahdokumen_mandatory_kupen++;
                                                                                    }
                                                                                } elseif (
                                                                                    isKupenHybrid(
                                                                                        $products->jenis_produk,
                                                                                    )
                                                                                ) {
                                                                                    if (
                                                                                        $warna == 'error' ||
                                                                                        $warna == 'success'
                                                                                    ) {
                                                                                        $jumlahdokumen_kupenhybrid++;
                                                                                    }
                                                                                    if ($warna == 'error') {
                                                                                        $jumlahdokumen_mandatory_kupenhybrid++;
                                                                                    }
                                                                                }
                                                                            @endphp

                                                                            <div class="card mb-2">
                                                                                <div class="card-body">
                                                                                    <div aria-multiselectable="true"
                                                                                        class="accordion"
                                                                                        id="accordion-left-{{ $dokumen->id }}"
                                                                                        role="tablist">
                                                                                        <div class="d-flex flex-row justify-content-between align-items-center bg-gray-100">
                                                                                            <div class="pd-1 bg-gray-100">
                                                                                                <div class="card-header-{{ $warna }}"
                                                                                                    id="headingOne"
                                                                                                    role="tab">
                                                                                                    <a aria-controls="collapseOne{{ $dokumen->id }}"
                                                                                                        aria-expanded="true"
                                                                                                        data-toggle="collapse"
                                                                                                        href="#collapseOne{{ $dokumen->id }}">
                                                                                                        <span
                                                                                                            class="font-weight-bold">{{ $dokumenCounter }}.</span>

                                                                                                        @if (isKupeg($products->jenis_produk))
                                                                                                            @php
                                                                                                                $badge = getBadge(
                                                                                                                    $dokumen->is_mandatory_kupeg,
                                                                                                                    $dokumen->nama_dokumen,
                                                                                                                    $dokumen->label_only,
                                                                                                                    $datafile->status_pernikahan,
                                                                                                                    $datafile->status_deviasi,
                                                                                                                    $datafile->early,
                                                                                                                    $datafile->status_usia_debitur,
                                                                                                                    $datafile->status_fronting_agent,
                                                                                                                    $datafile->take_over,
                                                                                                                    $datafile->fasilitas,
                                                                                                                    $datafile->status_pegawai,
                                                                                                                    $datafile->date_input
                                                                                                                );
                                                                                                                if (
                                                                                                                    $warna !==
                                                                                                                    'success'
                                                                                                                ) {
                                                                                                                    if (
                                                                                                                        strpos(
                                                                                                                            $badge,
                                                                                                                            'badge-danger',
                                                                                                                        ) !==
                                                                                                                        false
                                                                                                                    ) {
                                                                                                                        $jumlahdokumen_wajib_kupeg++;
                                                                                                                    } elseif (
                                                                                                                        strpos(
                                                                                                                            $badge,
                                                                                                                            'badge-secondary',
                                                                                                                        ) !==
                                                                                                                        false
                                                                                                                    ) {
                                                                                                                        $jumlahdokumen_opsional_kupeg++;
                                                                                                                    }
                                                                                                                }
                                                                                                            @endphp
                                                                                                            {!! $badge !!}
                                                                                                        @elseif(isKupen($products->jenis_produk))
                                                                                                                @php
                                                                                                                    $badge = getBadge(
                                                                                                                        $dokumen->is_mandatory_kupen,
                                                                                                                        $dokumen->nama_dokumen,
                                                                                                                        $dokumen->label_only,
                                                                                                                        $datafile->status_pernikahan,
                                                                                                                        $datafile->status_deviasi,
                                                                                                                        $datafile->early,
                                                                                                                        $datafile->status_usia_debitur,
                                                                                                                        $datafile->status_fronting_agent,
                                                                                                                        $datafile->take_over,
                                                                                                                        $datafile->fasilitas,
                                                                                                                        $datafile->status_pegawai,
                                                                                                                        $datafile->date_input
                                                                                                                    );
                                                                                                                    if (
                                                                                                                        $warna !==
                                                                                                                        'success'
                                                                                                                    ) {
                                                                                                                        if (
                                                                                                                            strpos(
                                                                                                                                $badge,
                                                                                                                                'badge-danger',
                                                                                                                            ) !==
                                                                                                                            false
                                                                                                                        ) {
                                                                                                                            $jumlahdokumen_wajib_kupen++;
                                                                                                                        } elseif (
                                                                                                                            strpos(
                                                                                                                                $badge,
                                                                                                                                'badge-secondary',
                                                                                                                            ) !==
                                                                                                                            false
                                                                                                                        ) {
                                                                                                                            $jumlahdokumen_opsional_kupen++;
                                                                                                                        }
                                                                                                                    }
                                                                                                                @endphp
                                                                                                                {!! $badge !!}
                                                                                                            @elseif(isKupenHybrid($products->jenis_produk))
                                                                                                                @php
                                                                                                                    $badge = getBadge(
                                                                                                                        $dokumen->is_mandatory_kupen_hybrid,
                                                                                                                        $dokumen->nama_dokumen,
                                                                                                                        $dokumen->label_only,
                                                                                                                        $datafile->status_pernikahan,
                                                                                                                        $datafile->status_deviasi,
                                                                                                                        $datafile->early,
                                                                                                                        $datafile->status_usia_debitur,
                                                                                                                        $datafile->status_fronting_agent,
                                                                                                                        $datafile->take_over,
                                                                                                                        $datafile->fasilitas,
                                                                                                                        $datafile->status_pegawai,
                                                                                                                        $datafile->date_input
                                                                                                                    );
                                                                                                                    if (
                                                                                                                        $warna !==
                                                                                                                        'success'
                                                                                                                    ) {
                                                                                                                        if (
                                                                                                                            strpos(
                                                                                                                                $badge,
                                                                                                                                'badge-danger',
                                                                                                                            ) !==
                                                                                                                            false
                                                                                                                        ) {
                                                                                                                            $jumlahdokumen_wajib_kupenhybrid++;
                                                                                                                        } elseif (
                                                                                                                            strpos(
                                                                                                                                $badge,
                                                                                                                                'badge-secondary',
                                                                                                                            ) !==
                                                                                                                            false
                                                                                                                        ) {
                                                                                                                            $jumlahdokumen_opsional_kupenhybrid++;
                                                                                                                        }
                                                                                                                    }
                                                                                                                @endphp
                                                                                                                {!! $badge !!}
                                                                                                            @endif
                                                                                                        {{ $dokumen->nama_dokumen }}
                                                                                                    </a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                            @if (Session('role') == 'staff')
                                                                                            <div class="d-flex flex-row justify-content-between align-items-center bg-gray-100">
                                                                                                <div
                                                                                                    class="pd-1 bg-gray-100">
                                                                                                    @if ($dokumen->subDokumen->isEmpty())
                                                                                                        <div
                                                                                                            class="form-group-fileupload">
                                                                                                            <div
                                                                                                                class="input-group">
                                                                                                                @if (isKupeg($products->jenis_produk))
                                                                                                                    <input
                                                                                                                        type="file"
                                                                                                                        name="{{ $dokumen->nama_dokumen }}"
                                                                                                                        id="{{ $dokumen->nama_dokumen }}"
                                                                                                                        class="form-control"
                                                                                                                        {{ $dokumen->is_mandatory_kupeg ? 'required' : '' }}>
                                                                                                                @elseif(isKupen($products->jenis_produk))
                                                                                                                    <input
                                                                                                                        type="file"
                                                                                                                        name="{{ $dokumen->nama_dokumen }}"
                                                                                                                        id="{{ $dokumen->nama_dokumen }}"
                                                                                                                        class="form-control"
                                                                                                                        {{ $dokumen->is_mandatory_kupen ? 'required' : '' }}>
                                                                                                                @elseif(isKupenHybrid($products->jenis_produk))
                                                                                                                    <input
                                                                                                                        type="file"
                                                                                                                        name="{{ $dokumen->nama_dokumen }}"
                                                                                                                        id="{{ $dokumen->nama_dokumen }}"
                                                                                                                        class="form-control"
                                                                                                                        {{ $dokumen->is_mandatory_kupen_hybrid ? 'required' : '' }}>
                                                                                                                @endif
                                                                                                                <div
                                                                                                                    class="input-group-append">
                                                                                                                    <button
                                                                                                                        class="btn btn-primary upload-btn"
                                                                                                                        data-type="dokumen"
                                                                                                                        data-id="{{ $dokumen->id }}">Upload</button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div
                                                                                                                class="progress mt-2 d-none">
                                                                                                                <div class="progress-bar"
                                                                                                                    role="progressbar"
                                                                                                                    style="width: 0%">
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="alert mt-2 d-none"
                                                                                                                role="alert">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                            @endif
                                                                                        

                                                                                        <!-- File List Section -->
                                                                                        <div aria-labelledby="headingOne"
                                                                                            class="collapse"
                                                                                            data-parent="#accordion-left-{{ $dokumen->id }}"
                                                                                            id="collapseOne{{ $dokumen->id }}"
                                                                                            role="tabpanel">
                                                                                            <div
                                                                                                class="uploaded-files-container">
                                                                                                <ul
                                                                                                    class="uploaded-files-list">
                                                                                                    @php
                                                                                                        $matchingFiles = $detailfile->filter(
                                                                                                            function (
                                                                                                                $file,
                                                                                                            ) use (
                                                                                                                $dokumen,
                                                                                                            ) {
                                                                                                                return $file->alias ===
                                                                                                                    $dokumen->nama_dokumen;
                                                                                                            },
                                                                                                        );
                                                                                                    @endphp

                                                                                                    @foreach ($matchingFiles as $file)
                                                                                                        <li>
                                                                                                            <div
                                                                                                                class="d-flex flex-row justify-content-between bg-gray-100">
                                                                                                                <div
                                                                                                                    class="pd-1">
                                                                                                                    <a class="text-secondary"
                                                                                                                        data-toggle="modal"
                                                                                                                        id="mediumButton"
                                                                                                                        data-target="#mediumModal"
                                                                                                                        data-attr="{{ route('datafile.showpdf', $file->file) }}">
                                                                                                                        {{ getlastnamefile($file->file) }}
                                                                                                                    </a>
                                                                                                                    <a href="{{ asset('indexed' . $file->file) }}"
                                                                                                                        target="_blank">
                                                                                                                        <i
                                                                                                                            class="fas fa-external-link-alt"></i>
                                                                                                                    </a>
                                                                                                                    {!! getScore($file->loan_app_no, $file->alias, $file->file) !!}
                                                                                                                </div>
                                                                                                                @if (checkCanDelete($datafile->loan_app_no))
                                                                                                                    <a href="deletedetailfile/{{ $datafile->loan_app_no }}/{{ $file->id }}"
                                                                                                                        target="_self">
                                                                                                                        <div
                                                                                                                            class="pd-1 bg-danger">
                                                                                                                            <span
                                                                                                                                style="color:white">X</span>
                                                                                                                        </div>
                                                                                                                    </a>
                                                                                                                @endif
                                                                                                            </div>
                                                                                                        </li>
                                                                                                    @endforeach
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- Sub Documents Section -->
                                                                                    @if ($dokumen->subDokumen->isNotEmpty())
                                                                                        @foreach ($dokumen->subDokumen as $subDokumen)
                                                                                            @php
                                                                                                $tampilkanSubDokumen =
                                                                                                    (isKupeg(
                                                                                                        $products->jenis_produk,
                                                                                                    ) &&
                                                                                                        $subDokumen->kupeg) ||
                                                                                                    (isKupen(
                                                                                                        $products->jenis_produk,
                                                                                                    ) &&
                                                                                                        $subDokumen->kupen) ||
                                                                                                    (isKupenHybrid(
                                                                                                        $products->jenis_produk,
                                                                                                    ) &&
                                                                                                        $subDokumen->kupen_hybrid);

                                                                                                if (
                                                                                                    $tampilkanSubDokumen
                                                                                                ) {
                                                                                                    $subDokumenCounter++;
                                                                                                    $warnasub = get_warna_new(
                                                                                                        $datafile->loan_app_no,
                                                                                                        $subDokumen->nama_sub_dokumen,
                                                                                                        '0',
                                                                                                    );
                                                                                                }
                                                                                            @endphp

                                                                                            @if ($tampilkanSubDokumen)
                                                                                                <div class="ml-4">
                                                                                                    <div
                                                                                                        class="form-group">
                                                                                                        <div aria-multiselectable="true"
                                                                                                            class="accordion"
                                                                                                            id="accordionSubdokumen-left-{{ $subDokumen->id }}"
                                                                                                            role="tablist">
                                                                                                            <div class="d-flex flex-row justify-content-between bg-gray-100">
                                                                                                                <div class="pd-1 bg-gray-100">
                                                                                                                    <div class="card-header-{{ $warnasub }}"
                                                                                                                        id="headingSubDoc"
                                                                                                                        role="tab">
                                                                                                                        <a aria-controls="collapseTwo{{ $subDokumen->id }}"
                                                                                                                            aria-expanded="true"
                                                                                                                            data-toggle="collapse"
                                                                                                                            href="#collapseTwo{{ $subDokumen->id }}">
                                                                                                                            <span
                                                                                                                                class="font-weight-bold">{{ $dokumenCounter }}.{{ $subDokumenCounter }}.</span>
                                                                                                                            @if (isKupeg($products->jenis_produk))
                                                                                                                                @php
                                                                                                                                    $badge = getBadge(
                                                                                                                                        $subDokumen->is_mandatory_kupeg,
                                                                                                                                        $subDokumen->nama_sub_dokumen,
                                                                                                                                        0,
                                                                                                                                        $datafile->status_pernikahan,
                                                                                                                                        $datafile->status_deviasi,
                                                                                                                                        $datafile->early,
                                                                                                                                        $datafile->status_usia_debitur,
                                                                                                                                        $datafile->status_fronting_agent,
                                                                                                                                        $datafile->take_over,
                                                                                                                                        $datafile->fasilitas,
                                                                                                                                        $datafile->status_pegawai,
                                                                                                                                        $datafile->date_input
                                                                                                                                    );
                                                                                                                                    if (
                                                                                                                                        $warnasub !==
                                                                                                                                        'success'
                                                                                                                                    ) {
                                                                                                                                        if (
                                                                                                                                            strpos(
                                                                                                                                                $badge,
                                                                                                                                                'badge-danger',
                                                                                                                                            ) !==
                                                                                                                                            false
                                                                                                                                        ) {
                                                                                                                                            $jumlahdokumen_wajib_kupeg++;
                                                                                                                                        } elseif (
                                                                                                                                            strpos(
                                                                                                                                                $badge,
                                                                                                                                                'badge-secondary',
                                                                                                                                            ) !==
                                                                                                                                            false
                                                                                                                                        ) {
                                                                                                                                            $jumlahdokumen_opsional_kupeg++;
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                @endphp
                                                                                                                                {!! $badge !!}
                                                                                                                            @elseif(isKupen($products->jenis_produk))
                                                                                                                                @php
                                                                                                                                    $badge = getBadge(
                                                                                                                                        $subDokumen->is_mandatory_kupen,
                                                                                                                                        $subDokumen->nama_sub_dokumen,
                                                                                                                                        0,
                                                                                                                                        $datafile->status_pernikahan,
                                                                                                                                        $datafile->status_deviasi,
                                                                                                                                        $datafile->early,
                                                                                                                                        $datafile->status_usia_debitur,
                                                                                                                                        $datafile->status_fronting_agent,
                                                                                                                                        $datafile->take_over,
                                                                                                                                        $datafile->fasilitas,
                                                                                                                                        $datafile->status_pegawai,
                                                                                                                                        $datafile->date_input
                                                                                                                                    );
                                                                                                                                    if (
                                                                                                                                        $warnasub !==
                                                                                                                                        'success'
                                                                                                                                    ) {
                                                                                                                                        if (
                                                                                                                                            strpos(
                                                                                                                                                $badge,
                                                                                                                                                'badge-danger',
                                                                                                                                            ) !==
                                                                                                                                            false
                                                                                                                                        ) {
                                                                                                                                            $jumlahdokumen_wajib_kupen++;
                                                                                                                                        } elseif (
                                                                                                                                            strpos(
                                                                                                                                                $badge,
                                                                                                                                                'badge-secondary',
                                                                                                                                            ) !==
                                                                                                                                            false
                                                                                                                                        ) {
                                                                                                                                            $jumlahdokumen_opsional_kupen++;
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                @endphp
                                                                                                                                {!! $badge !!}
                                                                                                                            @elseif(isKupenHybrid($products->jenis_produk))
                                                                                                                                @php
                                                                                                                                    $badge = getBadge(
                                                                                                                                        $subDokumen->is_mandatory_kupen_hybrid,
                                                                                                                                        $subDokumen->nama_sub_dokumen,
                                                                                                                                        0,
                                                                                                                                        $datafile->status_pernikahan,
                                                                                                                                        $datafile->status_deviasi,
                                                                                                                                        $datafile->early,
                                                                                                                                        $datafile->status_usia_debitur,
                                                                                                                                        $datafile->status_fronting_agent,
                                                                                                                                        $datafile->take_over,
                                                                                                                                        $datafile->fasilitas,
                                                                                                                                        $datafile->status_pegawai,
                                                                                                                                        $datafile->date_input
                                                                                                                                    );
                                                                                                                                    if (
                                                                                                                                        $warnasub !==
                                                                                                                                        'success'
                                                                                                                                    ) {
                                                                                                                                        if (
                                                                                                                                            strpos(
                                                                                                                                                $badge,
                                                                                                                                                'badge-danger',
                                                                                                                                            ) !==
                                                                                                                                            false
                                                                                                                                        ) {
                                                                                                                                            $jumlahdokumen_wajib_kupenhybrid++;
                                                                                                                                        } elseif (
                                                                                                                                            strpos(
                                                                                                                                                $badge,
                                                                                                                                                'badge-secondary',
                                                                                                                                            ) !==
                                                                                                                                            false
                                                                                                                                        ) {
                                                                                                                                            $jumlahdokumen_opsional_kupenhybrid++;
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                @endphp
                                                                                                                                {!! $badge !!}
                                                                                                                            @endif

                                                                                                                            {{ $subDokumen->nama_sub_dokumen }}
                                                                                                                        </a>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                                @if (Session('role') == 'staff')
                                                                                                                <div class="d-flex flex-row justify-content-between bg-gray-100">
                                                                                                                    <div class="pd-1 bg-gray-100">
                                                                                                                        
                                                                                                                            <div
                                                                                                                                class="form-group-fileupload">
                                                                                                                                <div
                                                                                                                                    class="input-group">
                                                                                                                                    @if(isKupeg($products->jenis_produk))
                                                                                                                                            
                                                                                                                                            <input type="file" name="{{ $subDokumen->nama_sub_dokumen }}" id="{{ $subDokumen->nama_sub_dokumen }}" class="form-control" {{ $subDokumen->is_mandatory_kupeg ? 'required' : '' }}>
                                                                                                                                        
                                                                                                                                        @elseif(isKupen($products->jenis_produk))
                                                                                                                                        
                                                                                                                                            <input type="file" name="{{ $subDokumen->nama_sub_dokumen }}" id="{{ $subDokumen->nama_sub_dokumen }}" class="form-control" {{ $subDokumen->is_mandatory_kupen ? 'required' : '' }}>
                                                                                                                                        @elseif(isKupenHybrid($products->jenis_produk))
                                                                                                                                        
                                                                                                                                        <input type="file" name="{{ $subDokumen->nama_sub_dokumen }}" id="{{ $subDokumen->nama_sub_dokumen }}" class="form-control" {{ $subDokumen->is_mandatory_kupen_hybrid ? 'required' : '' }}>
                                                                                                                                    
                                                                                                                                        @endif
                                                                                                                                        <div class="input-group-append">
                                                                                                                                            <button class="btn btn-primary upload-btn" data-type="sub_dokumen" data-id="{{ $subDokumen->id }}">Upload</button>
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div
                                                                                                                                    class="progress mt-2 d-none">
                                                                                                                                    <div class="progress-bar"
                                                                                                                                        role="progressbar"
                                                                                                                                        style="width: 0%">
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div class="alert mt-2 d-none"
                                                                                                                                    role="alert">
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                @endif
                                                                                                            

                                                                                                            <div aria-labelledby="headingSubDoc"
                                                                                                                class="collapse"
                                                                                                                data-parent="#accordionSubdokumen-left-{{ $subDokumen->id }}"
                                                                                                                id="collapseTwo{{ $subDokumen->id }}"
                                                                                                                role="tabpanel">
                                                                                                                <div
                                                                                                                    class="uploaded-files-container">
                                                                                                                    <ul
                                                                                                                        class="uploaded-files-list">
                                                                                                                        @php
                                                                                                                            $matchingFiles = $detailfile->filter(
                                                                                                                                function (
                                                                                                                                    $file,
                                                                                                                                ) use (
                                                                                                                                    $subDokumen,
                                                                                                                                ) {
                                                                                                                                    return $file->alias ===
                                                                                                                                        $subDokumen->nama_sub_dokumen;
                                                                                                                                },
                                                                                                                            );
                                                                                                                        @endphp

                                                                                                                        @foreach ($matchingFiles as $file)
                                                                                                                            <li>
                                                                                                                                <div
                                                                                                                                    class="d-flex flex-row justify-content-between bg-gray-100">
                                                                                                                                    <div
                                                                                                                                        class="pd-1">
                                                                                                                                        <a class="text-secondary"
                                                                                                                                            data-toggle="modal"
                                                                                                                                            id="mediumButton"
                                                                                                                                            data-target="#mediumModal"
                                                                                                                                            data-attr="{{ route('datafile.showpdf', $file->file) }}">
                                                                                                                                            {{ getlastnamefile($file->file) }}
                                                                                                                                        </a>
                                                                                                                                        <a href="{{ asset('indexed' . $file->file) }}"
                                                                                                                                            target="_blank">
                                                                                                                                            <i
                                                                                                                                                class="fas fa-external-link-alt"></i>
                                                                                                                                        </a>
                                                                                                                                        {!! getScore($file->loan_app_no, $file->alias, $file->file) !!}
                                                                                                                                    </div>
                                                                                                                                    @if (checkCanDelete($datafile->loan_app_no))
                                                                                                                                        <a href="deletedetailfile/{{ $datafile->loan_app_no }}/{{ $file->id }}"
                                                                                                                                            target="_self">
                                                                                                                                            <div
                                                                                                                                                class="pd-1 bg-danger">
                                                                                                                                                <span
                                                                                                                                                    style="color:white">X</span>
                                                                                                                                            </div>
                                                                                                                                        </a>
                                                                                                                                    @endif
                                                                                                                                </div>
                                                                                                                            </li>
                                                                                                                        @endforeach
                                                                                                                    </ul>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                               <hr>
                               <br>
                                @if(Session("role")=="staff")
                                <div class="panel panel-primary tabs-style-2">
                                    <div class=" tab-menu-heading">
                                        <div class="tabs-menu1">
                                            <!-- Tabs -->
                                            <ul class="nav panel-tabs main-nav-line">
                                                <!-- <li><a href="#tabupload" class="nav-link active" data-toggle="tab">Upload Document</a></li> -->
                                                <li><a href="#tabcopy" class="nav-link active" data-toggle="tab">Copy Document</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body tabs-menu-body main-content-body-right border">
                                        <div class="tab-content">
                                            <!-- <div class="tab-pane active" id="tabupload">
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-8">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="main-content-label mg-b-5">
                                                                    Upload Document
                                                                </div>
                                                                <div id="MsgErrorUpload"></div>
                                                                <form id="fileUploadFormKK" method="POST" action="{{ route('datafile.uploadfile') }}" enctype="multipart/form-data">
                                                                        @csrf
                                                                                                                         
                                                                    <div class="pd-10 pd-sm-10 bg-gray-100">
                                                                        <p class="tx-12 text-muted card-sub-title">Silahkan Upload file dalam format <i style="color:red">.pdf dan max size 2MB</i></p>
                                                                        <div class="row row-xs">
                                                                            
                                                                            <div class="col-md-5">
                                                                                <select  class="form-control SlectBox SumoUnder" name="type">
                                                                                    @foreach ($map_product as $item)
                                                                                    <option  value="{{$item->nama_dokumen}}">{{$item->nama_dokumen}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-5">
                                                                                <input name="loan_app_no" type="hidden" value=" {{ $datafile->loan_app_no }}">
                                                                            <input name="file" type="file" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-1">
                                                                                <input type="submit" value="Submit" class="btn btn-primary">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row row-xs">
                                                                            <div class="col-md-12">
                                                                                <br>
                                                                                <div id="list_kk">
                                                                                <div class="progress">
                                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger kk" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;height:20px;font-size:13px"></div>
                                                                                </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="tab-pane active" id="tabcopy">
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-8">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="main-content-label mg-b-5">
                                                                    Copy Document
                                                                </div>
                                                                <div id="MsgErrorCopy"></div>
                                                                    <form id="fileCopyFormKK" method="POST" action="{{ route('datafile.copyfile') }}" enctype="multipart/form-data">
                                                                        @csrf
                                                                                                                         
                                                                    <div class="pd-10 pd-sm-10 bg-gray-100">
                                                                        <p class="tx-12 text-muted card-sub-title">Silahkan masukan Loan App No.</p>
                                                                        <div class="row row-xs">
                                                                            <div class="col-md-5">
                                                                            <input name="loan_app_no_src" type="text" value="" placeholder="Loan App No" class="form-control">
                                                                            <input name="loan_app_no" type="hidden" value="{{ $datafile->loan_app_no }}">
                                                                            </div>
                                                                            <div class="col-md-1">
                                                                                <input type="submit" value="Copy" class="btn btn-primary">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row row-xs">
                                                                            <div class="col-md-12">
                                                                                <br>
                                                                                <div id="list_kk">
                                                                                <div class="progress">
                                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger kk" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;height:20px;font-size:13px"></div>
                                                                                </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               @endif
                                </div>

                                <div class="tab-pane" id="tab3">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <!-- <div class="card"> -->
                                            <!-- <div class="card-body"> -->
                                            <div class="main-content-label mg-b-5">
                                                List Review
                                                @php
                                                    // Uncomment this to see the counts (for debugging)
                                                    /*
                                                        print_r([
                                                             'Wajib Kupeg' => $jumlahdokumen_wajib_kupeg,
                                                             'Opsional Kupeg' => $jumlahdokumen_opsional_kupeg,
                                                             'Wajib Kupen' => $jumlahdokumen_wajib_kupen,
                                                             'Opsional Kupen' => $jumlahdokumen_opsional_kupen,
                                                             'Wajib KupenHybrid' => $jumlahdokumen_wajib_kupenhybrid,
                                                             'Opsional KupenHybrid' => $jumlahdokumen_opsional_kupenhybrid
                                                         ]);
                                                         */
                                                @endphp
                                            </div>
                                            <div class="pd-12 pd-sm-12 bg-gray-100">
                                                <div id="ListReview">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="">
                                                                <!-- <div class="card-body"> -->
                                                                <div class="table-responsive">

                                                                    <table
                                                                        class="table table-bordered table-hover  mb-0 text-md-nowrap border">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>@sortablelink('id', 'Activity Review')</th>
                                                                                <th>@sortablelink('comment_date', 'REVIEW DATE')</th>
                                                                                <th>User</th>
                                                                                <th>Level User</th>
                                                                                <th>Status</th>
                                                                                <th>Note</th>
                                                                                <th>TBO Date</th>
                                                                                <th>Reason</th>

                                                                            </tr>
                                                                            <thead>
                                                                                <?php
                                                                                $direction = app('request')->input('direction');
                                                                                if ($direction == 'desc') {
                                                                                    $i = count($comment);
                                                                                } else {
                                                                                    $i = 1;
                                                                                }
                                                                                
                                                                                ?>
                                                                            <tbody>
                                                                                @foreach ($comment as $br)
                                                                                    <tr>

                                                                                        @if ($direction == 'desc')
                                                                                            <td>{{ $i-- }}</td>
                                                                                        @else
                                                                                            <td>{{ $i++ }}</td>
                                                                                        @endif
                                                                                        <!-- <td>{{ $br->id }}</td> -->
                                                                                        <td>{{ $br->comment_date }}</td>
                                                                                        <td>{{ getnameuser($br->user_name) }}
                                                                                        </td>
                                                                                        <td>{{ getLevel($br->level_spv) }}
                                                                                        </td>
                                                                                        <td>{!! getFlagColor($br->flag_spv) !!}</td>
                                                                                        <td>
                                                                                            {{ $br->comment }}
                                                                                            @if(!empty($datafile->file_bukti_verifikator) && in_array($br->level_spv, ['team_verifikator_lvl1', 'user_verif1']))
                                                                                                <br><br>
                                                                                                <a href="{{ asset('indexed' . $datafile->file_bukti_verifikator) }}"
                                                                                                   target="_blank"
                                                                                                   class="text-primary">
                                                                                                    <i class="fas fa-file-pdf"></i> Lihat Dokumen Verifikasi
                                                                                                    <i class="fas fa-external-link-alt"></i>
                                                                                                </a>
                                                                                            @endif
                                                                                        </td>
                                                                                        @if ($br->tbo_date != null)
                                                                                            <td>{{ date_format(date_create($br->tbo_date), 'Y-m-d') }}
                                                                                            </td>
                                                                                        @else
                                                                                            <td></td>
                                                                                        @endif
                                                                                        <td>{{ $br->reason }}</td>


                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- <div class="vtimeline">
                                                                           
                                                                                <?php
                                                                                $x = 1;
                                                                                foreach ($comment as $key => $value) {
                                                                                    $comment = $value->comment;
                                                                                    $reason = $value->reason;
                                                                                    $nik = $value->user_name;
                                                                                    $tbo_date = $value->tbo_date;
                                                                                    $flag_spv = $value->flag_spv;
                                                                                    $level_spv = $value->level_spv;
                                                                                    $flag_name = '';
                                                                                
                                                                                    $comment_date = $value->comment_date;
                                                                                
                                                                                    $inverted = '';
                                                                                    if ($x % 2 == 0) {
                                                                                        $inverted = 'timeline-inverted';
                                                                                    }
                                                                                
                                                                                    // if($level_spv=='spv1'){
                                                                                    //     $lbl_level_spv='Spv Branch - ';
                                                                                    // }else if($level_spv=='spv2'){
                                                                                    //     $lbl_level_spv='DCRM REVIEWER - ';
                                                                                    // }else if($level_spv=='spv3' || $level_spv=='spv4'){
                                                                                    //     $lbl_level_spv='DCRM TEAM LEADER - ';
                                                                                    // }else if($level_spv=='pcp'){
                                                                                    //     $lbl_level_spv='PCP - ';
                                                                                    // }else if($level_spv=='pc'){
                                                                                    //     $lbl_level_spv='PC - ';
                                                                                    // }else{
                                                                                    //     $lbl_level_spv='';
                                                                                    // }
                                                                                
                                                                                    if ($level_spv == 'staff') {
                                                                                        $lbl_level_spv = 'BRANCH';
                                                                                    } elseif ($level_spv == 'spv3' || $level_spv == 'spv4') {
                                                                                        $lbl_level_spv = 'DCRM';
                                                                                    } else {
                                                                                        $lbl_level_spv = '';
                                                                                    }
                                                                                
                                                                                    if ($flag_spv == 1) {
                                                                                        $flag_name = "<div style='color:green;'> " . 'X' . $value->level_spv . $lbl_level_spv . 'Verify</div>';
                                                                                    } elseif ($flag_spv == 2) {
                                                                                        $flag_name = "<div style='color:orange;'> " . $lbl_level_spv . 'Not Verify</div>';
                                                                                    } elseif ($flag_spv == 3) {
                                                                                        $flag_name = "<div style='color:blue;'> " . $lbl_level_spv . 'TBO (To Be Obtained)</div>';
                                                                                    } elseif ($flag_spv == 4) {
                                                                                        $flag_name = "<div style='color:red;'> " . $lbl_level_spv . 'Reject</div>';
                                                                                    }
                                                                                
                                                                                    $tag_reason = '';
                                                                                    $tag_comment = '';
                                                                                    $tag_tbo_date = '';
                                                                                    if ($reason == '') {
                                                                                        $tag_reason = '';
                                                                                    } else {
                                                                                        $tag_reason = '<p><b>Reason:</b> &nbsp;' . $reason . '</p>';
                                                                                    }
                                                                                
                                                                                    if ($comment == '') {
                                                                                        $tag_comment = '';
                                                                                    } else {
                                                                                        $tag_comment = '<p><b>Note:</b> &nbsp;' . $comment . '</p>';
                                                                                    }
                                                                                    if ($tbo_date == '') {
                                                                                        $tag_tbo_date = '';
                                                                                    } else {
                                                                                        $tag_tbo_date = '<p><b>TBO Date:</b> &nbsp;' . $tbo_date . '</p>';
                                                                                    }
                                                                                    //dd(getnameuser("1"));
                                                                                
                                                                                    echo '
                                                                                                                                                            <div class="timeline-wrapper ' .
                                                                                        $inverted .
                                                                                        ' timeline-wrapper-info">
                                                                                                                                                                <div class="timeline-badge"></div>
                                                                                                                                                                <div class="timeline-panel">
                                                                                                                                                                    <div class="timeline-heading">
                                                                                                                                                                        <h6 class="timeline-title">' .
                                                                                        $flag_name .
                                                                                        '</h6>
                                                                                                                                                                    </div>
                                                                                                                                                                    <div class="timeline-body">
                                                                                                                                                                        ' .
                                                                                        $tag_reason .
                                                                                        $tag_comment .
                                                                                        $tag_tbo_date .
                                                                                        '
                                                                                                                                                                    </div>
                                                                                                                                                                    <div class="timeline-footer d-flex align-items-center flex-wrap">
                                                                                                                                                                        <span></span>
                                                                                                                                                                        <span class="ml-auto"><i class="fe fe-calendar text-muted mr-1"></i>' .
                                                                                        $comment_date .
                                                                                        ' by ' .
                                                                                        getnameuser($nik) .
                                                                                        '</span>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                            ';
                                                                                    $x++;
                                                                                }
                                                                                ?>

                                                                                
                                                                                
                                                                            </div>
                                                                            -->

                                                                <!-- </div> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">


                                                            @if ($jumlahdokumen_wajib_kupeg === 0 && $jumlahdokumen_wajib_kupen === 0 && $jumlahdokumen_wajib_kupenhybrid === 0)


                                                                <?php
                                                                $previousUrl = url()->previous();
                                                                
                                                                $current = new DateTime(); // Tanggal hari ini
                                                                $current->setTime(0, 0, 0);
                                                                $date_submit = new DateTime($datafile->date_input);
                                                                $date_submit->setTime(0, 0, 0);
                                                                $selisih = $current->diff($date_submit);

                                                                ?>
                                                                {{--
                                                                    @if (
                                                                        ($previousUrl == 'https://dms.bankwoorisaudara.com/pickup' && Session("role")=='spv2')
                                                                        || (($selisih->days >= 2) && Session("role")=='spv2')
                                                                        || (Session("role")=='staff')
                                                                        || (Session("role")=='spv3')
                                                                        || (Session("role")=='spv4')
                                                                        || getUserPickupLoan($datafile->loan_app_no,Session("nik")))
                                                                --}}
                                                                @if (
                                                                    (($previousUrl == 'https://dms.bankwoorisaudara.com/pickup' &&
                                                                        (Session('role') == 'spv3' || Session('role') == 'spv4')) ||
                                                                        ($selisih->days >= 2 && (Session('role') == 'spv3' || Session('role') == 'spv4')) ||
                                                                        Session('role') == 'staff' ||
                                                                        getUserPickupLoan($datafile->loan_app_no, Session('nik'))) &&
                                                                    !in_array(Session('role'), ['team_verifikator_lvl1', 'team_verifikator_lvl2']))
                                                                    <div class="main-content-label mg-b-5">
                                                                        Add Review
                                                                    </div>

                                                                    <div id="MsgError"></div>
                                                                    <div class="pd-10 pd-sm-10 bg-gray-100">
                                                                        <form id="addReview" method="POST"
                                                                            action="{{ route('datafile.updateflag') }}"
                                                                            enctype="multipart/form-data">
                                                                            @csrf

                                                                            <div class="mb-12">
                                                                                <label for="flag_spv"
                                                                                    class="form-label">Status
                                                                                    Document</label>
                                                                                <select class="flag_spv form-control"
                                                                                    name="flag_spv" id="flag_spv"
                                                                                    style="width: 30%">
                                                                                    {{-- <option hidden>Choose Status Document</option>
                                                                                @foreach ($flag_spv as $item)
                                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                @endforeach --}}
                                                                                </select>
                                                                            </div>
                                                                            <div class="mb-12" id="field_reason"
                                                                                style="display:none;">
                                                                                <input name="loan_app_no" type="hidden"
                                                                                    value=" {{ $datafile->loan_app_no }}">
                                                                                <label for="reason"
                                                                                    class="form-label">Reason</label>
                                                                                <select class="reason form-control pd-30"
                                                                                    name="reason[]" id="reason"
                                                                                    multiple="multiple"
                                                                                    style="width: 50%">
                                                                                </select>
                                                                            </div>
                                                                            <div class="mb-12">
                                                                                <label for="reason"
                                                                                    class="form-label">Add Note <i>(Max 150
                                                                                        Karakter)</i></label>
                                                                                <textarea name="comment" id="comment" placeholder="Add Note" class="form-control" rows="3"
                                                                                    maxlength="150"></textarea>
                                                                                <i>
                                                                                    <div id="char-count">Sisa Karakter: 150
                                                                                    </div>
                                                                                </i>
                                                                            </div>
                                                                            <div class="mb-12" id="field_tbo"
                                                                                style="display:none;">
                                                                                <label for="tbo_date"
                                                                                    class="form-label">TBO Date</label>
                                                                                {{-- <p class="mg-b-20">Datetimepicker style variant on top of AmazeUI Datetimepicker.</p> --}}
                                                                                <div class="row row-sm">
                                                                                    <div class="input-group col-md-4">
                                                                                        <div class="input-group-prepend">
                                                                                            <div class="input-group-text">
                                                                                                <i
                                                                                                    class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                                                            </div>
                                                                                        </div><input class="form-control"
                                                                                            id="tbo_date" type="text"
                                                                                            name="tbo_date">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mb-12">
                                                                                <br>
                                                                                <input type="submit" value="Submit"
                                                                                    class="btn btn-primary">
                                                                            </div>
                                                                            <div class="row row-xs">
                                                                                <div class="col-md-12">
                                                                                    <br>
                                                                                    <div id="list_kk">
                                                                                        <div class="progress">
                                                                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger kk"
                                                                                                role="progressbar"
                                                                                                aria-valuenow="0"
                                                                                                aria-valuemin="0"
                                                                                                aria-valuemax="100"
                                                                                                style="width: 0%;height:20px;font-size:13px">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    @else
                                                                        @if (Session('role') == 'spv2' && Session('role') == 'spv3' && Session('role') == 'spv4')
                                                                            <div class="alert alert-danger mg-b-0"
                                                                                role="alert">
                                                                                <button aria-label="Close" class="close"
                                                                                    data-dismiss="alert" type="button">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                                <p><b>Perhatian: Silahkan Review Melalui
                                                                                        Menu Pickup.</b></p>
                                                                            </div>
                                                                        @endif
                                                                @endif
                                                        </div>
                                                    @else
                                                        <div class="alert alert-danger mg-b-0" role="alert">
                                                            <button aria-label="Close" class="close"
                                                                data-dismiss="alert" type="button">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            <p><b>Perhatian: Masih Ada Dokumen Mandatori Yang Belum
                                                                    Diupload, Silahkan lengkapi terlebih dahulu.</b></p>
                                                        </div>
                                                        @endif

                                                        {{-- ========================================= --}}
                                                    {{-- FORM TEAM VERIFIKATOR - START --}}
                                                    {{-- ========================================= --}}

                                                    @php
                                                        // Cek apakah produk memerlukan team verifikator
                                                        $jenis_produk = $datafile->masterproduk->first()->jenis_produk ?? 0;
                                                        $need_verifikator = in_array($jenis_produk, [2, 3, 8]); // KUPEN Hybrid, KUPEG, KUPEN Hybrid GO
                                                    @endphp

                                                    {{-- FORM TEAM VERIFIKATOR LEVEL 1 --}}
                                                    @if($need_verifikator && Session("role")=='team_verifikator_lvl1' && $datafile->final_status_verif1 == 0)
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="card">
                                                                <div class="card-header bg-primary">
                                                                    <h3 class="card-title text-white">
                                                                        <i class="fa fa-clipboard-check"></i> Form Review - Team Verifikator Level 1 (Rekomendasi)
                                                                    </h3>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="alert alert-info">
                                                                        <strong><i class="fa fa-info-circle"></i> Informasi:</strong><br>
                                                                        - Status Anda adalah <strong>Rekomendasi</strong><br>
                                                                        - Review akan dilanjutkan ke Team Verifikator Level 2 untuk keputusan final<br>
                                                                        - Pastikan review Anda akurat karena akan menjadi bahan pertimbangan Level 2
                                                                    </div>

                                                                    <form id="formReviewVerif1" method="POST" action="{{ route('datafile.updateflag') }}">
                                                                        @csrf
                                                                        <input type="hidden" name="loan_app_no" value="{{ $datafile->loan_app_no }}">

                                                                        <div class="form-group">
                                                                            <label for="flag_verif1">Rekomendasi <span class="text-danger">*</span></label>
                                                                            <select name="flag_spv" class="form-control" id="flag_verif1" required style="width: 50%">
                                                                                <option value="">-- Pilih Rekomendasi --</option>
                                                                                <option value="5">
                                                                                    <i class="fa fa-thumbs-up"></i> Approve (Direkomendasikan untuk Lanjut)
                                                                                </option>
                                                                                <option value="6">
                                                                                    <i class="fa fa-thumbs-down"></i> Not Approve (Tidak Direkomendasikan)
                                                                                </option>
                                                                            </select>
                                                                            <small class="text-muted">
                                                                                Pilih "Approve" jika dokumen layak dilanjutkan, atau "Not Approve" jika ada masalah
                                                                            </small>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="comment_verif1">Komentar <span class="text-danger">*</span></label>
                                                                            <textarea name="comment" id="comment_verif1"
                                                                                      class="form-control" rows="4"
                                                                                      placeholder="Berikan komentar detail terkait rekomendasi Anda..."
                                                                                      maxlength="150" required></textarea>
                                                                            <small class="text-muted">Sisa karakter: <span id="char_count_verif1">150</span></small>
                                                                        </div>

                                                                        <div class="form-group" id="reason_verif1_group" style="display:none;">
                                                                            <label for="reason_verif1">Alasan Not Approve</label>
                                                                            <select name="reason[]" id="reason_verif1" class="form-control select2" multiple style="width: 70%">
                                                                                {{-- Akan di-populate via AJAX dari database --}}
                                                                            </select>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <button type="submit" class="btn btn-primary btn-lg">
                                                                                <i class="fa fa-paper-plane"></i> Submit Rekomendasi
                                                                            </button>
                                                                            <button type="reset" class="btn btn-secondary btn-lg">
                                                                                <i class="fa fa-redo"></i> Reset
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    {{-- NEW FLOW: FORM UPLOAD FILE BUKTI - TEAM VERIFIKATOR LEVEL 1 (STATE 3) --}}
                                                    @if($need_verifikator && Session("role")=='team_verifikator_lvl1' &&
                                                        $datafile->final_status_verif1 > 0 &&
                                                        $datafile->final_status_verif2 > 0 &&
                                                        empty($datafile->file_bukti_verifikator))
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="card">
                                                                <div class="card-header bg-info">
                                                                    <h3 class="card-title text-white">
                                                                        <i class="fa fa-upload"></i> Upload File Bukti Pemeriksaan - Team Verifikator Level 1
                                                                    </h3>
                                                                </div>
                                                                <div class="card-body">
                                                                    {{-- Show Verif2 Decision --}}
                                                                    <div class="alert alert-{{ $datafile->final_status_verif2 == 5 ? 'success' : 'danger' }}">
                                                                        <h5><i class="fa fa-info-circle"></i> Keputusan Team Verifikator Level 2:</h5>
                                                                        <strong>Status: {{ $datafile->final_status_verif2 == 5 ? 'APPROVE' : 'NOT APPROVE' }}</strong><br>
                                                                        <small>
                                                                            Oleh: {{ $datafile->user_verif2 }}<br>
                                                                            Tanggal: {{ $datafile->date_flag_verif2 }}
                                                                        </small>
                                                                    </div>

                                                                    <div class="alert alert-warning">
                                                                        <strong><i class="fa fa-exclamation-triangle"></i> PENTING:</strong><br>
                                                                        - Upload file bukti pemeriksaan adalah <strong>WAJIB</strong><br>
                                                                        - Loan tidak akan diproses sampai file bukti diupload<br>
                                                                        - Format: PDF, JPG, PNG (Max 2MB)<br>
                                                                        - Setelah upload, loan akan diproses otomatis sesuai keputusan Verif2
                                                                    </div>

                                                                    <form id="formUploadBuktiVerif1" method="POST" action="javascript:void(0)">
                                                                        @csrf
                                                                        <input type="hidden" name="loan_app_no" value="{{ $datafile->loan_app_no }}">

                                                                        <div class="form-group">
                                                                            <label for="file_bukti">File Bukti Pemeriksaan <span class="text-danger">*</span></label>
                                                                            <input type="file" name="file_bukti" id="file_bukti"
                                                                                   class="form-control"
                                                                                   accept=".pdf,.jpg,.jpeg,.png"
                                                                                   required>
                                                                            <small class="text-muted">
                                                                                Upload file bukti hasil pemeriksaan (PDF, JPG, PNG - Max 2MB)
                                                                            </small>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <div class="progress" id="upload_progress_new" style="display:none;">
                                                                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                                     role="progressbar"
                                                                                     style="width: 0%"
                                                                                     id="upload_progress_bar_new">0%</div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <button type="submit" class="btn btn-info btn-lg" id="btnUploadBuktiNew">
                                                                                <i class="fa fa-upload"></i> Upload File Bukti
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    {{-- FORM TEAM VERIFIKATOR LEVEL 2 --}}
                                                    @if($need_verifikator && Session("role")=='team_verifikator_lvl2' && $datafile->final_status_verif2 == 0 && $datafile->final_status_verif1 > 0)
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="card">
                                                                <div class="card-header bg-success">
                                                                    <h3 class="card-title text-white">
                                                                        <i class="fa fa-gavel"></i> Form Review - Team Verifikator Level 2 (Keputusan Final)
                                                                    </h3>
                                                                </div>
                                                                <div class="card-body">
                                                                    {{-- Tampilkan Rekomendasi dari Level 1 --}}
                                                                    <div class="alert {{ $datafile->final_status_verif1 == 5 ? 'alert-success' : 'alert-warning' }}">
                                                                        <h5><strong><i class="fa fa-info-circle"></i> Rekomendasi dari Team Verifikator Level 1:</strong></h5>
                                                                        <div class="mt-2">
                                                                            Status:
                                                                            @if($datafile->final_status_verif1 == 5)
                                                                                <span class="badge badge-success badge-lg">
                                                                                    <i class="fa fa-thumbs-up"></i> APPROVE (Direkomendasikan)
                                                                                </span>
                                                                            @elseif($datafile->final_status_verif1 == 6)
                                                                                <span class="badge badge-warning badge-lg">
                                                                                    <i class="fa fa-thumbs-down"></i> NOT APPROVE (Tidak Direkomendasikan)
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                        <div class="mt-2">
                                                                            <small class="text-muted">
                                                                                Oleh: <strong>{{ $datafile->user_verif1 }}</strong> |
                                                                                Tanggal: <strong>{{ $datafile->date_flag_verif1 }}</strong>
                                                                            </small>
                                                                        </div>
                                                                    </div>

                                                                    <div class="alert alert-info">
                                                                        <strong><i class="fa fa-exclamation-triangle"></i> PERHATIAN (NEW FLOW):</strong><br>
                                                                        - Keputusan Anda adalah <strong>FINAL</strong><br>
                                                                        - Jika APPROVE: Loan akan mengikuti status SPV ({{ $datafile->final_status_spv2 == 1 ? 'Verify' : 'TBO' }})<br>
                                                                        - Jika NOT APPROVE: Loan akan ditolak dan tidak bisa lanjut ke proses dropping<br>
                                                                        - <strong>Setelah submit, Team Verifikator Level 1 akan upload file bukti pemeriksaan</strong>
                                                                    </div>

                                                                    <form id="formReviewVerif2" method="POST" action="{{ route('datafile.updateflag') }}">
                                                                        @csrf
                                                                        <input type="hidden" name="loan_app_no" value="{{ $datafile->loan_app_no }}">

                                                                        <div class="form-group">
                                                                            <label for="flag_verif2">Keputusan Final <span class="text-danger">*</span></label>
                                                                            <select name="flag_spv" class="form-control" id="flag_verif2" required style="width: 50%">
                                                                                <option value="">-- Pilih Keputusan Final --</option>
                                                                                <option value="5">
                                                                                    <i class="fa fa-check-circle"></i> APPROVE (Setuju untuk Lanjut)
                                                                                </option>
                                                                                <option value="6">
                                                                                    <i class="fa fa-times-circle"></i> NOT APPROVE (Tidak Disetujui - FINAL REJECTION)
                                                                                </option>
                                                                            </select>
                                                                            <small class="text-muted">
                                                                                <i class="fa fa-lightbulb"></i>
                                                                                @if($datafile->final_status_spv2 == 1)
                                                                                    Jika Approve: Final status akan menjadi <strong>VERIFY</strong> (setelah verif1 upload file)
                                                                                @else
                                                                                    Jika Approve: Final status akan menjadi <strong>TBO</strong> (setelah verif1 upload file)
                                                                                @endif
                                                                                <br>
                                                                                Jika Not Approve: Semua status akan menjadi <strong>NOT APPROVE (6)</strong> (setelah verif1 upload file)
                                                                            </small>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="comment_verif2">Komentar <span class="text-danger">*</span></label>
                                                                            <textarea name="comment" id="comment_verif2"
                                                                                      class="form-control" rows="5"
                                                                                      placeholder="Berikan komentar detail terkait keputusan final Anda..."
                                                                                      maxlength="150" required></textarea>
                                                                            <small class="text-muted">Sisa karakter: <span id="char_count_verif2">150</span></small>
                                                                        </div>

                                                                        <div class="form-group" id="reason_verif2_group" style="display:none;">
                                                                            <label for="reason_verif2">Alasan Not Approve <span class="text-danger">*</span></label>
                                                                            <select name="reason[]" id="reason_verif2" class="form-control select2" multiple style="width: 70%">
                                                                                {{-- Akan di-populate via AJAX --}}
                                                                            </select>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <button type="submit" class="btn btn-success btn-lg" id="btnSubmitVerif2">
                                                                                <i class="fa fa-check-double"></i> Submit Keputusan Final
                                                                            </button>
                                                                            <button type="reset" class="btn btn-secondary btn-lg">
                                                                                <i class="fa fa-redo"></i> Reset
                                                                            </button>
                                                                        </div>

                                                                        <div class="progress mt-3" id="uploadProgress" style="display:none;">
                                                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                                 role="progressbar" style="width: 0%">0%</div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    {{-- ========================================= --}}
                                                    {{-- FORM TEAM VERIFIKATOR - END --}}
                                                    {{-- ========================================= --}}

                                                    {{-- ========================================= --}}
                                                    {{-- FORM SPV READY TO DISBURSE - START --}}
                                                    {{-- ========================================= --}}
                                                    @if(in_array(Session("role"), ['spv2', 'spv3', 'spv4']) &&
                                                        !empty($datafile->file_bukti_verifikator) &&
                                                        $datafile->ready_to_disburs == 0)
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="card">
                                                                <div class="card-header bg-success">
                                                                    <h3 class="card-title text-white">
                                                                        <i class="fa fa-money-check-alt"></i> Ready to Disburse - SPV
                                                                    </h3>
                                                                </div>
                                                                <div class="card-body">
                                                                    {{-- Display Current Loan Status --}}
                                                                    <div class="alert alert-info">
                                                                        <h5><strong><i class="fa fa-info-circle"></i> Status Loan:</strong></h5>
                                                                        <table class="table table-sm table-borderless mt-2">
                                                                            <tr>
                                                                                <td width="200"><strong>Loan App No:</strong></td>
                                                                                <td>{{ $datafile->loan_app_no }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><strong>Nama Debitur:</strong></td>
                                                                                <td>{{ $datafile->nama_debitur }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><strong>Final Status:</strong></td>
                                                                                <td>
                                                                                    @if($datafile->final_status == 1)
                                                                                        <span class="badge badge-success badge-lg">VERIFY</span>
                                                                                    @elseif($datafile->final_status == 3)
                                                                                        <span class="badge badge-warning badge-lg">TBO</span>
                                                                                    @elseif($datafile->final_status == 6)
                                                                                        <span class="badge badge-danger badge-lg">NOT APPROVE</span>
                                                                                    @else
                                                                                        <span class="badge badge-secondary badge-lg">-</span>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><strong>File Bukti Verifikator:</strong></td>
                                                                                <td>
                                                                                    <a href="{{ asset('indexed' . $datafile->file_bukti_verifikator) }}"
                                                                                       target="_blank"
                                                                                       class="text-primary">
                                                                                        <i class="fas fa-file-pdf"></i> Lihat Dokumen
                                                                                        <i class="fas fa-external-link-alt fa-xs"></i>
                                                                                    </a>
                                                                                    <br>
                                                                                    <small class="text-muted">
                                                                                        Uploaded by: {{ $datafile->user_verif1 ?? '-' }}
                                                                                    </small>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>

                                                                    <div class="alert alert-warning">
                                                                        <strong><i class="fa fa-exclamation-triangle"></i> PENTING:</strong><br>
                                                                        - Pastikan semua dokumen sudah direview dengan benar<br>
                                                                        - File bukti verifikator sudah diupload oleh Team Verifikator Level 1<br>
                                                                        - Setelah flag "Ready to Disburse", loan akan hilang dari list Pending Disbursement<br>
                                                                        - Tindakan ini tidak dapat dibatalkan
                                                                    </div>

                                                                    <form id="formReadyToDisburse">
                                                                        @csrf
                                                                        <input type="hidden" name="loan_app_no" value="{{ $datafile->loan_app_no }}">

                                                                        <div class="form-group text-center">
                                                                            <button type="submit" class="btn btn-success btn-lg" id="btnReadyToDisburse">
                                                                                <i class="fa fa-check-circle"></i> Flag Ready to Disburse
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    {{-- ========================================= --}}
                                                    {{-- FORM SPV READY TO DISBURSE - END --}}
                                                    {{-- ========================================= --}}

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <!-- </div> -->
                                    <!-- </div> -->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>

    <!-- medium modal -->
    <div class="modal fade mediumModal-modal-lg" id="mediumModal" role="dialog"
        aria-labelledby="mediumModalLabel" aria-modal="true" aria-hidden="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="mediumBody">
                    <div>
                        <!-- the result to be displayed apply here -->
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <!-- SweetAlert2 JavaScript - Load first before other scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).on('click', '#mediumButton', function(event) {
            event.preventDefault();
            // Remove focus from the button to prevent aria-hidden focus conflict
            $(this).blur();
            let href = $(this).attr('data-attr');
            console.log(href);
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    // Remove any existing aria-hidden before showing modal
                    $('#mediumModal').removeAttr('aria-hidden').removeAttr('tabindex');
                    $('#mediumModal').modal("show");
                    $('#mediumBody').html(result).show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

        // Variable to store textarea values before modal opens
        let textareaBackup = {};

        // Comprehensive modal focus management solution
        $(document).ready(function() {
            // Disable Bootstrap's enforceFocus globally
            if ($.fn.modal && $.fn.modal.Constructor) {
                $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            }

            const modal = document.getElementById('mediumModal');
            let triggerElement = null;

            // Simpan elemen yang memicu modal dan debugging
            $('#mediumModal').on('show.bs.modal', function(event) {
                // Backup semua textarea dengan name="comment" sebelum modal dibuka
                $('textarea[name="comment"]').each(function(index) {
                    textareaBackup[index] = $(this).val();
                });

                triggerElement = event.relatedTarget || document.activeElement;
                window.triggerElement = triggerElement; // Global access

                // Remove inert when modal opens
                this.removeAttribute('inert');

                // Debug logging
            });

            // Monitor and fix aria-hidden attribute changes on modal per W3C ARIA spec
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes') {
                        const modal = $('#mediumModal')[0];
                        if (modal) {
                            // Per W3C ARIA spec: modal should not have aria-hidden="true" when active
                            if (modal.hasAttribute('aria-hidden') && modal.getAttribute('aria-hidden') === 'true') {
                                modal.setAttribute('aria-hidden', 'false');
                            }
                            // Remove problematic tabindex
                            if (modal.hasAttribute('tabindex') && modal.getAttribute('tabindex') === '-1') {
                                modal.removeAttribute('tabindex');
                            }
                        }
                    }
                });
            });

            // Start observing modal for attribute changes
            if (modal) {
                observer.observe(modal, {
                    attributes: true,
                    attributeFilter: ['aria-hidden', 'tabindex']
                });
            }
        });

        // Fix textarea focus issue after modal close
        $('#mediumModal').on('shown.bs.modal', function () {
            // Remove aria-hidden and tabindex when modal is shown
            $(this).removeAttr('aria-hidden').removeAttr('tabindex');
            // Disable Bootstrap's focus enforcement
            $(document).off('focusin.modal');

            // Fix Select2 elements that interfere with textarea focus
            $(this).find('.select2-hidden-accessible').removeAttr('tabindex').removeAttr('aria-hidden');
            $(this).find('.select2-container').removeAttr('tabindex');
            $(this).find('[aria-hidden="true"]').not('.select2-selection__arrow b, .dropdown-wrapper, span[aria-hidden="true"]').removeAttr('aria-hidden');

            // Ensure textarea is focusable
            $(this).find('textarea').removeAttr('tabindex').removeAttr('aria-hidden').prop('disabled', false);
        });

        // Handle modal hide event with inert attribute
        $('#mediumModal').on('hide.bs.modal', function () {
            // Set inert saat modal mulai ditutup (modern approach)
            this.setAttribute('inert', '');

            // Remove focus from any active element in modal before closing
            $(this).find('button, input, textarea, select').blur();
            // Remove any focus trap
            $(document).off('focusin.modal');

            // Immediately enable textarea when modal starts closing
            $('textarea[name="comment"]').each(function() {
                $(this).prop('disabled', false)
                      .prop('readonly', false)
                      .css('pointer-events', 'auto');
            });
        });

        // Comprehensive modal cleanup when hidden
        $('#mediumModal').on('hidden.bs.modal', function () {
            const modal = this;

            // Cleanup ezoom and PDFObject before clearing content
            if (typeof window.cleanupEzoom === 'function') {
                window.cleanupEzoom();
            }
            if (typeof window.cleanupPDFObject === 'function') {
                window.cleanupPDFObject();
            }

            // Clear modal content
            $('#mediumBody').empty();

            // Force remove all problematic attributes
            $(modal).removeAttr('aria-hidden').removeAttr('tabindex').removeClass('show');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').removeAttr('style');

            // Remove any global event listeners that might interfere with input
            $(document).off('mousedown.modal keydown.modal click.modal');
            $(window).off('resize.modal scroll.modal');

            // Reset all textareas on the page - comprehensive cleanup
            $('textarea[name="comment"]').each(function(index) {
                // Restore nilai textarea dari backup instead of clearing it
                const backupValue = textareaBackup[index] || '';
                $(this).val(backupValue)
                       .prop('disabled', false)
                       .prop('readonly', false)
                       .removeAttr('tabindex')
                       .removeAttr('aria-hidden')
                       .removeAttr('style')
                       .off('.ezoom')
                       .css({
                           'pointer-events': 'auto',
                           'user-select': 'auto',
                           '-webkit-user-select': 'auto',
                           '-moz-user-select': 'auto',
                           '-ms-user-select': 'auto'
                       });

                // Update char counter immediately after restore
                const currentLength = backupValue.length;
                const remainingChars = 100 - currentLength;
                $('#char-count').text(`Sisa Karakter: ${remainingChars}`);
            });

            // Reset select2 elements
            $('.select2-hidden-accessible').removeAttr('tabindex').removeAttr('aria-hidden');
            $('.select2-container').removeAttr('tabindex');

            // Immediate textarea cleanup and enable
            $('textarea[name="comment"]').each(function(index) {
                const $textarea = $(this);
                const element = this;

                // NUCLEAR OPTION: Clone and replace element to remove ALL event listeners
                const clone = element.cloneNode(true);
                // Restore nilai textarea dari backup instead of clearing it
                const backupValue = textareaBackup[index] || '';
                clone.value = backupValue;
                clone.disabled = false;
                clone.readOnly = false;
                clone.removeAttribute('disabled');
                clone.removeAttribute('readonly');
                clone.removeAttribute('tabindex');
                clone.removeAttribute('aria-hidden');
                clone.style.setProperty('pointer-events', 'auto', 'important');
                clone.style.setProperty('user-select', 'auto', 'important');
                clone.style.backgroundColor = 'white';
                clone.style.opacity = '1';
                clone.style.visibility = 'visible';

                // Add clean event handlers
                ['keydown', 'keypress', 'keyup', 'input', 'paste', 'cut', 'mousedown', 'mouseup', 'focus', 'blur'].forEach(eventType => {
                    clone.addEventListener(eventType, function(e) {
                        // Don't prevent default - let all input events through
                    }, true);
                });

                // Replace the original element
                element.parentNode.replaceChild(clone, element);

                // Re-bind char counter using global function
                setTimeout(function() {
                    if (window.bindCharCounter) {
                        window.bindCharCounter();
                    }
                    // Update char counter immediately based on restored value
                    const currentLength = clone.value.length;
                    const remainingChars = 100 - currentLength;
                    $('#char-count').text(`Sisa Karakter: ${remainingChars}`);
                }, 50);

                // Test if textarea accepts input
                setTimeout(function() {
                    clone.focus();
                    const originalValue = clone.value; // Save original value
                    clone.value = 'test';
                    if (clone.value === 'test') {
                        clone.value = originalValue; // Restore original value instead of clearing
                    }
                }, 100);
            });

            // Focus management - pindahkan fokus ke textarea atau elemen yang aman
            setTimeout(function() {
                $(document).off('focusin.modal');

                // Force remove any remaining ezoom interference
                $(document).off('mousedown.ezoom mousemove.ezoom click.ezoom');
                $('body').off('.ezoom');

                // CRITICAL: Remove any global event listeners that might prevent input
                $(document).off('keydown keypress keyup input');
                $('body').off('keydown keypress keyup input');
                $(window).off('keydown keypress keyup input');

                // Check for and remove any preventing event handlers
                const textareaElement = $('textarea[name="comment"]').first()[0];
                if (textareaElement) {
                    // Test if keydown events are being prevented
                    const testKeyEvent = new KeyboardEvent('keydown', {
                        key: 'a',
                        code: 'KeyA',
                        keyCode: 65,
                        cancelable: true,
                        bubbles: true
                    });

                    textareaElement.dispatchEvent(testKeyEvent);

                    // Force enable input by removing ALL event attributes
                    ['onkeydown', 'onkeypress', 'onkeyup', 'oninput', 'onpaste', 'oncut'].forEach(attr => {
                        if (textareaElement[attr]) {
                            textareaElement[attr] = null;
                        }
                    });
                }

                // Final textarea recovery and focus
                const textareaJQuery = $('textarea[name="comment"]').first();
                if (textareaJQuery.length) {
                    const element = textareaJQuery[0];

                    // Ensure textarea is enabled
                    textareaJQuery.prop('disabled', false).prop('readonly', false);
                    // Restore nilai textarea dari backup instead of clearing it
                    const backupValue = textareaBackup[0] || '';
                    element.value = backupValue;
                    element.focus();

                    // Update char counter immediately based on restored value
                    const currentLength = element.value.length;
                    const remainingChars = 100 - currentLength;
                    $('#char-count').text(`Sisa Karakter: ${remainingChars}`);

                    // Test and fallback to new element if needed
                    setTimeout(function() {
                        element.dispatchEvent(new Event('focus'));
                        element.dispatchEvent(new Event('click'));
                        element.value = 'X';
                        element.dispatchEvent(new Event('input'));

                        const charCountText = $('#char-count').text();

                        if (element.value === 'X') {
                            // Restore nilai textarea dari backup instead of clearing it
                            const backupValue = textareaBackup[0] || '';
                            element.value = backupValue;
                            element.dispatchEvent(new Event('input'));
                            // Update char counter based on restored value
                            setTimeout(() => {
                                const currentLength = element.value.length;
                                const remainingChars = 100 - currentLength;
                                $('#char-count').text(`Sisa Karakter: ${remainingChars}`);
                            }, 5);
                        } else {
                            // Create completely new textarea as fallback
                            const newTextarea = document.createElement('textarea');
                            newTextarea.name = 'comment';
                            newTextarea.id = 'comment';
                            newTextarea.placeholder = 'Add Note';
                            newTextarea.className = 'form-control';
                            newTextarea.rows = 3;
                            newTextarea.maxLength = 100;
                            newTextarea.style.pointerEvents = 'auto';
                            newTextarea.style.userSelect = 'auto';
                            // Restore nilai textarea dari backup instead of leaving empty
                            const backupValue = textareaBackup[0] || '';
                            newTextarea.value = backupValue;

                            element.parentNode.replaceChild(newTextarea, element);

                            // Re-bind char counter to new element
                            setTimeout(function() {
                                if (window.bindCharCounter) {
                                    window.bindCharCounter();
                                }
                                // Update char counter immediately based on restored value
                                const currentLength = newTextarea.value.length;
                                const remainingChars = 100 - currentLength;
                                $('#char-count').text(`Sisa Karakter: ${remainingChars}`);
                            }, 100);
                        }
                    }, 200);
                } else if (window.triggerElement) {
                    window.triggerElement.focus();
                } else {
                    document.body.focus();
                }

                // Reset trigger element
                window.triggerElement = null;

                // Clear backup setelah semua restore process selesai (delay lebih lama)
                setTimeout(function() {
                    textareaBackup = {}; // Clear backup after restoration
                }, 500); // Delay lebih lama untuk memastikan semua restore selesai
            }, 300); // Delay sesuai durasi animasi modal
        });

        $(function() {

            $(document).ready(function() {




                $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                    localStorage.setItem('activeTab', $(e.target).attr('href'));
                });
                var activeTab = localStorage.getItem('activeTab');
                if (activeTab) {
                    $('#myTab a[href="' + activeTab + '"]').tab('show');
                }

                //$('#tbo_date').datepicker({format: 'yyyy-mm'});
                $('#tbo_date').datepicker({
                    format: "yyyy-mm-dd",
                    todayBtn: "linked"
                });

                var i = 1;


                $(document).ready(function() {
                    $(document).on('click', '.upload-btn', function(e) {
                        e.preventDefault();
                        var btn = $(this);
                        var form = btn.closest('.form-group-fileupload');
                        var fileInput = form.find('input[type="file"]');
                        var progressBar = form.find('.progress');
                        var progressBarInner = progressBar.find('.progress-bar');
                        var alert = form.find('.alert');
                        // Ambil ID sub-dokumen dari tombol, atau kosongkan jika dokumen biasa
                        var subDokumenId = btn.data('id');
                        // Seleksi list upload berdasarkan apakah ini dokumen atau sub-dokumen
                        var uploadedFilesList = subDokumenId ?
                            $('#uploaded-files-list-' + subDokumenId) // Sub-dokumen
                            :
                            form.closest('.card-body').find(
                            '.uploaded-files-list'); // Dokumen biasa


                        if (fileInput[0].files.length === 0) {
                            alert.removeClass('d-none alert-success').addClass(
                                'alert-danger').text('Pilih file terlebih dahulu.');
                            return;
                        }

                        var fileName = fileInput.attr('name');
                        var formData = new FormData();
                        formData.append('file', fileInput[0].files[0]);
                        formData.append('type', btn.data('type'));
                        formData.append('name', fileName);
                        formData.append('id', btn.data('id'));
                        formData.append('loan_app_no', '{{ $datafile->loan_app_no }}');
                        formData.append('_token', '{{ csrf_token() }}');

                        $.ajax({
                            url: '{{ route('datafile.dokumen_store') }}',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            xhr: function() {
                                var xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress",
                                    function(evt) {
                                        if (evt.lengthComputable) {
                                            var percentComplete = evt
                                                .loaded / evt.total;
                                            progressBar.removeClass(
                                                'd-none');
                                            progressBarInner.width(
                                                percentComplete * 100 +
                                                '%');
                                        }
                                    }, false);
                                return xhr;
                            },
                            success: function(response) {
                                alert.removeClass('d-none alert-danger')
                                    .addClass('alert-success').text(response
                                        .success);
                                if (response.file) {
                                    var fileLink = $('<a>')
                                        .attr('href', '/indexed/' + response
                                            .file.name
                                            ) // Path ke file yang diupload
                                        .attr('target',
                                        '_blank') // Membuka link di tab baru
                                        .text(response.file
                                        .alias); // Alias sebagai teks link

                                    var listItem = $('<li>').append(
                                    fileLink); // Bungkus link dalam elemen <li>

                                    uploadedFilesList.append(
                                    listItem); // Tambahkan ke <ul> yang sesuai (dokumen atau sub-dokumen)
                                }

                                fileInput.val('');
                            },
                            error: function(xhr) {
                                var errorMessage = 'Gagal mengupload file.';
                                if (xhr.responseJSON && xhr.responseJSON
                                    .errors && xhr.responseJSON.errors.file) {
                                    errorMessage = xhr.responseJSON.errors.file;
                                }
                                alert.removeClass('d-none alert-success')
                                    .addClass('alert-danger').text(
                                    errorMessage);
                            },
                            complete: function() {
                                setTimeout(function() {
                                    progressBar.addClass('d-none');
                                    progressBarInner.width('0%');
                                }, 2000);
                                location.reload();
                            }
                        });
                    });
                });
                <?php /*
               @for ($i = 1; $i <= $jmldokumen; $i++)
               $('#fileUploadFormKK{{$i}}').ajaxForm(
                    {
                    beforeSend: function () {
                        var percentage = '0';
                        $("#progress1 .progress-bar").css("width", percentage+'%', function() {
                          return $(this).attr("aria-valuenow", percentage) + "%";
                        });
                    },
                    uploadProgress: function (event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $("#progress{{$i}} .progress-bar").css("width", percentage+'%', function() {
                          return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    complete: function (xhr) {
                        var obj = JSON.parse(xhr.responseText);
                        
                        if(obj.hasOwnProperty('message')){
                            $('#MsgErrorUpload{{$i}}').empty();
                            var bodyerror=obj.errors;
                            
                            var msg="";
                            var msghead="<div class=\"alert alert-danger\"><strong>Whoops!</strong> "+obj.message+"<br><br><ul>";
                            var msgbody="";
                            var msgfoot="</ul></div>";
                            
                            $.each(bodyerror, function(key, msgerr){     
                                 msgbody+="<li>"+msgerr+"</li>";
                            });
                            msg=msghead+msgbody+msgfoot;  
                            $('#MsgErrorUpload{{$i}}').html(msg);
                            $('#progress{{$i}} .progress-bar').text('File upload Unsuccessfuly');
                        }else{     
                            $('#progress{{$i}} .progress-bar').text('File has uploaded');
                            location.reload();
                        }
                       
                        //location.reload();
                       
                        //$('#list_kk').html(res);
                    }
                });
               @endfor
              */
                ?>


                // $('#fileUploadFormKK1').ajaxForm({
                //         beforeSend: function () {
                //             var percentage = '0';
                //             $('#progress1 .progress-bar').css("width", percentage+'%', function() {
                //               return $(this).attr("aria-valuenow", percentage) + "%";
                //             });
                //         },
                //         uploadProgress: function (event, position, total, percentComplete) {
                //             var percentage = percentComplete;
                //             $('#progress1 .progress-bar').css("width", percentage+'%', function() {
                //               return $(this).attr("aria-valuenow", percentage) + "%";
                //             })
                //         },
                //         complete: function (xhr) {
                //             var obj = JSON.parse(xhr.responseText);

                //             if(obj.hasOwnProperty('message')){
                //                 $('#MsgErrorUpload1').empty();
                //                 var bodyerror=obj.errors;

                //                 var msg="";
                //                 var msghead="<div class=\"alert alert-danger\"><strong>Whoops!</strong> "+obj.message+"<br><br><ul>";
                //                 var msgbody="";
                //                 var msgfoot="</ul></div>";

                //                 $.each(bodyerror, function(key, msgerr){     
                //                      msgbody+="<li>"+msgerr+"</li>";
                //                 });
                //                 msg=msghead+msgbody+msgfoot;  
                //                 $('#MsgErrorUpload1').html(msg);
                //                 $('#progress1 .progress-bar').text('File upload Unsuccessfuly');
                //             }else{     
                //                 $('#progress1 .progress-bar').text('File has uploaded');
                //                 location.reload();
                //             }

                //             //location.reload();

                //             //$('#list_kk').html(res);
                //         }
                //     });


                //     $('#fileUploadFormKK2').ajaxForm({
                //         beforeSend: function () {
                //             var percentage = '0';
                //             $('#progress2 .progress-bar').css("width", percentage+'%', function() {
                //               return $(this).attr("aria-valuenow", percentage) + "%";
                //             });
                //         },
                //         uploadProgress: function (event, position, total, percentComplete) {
                //             var percentage = percentComplete;
                //             $('#progress2 .progress-bar').css("width", percentage+'%', function() {
                //               return $(this).attr("aria-valuenow", percentage) + "%";
                //             })
                //         },
                //         complete: function (xhr) {
                //             var obj = JSON.parse(xhr.responseText);

                //             if(obj.hasOwnProperty('message')){
                //                 $('#MsgErrorUpload2').empty();
                //                 var bodyerror=obj.errors;

                //                 var msg="";
                //                 var msghead="<div class=\"alert alert-danger\"><strong>Whoops!</strong> "+obj.message+"<br><br><ul>";
                //                 var msgbody="";
                //                 var msgfoot="</ul></div>";

                //                 $.each(bodyerror, function(key, msgerr){     
                //                      msgbody+="<li>"+msgerr+"</li>";
                //                 });
                //                 msg=msghead+msgbody+msgfoot;  
                //                 $('#MsgErrorUpload2').html(msg);
                //                 $('#progress2 .progress-bar').text('File upload Unsuccessfuly');
                //             }else{     
                //                 $('#progress2 .progress-bar').text('File has uploaded');
                //                 location.reload();
                //             }

                //             //location.reload();

                //             //$('#list_kk').html(res);
                //         }
                //     });

                $('#fileCopyFormKK').ajaxForm({
                    beforeSend: function() {
                        var percentage = '0';
                        $('.progress .progress-bar').css("width", percentage + '%', function() {
                            return $(this).attr("aria-valuenow", percentage) + "%";
                        });
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $('.progress .progress-bar').css("width", percentage + '%', function() {
                            return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    complete: function(xhr) {
                        var res = xhr.responseText;
                        //reset();
                        //$('.progress-bar').text('File has uploaded');
                        //location.reload();
                        var obj = JSON.parse(xhr.responseText);

                        if (obj.hasOwnProperty('message')) {
                            $('#MsgErrorCopy').empty();
                            var bodyerror = obj.errors;

                            var msg = "";
                            var msghead =
                                "<div class=\"alert alert-danger\"><strong>Whoops!</strong> " +
                                obj.message + "<br><br><ul>";
                            var msgbody = "";
                            var msgfoot = "</ul></div>";

                            $.each(bodyerror, function(key, msgerr) {
                                msgbody += "<li>" + msgerr + "</li>";
                            });
                            msg = msghead + msgbody + msgfoot;
                            $('#MsgErrorCopy').html(msg);
                            $('.progress-bar').text('File upload Unsuccessfuly');
                        } else {
                            $('.progress-bar').text('File has uploaded');
                            location.reload();
                        }
                    }
                });

                $('#addReview').ajaxForm({
                    beforeSend: function() {
                        var percentage = '0';
                        $('.progress .progress-bar').css("width", percentage + '%', function() {
                            return $(this).attr("aria-valuenow", percentage) + "%";
                        });
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $('.progress .progress-bar').css("width", percentage + '%', function() {
                            return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    complete: function(xhr) {

                        var obj = JSON.parse(xhr.responseText);

                        if (obj.hasOwnProperty('message')) {
                            $('#MsgError').empty();
                            var bodyerror = obj.errors;

                            var msg = "";
                            var msghead =
                                "<div class=\"alert alert-danger\"><strong>Whoops!</strong> " +
                                obj.message + "<br><br><ul>";
                            var msgbody = "";
                            var msgfoot = "</ul></div>";

                            $.each(bodyerror, function(key, msgerr) {
                                msgbody += "<li>" + msgerr + "</li>";
                            });
                            msg = msghead + msgbody + msgfoot;
                            $('#MsgError').html(msg);
                            $('.progress-bar').text('Review Document Unsuccessfuly');
                        } else {
                            $('#MsgError').empty();
                            $('.progress-bar').text('Review Document Done');
                            var idloan = $('#loan_app_no').val();
                            $.ajax({
                                url: '/getReview/' + obj.loan_app_no,
                                type: "GET",
                                data: {
                                    "_token": "{{ csrf_token() }}"
                                },
                                dataType: "json",
                                success: function(data) {
                                    location.reload();
                                    // if(data){


                                    //     $('#ListReview').empty();
                                    //     var head_timeline="<div class=\"row\"><div class=\"col-lg-12\"><div class=\"\"><div class=\"card-body\"><div class=\"vtimeline\">";
                                    //     var body_timeline="";
                                    //     var foot_timeline="</div></div></div></div></div>";
                                    //     var x=1;

                                    //     $.each(data, function(key, review){
                                    //         var comment=review.comment;
                                    //         var reason=review.reason;
                                    //         var nik=review.user_name;
                                    //         var tbo_date=review.tbo_date;
                                    //         var flag_spv=review.flag_spv;
                                    //         var level_spv=review.level_spv;
                                    //         var flag_name="";
                                    //         var name=review.name;

                                    //         var comment_date =review.comment_date;

                                    //         var inverted='';
                                    //         if(x % 2 == 0){
                                    //             inverted="timeline-inverted";
                                    //         }

                                    //         if(level_spv=='spv1'){
                                    //             lbl_level_spv='Spv Branch - ';
                                    //         }else if(level_spv=='spv2'){
                                    //             lbl_level_spv='DCRM REVIEWER - ';
                                    //         }else if(level_spv=='spv3'){
                                    //             lbl_level_spv='DCRM TEAM LEADER - ';
                                    //         }
                                    //         if(flag_spv == 1){
                                    //             flag_name="<div style='color:green;'> "+lbl_level_spv+"Verify</div>";
                                    //         }else if(flag_spv == 2){
                                    //             flag_name="<div style='color:orange;'> "+lbl_level_spv+"Not Verify</div>";
                                    //         }else if(flag_spv == 3){
                                    //             flag_name="<div style='color:blue;'> "+lbl_level_spv+"TBO (To Be Obtained)</div>";
                                    //         }else if(flag_spv == 4){
                                    //             flag_name="<div style='color:red;'> "+lbl_level_spv+"Reject</div>";
                                    //         }
                                    //         if(reason ==""){
                                    //             tag_reason="";
                                    //         }else{
                                    //             tag_reason="<p><b>Reason: &nbsp;</b>" + reason + "\</p>";
                                    //         }
                                    //         if(comment ==""){
                                    //             tag_comment="";
                                    //         }else{
                                    //             tag_comment="<p><b>Note: &nbsp;</b>" + comment + "\</p>";
                                    //         }
                                    //         if(tbo_date ==null){
                                    //             tag_tbo_date="";
                                    //         }else{
                                    //             tag_tbo_date="<p><b>TBO Date: &nbsp;</b>" + tbo_date + "\</p>";
                                    //         }
                                    //         body_timeline+="<div class=\"timeline-wrapper "+inverted+" timeline-wrapper-info\"><div class=\"timeline-badge\"></div><div class=\"timeline-panel\"><div class=\"timeline-heading\"><h6 class=\"timeline-title\">"+flag_name+"</h6></div><div class=\"timeline-body\">"+tag_reason+" "+tag_comment+" "+tag_tbo_date+"</div><div class=\"timeline-footer d-flex align-items-center flex-wrap\"><span></span><span class=\"ml-auto\"><i class=\"fe fe-calendar text-muted mr-1\"></i>" +comment_date+ "\ by " + name +"\</span></div></div></div>";
                                    //         x=x+1;
                                    //     });
                                    //     $('#ListReview').html(head_timeline+body_timeline+foot_timeline);
                                    // }else{
                                    //     $('#ListReview').empty();
                                    // }
                                }
                            });
                        }
                        //location.reload();

                        //$('#list_kk').html(res);
                    }
                });


            });

            $('#flag_spv').on('change', function() {
                var flag_spv = $(this).val();
                var currentComment = $("#comment").val(); // Simpan current value

                if (flag_spv == 1) {
                    $("#field_reason").hide();
                    $("#field_tbo").hide();
                    // Only set default if textarea is empty
                    if (!currentComment || currentComment.trim() === '') {
                        $("#comment").val('Dokumen Lengkap Dan Sesuai');
                        $('#char-count').text(`Sisa Karakter: 74`);
                    } else {
                        // Update char count based on current content
                        var remaining = 100 - currentComment.length;
                        $('#char-count').text(`Sisa Karakter: ${remaining}`);
                    }
                } else if (flag_spv == 2) {
                    // Don't clear textarea if it has content
                    if (!currentComment || currentComment.trim() === '') {
                        $("#comment").val('');
                    }
                    var remaining = 100 - $("#comment").val().length;
                    $('#char-count').text(`Sisa Karakter: ${remaining}`);
                    $("#field_reason").show();
                    $("#field_tbo").hide();
                } else if (flag_spv == 3) {
                    // Don't clear textarea if it has content
                    if (!currentComment || currentComment.trim() === '') {
                        $("#comment").val('');
                    }
                    var remaining = 100 - $("#comment").val().length;
                    $('#char-count').text(`Sisa Karakter: ${remaining}`);
                    $("#field_reason").show();
                    $("#field_tbo").show();
                } else if (flag_spv == 4) {
                    // Don't clear textarea if it has content
                    if (!currentComment || currentComment.trim() === '') {
                        $("#comment").val('');
                    }
                    var remaining = 100 - $("#comment").val().length;
                    $('#char-count').text(`Sisa Karakter: ${remaining}`);
                    $("#field_reason").show();
                    $("#field_tbo").hide();
                } else {
                    // Don't clear textarea if it has content
                    if (!currentComment || currentComment.trim() === '') {
                        $("#comment").val('');
                    }
                    var remaining = 100 - $("#comment").val().length;
                    $('#char-count').text(`Sisa Karakter: ${remaining}`);
                    $("#field_reason").hide();
                    $("#field_tbo").hide();
                }




                if (flag_spv) {
                    $.ajax({
                        url: '/getReason/' + flag_spv,
                        type: "GET",
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function(data) {
                            if (data) {
                                $('#reason').empty();
                                //$('#reason').append('<option hidden>Choose Reason</option>'); 
                                $.each(data, function(key, reason) {
                                    $('select[name="reason[]"]').append(
                                        '<option value="' + reason.reason + '">' +
                                        reason.reason + '</option>');
                                });
                            } else {
                                $('#reason').empty();
                            }
                        }
                    });
                } else {
                    $('#reason').empty();
                }
            });


            $('.flag_spv').select2({
                placeholder: 'Select Status Document',
                width: 'resolve',
                ajax: {
                    url: '/getFlag',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $('.reason').select2({
                placeholder: "Select Reason",
                width: 'resolve',
                allowClear: true
            });

            // Character counter with modal-safe binding
            function bindCharCounter() {
                // Remove any existing handlers first
                $('#comment').off('input keyup paste keydown keypress');

                // Bind clean event handlers
                $('#comment').on('input keyup paste', function(event) {
                    // For keydown/keypress, don't prevent default unless necessary
                    if (event.type === 'keydown' || event.type === 'keypress') {
                        if (event.key && event.key.length === 1 && this.value.length >= 100) {
                            event.preventDefault();
                            return false;
                        }
                    }

                    // Update counter
                    setTimeout(() => {
                        const currentLength = this.value.length;
                        const remainingChars = 150 - currentLength;

                        if (remainingChars <= 0) {
                            $('#char-count').text('Melampaui jumlah maksimal karakter yang diizinkan!');
                        } else {
                            $('#char-count').text(`Sisa Karakter: ${remainingChars}`);
                        }
                    }, 1);
                });
            }

            // Initial binding
            bindCharCounter();

            // Make function available globally for modal cleanup
            window.bindCharCounter = bindCharCounter;

        });
    </script>

    {{-- TEAM VERIFIKATOR SCRIPTS --}}
    <script>
    $(document).ready(function() {
        // ========== TEAM VERIFIKATOR LEVEL 1 ==========

        // Character counter for verif1
        $('#comment_verif1').on('input keyup', function() {
            var maxLength = 150;
            var currentLength = $(this).val().length;
            var remaining = maxLength - currentLength;
            $('#char_count_verif1').text(remaining);
        });

        // Show/hide reason field based on status for verif1
        $('#flag_verif1').on('change', function() {
            if ($(this).val() == '6') {
                $('#reason_verif1_group').show();
                loadReasonsVerif1('reason_verif1', 2); // 2 = Not Verify reasons
            } else {
                $('#reason_verif1_group').hide();
            }
        });

        // Load reasons via AJAX for verif1
        function loadReasonsVerif1(selectId, flagId) {
            $.ajax({
                url: '/getReason/' + flagId,
                type: 'GET',
                success: function(data) {
                    $('#' + selectId).empty();
                    $.each(data, function(key, value) {
                        $('#' + selectId).append(
                            $('<option></option>').val(value.reason).text(value.reason)
                        );
                    });
                }
            });
        }

        // Handle form submit for verif1
        $('#formReviewVerif1').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serialize();
            var submitBtn = $(this).find('button[type="submit"]');

            // Disable submit button
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        alert('SUCCESS!\n\n' + response.message + '\n\nLoan App No: ' + response.loan_app_no);

                        // Redirect to queue page
                        window.location.href = '{{ route("datafile.waiting_verifikator_lvl1_recommendation") }}';
                    } else {
                        alert('Error: ' + (response.message || 'Unknown error'));
                        submitBtn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Submit Rekomendasi');
                    }
                },
                error: function(xhr) {
                    var errorMsg = 'Terjadi kesalahan saat submit.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert('ERROR!\n\n' + errorMsg);
                    submitBtn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Submit Rekomendasi');
                }
            });
        });

        // ========== TEAM VERIFIKATOR LEVEL 2 ==========

        // Character counter for verif2
        $('#comment_verif2').on('input keyup', function() {
            var maxLength = 150;
            var currentLength = $(this).val().length;
            var remaining = maxLength - currentLength;
            $('#char_count_verif2').text(remaining);
        });

        // Show/hide reason field for verif2
        $('#flag_verif2').on('change', function() {
            if ($(this).val() == '6') {
                $('#reason_verif2_group').show();
                $('#reason_verif2').attr('required', true);
                loadReasonsVerif2('reason_verif2', 2);
            } else {
                $('#reason_verif2_group').hide();
                $('#reason_verif2').attr('required', false);
            }
        });

        // Load reasons for verif2
        function loadReasonsVerif2(selectId, flagId) {
            $.ajax({
                url: '/getReason/' + flagId,
                type: 'GET',
                success: function(data) {
                    $('#' + selectId).empty();
                    $.each(data, function(key, value) {
                        $('#' + selectId).append(
                            $('<option></option>').val(value.reason).text(value.reason)
                        );
                    });
                }
            });
        }

        // NEW FLOW: Handle form submit for verif2 (NO FILE UPLOAD)
        $('#formReviewVerif2').on('submit', function(e) {
            e.preventDefault();

            var submitBtn = $('#btnSubmitVerif2');

            // Validate
            if (!$('#flag_verif2').val()) {
                alert('Pilih keputusan final terlebih dahulu');
                return;
            }

            if (!$('#comment_verif2').val()) {
                alert('Komentar wajib diisi');
                return;
            }

            // Confirm
            var flag = $('#flag_verif2').val();
            var flagText = flag == '5' ? 'APPROVE' : 'NOT APPROVE';
            var confirmMsg = 'Apakah Anda yakin dengan keputusan: ' + flagText + '?\n\n' +
                           'Setelah submit, Team Verifikator Level 1 akan upload file bukti pemeriksaan.';

            if (!confirm(confirmMsg)) {
                return;
            }

            // Disable button
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

            // Submit decision (NO FILE UPLOAD)
            $.ajax({
                url: '{{ route("datafile.updateflag") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    loan_app_no: $('input[name="loan_app_no"]').val(),
                    flag_spv: $('#flag_verif2').val(),
                    comment: $('#comment_verif2').val(),
                    reason: $('#reason_verif2').val()
                },
                success: function(res) {
                    if (res.success) {
                        alert('SUCCESS!\n\n' + res.message + '\n\nLoan App No: ' + res.loan_app_no +
                              '\n\nLoan akan muncul di queue Team Verifikator Level 1 untuk upload file bukti.');
                        window.location.href = '{{ url("waiting_verifikator_lvl2") }}';
                    } else {
                        alert('Error: ' + (res.message || 'Unknown error'));
                        submitBtn.prop('disabled', false).html('<i class="fa fa-check-double"></i> Submit Keputusan Final');
                    }
                },
                error: function(xhr) {
                    var errorMsg = 'Gagal menyimpan keputusan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg += ': ' + xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMsg += ':\n';
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errorMsg += '- ' + value[0] + '\n';
                        });
                    }
                    alert(errorMsg);
                    submitBtn.prop('disabled', false).html('<i class="fa fa-check-double"></i> Submit Keputusan Final');
                }
            });
        });
    });
    </script>

    <!-- Upload File Bukti Verifikator Handler -->
    <script>
    $(document).ready(function() {
        console.log('Upload File Bukti script loaded');
        console.log('jQuery version:', $.fn.jquery);
        console.log('Swal available:', typeof Swal !== 'undefined');
        console.log('Form element exists:', $('#formUploadBuktiVerif1').length);

        $('#formUploadBuktiVerif1').on('submit', function(e) {
            console.log('Form submitted - preventing default');
            e.preventDefault();

            var formData = new FormData(this);
            var btn = $('#btnUploadBuktiNew');

            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Uploading...');
            $('#upload_progress_new').show();

            $.ajax({
                url: '{{ route("datafile.uploadBuktiVerifikator") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            $('#upload_progress_bar_new').css('width', percentComplete + '%')
                                .text(Math.round(percentComplete) + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    console.log('Upload success:', response);
                    if(response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: true
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                        btn.prop('disabled', false).html('<i class="fa fa-upload"></i> Upload File Bukti');
                        $('#upload_progress_new').hide();
                    }
                },
                error: function(xhr) {
                    console.log('Upload error:', xhr);
                    var message = 'Terjadi kesalahan saat upload file';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: message
                    });
                    btn.prop('disabled', false).html('<i class="fa fa-upload"></i> Upload File Bukti');
                    $('#upload_progress_new').hide();
                }
            });
        });
    });
    </script>

    <!-- Ready to Disburse Handler -->
    <script>
    $(document).ready(function() {
        console.log('Ready to Disburse script loaded');

        $('#formReadyToDisburse').on('submit', function(e) {
            console.log('Form Ready to Disburse submitted');
            e.preventDefault();

            var loanAppNo = $(this).find('input[name="loan_app_no"]').val();
            var btn = $('#btnReadyToDisburse');

            Swal.fire({
                title: 'Konfirmasi',
                html: 'Flag loan <strong>' + loanAppNo + '</strong> sebagai <strong>Ready to Disburse</strong>?<br><br>' +
                      '<small class="text-danger">Tindakan ini tidak dapat dibatalkan</small>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fa fa-check"></i> Ya, Flag Ready',
                cancelButtonText: '<i class="fa fa-times"></i> Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Disable button
                    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

                    // Send AJAX request
                    $.ajax({
                        url: '{{ url("flag-ready-to-disburs") }}/' + loanAppNo,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Flag success:', response);
                            if(response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: true
                                }).then(() => {
                                    // Redirect to pending disbursement list
                                    window.location.href = '{{ route("datafile.pendingDisbursement") }}';
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message
                                });
                                btn.prop('disabled', false).html('<i class="fa fa-check-circle"></i> Flag Ready to Disburse');
                            }
                        },
                        error: function(xhr) {
                            console.log('Flag error:', xhr);
                            var message = 'Terjadi kesalahan saat flag ready to disburse';
                            if(xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: message
                            });
                            btn.prop('disabled', false).html('<i class="fa fa-check-circle"></i> Flag Ready to Disburse');
                        }
                    });
                }
            });
        });
    });
    </script>

@endsection
