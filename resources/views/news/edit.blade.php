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
						<h4 class="content-title mb-1">NEWS</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">News</a></li>
								<li class="breadcrumb-item active" aria-current="page">Edit</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit News</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('news.index') }}"> Back</a>
            </div>
        </div>
    </div>
   
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
  
    <form id="formNEWS" action="{{ route('news.update',$news->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
   

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="main-content-label mg-b-5">
                            Edit News
                        </div>
                        {{-- <p class="mg-b-20 text-muted">It is Very Easy to Customize and it uses in your website apllication.</p> --}}
                        <div class="pd-30 pd-sm-40 bg-gray-100">
                            

                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-12">
                                    <label class="form-label mg-b-0">Title</label>
                                </div>
                                <div class="col-md-12 mg-t-5">
                                    
                                    <input type="text" name="title" class="form-control" placeholder="Question" value="{{ $news->title }}">
                                </div>
                            </div>
                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-12">
    
                                    <label class="form-label mg-b-0">Description</label>
                                </div>
                                <div class="col-md-12 mg-t-5">
                                    <div class="ql-wrapper ql-wrapper-demo bg-gray-100">
                                        {{-- <input type="hidden" name="answer"/> --}}
                                        <div id="descEditor">
                                        </div>
                                    </div>
                                    {{-- <textarea name="answer" col=  placeholder="Add Answer" class="form-control" rows="3"></textarea> --}}
                                </div>
                            </div>
                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-12">
                                    <label class="form-label mg-b-0">File</label>
                                </div>
                                <div class="col-md-12 mg-t-5">
                                    <input name="file" type="file" class="form-control">
                                    <a target="_blank" href="/uploads/{{ $news->file }}">{{$news->file}}</a>
                                </div>
                            </div>
                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-12">
                                    <label class="form-label mg-b-0">Popup</label>
                                </div>
                                <div class="col-md-12 mg-t-5">
                                    <select name="popup" class="form-control SlectBox SumoUnder">
                                        <!--placeholder-->
                                        <?php 
                                        if($news->popup == 1){
                                            $selected='selected';
                                        }else{
                                            $selected='';
                                        }
                                        ?>
                                        <option value="1" {{$selected}}>Enable</option>
                                        <option value="0" {{$selected}}>Disable</option>
                                    </select>
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
		{{-- <script src="{{URL::asset('assets/js/form-editor.js')}}"></script> --}}
    <script>
        
        // var form = document.getElementById("formFAQ"); // get form by ID

        // form.onsubmit = function() { // onsubmit do this first
        //                      var answer = document.querySelector('input[name=answer]'); // set name input var
        //                      answer.value = JSON.stringify(quill.getContents()); // populate name input with quill data
        //                      return true; // submit form
        //                    }
        function initQuill(html) {
            var l = Quill.import("ui/icons");
            l.bold = '<i class="la la-bold" aria-hidden="true"></i>', l.italic = '<i class="la la-italic" aria-hidden="true"></i>', l.underline = '<i class="la la-underline" aria-hidden="true"></i>', l.strike = '<i class="la la-strikethrough" aria-hidden="true"></i>', l.list.ordered = '<i class="la la-list-ol" aria-hidden="true"></i>', l.list.bullet = '<i class="la la-list-ul" aria-hidden="true"></i>', l.link = '<i class="la la-link" aria-hidden="true"></i>', l.image = '<i class="la la-image" aria-hidden="true"></i>', l.video = '<i class="la la-film" aria-hidden="true"></i>', l["code-block"] = '<i class="la la-code" aria-hidden="true"></i>';
            var i = [
                [{
                    header: [1, 2, 3, 4, 5, 6, !1]
                }],
                ["bold", "italic", "underline", "strike"],
                [{
                    list: "ordered"
                }, {
                    list: "bullet"
                }],
                ["link", "image", "video"]
            ];

                var quill = new Quill('#descEditor', {
                    modules: {
                        toolbar: i
                    },
                        theme: "snow"
                    }
                );

                var delta = quill.clipboard.convert(html);
                quill.setContents(delta, 'silent');
        }
        $(document).ready(function(){
            var html = `{!! $news->desc !!}`;

            initQuill(html);


           
            $("#formNEWS").on("submit", function () {
                var myEditor = document.querySelector('#descEditor');
                var html = myEditor.children[0].innerHTML;
                $(this).append("<textarea name='desc' style='display:none'>"+html+"</textarea>");
            });
        });
    </script>
@endsection