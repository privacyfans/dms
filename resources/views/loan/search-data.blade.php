@extends('layouts.app')

@section('styles')
<style>
    @keyframes blink-warning {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    .warning-title {
        animation: blink-warning 1.5s ease-in-out infinite;
        color: #dc3545 !important; /* Red */
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        display: block;
        margin-bottom: 10px;
    }

    .warning-subtitle {
        color: #000000 !important; /* Black */
        font-size: 16px;
        font-weight: normal;
        text-align: center;
        display: block;
        margin-bottom: 15px;
    }

    .warning-list-header {
        font-size: 13px;
        margin-top: 20px;
        text-align: center;
    }

    .warning-list {
        font-size: 12px;
        text-align: center;
        list-style-position: inside;
    }

    .warning-list li {
        font-size: 12px;
    }

    .alert-warning-orange {
        background-color: #ff9800 !important;
        border-color: #ff9800 !important;
        color: #000000 !important;
        box-shadow: 0 8px 16px rgba(255, 152, 0, 0.4), 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Package Document</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Search</a></li>
								<li class="breadcrumb-item active" aria-current="page">Loan</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
@if (Session::has('error'))
<div class="col-md-12">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong><i class="fa fa-exclamation-triangle"></i> Error!</strong>
        <p class="mb-0 mt-2">{{ Session::get('error') }}</p>
    </div>
</div>
@endif

@if (isset($validationWarning) && !empty($validationWarning))
<div class="col-md-12 d-flex justify-content-center">
    <div class="alert alert-warning alert-warning-orange alert-dismissible fade show" role="alert" style="display: inline-block; width: auto; max-width: 90%;">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <div>
            <span class="warning-title"><i class="fa fa-exclamation-triangle"></i> PERINGATAN</span>
            <span class="warning-subtitle">Pastikan Data dan Informasi Yang Dimasukkan Benar dan Sesuai Dengan Ketentuan!</span>
        </div>
        <p class="mb-2 mt-2" style="text-align: center;">{{ $validationWarning }}</p>

        @if (!empty($availableInstansi))
            <p class="mb-1 mt-3 warning-list-header"><strong>Instansi yang tersedia harus mengandung/dimulai awalan instansi berikut ini :</strong></p>
            <ul class="mb-0 warning-list" style="padding-left: 20px;">
                @foreach ($availableInstansi as $inst)
                    <li>{{ $inst }}</li>
                @endforeach
            </ul>
        @else
            <p class="mb-0 mt-2 warning-list-header"><em>Tidak ada instansi yang terdaftar untuk klasifikasi debitur ini.</em></p>
        @endif
    </div>
</div>
@endif
<form action="{{ route('datafile.savesearchLoan') }}" method="POST">
    @csrf
	<div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<div class="card">
							<div class="card-body">
								<div class="main-content-label mg-b-5">
									Data Loan # {{ $modul}} {{$loan_app_no}}
								</div>
								<p class="mg-b-20 text-muted">Silahkan cek kembali data dibawah ini. Jika sudah sesuai silahkan klik tomobol Save</p>
								<div class="row">
									<div class="col-lg-6 col-md-6">
										<div class="card">
											<div class="card-body">
												<div class="pd-30 pd-sm-40 bg-gray-100">
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Loan App No</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="hidden" name="modul" value="{{ $modul }}">	
															<input class="form-control" placeholder="" type="text" name="loan_app_no" value="{{ $loan_app_no }}" readonly>
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">No Cif</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="no_cif" value="{{ $no_cif }}" readonly> 
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Kode Cabang</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="kode_cabang" value="{{ $kode_cabang }}" readonly>
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Nama Cabang</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="nama_cabang" value="{{ $nama_cabang }}" readonly>
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Nama Debitur</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="nama_debitur" value="{{ $nama_debitur }}" readonly>
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">No Ktp</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="no_ktp" value="{{ $no_ktp }}" readonly>
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Tgl Lahir</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="tgl_lahir" value="{{ changeDate($tgl_lahir) }} ({{ $status_usia_debitur }})" readonly>
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Status Pernikahan</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="hidden" name="kd_status_pernikahan" value="{{ $kd_status_pernikahan }}">
															<input class="form-control" placeholder="" type="text" name="status_pernikahan" value="{{ $status_pernikahan }}" readonly>
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Pekerjaan</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="pekerjaan" value="{{ $pekerjaan }}" readonly>
														</div>
													</div>
													
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Alamat Rumah</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="alamat_rumah" value="{{ $alamat_rumah }}" readonly>
														</div>
													</div>
													
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Instansi</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="instansi" value="{{ $instansi }}" readonly>
														</div>
													</div>
													@if(str_contains($modul, 'KUPEN'))
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Klasifikasi Debitur</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="debtor_classification_combined" value="{{ $debtor_classification_code }} - {{ $debtor_classification_name }}" readonly>
															<input type="hidden" name="debtor_classification_code" value="{{ $debtor_classification_code }}">
															<input type="hidden" name="debtor_classification_name" value="{{ $debtor_classification_name }}">
														</div>
													</div>
													@endif
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Alamat Kantor</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="alamat_kantor" value="{{ $alamat_kantor }}" readonly>
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">No Tlp Kantor</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="no_tlp_kantor" value="{{ $no_tlp_kantor }}" readonly>
														</div>
													</div>
													
													
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6">
										<div class="card">
											<div class="card-body">
												<div class="pd-30 pd-sm-40 bg-gray-100">
											

												<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Produk</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="produk" value="{{ $produk }}" readonly>
														</div>
														
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Plafond</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="plafond" value="{{ $plafond }}" readonly>
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Jangka Waktu</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="jangka_waktu" value="{{ $jangka_waktu }}" readonly>
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Rate</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="rate" value="{{ $rate }}" readonly>
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Angsuran</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="angsuran" value="{{ $angsuran }}" readonly>
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Tgl Jatuh Tempo</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="tgl_jatuh_tempo" value="{{ $tgl_jatuh_tempo }}" readonly>
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Fasilitas</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="fasilitas" value="{{ $fasilitas }}" readonly>
														</div>
													</div>

													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Early</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															@php
																if($early == 0){
																	$early_desc="TIDAK";
																}else{
																	$early_desc="YA";
																}	
															@endphp
																
															<input class="form-control" placeholder="" type="text" name="early-desc" value="{{ $early_desc }}" readonly>
															<input class="form-control" placeholder="" type="hidden" name="early" value="{{ $early }}" readonly>
														</div>
													</div>
													
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Take Over</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															@php
																if($take_over == 0){
																	$take_over_desc="TIDAK";
																}else{
																	$take_over_desc="YA";
																}	
															@endphp
																
															<input class="form-control" placeholder="" type="text" name="take_over-desc" value="{{ $take_over_desc }}" readonly>
															<input class="form-control" placeholder="" type="hidden" name="take_over" value="{{ $take_over }}" readonly>
														</div>
													</div>

													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Take Over Hari Ini</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															@php
																if($take_over_hari_ini == 0){
																	$take_over_hari_ini_desc="TIDAK";
																}else{
																	$take_over_hari_ini_desc="YA";
																}	
															@endphp
																
															<input class="form-control" placeholder="" type="text" name="take_over_hari_ini-desc" value="{{ $take_over_hari_ini_desc }}" readonly>
															<input class="form-control" placeholder="" type="hidden" name="take_over_hari_ini" value="{{ $take_over_hari_ini }}" readonly>
														</div>
													</div>

													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Status Deviasi</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															@php
																if($status_deviasi == 0){
																	$status_deviasi_desc="TIDAK";
																}else{
																	$status_deviasi_desc="YA";
																}	
															@endphp
																
															<input class="form-control" placeholder="" type="text" name="status_deviasi-desc" value="{{ $status_deviasi_desc }}" readonly>
															<input class="form-control" placeholder="" type="hidden" name="status_deviasi" value="{{ $status_deviasi }}" readonly>
														</div>
													</div>

													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Fronting Agent</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															@php
																if($fronting_agent == 0){
																	$fronting_agent_desc="TIDAK";
																}else{
																	$fronting_agent_desc="YA";
																}	
															@endphp
																
															<input class="form-control" placeholder="" type="text" name="fronting_agent-desc" value="{{ $fronting_agent_desc }}" readonly>
															<input class="form-control" placeholder="" type="hidden" name="fronting_agent" value="{{ $fronting_agent }}" readonly>
														</div>
													</div>
													@if($modul=='KUPEG' || $modul=='KUPEN HYBRID' || $modul=='KUPEN HYBRID GO')			
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-4">
															<label class="form-label mg-b-0">Status Pegawai</label>
														</div>
														<div class="col-md-8 mg-t-5 mg-md-t-0">
															<input class="form-control" placeholder="" type="text" name="status_pegawai-desc" value="{{ $status_pegawai }}" readonly>
															
														</div>
													</div>
													@endif
													<input class="form-control" placeholder="" type="hidden" name="status_pegawai" value="{{ $status_pegawai }}" readonly>
													<input class="form-control" placeholder="" type="hidden" name="status_usia_debitur" value="{{ $status_usia_debitur }}" readonly>
												
												</div>
											</div>
										</div>
									</div>
								</div>
								@if(!$isValidInstansi)
									<button type="button" class="btn btn-secondary pd-x-30 mg-r-5 mg-t-5" disabled title="Data instansi tidak valid. Silakan hubungi administrator.">
										<i class="fa fa-lock"></i> Save (Disabled)
									</button>
									<small class="text-danger d-block mt-2">
										<i class="fa fa-info-circle"></i> Tombol Save dinonaktifkan karena instansi tidak sesuai dengan klasifikasi debitur.
									</small>
								@else
									<button type="submit" class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">Save</button>
								@endif
								<a class="btn btn-dark pd-x-30 mg-t-5" href="{{ route('datafile.searchLoan') }}">Cancel</a>
							</div>
						</div>
					</div>
				
					
				</div>
				<!-- /row -->
				
			</div>
		</div>
	</div>
	<!--/div-->
</form>               
                    



    

@endsection