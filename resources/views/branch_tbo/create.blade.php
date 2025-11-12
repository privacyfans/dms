@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Setting Max TBO Overdue</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Setting Max TBO Overdue</a></li>
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
   
<form action="{{ route('branch_tbo.store') }}" method="POST">
    @csrf
  
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        Setting TBO Branch
                    </div>
                    {{-- <p class="mg-b-20 text-muted">It is Very Easy to Customize and it uses in your website apllication.</p> --}}
                    <div class="pd-30 pd-sm-40 bg-gray-100">
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Branch Type</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <input type="text" name="branch_code" class="form-control" placeholder="Branch Code">
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Max</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <input type="text" name="branch_name" class="form-control" placeholder="Branch Name">
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