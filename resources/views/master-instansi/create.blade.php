@extends('layouts.app')

@section('styles')
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Master Instansi</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Master Instansi</a></li>
								<li class="breadcrumb-item active" aria-current="page">Create</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Add New Instansi</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('master-instansi.index') }}"> Back</a>
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

<form action="{{ route('master-instansi.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        Create Master Instansi
                    </div>
                    <div class="pd-30 pd-sm-40 bg-gray-100">
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Instansi</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <input type="text" name="instansi" class="form-control" placeholder="Nama Instansi">
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Klasifikasi Debitur</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <select class="livesearch-klasifikasi form-control p-3" name="klasifikasi_debitur"></select>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->



</form>
@endsection
@section('scripts')
<script type="text/javascript">
    $('.livesearch-klasifikasi').select2({
        placeholder: 'Select Klasifikasi Debitur',
        ajax: {
            url: '/klasifikasi-search',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.klasifikasi_debitur,
                            id: item.klasifikasi_debitur
                        }
                    })
                };
            },
            cache: true
        }
    });
</script>
@endsection
