@extends('layouts.app')

@section('styles')
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Master Klasifikasi Debitur</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Master Klasifikasi Debitur</a></li>
								<li class="breadcrumb-item active" aria-current="page">Edit</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Klasifikasi Debitur</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('master-klasifikasi-debitur.index') }}"> Back</a>
        </div>
    </div>
</div>
<br>
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

<form action="{{ route('master-klasifikasi-debitur.update', $masterKlasifikasiDebitur->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        Edit Master Klasifikasi Debitur
                    </div>
                    <div class="pd-30 pd-sm-40 bg-gray-100">
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Klasifikasi Debitur</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <input type="text" name="klasifikasi_debitur" class="form-control" placeholder="Klasifikasi Debitur" value="{{ $masterKlasifikasiDebitur->klasifikasi_debitur }}">
                            </div>
                        </div>

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Status</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <select class="form-control select2-no-search" name="status">
                                    <option value="active" {{ $masterKlasifikasiDebitur->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $masterKlasifikasiDebitur->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->



</form>
@endsection
