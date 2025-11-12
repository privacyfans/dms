			<!-- main-header -->
			<div class="main-header sticky side-header nav nav-item">
				<div class="container-fluid">
					<div class="main-header-left">
						<div class="responsive-logo">
							<a href="{{url('index')}}">DMS</a>
							{{-- <a href="{{url('index')}}"><img src="{{URL::asset('assets/img/brand/logo.png')}}" class="logo-1 logo-color1" alt="logo"></a>
							<a href="{{url('index')}}"><img src="{{URL::asset('assets/img/brand/logo2.png')}}" class="logo-1 logo-color2" alt="logo"></a>
							<a href="{{url('index')}}"><img src="{{URL::asset('assets/img/brand/logo3.png')}}" class="logo-1 logo-color3" alt="logo"></a>
							<a href="{{url('index')}}"><img src="{{URL::asset('assets/img/brand/logo4.png')}}" class="logo-1 logo-color4" alt="logo"></a>
							<a href="{{url('index')}}"><img src="{{URL::asset('assets/img/brand/logo5.png')}}" class="logo-1 logo-color5" alt="logo"></a>
							<a href="{{url('index')}}"><img src="{{URL::asset('assets/img/brand/logo6.png')}}" class="logo-1 logo-color6" alt="logo"></a>
							<a href="{{url('index')}}"><img src="{{URL::asset('assets/img/brand/logo-white.png')}}" class="dark-logo-1" alt="logo"></a>
							<a href="{{url('index')}}"><img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="logo-2 logo-color1" alt="logo"></a>
							<a href="{{url('index')}}"><img src="{{URL::asset('assets/img/brand/favicon2.png')}}" class="logo-2 logo-color2" alt="logo"></a>
							<a href="{{url('index')}}"><img src="{{URL::asset('assets/img/brand/favicon3.png')}}" class="logo-2 logo-color3" alt="logo"></a>
							<a href="{{url('index')}}"><img src="{{URL::asset('assets/img/brand/favicon4.png')}}" class="logo-2 logo-color4" alt="logo"></a>
							<a href="{{url('index')}}"><img src="{{URL::asset('assets/img/brand/favicon5.png')}}" class="logo-2 logo-color5" alt="logo"></a>
							<a href="{{url('index')}}"><img src="{{URL::asset('assets/img/brand/favicon6.png')}}" class="logo-2 logo-color6" alt="logo"></a>
							<a href="{{url('index')}}"><img src="{{URL::asset('assets/img/brand/favicon-white.png')}}" class="dark-logo-2" alt="logo"></a> --}}
						</div>
						<div class="app-sidebar__toggle d-md-none" data-toggle="sidebar">
							<a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
							<a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
						</div>
					</div>
					<div class="main-header-right">
						<div class="nav nav-item  navbar-nav-right ml-auto">
							<div class="nav-link" id="bs-example-navbar-collapse-1">
								<a  class="btn btn-info btn-block" href="{{url('news-list')}}" ><i class="fa fa-newspaper"></i>&nbsp;NEWS</a>
								</div>
							<div class="nav-link" id="bs-example-navbar-collapse-1">
							<a  class="btn btn-info btn-block" href="{{url('faq-list')}}" ><i class="fa fa-question-circle"></i>&nbsp;FAQ</a>
							</div>
							{{-- <div class="nav-link" id="bs-example-navbar-collapse-1">
								<form class="navbar-form" role="search">
									<div class="input-group">
										<input type="text" class="form-control" placeholder="Search">
										<span class="input-group-btn">
											<button type="reset" class="btn btn-default">
												<i class="fas fa-times"></i>
											</button>
											<button type="submit" class="btn btn-default nav-link resp-btn">
												<i class="fe fe-search"></i>
											</button>
										</span>
									</div>
								</form>
							</div> --}}
							{{-- <div class="main-header-search ml-0 d-sm-none d-none d-lg-block">
								<input class="form-control" id="search-input"  placeholder="Search for anything..." type="text"> 
								<a  class="btn btn-info btn-block" href="{{url('faq-list')}}" ><i class="fa fa-question-circle"></i>FAQ</a>
							</div> --}}
							{{-- <a  class="btn btn-info btn-block" href="{{url('faq-list')}}" ><i class="fa fa-question-circle"></i>FAQ</a> --}}
							@if(Session('role')=='staff') 
							<div class="dropdown nav-item main-header-notification">
								<a class="new nav-link" href="#"><i class="fe fe-bell"></i>{!! getNotifPulseStaff(Session('role')) !!}</span></a>
								<div class="dropdown-menu shadow">
									<div class="menu-header-content bg-primary text-left d-flex">
										<div class="">
											<h6 class="menu-header-title text-white mb-0">Notifications</h6>
										</div>
										<div class="my-auto ml-auto">
											{{-- <span class="badge badge-pill badge-warning float-right">Mark All Read</span> --}}
										</div>
									</div>
									<div class="main-notification-list Notification-scroll ps">
										<a class="d-flex p-3 border-bottom" href="{{  url('/verify') }}">
											<div class="notifyimg bg-success-transparent">
												<i class="la la-check-circle text-success"></i>
											</div>
											<div class="ml-3">
												<h5 class="notification-label mb-1">Status Verify ({{ getVerifyNotif(Session('role')) }})</h5>
												{{-- <div class="notification-subtext">1 hour ago</div> --}}
											</div>
											<div class="ml-auto">
												<i class="las la-angle-right text-right text-muted"></i>
											</div>
										</a>
									</div>
								</div>
							</div> 
							@endif
							@if(Session('role')=='spv1'  || Session('role')=='pc' || Session('role')=='pcp' || Session('role')=='spv2' ||Session('role')=='spv3') 
							<div class="dropdown nav-item main-header-notification">
								<a class="new nav-link" href="#"><i class="fe fe-bell"></i>{!! getNotifPulse(Session('role')) !!}</span></a>
								<div class="dropdown-menu shadow">
									<div class="menu-header-content bg-primary text-left d-flex">
										<div class="">
											<h6 class="menu-header-title text-white mb-0">Notifications</h6>
										</div>
										<div class="my-auto ml-auto">
											{{-- <span class="badge badge-pill badge-warning float-right">Mark All Read</span> --}}
										</div>
									</div>
									<div class="main-notification-list Notification-scroll ps">
										<a class="d-flex p-3 border-bottom" href="{{  url('/loans/?action=0') }}">
											<div class="notifyimg bg-black-1">
												<i class="la la-folder-plus text-black-1"></i>
											</div>
											<div class="ml-3">
												<h5 class="notification-label mb-1">Pending Approval ({{ getPendingNotif(Session('role'))}})</h5>
												{{-- <div class="notification-subtext">1 hour ago</div> --}}
											</div>
											<div class="ml-auto">
												<i class="las la-angle-right text-right text-muted"></i>
											</div>
										</a>
										<a class="d-flex p-3 border-bottom" href="{{  url('/loans/?action=2') }}">
											<div class="notifyimg bg-warning-transparent">
												<i class="la la-exclamation-triangle text-warning"></i>
											</div>
											<div class="ml-3">
												<h5 class="notification-label mb-1">Not Verify ({{ getNotVerifyNotif(Session('role'))}})</h5>
												{{-- <div class="notification-subtext">2 hour ago</div> --}}
											</div>
											<div class="ml-auto">
												<i class="las la-angle-right text-right text-muted"></i>
											</div>
										</a>
										<a class="d-flex p-3 border-bottom" href="{{  url('/loans/?action=3') }}">
											<div class="notifyimg bg-primary-transparent">
												<i class="la la-calendar-plus text-primary"></i>
											</div>
											<div class="ml-3">
												<h5 class="notification-label mb-1">To Be Obtained ({{ getTBONotif(Session('role'))}})</h5>
												{{-- <div class="notification-subtext">4 hour ago</div> --}}
											</div>
											<div class="ml-auto">
												<i class="las la-angle-right text-right text-muted"></i>
											</div>
										</a>
										
										{{-- <div class="dropdown-footer">
											<a href="{{ route('loans.index') }}">VIEW ALL</a>
										</div> --}}
									</div>
								</div>
							</div> 
							@endif
							<div class="dropdown main-profile-menu nav nav-item nav-link">
								<a class="profile-user d-flex" href=""><img alt="" src="{{URL::asset('assets/img/faces/user.png')}}">
									<div class="p-text d-none">
										<span class="p-name font-weight-bold">{{Session('name')}}</span>
										<small class="p-sub-text">{{Session('branch_name')}}</small>
									</div>
								</a>
								<div class="dropdown-menu shadow">
									<div class="main-header-profile header-img">
										{{-- <div class="main-img-user"><img alt="" src="{{URL::asset('assets/img/faces/6.jpg')}}"></div> --}}
										<h6>{{Session('name')}}</h6><span>{{Session('branch_name')}}</span>
									</div>
									{{-- <a class="dropdown-item" href=""><i class="fas fa-key"></i></i> Change Password</a> --}}
									<a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Sign Out </a>
									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
										@csrf
									</form>
								</div>
							</div>
							{{-- <div class="dropdown main-header-message right-toggle">
								<a class="nav-link pr-0" data-toggle="sidebar-right" data-target=".sidebar-right">
									<i class="ion ion-md-menu tx-20 bg-transparent"></i>
								</a>
							</div> --}}
						</div>
					</div>
				</div>
			</div>
			<!-- /main-header -->