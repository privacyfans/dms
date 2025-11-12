@extends('layouts.app')

@section('styles')

		<!--  Owl-carousel css-->
		<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet" />

		<!-- Maps css -->
		<link href="{{URL::asset('assets/plugins/jqvmap/jqvmap.min.css')}}" rel="stylesheet">

		<!-- Jvectormap css -->
        <link href="{{URL::asset('assets/plugins/jqvmap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />
		<style>
		p {
			display: block;
			margin: 0px !important;
		}
		</style>
@endsection


@section('content')



@if($news != null)
<!-- medium modal -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
aria-hidden="true">
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h4>{{$news->title}}</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body" id="mediumBody">
			<div>
				<p>{!! $news->desc !!}</p>
				<p>Link Document: <a target="_blank" href="/uploads/{{ $news->file }}">{{$news->file}}</a></p>
				<!-- the result to be displayed apply here -->
			</div>
		</div>
	</div>
</div>
</div>
@endif

							
									


				<div class="row row-sm">
					<div class="col-lg-12">
						<div class="row row-sm">
						@if (session('error'))
							
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
								<div class="card ">
									<div class="card-body iconfont text-left">
										
											<div class="alert alert-danger mg-b-0" role="alert">
												<button aria-label="Close" class="close" data-dismiss="alert" type="button">
													<span aria-hidden="true">&times;</span>
												</button>
												<p><b>{{ session('error') }}</b></p>
											</div>
										
									</div>
								</div>
							</div>
						@endif
							@if ($error!="")
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
								<div class="card ">
									<div class="card-body iconfont text-left">
										
											<div class="alert alert-danger mg-b-0" role="alert">
												<button aria-label="Close" class="close" data-dismiss="alert" type="button">
													<span aria-hidden="true">&times;</span>
												</button>
												<p><b>{{ $error }}</b></p>
											</div>
										
									</div>
								</div>
							</div>
							@endif
							
							
							<div class="col-lg-12">
								<div class="card mg-b-20">
									<div class="card-body d-flex p-3">
										<div class="card-title mb-0 mg-t-8" style="color:#0a5ef2;">Daily Task</div>
										<!-- <div class="ml-auto">
											<a class="d-block tx-20" data-placement="top" data-toggle="tooltip" title="" href="#" data-original-title="Add New User"><i class="si si-plus text-muted"></i></a></div> -->
									</div>
								</div>
							</div>
											<div class="col-sm">
												<a href="{{url('daily-transaction')}}">
													<div class="card"  style="max-width: 100%;background-color: #6cb3f3 !important;">
														
															<div class="card-body iconfont text-left">
																<div class="d-flex justify-content-between">
																	<h4 class="card-title mb-3" style="color:white !important;">Daily Transaction</h4>
																</div>
																<div class="d-flex mb-0">
																	<div class="">
																		<h1 class="mb-1 font-weight-bold" style="color:black !important;">{{ $totaldailyprocessed }} / {{ $totaldaily }}</h1>
																		<p class="mb-2 tx-12 text-muted" style="color:black !important;"><i class="fas fa-arrow-circle-up text-success"></i> Daily Transaction <br> <br></p>
																	</div>
																	<div class="card-chart bg-primary-transparent brround ml-auto mt-0">
																		<div class="badge badge-info">All</div>
																	</div>
																	</div>
															</div>
														
													</div>
												</a>
											</div>	

											<!-- <div class="col-sm">
												<a href="{{url('daily-spv1')}}">
													<div class="card"  style="max-width: 100%;background-color: #35b5f4 !important;">
														<div class="card-body iconfont text-left">
															<div class="d-flex justify-content-between">
																	<h4 class="card-title mb-3" style="color:white !important;">Waiting Verify Unit Bisnis SPV</h4>
															</div>
															<div class="d-flex mb-0">
																<div class="">
																	<h1 class="mb-1 font-weight-bold" style="color:white !important;">{{ $waitingdailyspv }}</h1>
																	<p class="mb-2 tx-12 text-muted" style="color:white !important;"><i class="fas fa-arrow-circle-up text-success"></i>  Dokumen yang belum di verifikasi oleh Unit Bisnis SPV. <br>  <br></p>
																</div>
																	<div class="card-chart bg-warning-transparent brround ml-auto mt-0">
																		<div class="badge badge-success">Unit Bisnis</div>
																	</div>
															</div>
														</div>
													</div>
												</a>
											</div>	

											<div class="col-sm">
												<a href="{{url('daily-bpr1')}}">
													<div class="card"  style="max-width: 100%;background-color: #35b5f4 !important;">
														<div class="card-body iconfont text-left">
															<div class="d-flex justify-content-between">
																<h4 class="card-title mb-3" style="color:white !important;">Waiting Verify Reviewer</h4>
															</div>
															<div class="d-flex mb-0">
																<div class="">
																	<h1 class="mb-1 font-weight-bold" style="color:white !important;">{{ $waitingdailybpr1 }}</h1>
																	<p class="mb-2 tx-12 text-muted" style="color:white !important;"><i class="fas fa-arrow-circle-up text-success"></i>   Dokumen yang belum di verifikasi oleh DCRM Reviewer. <br>  <br></p>
																</div>
																	<div class="card-chart bg-info-transparent brround ml-auto mt-0">
																		<div class="badge badge-secondary">DCRM</div>
																	</div>
															</div>	
														</div>
													</div>
												</a>
											</div> -->

											<div class="col-sm">
												<a href="{{url('daily-bpr2')}}">
													<div class="card"  style="max-width: 100%;background-color: #0D1836 !important;">
														<div class="card-body iconfont text-left">
															<div class="d-flex justify-content-between">
																<h4 class="card-title mb-3" style="color:white !important;">Waiting Verify DCRM</h4>
															</div>
															<div class="d-flex mb-0">
																<div class="">
																	<h1 class="mb-1 font-weight-bold" style="color:white !important;">{{ $waitingdailybpr2 }}</h1>
																	<p class="mb-2 tx-12 text-muted" style="color:white !important;"><i class="fas fa-arrow-circle-up text-success"></i>Dokumen yang belum di verifikasi oleh DCRM.<br><br></p>
																</div>
																	<div class="card-chart bg-info-transparent brround ml-auto mt-0">
																		<div class="badge badge-warning">DCRM</div>
																	</div>
															</div>	
														</div>
													</div>
												</a>
											</div>

											
							<!-- =======================	Pending Task		 -->
							<div class="col-lg-12">
								<div class="card mg-b-20">
									<div class="card-body d-flex p-3">
										<div class="card-title mb-0 mg-t-8" style="color:#0a5ef2;">Pending Task</div>
										<!-- <div class="ml-auto">
											<a class="d-block tx-20" data-placement="top" data-toggle="tooltip" title="" href="#" data-original-title="Add New User"><i class="si si-plus text-muted"></i></a></div> -->
									</div>
								</div>
							</div>
								
							<!-- ============== -->
							
							<!-- <div class="col-lg-4">
								
								
								@if(Session("role")=="spv2" || Session("role")=="spv3" || Session("role")=="spv4")
								<a href="{{url('waiting_spv1')}}">
								@endif
								<div class="card"  style="max-width: 30rem;background-color: #f5bf41 !important;">
									<div class="card-body iconfont text-left">
										<div class="d-flex justify-content-between">
											<h4 class="card-title mb-3" style="color:black !important;">Waiting Verify Unit Bisnis SPV</h4>
											
										</div>
										<div class="d-flex mb-0">
											<div class="">
												<h1 class="mb-1 font-weight-bold" style="color:black !important;">{{ $total_spv1 }}</h1>
												<p class="mb-2 tx-12 text-muted" style="color:black !important;"><i class="fas fa-arrow-circle-up text-success"></i> Dokumen yang belum di verifikasi oleh Unit Bisnis SPV</p>
											</div>
											<div class="card-chart bg-warning-transparent brround ml-auto mt-0">
												<div class="badge badge-success">Unit Bisnis</div>
											</div>
										</div>
									</div>
								</div>
								@if(Session("role")=="spv2" || Session("role")=="spv3" || Session("role")=="spv4")
								</a>
								@endif
							</div>
							<div class="col-lg-4">
								@if(Session("role")=="spv2" || Session("role")=="spv3" || Session("role")=="spv4")
								<a href="{{url('waiting_spv2')}}">
								@endif
								<div class="card"  style="max-width: 30rem;background-color: #35b5f4 !important;">
									<div class="card-body iconfont text-left">
										<div class="d-flex justify-content-between">
											<h4 class="card-title mb-3" style="color:white !important;">Waiting Verify Reviewer</h4>
											
										</div>
										<div class="d-flex mb-0">
											<div class="">
												<h1 class="mb-1   font-weight-bold" style="color:white !important;">{{ $total_spv2 }}</h1>
												<p class="mb-2 tx-12 text-muted" style="color:white !important;"><i class="fas fa-arrow-circle-up text-success"></i> Dokumen yang belum di verifikasi oleh DCRM Reviewer</p>
											</div>
											<div class="card-chart bg-info-transparent brround ml-auto mt-0">
												<div class="badge badge-secondary">DCRM</div>
											</div>
										</div>
									</div>
								</div>
								@if(Session("role")=="spv2" || Session("role")=="spv3" || Session("role")=="spv4")
								</a>
								@endif
							</div> -->

							<div class="col-lg-12">
								@if(Session("role")=="spv2" || Session("role")=="spv3" || Session("role")=="spv4")
								<a href="{{url('waiting_spv3')}}">
								@endif
								<div class="card"  style="background-color: #F5735B !important;">
									<div class="card-body iconfont text-left">
										<div class="d-flex justify-content-between">
											<h4 class="card-title mb-3" style="color:white !important;">Pending Verify DCRM</h4>
										</div>
										<div class="d-flex mb-0">
											<div class="">
												<h1 class="mb-1   font-weight-bold" style="color:white !important;">{{ $total_spv3 }}</h1>
												<p class="mb-2 tx-12 text-muted" style="color:white !important;"><i class="fas fa-arrow-circle-up text-success"></i> Dokumen yang belum di verifikasi oleh DCRM</p>
											</div>
											<div class="card-chart bg-info-transparent brround ml-auto mt-0">
												<div class="badge badge-warning">DCRM</div>
											</div>
										</div>
									</div>
								</div>
								@if(Session("role")=="spv2" || Session("role")=="spv3" || Session("role")=="spv4")
								</a>
								@endif
							</div>

							<!-- =======================	TBO Task		 -->
							<div class="col-lg-12">
								<div class="card mg-b-20">
									<div class="card-body d-flex p-3">
										<div class="main-content-label mb-0 mg-t-8" style="color:#0a5ef2;">TBO Task</div>
										<!-- <div class="ml-auto">
											<a class="d-block tx-20" data-placement="top" data-toggle="tooltip" title="" href="#" data-original-title="Add New User"><i class="si si-plus text-muted"></i></a></div> -->
									</div>
								</div>
							</div>
								
							<!-- ============== -->
							<div class="col-lg-3 col-md-6 col-sm-12">
							<a href="{{url('tbo')}}">

								<div class="card"  style="background-color: #64A1D8 !important;">
									<div class="card-body iconfont text-left">
										<div class="d-flex justify-content-between">
											<h4 class="card-title mb-3" style="color:white !important;">Total TBO</h4>
										</div>
										<div class="d-flex mb-0">
											<div class="">
												<h1 class="mb-1   font-weight-bold" style="color:#275A89 !important;">{{ $total_tbo }}</h1>
												<p class="mb-2 tx-12 text-muted" style="color:white !important;"><i class="fas fa-arrow-circle-up text-success"></i> Dokumen yang berstatus TBO<br>&nbsp;<br></p>
											</div>
											<div class="card-chart bg-primary-transparent brround ml-auto mt-0">
												<div class="badge badge-info">All</div>
											</div>
										</div>
									</div>
								</div>
							
							</a>
							</div>
							<div class="col-lg-3 col-md-6 col-sm-12">
							<a href="{{url('tbo_overdue')}}">

								<div class="card"  style="background-color: #EE664C !important;">
									<div class="card-body iconfont text-left">
										<div class="d-flex justify-content-between">
											<h4 class="card-title mb-3" style="color:white !important;">Data Loan All TBO Overdue</h4>
										</div>
										<div class="d-flex mb-0">
											<div class="">
												<h1 class="mb-1   font-weight-bold" style="color:#632115 !important;">{{ $total_tbo_overdue }}</h1>
												<p class="mb-2 tx-12 text-muted" style="color:white !important;"><i class="fas fa-arrow-circle-up text-success"></i> Dokumen yang berstatus TBO Overdue <br></p>
											</div>
											<div class="card-chart bg-white brround ml-auto mt-0" style="width: 150px !important; height: auto !important; ">
												<img src="{{URL::asset('assets/img/alert.gif')}}" alt="arrow" style="width: auto; height: auto; vertical-align: middle;">
											</div>
										</div>
									</div>
								</div>
							
							</a>
							</div>
							<div class="col-lg-3 col-md-6 col-sm-12">
							<a href="{{url('tbo_overdue_pending')}}">

								<div class="card"  style="background-color: #2983D9 !important;">
									<div class="card-body iconfont text-left">
										<div class="d-flex justify-content-between">
											<h4 class="card-title mb-3" style="color:white !important;">Data Loan All TBO Pending</h4>
										</div>
										<div class="d-flex mb-0">
											<div class="">
												<h1 class="mb-1   font-weight-bold" style="color:#162E50 !important;">{{ $total_tbo_pending }}</h1>
												<p class="mb-2 tx-12 text-muted" style="color:white !important;"><i class="fas fa-arrow-circle-up text-success"></i> Dokumen yang berstatus TBO Pending <br></p>
											</div>
											<div class="card-chart bg-primary-transparent brround ml-auto mt-0">
												<div class="badge badge-info">All</div>
											</div>
										</div>
									</div>
								</div>
							
							</a>
							</div>
							<!-- <div class="col-sm">
								@if(Session("role")=="spv2" || Session("role")=="spv3" || Session("role")=="spv4")
								<a href="{{url('waiting_tbo_spv2')}}">
								@endif
								<div class="card"  style="max-width: 17rem;background-color: #6cb3f3 !important;">
									<div class="card-body iconfont text-left">
										<div class="d-flex justify-content-between">
											<h4 class="card-title mb-3" style="color:black !important;">Waiting TBO Reviewer</h4>
											
										</div>
										<div class="d-flex mb-0">
											<div class="">
												<h1 class="mb-1   font-weight-bold" style="color:black !important;">{{ $total_spv2_tbo }}</h1>
												<p class="mb-2 tx-12 text-muted" style="color:black !important;"><i class="fas fa-arrow-circle-up text-success"></i> Dokumen yang belum di verifikasi oleh DCRM Reviewer <br></p>
											</div>
											<div class="card-chart bg-info-transparent brround ml-auto mt-0">
												<div class="badge badge-secondary">DCRM</div>
											</div>
										</div>
									</div>
								</div>
								@if(Session("role")=="spv2" || Session("role")=="spv3" || Session("role")=="spv4")
								</a>
								@endif
							</div> -->
							<div class="col-lg-3 col-md-6 col-sm-12">
								@if(Session("role")=="spv2" || Session("role")=="spv3" || Session("role")=="spv4")
								<a href="{{url('waiting_tbo_spv3')}}">
								@endif
								<div class="card"  style="background-color: #08364E !important;">
									<div class="card-body iconfont text-left">
										<div class="d-flex justify-content-between">
											<h4 class="card-title mb-3" style="color:white !important;">Waiting TBO DCRM </h4>
										</div>
										<div class="d-flex mb-0">
											<div class="">
												<h1 class="mb-1   font-weight-bold" style="color:#DDF3FF !important;">{{ $total_spv3_tbo }}</h1>
												<p class="mb-2 tx-12 text-muted" style="color:white !important;"><i class="fas fa-arrow-circle-up text-success"></i> Dokumen yang belum di verifikasi oleh DCRM</p>
											</div>
											<div class="card-chart bg-info-transparent brround ml-auto mt-0">
												<div class="badge badge-warning">DCRM</div>
											</div>
										</div>
									</div>
								</div>
								@if(Session("role")=="spv2" || Session("role")=="spv3" || Session("role")=="spv4")
								</a>
								@endif
							</div>
							
						</div>
					</div>
					
				</div>
				
				<!-- row close -->

@endsection('content')

@section('scripts')

		<!--Internal  Chart.bundle js -->
		<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>

		<!--Internal Sparkline js -->
		<script src="{{URL::asset('assets/plugins/jquery-sparkline/jquery.sparkline.min.js')}}"></script>

		<!-- Moment js -->
		<script src="{{URL::asset('assets/plugins/raphael/raphael.min.js')}}"></script>

		<!--Internal  Flot js-->
		<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.pie.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.resize.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.categories.js')}}"></script>
		<script src="{{URL::asset('assets/js/dashboard.sampledata.js')}}"></script>
		<script src="{{URL::asset('assets/js/chart.flot.sampledata.js')}}"></script>

		<!-- Chart-circle js -->
		<script src="{{URL::asset('assets/plugins/circle-progress/circle-progress.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/chart-circle/chart-circle.js')}}"></script>

		<!-- ECharts js-->
		<script src="{{URL::asset('assets/plugins/echart/echart.js')}}"></script>

		<script>
			// display a modal (small modal)
			$(document).on('click', '#smallButton', function(event) {
				event.preventDefault();
				let href = $(this).attr('data-attr');
				$.ajax({
					url: href,
					beforeSend: function() {
						$('#loader').show();
					},
					// return the result
					success: function(result) {
						$('#smallModal').modal("show");
						$('#smallBody').html(result).show();
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
	
			// display a modal (medium modal)
			$(document).on('click', '#mediumButton', function(event) {
				event.preventDefault();
				let href = $(this).attr('data-attr');
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
	
		</script>
		@if($news != null)
		<script>
			$('#mediumModal').modal("show");
			// window.setTimeout(function(){ 
			// 	// First check, if localStorage is supported.
			// 	if (window.localStorage) {
			// 		// Get the expiration date of the previous popup.
			// 		var nextPopup = localStorage.getItem( 'nextNewsletter' );
			
			// 		if (nextPopup > new Date()) {
			// 			return;
			// 		}
			
			// 		// Store the expiration date of the current popup in localStorage.
			// 		var expires = new Date();
			// 		expires = expires.setHours(expires.getHours() + 24);
			
			// 		localStorage.setItem( 'nextNewsletter', expires );
			// 	}
			
			// 	$('#mediumModal').modal("show");
			// }, 2000);
			// 
		</script>
		@endif

@endsection