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
						<h4 class="content-title mb-1">FREQUENTLY ASKED QUESTIONS</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">FAQ</a></li>
								<li class="breadcrumb-item active" aria-current="page">Index</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
{{-- <div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Add New Branch</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('branch.index') }}"> Back</a>
        </div>
    </div>
</div> --}}
   
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
   
<form id="formFAQ" action="{{ route('faq.store') }}" method="POST">
    @csrf
  
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        Faq
                    </div>
                    {{-- <p class="mg-b-20 text-muted">It is Very Easy to Customize and it uses in your website apllication.</p> --}}
                    <div class="pd-30 pd-sm-40 bg-gray-100">
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Question</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                
                                <input type="text" name="question" class="form-control" placeholder="Question">
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">

                                <label class="form-label mg-b-0">Answer</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <div class="ql-wrapper ql-wrapper-demo bg-gray-100">
                                    {{-- <input type="hidden" name="answer"/> --}}
									<div id="answerEditor">
									</div>
								</div>
                                <!-- <textarea name="answer" col=  placeholder="Add Answer" class="form-control" rows="3"></textarea> -->
                            </div>
                        </div>
                        
 
                        <button type="submit" class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">Submit</button>
                        {{-- <button class="btn btn-dark pd-x-30 mg-t-5">Cancel</button> --}}
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
        var form = document.getElementById("formFAQ"); // get form by ID

        form.onsubmit = function() { // onsubmit do this first
                             var answer = document.querySelector('input[name=answer]'); // set name input var
                             answer.value = JSON.stringify(quill.getContents()); // populate name input with quill data
                             return true; // submit form
                           }
        $(document).ready(function(){

            $("#formFAQ").on("submit", function () {
                var myEditor = document.querySelector('#answerEditor');
                var html = myEditor.children[0].innerHTML;
                $(this).append("<textarea name='answer' style='display:none'>"+html+"</textarea>");
            });
        });
    </script>
@endsection