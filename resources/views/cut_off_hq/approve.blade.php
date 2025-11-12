@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Approve Cut Off</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Approve Cut Off</a></li>
								<li class="breadcrumb-item active" aria-current="page">Edit</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Approve Setting Cut Off</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('cut_off.index') }}"> Back</a>
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
  
    <form action="{{ route('cut_off.approveSubmit') }}" method="POST">
        @csrf
        @method('POST')
   

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="main-content-label mg-b-5">
                            Approve Setting Cut Off
                        </div>
                        {{-- <p class="mg-b-20 text-muted">It is Very Easy to Customize and it uses in your website apllication.</p> --}}
                        <div class="pd-30 pd-sm-40 bg-gray-100">
                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-12">
                                    <label class="form-label mg-b-0">Purpose Cut Off Time</label>
                                </div>
                                <div class="col-md-12 mg-t-5">
                                    <input type="hidden" name="id" value="{{ $cut_off->id }}" class="form-control" placeholder="Cut Off">
                                    <input type="text" name="purpose_cut_off" value="{{ $cut_off->purpose_cut_off }}" class="form-control" placeholder="Cut Off">
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