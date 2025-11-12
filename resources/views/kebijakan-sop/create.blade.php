@extends('layouts.app')

@section('styles')
	<!--- Internal Select2 css-->
    <link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">

    <!--Internal  Quill css -->
    <link href="{{URL::asset('assets/plugins/quill/quill.snow.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/quill/quill.bubble.css')}}" rel="stylesheet">
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Kebijakan SOP</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Kebijakan SOP</a></li>
								<li class="breadcrumb-item active" aria-current="page">Create</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form id="formKebijakanSOP" action="{{ route('kebijakan-sop.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        Kebijakan SOP
                    </div>
                    <div class="pd-30 pd-sm-40 bg-gray-100">
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Title</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <input type="text" name="title" class="form-control" placeholder="Title">
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Deskripsi</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <div class="ql-wrapper ql-wrapper-demo bg-gray-100">
									<div id="descEditor">
									</div>
								</div>
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">URL (Link ke E-Sisdur)</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <input type="url" name="url" class="form-control" placeholder="https://example.com">
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Status</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <select class="form-control select2-no-search" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">Submit</button>
                        <a href="{{ route('kebijakan-sop.index') }}" class="btn btn-secondary pd-x-30 mg-t-5">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->

</form>
@endsection

@section('scripts')

		<!--Internal  Select2 js -->
		<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>

		<!--Internal quill js -->
		<script src="{{URL::asset('assets/plugins/quill/quill.min.js')}}"></script>

		<!--Internal  Perfect-scrollbar js -->
		<script src="{{URL::asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>

		<!-- Internal Form-editor js -->
		<script src="{{URL::asset('assets/js/form-editor.js')}}"></script>
    <script>
        $(document).ready(function(){
            $("#formKebijakanSOP").on("submit", function () {
                var myEditor = document.querySelector('#descEditor');
                var html = myEditor.children[0].innerHTML;
                $(this).append("<textarea name='deskripsi' style='display:none'>"+html+"</textarea>");
            });
        });
    </script>
@endsection
