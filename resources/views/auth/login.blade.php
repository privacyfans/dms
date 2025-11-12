@extends('layouts.customapp')

@section('custom-styles') 
<style>
    .custom-placeholder::placeholder {
        color: #2b87c8; /* Change this to your desired color */
        opacity: 1; /* Firefox */
    }

    .custom-placeholder:-ms-input-placeholder { /* Internet Explorer 10-11 */
        color: #2b87c8;
    }

    .custom-placeholder::-ms-input-placeholder { /* Microsoft Edge */
        color: #2b87c8;
    }
</style>
@endsection

@section('content')	
<div class="my-auto page page-h">
					<div class="main-signin-wrapper error-wrapper">
<div class="row">
					<div class="col-lg-12 col-md-12" style="padding-top: 40vh;padding-left: 70px;">
					
						<div class="card ">
							<div class="card-body " style="background-color: #2b87c8;border-radius: 10px;";>
								<div class="main-content-label mg-b-5" style="color: #fff">
								Please sign in to continue
								</div>
								@if ($message = Session::get('error'))
								<div class="alert alert-danger alert-block">
									<button type="button" class="close" data-dismiss="alert">×</button> 
									<strong>{{ $message }}</strong>
								</div>
								@endif
								<form method="POST" action="{{ route('loginehr') }}">
								@csrf
								<div class="pd-30 pd-sm-5 bg-gray-100">
									<div class="row row-xs">
										<div class="col-md-5 mg-t-10 mg-md-t-0">
										
										<input name="email" type="text"
											class="form-control custom-placeholder" id="username"
											placeholder="Enter NIK" autofocus  required="">
											
											@error('email')
											<span class="invalid-feedback" role="alert">
												<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
										
										<div class="col-md-5 mg-t-10 mg-md-t-0">
										
										<input type="password" name="password"
											class="form-control custom-placeholder"
											id="userpassword" value="" placeholder="Enter password"  required="">
											@error('password')
											<span class="invalid-feedback" role="alert">
												<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
										<div class="col-md mg-t-5 mg-md-t-0">
											<button class="btn btn-primary btn-block" type="submit"><i class="mdi mdi-keyboard-return"></i></button>
										</div>
									</div>
								</div>
								</form>
							</div>
						</div>
					</div>
					</div>
</div>
</div>		

@endsection('content')

@section('custom-scripts')

@endsection