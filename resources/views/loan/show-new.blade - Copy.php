@extends('layouts.app')

@section('styles')
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />

    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />

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
                                                                    ) use ($products) {
                                                                        return (isKupeg($products->jenis_produk) &&
                                                                            $dokumen->kupeg) ||
                                                                            (isKupen($products->jenis_produk) &&
                                                                                $dokumen->kupen) ||
                                                                            (isKupenHybrid($products->jenis_produk) &&
                                                                                $dokumen->kupen_hybrid);
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
                                                                                        <td>{{ $br->comment }}</td>
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
                                                                <?php /*
                                                                    @if (
                                                                        ($previousUrl == 'https://dms.bankwoorisaudara.com/pickup' && Session("role")=='spv2')
                                                                        || (($selisih->days >= 2) && Session("role")=='spv2')  
                                                                        || (Session("role")=='staff')
                                                                        || (Session("role")=='spv3') 
                                                                        || (Session("role")=='spv4')
                                                                        || getUserPickupLoan($datafile->loan_app_no,Session("nik")))
                                                                    */
                                                                ?>
                                                                @if (
                                                                    ($previousUrl == 'https://dms.bankwoorisaudara.com/pickup' &&
                                                                        (Session('role') == 'spv3' || Session('role') == 'spv4')) ||
                                                                        ($selisih->days >= 2 && (Session('role') == 'spv3' || Session('role') == 'spv4')) ||
                                                                        Session('role') == 'staff' ||
                                                                        getUserPickupLoan($datafile->loan_app_no, Session('nik')))
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
                                                                                    class="form-label">Add Note <i>(Max 100
                                                                                        Karakter)</i></label>
                                                                                <textarea name="comment" id="comment" placeholder="Add Note" class="form-control" rows="3"
                                                                                    maxlength="100"></textarea>
                                                                                <i>
                                                                                    <div id="char-count">Sisa Karakter: 100
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
    <div class="modal fade mediumModal-modal-lg" id="mediumModal" tabindex="-1" role="dialog"
        aria-labelledby="mediumModalLabel" aria-hidden="true">
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
    <script>
        $(document).on('click', '#mediumButton', function(event) {
            event.preventDefault();
            let href = $(this).attr('data-attr');
            console.log(href);
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
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
                        console.log(subDokumenId);
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
                        console.log(fileName);
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
                                console.log('Upload success:', response);
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
                                console.log('Upload error:', xhr);
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
                        console.log("1. i adalah ="+{{$i}});
                        $("#progress1 .progress-bar").css("width", percentage+'%', function() {
                          return $(this).attr("aria-valuenow", percentage) + "%";
                        });
                    },
                    uploadProgress: function (event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        console.log("2. i adalah ={{$i}}");
                        $("#progress{{$i}} .progress-bar").css("width", percentage+'%', function() {
                          return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    complete: function (xhr) {
                        var obj = JSON.parse(xhr.responseText);
                        
                        if(obj.hasOwnProperty('message')){
                            console.log("3. i adalah ={{$i}}");
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
                            console.log("4. i adalah ={{$i}}");
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
                console.log(flag_spv);

                if (flag_spv == 1) {
                    $("#field_reason").hide();
                    $("#field_tbo").hide();
                    $("#comment").val('Dokumen Lengkap Dan Sesuai');
                    $('#char-count').text(`Sisa Karakter: 74`);
                } else if (flag_spv == 2) {
                    $("#comment").val('');
                    $('#char-count').text(`Sisa Karakter: 100`);
                    $("#field_reason").show();
                    $("#field_tbo").hide();
                } else if (flag_spv == 3) {
                    $('#char-count').text(`Sisa Karakter: 100`);
                    $("#comment").val('');
                    $("#field_reason").show();
                    $("#field_tbo").show();
                } else if (flag_spv == 4) {
                    $('#char-count').text(`Sisa Karakter: 100`);
                    $("#comment").val('');
                    $("#field_reason").show();
                    $("#field_tbo").hide();
                } else {
                    $('#char-count').text(`Sisa Karakter: 100`);
                    $("#comment").val('');
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

            $('#comment').on('input', function() {
                const maxLength = 100;
                const currentLength = $(this).val().length;
                const remainingChars = maxLength - currentLength;

                if (remainingChars == 0) {
                    $('#char-count').text('Melampaui jumlah maksimal karakter yang diizinkan!');

                } else {
                    $('#char-count').text(`Sisa Karakter: ${remainingChars}`);
                }
            });

        });
    </script>
@endsection
