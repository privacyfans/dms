<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="Description" content="Document Management System">
		<meta name="Author" content="Spruko Technologies Private Limited">
		<meta name="Keywords" content="admin template, admin dashboard, bootstrap dashboard template, bootstrap 4 admin template, laravel, php framework, php laravel, laravel framework, php mvc, laravel admin panel, laravel admin panel, laravel template, laravel bootstrap, blade laravel, best php framework"/>

		<!-- Title -->
		<title> Document Management System </title>

		@include('layouts.custom-styles')

	</head>

    <body class="main-body light-theme">

		<!-- Loader -->
		<!-- <div id="global-loader">
			<img src="{{URL::asset('assets/img/loader-2.svg')}}" class="loader-img" alt="Loader">
		</div> -->
		<!-- /Loader -->

		<!-- main-signin-wrapper -->

        <div class="my-auto page page-h">

            @yield('content')

        </div>

		<!-- /main-signin-wrapper -->
		{{-- @include('layouts.horizontalmenu.footer') --}}

        @include('layouts.custom-scripts')
        
	</body>
</html>