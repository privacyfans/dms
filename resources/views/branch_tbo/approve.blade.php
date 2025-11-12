@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Approve Setting Max TBO Overdue</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Approve Setting Max TBO Overdue</a></li>
								<li class="breadcrumb-item active" aria-current="page">Edit</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Approve Setting Max TBO Overdue</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('branch_tbo.index') }}"> Back</a>
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
  
    <form action="{{ route('branch_tbo.approveSubmit') }}" method="POST">
        @csrf
        @method('POST')
   

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="main-content-label mg-b-5">
                            Approve Setting Max TBO
                        </div>
                        {{-- <p class="mg-b-20 text-muted">It is Very Easy to Customize and it uses in your website apllication.</p> --}}
                        <div class="pd-30 pd-sm-40 bg-gray-100">
                            <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-12">
                                        <label class="form-label mg-b-0">Branch Type Name</label>
                                    </div>
                                    <div class="col-md-12 mg-t-5">
                                        <input type="hidden" name="branch_type" value="{{ $branch_tbo->branch_type }}" class="form-control" placeholder="Branch Type">
                                        <input type="text" name="branch_type_name" value="{{ $branch_tbo->branchtypes[0]->branch_type_name }}" class="form-control" placeholder="Branch Type Name" readonly>
                                    </div>
                            </div>
                                

                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-12">
                                    <label class="form-label mg-b-0">Purpose Max TBO</label>
                                </div>
                               
                                <div class="col-md-12 mg-t-5">
                                    <input type="hidden" name="id" value="{{ $branch_tbo->id }}" class="form-control" placeholder="Jumlah">
                                    <input type="text" name="purpose_jml" value="{{ $branch_tbo->purpose_jml }}" class="form-control" placeholder="Jumlah">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label mg-b-0">Approve</label>
                                </div>
                                <div class="col-md-12 mg-t-5">
                                    <select name="approve_flag" class="form-control SlectBox SumoUnder">
										<!--placeholder-->
										<option value="1">Approve</option>
										<option value="2">Not Approve</option>
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