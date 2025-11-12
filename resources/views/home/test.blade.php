@extends('layouts.app')

@section('styles')

		<!--  Owl-carousel css-->
		<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet" />

		<!-- Maps css -->
		<link href="{{URL::asset('assets/plugins/jqvmap/jqvmap.min.css')}}" rel="stylesheet">

		<!-- Jvectormap css -->
        <link href="{{URL::asset('assets/plugins/jqvmap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />

@endsection

@section('breadcrumb')
					<div class="left-content">
						<h4 class="content-title mb-2">Hi, welcome back! {{  session('name') }}</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Dashboard</a></li>
								<li class="breadcrumb-item active" aria-current="page">Analytics &amp; Monitoring</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

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
							@if ($error!="")
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
								<div class="card">
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
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
								
								
								<div class="card">
									<div class="card-body iconfont text-left">
										<div class="d-flex justify-content-between">
											<h4 class="card-title mb-3">Total Request</h4>
										</div>
										<div class="d-flex mb-0">
											<div class="">
												<h4 class="mb-1 font-weight-bold">{{ $total }}</h4>
												<p class="mb-2 tx-12 text-muted"><i class="fas fa-arrow-circle-up text-success"></i> Total Document Request</p>
											</div>
											<div class="card-chart bg-primary-transparent brround ml-auto mt-0">
												<a class="badge badge-info" href="#">All</a>
											</div>
										</div>
										
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
								<div class="card">
									<div class="card-body iconfont text-left">
										<div class="d-flex justify-content-between">
											<h4 class="card-title mb-3">Waiting Verify Supervisor</h4>
											
										</div>
										<div class="d-flex mb-0">
											<div class="">
												<h4 class="mb-1 font-weight-bold">{{ $total_spv1 }}</h4>
												<p class="mb-2 tx-12 text-muted"><i class="fas fa-arrow-circle-up text-success"></i> Dokumen yang belum di verifikasi oleh SPV</p>
											</div>
											<div class="card-chart bg-warning-transparent brround ml-auto mt-0">
												<a class="badge badge-success" href="#">Unit Bisnis</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
								<div class="card">
									<div class="card-body iconfont text-left">
										<div class="d-flex justify-content-between">
											<h4 class="card-title mb-3">Waiting Verify Level 1</h4>
											
										</div>
										<div class="d-flex mb-0">
											<div class="">
												<h4 class="mb-1   font-weight-bold">{{ $total_spv2 }}</h4>
												<p class="mb-2 tx-12 text-muted"><i class="fas fa-arrow-circle-up text-success"></i> Dokumen yang belum di verifikasi oleh BPR 1</p>
											</div>
											<div class="card-chart bg-info-transparent brround ml-auto mt-0">
												<a class="badge badge-secondary" href="#">BPR 1</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
								<div class="card">
									<div class="card-body iconfont text-left">
										<div class="d-flex justify-content-between">
											<h4 class="card-title mb-3">Waiting Verify Level 2</h4>
										</div>
										<div class="d-flex mb-0">
											<div class="">
												<h4 class="mb-1   font-weight-bold">{{ $total_spv3 }}</h4>
												<p class="mb-2 tx-12 text-muted"><i class="fas fa-arrow-circle-up text-success"></i> Dokumen yang belum di verifikasi oleh BPR 2</p>
											</div>
											<div class="card-chart bg-info-transparent brround ml-auto mt-0">
												<a class="badge badge-warning" href="#">BPR 2</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							{{-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
								<div class="card overflow-hidden">
									<div class="card-header bg-transparent pd-b-0 pd-t-30 bd-b-0">
										<div class="d-flex justify-content-between">
											<h4 class="card-title mg-b-10">Product Overview</h4>
											<div class="card-options ml-auto">
											</div>
										</div>
										<p class="tx-12 text-muted mb-0">Total Produk Perbulan.</p>
									</div>
									<div class="card-body pb-0 pd-t-10">
										<div class="sales-flot overflow-hidden">
											<div id="chart" class="BarChartShadow ht-200"></div>
										</div>
									</div>
									<div class="card-body border-top-0 pd-t-12">
										<div class="row row-sm">
											<div class="col-xl-7 col-lg-7 mx-auto d-block">
												<div class="progress mg-b-10 rounded-20">
													<div class="progress-bar ht-25 wd-100p bg-success tx-14" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">Total Request</div>
												</div>
												<div class="row mb-0">
													<div class="col-12">
														<div class="media text-center">
															<div class="media-body">
																<h4 class="tx-22 font-weight-bold mb-1 ">13,375</h4>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							 --}}
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

		<!--Internal  index js -->
		<script src="{{URL::asset('assets/plugins/apexcharts/apexcharts.js')}}"></script>
		
		<script src="{{URL::asset('assets/js/index.js')}}"></script>
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
			
			window.setTimeout(function(){ 
				// First check, if localStorage is supported.
				if (window.localStorage) {
					// Get the expiration date of the previous popup.
					var nextPopup = localStorage.getItem( 'nextNewsletter' );
			
					if (nextPopup > new Date()) {
						return;
					}
			
					// Store the expiration date of the current popup in localStorage.
					var expires = new Date();
					expires = expires.setHours(expires.getHours() + 24);
			
					localStorage.setItem( 'nextNewsletter', expires );
				}
			
				$('#mediumModal').modal("show");
			}, 2000);
			</script>
		@endif

@endsection