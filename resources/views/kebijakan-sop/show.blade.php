@extends('layouts.app')

@section('styles')
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Kebijakan SOP</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Kebijakan SOP</a></li>
								<li class="breadcrumb-item active" aria-current="page">Show</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Show Kebijakan SOP</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('kebijakan-sop.index') }}"> Back</a>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="main-content-label mg-b-5">
                    Detail Kebijakan SOP
                </div>
                <div class="pd-30 pd-sm-40 bg-gray-100">
                    <div class="row row-xs align-items-center mg-b-20">
                        <div class="col-md-3">
                            <label class="form-label mg-b-0"><strong>Title:</strong></label>
                        </div>
                        <div class="col-md-9">
                            {{ $kebijakanSop->title }}
                        </div>
                    </div>
                    <div class="row row-xs align-items-center mg-b-20">
                        <div class="col-md-3">
                            <label class="form-label mg-b-0"><strong>Deskripsi:</strong></label>
                        </div>
                        <div class="col-md-9">
                            {!! $kebijakanSop->deskripsi !!}
                        </div>
                    </div>
                    <div class="row row-xs align-items-center mg-b-20">
                        <div class="col-md-3">
                            <label class="form-label mg-b-0"><strong>URL:</strong></label>
                        </div>
                        <div class="col-md-9">
                            <a href="{{ $kebijakanSop->url }}" target="_blank">{{ $kebijakanSop->url }}</a>
                        </div>
                    </div>
                    <div class="row row-xs align-items-center mg-b-20">
                        <div class="col-md-3">
                            <label class="form-label mg-b-0"><strong>Status:</strong></label>
                        </div>
                        <div class="col-md-9">
                            @if($kebijakanSop->status == 'active')
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </div>
                    </div>
                    <div class="row row-xs align-items-center mg-b-20">
                        <div class="col-md-3">
                            <label class="form-label mg-b-0"><strong>Created At:</strong></label>
                        </div>
                        <div class="col-md-9">
                            {{ $kebijakanSop->created_at ? $kebijakanSop->created_at->format('d-m-Y H:i') : '-' }}
                        </div>
                    </div>
                    <div class="row row-xs align-items-center mg-b-20">
                        <div class="col-md-3">
                            <label class="form-label mg-b-0"><strong>Updated At:</strong></label>
                        </div>
                        <div class="col-md-9">
                            {{ $kebijakanSop->updated_at ? $kebijakanSop->updated_at->format('d-m-Y H:i') : '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
