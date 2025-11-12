@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">NEWS</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">News</a></li>
								<li class="breadcrumb-item active" aria-current="page">Show</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show News</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('news.index') }}"> Back</a>
            </div>
        </div>
    </div>
   <br>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    {{-- <div class="main-content-label mg-b-5">
                        User {{ $branch->nik }}
                    </div> --}}
                    {{-- <p class="mg-b-20 text-muted">It is Very Easy to Customize and it uses in your website apllication.</p> --}}
                    <div class="pd-30 pd-sm-40 bg-gray-100">
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-3">
                                <label class="form-label mg-b-0">Title</label>
                            </div>
                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                {!! $news->title !!}
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-3">
                                <label class="form-label mg-b-0">Desc</label>
                            </div>
                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                {!! $news->desc !!}
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-3">
                                <label class="form-label mg-b-0">File</label>
                            </div>
                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                {!! $news->file !!}
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-3">
                                <label class="form-label mg-b-0">Popup</label>
                            </div>
                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                {!! $news->popup !!}
                            </div>
                        </div>
                        
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->

@endsection