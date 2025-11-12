		<!-- main-header opened -->
		<div class="main-header nav nav-item hor-header">
			<div class="container">
				<div class="main-header-left ">
					<a class="animated-arrow hor-toggle horizontal-navtoggle"><span></span></a><!-- sidebar-toggle-->
					<a  class="header-brand" href="{{url('index')}}" style="display: flex; align-items: center;">
					<img src="{{URL::asset('assets/img/DMSLogo3.3.png')}}" alt="DMS Logo" style="height: 25px; width: auto;">
				</div><!-- search -->
				<div class="main-header-right">
					<div class="nav nav-item  navbar-nav-right ml-auto">
						<!-- <div class="nav-link" id="bs-example-navbar-collapse-1">
								<a  class="btn btn-primary btn-block" href="#" >Server Time: <span id="server-time"> </span> WIB</a>
						</div> -->
						<!-- <div class="nav-link" id="bs-example-navbar-collapse-1">
								<a  class="btn btn-primary btn-block " href="{{url('queue-reviewer')}}" style="font-weight: 700 !important;font-size: 18px !important;" >QUEUE REVIEWER <?php /*: &nbsp;{{ getWaitingNoRegistration() }} */?></a>
								
						</div> -->
						<div class="nav-link" id="bs-example-navbar-collapse-1">
								
								<a  class="btn btn-primary btn-block " href="{{url('queue-team-leader')}}" style="font-weight: 700 !important;" >QUEUE DCRM <?php /*: &nbsp;{{ getWaitingNoRegistrationTeamLeader() }} */?></a>
						</div>
						@if(session("role")=="staff" || session("role")=="spv1" || session("role")=="pc" || session("role")=="pcp")
						<div class="nav-link" id="bs-example-navbar-collapse-1">
								<a  class="btn btn-primary btn-block" href="{{url('tbo_overdue')}}" >Overdue: &nbsp;{{ getLimitTBOOverdueHeader(Session('branch_code')) }}</a>
								</div>
						@endif
						<div class="nav-link" id="bs-example-navbar-collapse-1">
								<a  class="btn btn-info btn-block" href="{{url('tc-peraturan-kebijakan')}}" ><i class="fa fa-newspaper"></i>&nbsp;Peraturan dan Kebijakan Produk</a>
								</div>
						<div class="nav-link" id="bs-example-navbar-collapse-1">
								<a  class="btn btn-info btn-block" href="{{url('news-list')}}" ><i class="fa fa-newspaper"></i>&nbsp;NEWS</a>
								</div>
						<div class="nav-link" id="bs-example-navbar-collapse-1">
							<a  class="btn btn-info btn-block" href="{{url('faq-list')}}" ><i class="fa fa-question-circle"></i>&nbsp;FAQ</a>
						</div>
						
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
							@if(Session('role')=='spv1'  || Session('role')=='pc' || Session('role')=='pcp' || Session('role')=='spv2' ||Session('role')=='spv3' ||Session('role')=='spv4') 
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
										<a class="d-flex p-3 border-bottom" href="{{  url('/loans/?action=5') }}">
											<div class="notifyimg bg-black-1">
												<i class="la la-folder-plus text-black-1"></i>
											</div>
											<div class="ml-3">
												<h5 class="notification-label mb-1">Pending Approval Daily ({{ getWaitingNotif(Session('role'))}})</h5>
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
										<a class="d-flex p-3 border-bottom" href="{{  url('/tbo') }}">
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
										<a class="d-flex p-3 border-bottom" href="{{  url('/loans/?action=4') }}">
											<div class="notifyimg bg-danger-transparent">
												<i class="la la-trash text-danger"></i>
											</div>
											<div class="ml-3">
												<h5 class="notification-label mb-1">Reject ({{ getRejectNotif(Session('role'))}})</h5>
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

					
					</div>
				</div>
			</div>
		</div>
		<!-- main-header closed -->

		<script>
        // function formatTime(date) {
        //     const hours = String(date.getHours()).padStart(2, '0');
        //     const minutes = String(date.getMinutes()).padStart(2, '0');
        //     const seconds = String(date.getSeconds()).padStart(2, '0');
        //     return `${hours}:${minutes}:${seconds}`;
        // }

        // function updateServerTime() {
        //     fetch('/get-server-time')
        //         .then(function(response) {
        //             return response.json();
        //         })
        //         .then(function(data) {
        //             const serverTimeElement = document.getElementById('server-time');
        //             const formattedTime = formatTime(new Date(data.server_time));
        //             serverTimeElement.textContent = formattedTime;
        //         })
        //         .catch(function(error) {
        //             console.error('Error fetching server time:', error);
        //         });
        // }

        // // Update server time every 1 second
        // setInterval(updateServerTime, 1000);

        // Initial update
        //updateServerTime();
    </script>