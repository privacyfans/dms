@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Data File</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Tables</a></li>
								<li class="breadcrumb-item active" aria-current="page">Basic Tables</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')

				<!-- row opened -->
				<div class="row row-sm">
					
					<!--div-->
					<div class="col-xl-12">
						<div class="card">
							<div class="card-header pb-0 pd-t-25">
								<div class="d-flex justify-content-between">
									<h4 class="card-title mg-b-0">Data File</h4>
								</div>
								
							</div>
							<div class="card-body">
								<div class="table-responsive">
                                    <livewire:detail-file>
								</div>
							</div>
						</div>
					</div>
					<!--/div-->
				</div>
				<!-- /row -->

@endsection('content')

@section('scripts') 

		<!-- Eva-icons js -->
		<script src="{{URL::asset('assets/js/eva-icons.min.js')}}"></script>
       
@endsection
