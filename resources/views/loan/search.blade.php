@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">New Request Document</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Search</a></li>
								<li class="breadcrumb-item active" aria-current="page">Loan</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')


@section('content')

<form action="{{ route('datafile.submitsearchLoan') }}" method="POST">
    @csrf
                <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
						<div class="card">
							<div class="card-body">
								<div class="main-content-label mg-b-5">
									New Request Document
								</div>
								<p class="mg-b-20 text-muted">Silahkan cari menggunakan Loan App Number</p>
								
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

								<div class="row mg-t-10">
                                    <div class="col-lg-3">
										{{-- <label class="rdiobox"><input checked name="search_option" value="Loan App No" type="radio"> <span>Loan App No</span></label> --}}
										<input type="hidden" id="search_option" name="search_option" value="Loan App No">
									</div>
									{{-- <div class="col-lg-3 mg-t-20 mg-lg-t-0">
										<label class="rdiobox"><input name="search_option" value="CIF" type="radio"> <span>CIF</span></label>
									</div> --}}
									
                                        	
                                    
								</div>
							

                                <div class="row mg-t-12">
                                    <div class="col-lg-2">
										<p class="mg-b-10">Loan App No</p>
                                        <input type="text" name="nomor" value="" class="form-control" required="" placeholder="Search Loan App No ...">		
                                    </div>
									<div class="col-lg-2">
										<p class="mg-b-10">Produk</p>
										<select class="form-control select2-no-search" id="produk" name="produk" required="">
											<option label="Pilih Produk">
											</option>
											<option value="KUPEN UMUM">
												KUPEN UMUM
											</option>
											<option value="KUPEN JANDA">
												KUPEN JANDA
											</option>
											<option value="KUPEN HYBRID">
												KUPEN HYBRID
											</option>
											<option value="KUPEN HYBRID GO">
												KUPEN HYBRID GO
											</option>
											<option value="KUPEG">
												KUPEG
											</option>
											<option value="KPH">
												KPH
											</option>
											<option value="THT">
												THT
											</option>
											<option value="KPKB-WNI">
												KPKB-WNI
											</option>
											<option value="KPKB-WNA">
												KPKB-WNA
											</option>
											<option value="TAWON">
												TAWON
											</option>
										</select>
									</div>

									<div class="col-lg-2" >
										<div id="fas">
											<p class="mg-b-10">Fasilitas</p>
											<select class="form-control select2-no-search" id="fasilitas" name="fasilitas">
												<option value="1">
												Fasilitas 1
												</option>
												<option value="2">
												Fasilitas 2
												</option>
												<option value="3">
													Fasilitas 3
													</option>
											</select>
										</div>
										<div id="job">
											<p class="mg-b-10">Pekerjaan</p>
											<select class="form-control select2-no-search" id="pekerjaan" name="pekerjaan">
												<option value="KARYAWAN">
												KARYAWAN
												</option>
												<option value="PROFESIONAL">
												PROFESIONAL
												</option>
												<option value="WIRASWASTA">
												WIRASWASTA
												</option>
											</select>
										</div>
										
									</div>
									

									
									
									


                                </div>
								<div class="row mt-3">
								
								<div class="col-lg-2" >
										<div id="div_take_over">
											<p class="mg-b-10">Take Over</p>
											<select class="form-control select2-no-search" id="take_over" name="take_over">
												<option value="0">
													TIDAK
												</option>
												<option value="1">
													YA
												</option>
											</select>
										</div>
									</div>

									<div class="col-lg-2" >
										<div id="div_early">
											<p class="mg-b-10">Early</p>
											<select class="form-control select2-no-search" id="early" name="early">
												<option value="0">
													TIDAK
												</option>
												<option value="1">
													YA
												</option>
											</select>
										</div>
									</div>

									<div class="col-lg-2" >
										<div id="div_take_over_hari_ini">
											<p class="mg-b-10">Take Over Hari Ini</p>
											<select class="form-control select2-no-search" id="take_over_hari_ini" name="take_over_hari_ini">
												<option value="0">
													TIDAK
												</option>
												<option value="1">
													YA
												</option>
											</select>
										</div>
									</div>
									
									
									
								
								</div>
								<div class="row mt-3">
									
									<div class="col-lg-2" >
										<div id="div_status_deviasi">
											<p class="mg-b-10">Status Deviasi</p>
											<select class="form-control select2-no-search" id="status_deviasi" name="status_deviasi">
												<option value="0">
													TIDAK
												</option>
												<option value="1">
													YA
												</option>
											</select>
										</div>
									</div>
									<div class="col-lg-2" >
										<div id="div_fronting_agent">
											<p class="mg-b-10">Fronting Agent</p>
											<select class="form-control select2-no-search" id="fronting_agent" name="fronting_agent">
												<option value="0">
													TIDAK
												</option>
												<option value="1">
													YA
												</option>
											</select>
										</div>
									</div>
									<div class="col-lg-2" >
										<div id="div_status_pegawai">
											<p class="mg-b-10">Status Pegawai</p>
											<select class="form-control select2-no-search" id="status_pegawai" name="status_pegawai">
												<option value="">
												Pilih Status Pegawai
												</option>
												<option value="SWASTA">
												SWASTA
												</option>
												<option value="TNI/ Polri">
												TNI/ Polri
												</option>
												<option value="ASN">
												ASN
												</option>
											</select>
										</div>
									</div>
								</div>
								
								<div class="row mg-t-10">
								<div class="col-lg-3">
										<button type="submit" class="btn btn-main-primary "><i class="fas fa-search"></i> &nbsp;Search</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--/div-->
</form>               
                   



    

@endsection

@section('scripts')

<script>
   
   $(function () {
            $(document).ready(function () {
					$('#fas').hide(); 
					$('#div_early').hide(); 
					$('#div_take_over').hide(); 
					$('#div_take_over_hari_ini').hide(); 
					$('#div_fronting_agent').hide(); 
					//$('#div_status_menikah').hide(); 
					//$('#div_ket_tidak_menikah').hide(); 
					$('#div_status_deviasi').hide(); 
					$('#div_status_pegawai').hide(); 
					$('#produk').change(function(){
						var selectedProduct = $(this).val();
        				var $statusPegawai = $('#status_pegawai');
						// Hapus semua opsi yang ada
						$statusPegawai.empty();
        
						// Tambahkan opsi berdasarkan produk yang dipilih
						if (selectedProduct === "KUPEG") {
							$statusPegawai.append(new Option("Pilih Status Pegawai", ""));
							$statusPegawai.append(new Option("SWASTA", "SWASTA"));
							$statusPegawai.append(new Option("TNI/ Polri", "TNI/ Polri"));
							$statusPegawai.append(new Option("ASN", "ASN"));
						} else {
							$statusPegawai.append(new Option("Pilih Status Pegawai", ""));
							$statusPegawai.append(new Option("TNI/ Polri", "TNI/ Polri"));
							$statusPegawai.append(new Option("ASN", "ASN"));
						}
						
						// Trigger perubahan untuk memperbarui tampilan Select2 (jika digunakan)
						$statusPegawai.trigger('change');
						
						if($('#produk').val() == 'KUPEN UMUM' || 
						   $('#produk').val() == 'KUPEN JANDA' || 
						   $('#produk').val() == 'KUPEN HYBRID' || 
						   $('#produk').val() == 'KUPEN HYBRID GO' || 
						   $('#produk').val() == 'KUPEG')
						   {

						   if($('#produk').val() == 'KUPEN UMUM' || $('#produk').val() == 'KUPEN JANDA'){
								$('#div_status_pegawai').hide();
								$('#status_pegawai').removeAttr('required');
						   }else{
								$('#div_status_pegawai').show();
								$('#status_pegawai').attr('required', true);
						   }

						  

							$('#fas').show(); 
							$('#div_take_over').show(); 
							$('#div_status_deviasi').show(); 
							$('#div_fronting_agent').show(); 
							
							$('#take_over').change(function(){
								if($('#take_over').val() == '0') {
									$('#div_early').hide(); 
									$('#div_take_over_hari_ini').hide(); 
								} else{
									$('#div_take_over_hari_ini').show(); 
									$('#div_early').show(); 
									$('#early').val(0); 
							}});
							
							$('#early').change(function(){
								if($('#early').val() == '0') {
									$('#div_take_over_hari_ini').show(); 
									$('#take_over_hari_ini').val(0); 
								} else{
									$('#div_take_over_hari_ini').hide(); 
							}
							}
							);
						} else {
							$('#fas').hide(); 
							$('#div_status_deviasi').hide(); 
							$('#div_status_pegawai').hide(); 
							$('#div_fronting_agent').hide(); 
							$('#div_take_over').hide(); 
							$('#div_take_over_hari_ini').hide(); 
							$('#div_early').hide(); 
						} 
					});

					$('#job').hide(); 
					$('#produk').change(function(){
						if($('#produk').val() == 'KPKB-WNI') {
							$('#job').show(); 
						} else {
							$('#job').hide(); 
							
						} 
					});

					$('form').on('submit', function(e){
						if($('#div_status_pegawai').is(':visible')) {
							if($('#status_pegawai').val() == '') {
								e.preventDefault();
								alert('Status Pegawai harus dipilih!');
								$('#status_pegawai').focus();
								return false;
							}
						}
					});
			})
	});



</script>

@endsection