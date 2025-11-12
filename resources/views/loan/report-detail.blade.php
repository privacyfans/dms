@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Report Detail</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Search</a></li>
								<li class="breadcrumb-item active" aria-current="page">Loan</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')


@section('content')

<form action="{{ route('datafile.reportDetailSubmit') }}" method="POST">
    @csrf
                <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
						<div class="card">
							<div class="card-body">
								<div class="main-content-label mg-b-5">
									Report Detail
								</div>
								<p class="mg-b-20 text-muted">Silahkan masukan parameter report yang diinginkan</p>
								
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

								<div class="pd-30 pd-sm-40 bg-gray-100">
									<div class="row row-xs align-items-center mg-b-20">
										<div class="col-md-12">
											<label class="form-label mg-b-0">From Date</label>
										</div>
										<div class="col-md-12 mg-t-5">
											<input class="form-control" id="from_date" type="text" name="from_date" required="">
										</div>
									</div>
									<div class="row row-xs align-items-center mg-b-20">
										<div class="col-md-12">
											<label class="form-label mg-b-0">End Date</label>
										</div>
										<div class="col-md-12 mg-t-5">
											<input class="form-control" id="end_date" type="text" name="end_date" required="">
										</div>
									</div>
									<div class="row row-xs align-items-center mg-b-20">
										<div class="col-md-12">
											<label class="form-label mg-b-0">Branch Code</label>
										</div>
										<div class="col-md-12 mg-t-5">
											{{-- <input type="text" name="cabang" class="form-control" placeholder="Branch"> --}}
											<select class="livesearch-branch form-control p-3" name="cabang"></select>
										</div>
									</div>
									<div class="row row-xs align-items-center mg-b-20">
										<div class="col-md-12">
											<label class="form-label mg-b-0">Final Status Verification</label>
										</div>
										<div class="col-md-12 mg-t-5">
											<select class="livesearch-final-status form-control p-3" name="final_status"></select>
										</div>
									</div>
									<div class="row row-xs align-items-center mg-b-20">
										<div class="col-md-12">
											<label class="form-label mg-b-0">NIK BPR Level 1</label>
										</div>
										<div class="col-md-12 mg-t-5">
											<input class="form-control" placeholder="Enter NIK" type="text" name="nik_brp1">
										</div>
									</div>
									<div class="row row-xs align-items-center mg-b-20">
										<div class="col-md-12">
											<label class="form-label mg-b-0">NIK BPR Level 2</label>
										</div>
										<div class="col-md-12 mg-t-5">
											<input class="form-control" placeholder="Enter NIK" type="text" name="nik_brp2">
										</div>
									</div>
									
									<button class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">Download</button>
									<a class="btn btn-dark pd-x-30 mg-t-5" href="{{ route('index') }}">Cancel</a>
								</div>
							</div>
						</div>
					</div>
					<!--/div-->
</form>               
                   



    

@endsection

@section('scripts')

<script>
	$('.livesearch-branch').select2({
        placeholder: 'Select Branch',
        ajax: {
            url: '/branch-search',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.branch_name,
                            id: item.branch_code
                        }
                    })
                };
            },
            cache: true
        }
    });

	$('.livesearch-final-status').select2({
        placeholder: 'Select Status Document',
		width: 'resolve',
		ajax: {
			url: '/getFlag',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				return {
					results: $.map(data, function (item) {
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

	
   
   $(function () {
            $(document).ready(function () {
                $('#from_date').datepicker({
                    format: "yyyy-mm-dd",
                    todayBtn: "linked"
                });
				$('#end_date').datepicker({
                    format: "yyyy-mm-dd",
                    todayBtn: "linked"
                });
			})
	});
</script>
@endsection

