		<!--Horizontal-main -->
		<div class="sticky">
			<div class="horizontal-main hor-menu clearfix">
				<div class="horizontal-mainwrapper container clearfix">
					<!--Nav-->
					<nav class="horizontalMenu clearfix">
						<ul class="horizontalMenu-list">
							<li>
								<a href="{{url('index')}}"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
									<i class="fas fa-chart-line side-menu__icon"></i>
								Dashboard </a>
							</li>

							@if(Session("role")=="staff")
							<li>
								<a href="{{url('search-loan')}}"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
									<i class="fas fa-plus side-menu__icon"></i>
								New Request</a>
							</li>
							@endif
							@if(Session("role")=="spv2" || Session("role")=="spv3" || Session("role")=="spv4")
							<li>
								<a href="{{url('pickup')}}"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
									<i class="fas fa-hand-pointer side-menu__icon"></i>
								Pickup</a>
							</li>
							@endif
							<?php /*
							@if(Session("branch_type")==1)
								@if(Session("role")=="spv1")
								<li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
								<i class="fas fa-cubes side-menu__icon"></i>
								Request<i class="fe fe-chevron-down horizontal-icon"></i></a>
									<ul class="sub-menu">
										<li><a href="{{url('perpanjangan_jam')}}">Perpanjangan Jam Layanan</a></li>
										<!-- <li><a href="{{url('perpanjangan_tbo')}}">Perpanjangan Tgl TBO</a></li> -->
									</ul>
								</li>
								@endif
							@else
								@if(Session("role")=="pcp")
									<li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
									<i class="fas fa-cubes side-menu__icon"></i>
									Request<i class="fe fe-chevron-down horizontal-icon"></i></a>
										<ul class="sub-menu">
											<li><a href="{{url('perpanjangan_jam')}}">Perpanjangan Jam Layanan</a></li>
											<!-- <li><a href="{{url('perpanjangan_tbo')}}">Perpanjangan Tgl TBO</a></li> -->
										</ul>
									</li>
									@endif
							@endif
							*/?>
							@if(Session("role")=="pc" || Session("role")=="pcp")
								<li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
								<i class="fas fa-cubes side-menu__icon"></i>
								Request<i class="fe fe-chevron-down horizontal-icon"></i></a>
									<ul class="sub-menu">
										<li><a href="{{url('perpanjangan_jam')}}">Perpanjangan Jam Layanan</a></li>
										<!-- <li><a href="{{url('perpanjangan_tbo')}}">Perpanjangan Tgl TBO</a></li> -->
									</ul>
								</li>
								@endif
							<li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
							<i class="fas fa-cubes side-menu__icon"></i>
							Product<i class="fe fe-chevron-down horizontal-icon"></i></a>
								<ul class="sub-menu">
									<li><a href="{{url('kupen')}}">KUPEN UMUM</a></li>
									<li><a href="{{url('kupen-janda-duda')}}">KUPEN JANDA</a></li>
									<li><a href="{{url('kupen-hybrid')}}">KUPEN HYBRID</a></li>
									<li><a href="{{url('kupen-hybrid-go')}}">KUPEN HYBRID GO</a></li>
									<li><a href="{{url('kupeg')}}">KUPEG</a></li>
									<li><a href="{{url('kph')}}">KPH</a></li>
									<li><a href="{{url('tht')}}">THT</a></li>
									<li><a href="{{url('kpkb-wni')}}">KPKB - WNI</a></li>
									<li><a href="{{url('kpkb-wna')}}">KPKB - WNA</a></li>
									<li><a href="{{url('tawon')}}">TAWON</a></li>
								</ul>
							</li>

							@if(Session('role')=='spv2' || Session('role')=='spv3' || Session('role')=='spv4')
							<li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
							<i class="fas fa-cubes side-menu__icon"></i>
							TBO<i class="fe fe-chevron-down horizontal-icon"></i></a>
								<ul class="sub-menu">
									<li><a href="{{url('tbo')}}">All TBO</a></li>
									<li><a href="{{url('tbo_overdue_pending')}}">Pending TBO</a></li>
									<li><a href="{{url('tbo_overdue')}}">Pending TBO Overdue</a></li>
								</ul>
							</li>

							{{-- MENU PENDING DISBURSEMENT - SPV2, SPV3, SPV4 --}}
							<li>
								<a href="{{route('datafile.pendingDisbursement')}}"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
									<i class="fas fa-money-check-alt side-menu__icon"></i>
								Pending Disbursement
								@php
									// Hitung jumlah loan yang pending disbursement
									$count_pending_disburs_query = \App\Models\DataFileModel::where('file_bukti_verifikator', '!=', '')
										->whereNotNull('file_bukti_verifikator')
										->where('ready_to_disburs', 0)
										->where('processed', 1);

									// Apply branch filtering based on role
									if(Session('role') == 'spv2') {
										$count_pending_disburs_query->where('branch_code', Session('branch_code'));
									} elseif(Session('role') == 'spv3') {
										$branchlist = Session('branchlist');
										if($branchlist && $branchlist != 'all') {
											$branches = explode(',', $branchlist);
											$count_pending_disburs_query->whereIn('branch_code', $branches);
										}
									}
									// SPV4 sees all

									$count_pending_disburs = $count_pending_disburs_query->count();
								@endphp
								@if($count_pending_disburs > 0)
									<span class="badge badge-success side-badge">{{ $count_pending_disburs }}</span>
								@endif
								</a>
							</li>
							@endif

							{{-- MENU TEAM VERIFIKATOR - START --}}
							@if(Session('role')=='team_verifikator_lvl1')
							{{-- Menu 1: Pending Recommendation --}}
							<li>
								<a href="{{route('datafile.waiting_verifikator_lvl1_recommendation')}}"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
									<i class="fas fa-file side-menu__icon"></i>
								Pending Recommendation
								@php
									// Hitung jumlah loan yang menunggu rekomendasi (State 1)
									$count_recommendation = \App\Models\DataFileModel::whereHas('masterproduk', function($query) {
											$query->whereIn('jenis_produk', [2, 3, 8]);
										})
										->where(function($query) {
											$query->where(function($q) {
												$q->where('final_status_spv2', 1)->where('final_status_spv3', 1);
											})->orWhere(function($q) {
												$q->where('final_status_spv2', 3)->where('final_status_spv3', 3);
											});
										})
										->where('date_input','>=', '2025-08-01')
										->where('final_status_verif1', 0)
										->where('final_status_verif2', 0)
										->count();
								@endphp
								@if($count_recommendation > 0)
									<span class="badge badge-warning side-badge">{{ $count_recommendation }}</span>
								@endif
								</a>
							</li>

							{{-- Menu 2: Pending Upload File --}}
							<li>
								<a href="{{route('datafile.waiting_verifikator_lvl1_upload')}}"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
									<i class="fas fa-upload side-menu__icon"></i>
								Pending Verification Form
								@php
									// Hitung jumlah loan yang menunggu upload (State 3)
									$count_upload = \App\Models\DataFileModel::whereHas('masterproduk', function($query) {
											$query->whereIn('jenis_produk', [2, 3, 8]);
										})
										->where(function($query) {
											$query->where(function($q) {
												$q->where('final_status_spv2', 1)->where('final_status_spv3', 1);
											})->orWhere(function($q) {
												$q->where('final_status_spv2', 3)->where('final_status_spv3', 3);
											});
										})
										->where('date_input','>=', '2025-08-01')
										->whereIn('final_status_verif1', [5, 6])
										->whereIn('final_status_verif2', [5, 6])
										->whereNull('file_bukti_verifikator')
										->count();
								@endphp
								@if($count_upload > 0)
									<span class="badge badge-info side-badge">{{ $count_upload }}</span>
								@endif
								</a>
							</li>
							@endif

							@if(Session('role')=='team_verifikator_lvl2')
							<li>
								<a href="{{url('waiting_verifikator_lvl2')}}"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
									<i class="fas fa-gavel side-menu__icon"></i>
								Queue Verifikator Level 2
								@php
									// Hitung jumlah loan yang menunggu
									$count_verif2 = \App\Models\DataFileModel::whereHas('masterproduk', function($query) {
											$query->whereIn('jenis_produk', [2, 3, 8]);
										})
										->whereIn('final_status_verif1', [5, 6])
										->where('final_status_verif2', 0)
										->count();
								@endphp
								@if($count_verif2 > 0)
									<span class="badge badge-danger side-badge">{{ $count_verif2 }}</span>
								@endif
								</a>
							</li>
							@endif
							{{-- MENU TEAM VERIFIKATOR - END --}}
							<li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
							<i class="fas fa-file-download side-menu__icon"></i>
								Report<i class="fe fe-chevron-down horizontal-icon"></i></a>
								<ul class="sub-menu">
									<li><a href="{{url('report')}}">Download Report</a></li>
									<li><a href="{{url('report-detail')}}">Download Report Detail</a></li>
									@if(Session('role')=='spv3' || Session('role')=='spv4'|| Session('role')=='spv5' ||Session('role')=='administrator')
									<li><a href="{{url('chart')}}">Chart</a></li>
									<li><a href="{{url('report-sla')}}">SLA</a></li>
									<!-- <li><a href="{{url('report-tgl-tbo')}}">Perpanjangan Tanggal TBO</a></li> -->
									<li><a href="{{url('report-waktu-layanan')}}">Perpanjangan Waktu Layanan</a></li>
									@endif
								</ul>
							</li>
							@if(Session('role')=='spv3' || Session('role')=='spv4' || Session('role')=='administrator' || Session('role')=='monitoring')
							<li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
							<i class="fas fa-cubes side-menu__icon"></i>
							Master<i class="fe fe-chevron-down horizontal-icon"></i></a>
								<ul class="sub-menu">
									@if(Session('role')=='spv4' || Session('role')=='administrator')
									<li><a href="{{url('users')}}">User</a></li>
									<li><a href="{{url('branch')}}">Branch</a></li>
									<li><a href="{{url('faq')}}">FAQ</a></li>
									<li><a href="{{url('news')}}">News</a></li>

									<li><a href="{{ route('setting_time.edit', 1) }}">Setting Request Cut Off</a></li>
									@endif
									@if(Session('role')=='spv4' || Session('role')=='administrator' ||Session('role')=='spv3')
									<li><a href="{{url('cut_off')}}">List Cut Off</a></li>
									<li><a href="{{url('cut_off_hq')}}">Setting Cut Off HQ</a></li>
									<li><a href="{{url('branch_tbo')}}">Setting Max TBO Overdue</a></li>
									<li><a href="{{route('admin.locked_loans')}}">Unlock Loan App No</a></li>
									<li><a href="{{route('setting_ai.edit',1)}}">Setting AI Validator</a></li>
									<li><a href="{{url('master-klasifikasi-debitur')}}">Klasifikasi Debitur</a></li>
									<li><a href="{{url('master-instansi')}}">Instansi</a></li>
									<li><a href="{{url('kebijakan-sop')}}">Kebijakan SOP</a></li>
									<li><a href="{{url('internal-memo')}}">Internal Memo</a></li>
									@endif
									<li><a href="{{route('loan.indexhistory')}}">Search History Data</a></li>

								</ul>
							</li>
							@endif
							<!-- @if(Session('role')=='spv4' || Session('role')=='spv5' || Session("role")=="pc")
								@endif-->
								@if(Session('role')=='spv4')
							<li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
							<i class="fas fa-cubes side-menu__icon"></i>
							Approve<i class="fe fe-chevron-down horizontal-icon"></i></a>
								<ul class="sub-menu">

									<li><a href="{{url('perpanjangan_jam')}}">Approve Perpanjangan Jam Layanan</a></li>


								</ul>
							</li>
							@endif

							@if(Session('role')=='spv5')
							<li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div>
							<i class="fas fa-cubes side-menu__icon"></i>
							Approve<i class="fe fe-chevron-down horizontal-icon"></i></a>
								<ul class="sub-menu">

									<!-- <li><a href="{{url('perpanjangan_jam')}}">Approve Perpanjangan Jam Layanan</a></li> -->
									<!-- <li><a href="{{url('perpanjangan_tbo')}}">Approve Perpanjangan Tgl TBO</a></li> -->
									@if(Session('role')=='spv5')
									<li><a href="{{url('branch_tbo')}}">Approve Setting Max TBO Overdue</a></li>
									<li><a href="{{url('cut_off_hq')}}">Approve Cut Off HQ</a></li>
									<li><a href="{{url('cut_off')}}">List Cut Off</a></li>
									@endif

								</ul>
							</li>
							@endif
							<!-- <li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div><svg class="hor__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M17 7H7V4H5v16h14V4h-2z" opacity=".3"/><path d="M19 2h-4.18C14.4.84 13.3 0 12 0S9.6.84 9.18 2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm7 18H5V4h2v3h10V4h2v16z"/></svg> Elements<i class="fe fe-chevron-down horizontal-icon"></i></a>
								<div class="horizontal-megamenu clearfix">
									<div class="container">
										<div class="mega-menubg hor-mega-menu">
											<div class="row">
												<div class="col-lg-3 col-md-12 col-xs-12 link-list">
													<ul class="p-0">
														<li><h3 class="fs-14 font-weight-bold mb-1 mt-2">Elements</h3></li>
														<li><a href="{{url('alerts')}}" class="slide-item">Alerts</a></li>
														<li><a href="{{url('avatar')}}" class="slide-item">Avatar</a></li>
														<li><a href="{{url('breadcrumbs')}}" class="slide-item">Breadcrumbs</a></li>
														<li><a href="{{url('buttons')}}" class="slide-item">Buttons</a></li>
														<li><a href="{{url('badge')}}" class="slide-item">Badge</a></li>
														<li><a href="{{url('dropdown')}}" class="slide-item">Dropdown</a></li>
														<li><a href="{{url('thumbnails')}}" class="slide-item">Thumbnails</a></li>
														<li><a href="{{url('images')}}" class="slide-item">Images</a></li>
														<li><a href="{{url('list-group')}}" class="slide-item">List Group</a></li>
													</ul>
												</div>
												<div class="col-lg-3 col-md-12 col-xs-12 link-list">
													<ul class="p-0">
														<li><a href="{{url('navigation')}}" class="slide-item">Navigation</a></li>
														<li><a href="{{url('pagination')}}" class="slide-item">Pagination</a></li>
														<li><a href="{{url('popover')}}" class="slide-item">Popover</a></li>
														<li><a href="{{url('progress')}}" class="slide-item">Progress</a></li>
														<li><a href="{{url('spinners')}}" class="slide-item">Spinners</a></li>
														<li><a href="{{url('media-object')}}" class="slide-item">Media Object</a></li>
														<li><a href="{{url('typography')}}" class="slide-item">Typography</a></li>
														<li><a href="{{url('tooltip')}}" class="slide-item">Tooltip</a></li>
														<li><a href="{{url('toast')}}" class="slide-item">Toast</a></li>
														<li><a href="{{url('tags')}}" class="slide-item">Tags</a></li>
													</ul>
												</div>
												<div class="col-lg-3 col-md-12 col-xs-12 link-list">
													<ul class="p-0">
														<li><a href="{{url('tabs')}}" class="slide-item">Tabs</a></li>
														<li><h3 class="fs-14 font-weight-bold mb-1 mt-4">Apps</h3></li>
														<li><a href="{{url('cards')}}" class="slide-item">Cards</a></li>
														<li><a href="{{url('darggablecards')}}" class="slide-item">Draggable-cards</a></li>
														<li><a href="{{url('rangeslider')}}" class="slide-item">Range-slider</a></li>
														<li><a href="{{url('calendar')}}" class="slide-item">Calendar</a></li>
														<li><a href="{{url('contacts')}}" class="slide-item">Contacts</a></li>
														<li><a href="{{url('image-compare')}}" class="slide-item">Image-compare</a></li>
														<li><a href="{{url('notification')}}" class="slide-item">Notification</a></li>
														<li><a href="{{url('widget-notification')}}" class="slide-item">Widget-notification</a></li>
													</ul>
												</div>
												<div class="col-lg-3 col-md-12 col-xs-12 link-list">
													<ul class="p-0">
														<li><a href="{{url('treeview')}}" class="slide-item">Treeview</a></li>
														<li><h3 class="fs-14 font-weight-bold mb-1 mt-4">Maps</h3></li>
														<li><a href="{{url('map-leaflet')}}" class="slide-item">Leaflet Maps</a></li>
														<li><a href="{{url('map-vector')}}" class="slide-item">Vector Maps</a></li>
														<li><h3 class="fs-14 font-weight-bold mb-1 mt-4">Tables</h3></li>
														<li><a href="{{url('table-basic')}}" class="slide-item">Basic Tables</a></li>
														<li><a href="{{url('table-data')}}" class="slide-item">Data Tables</a></li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</li>
							<li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div><svg class="hor__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 3.19L5 6.3V12h7v8.93c3.72-1.15 6.47-4.82 7-8.94h-7v-8.8z" opacity=".3"/><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 19.93V12H5V6.3l7-3.11v8.8h7c-.53 4.12-3.28 7.79-7 8.94z"/></svg> Advanced UI <i class="fe fe-chevron-down horizontal-icon"></i></a>
								<ul class="sub-menu">
									<li><a href="{{url('accordion')}}" class="slide-item"> Accordion</a></li>
									<li><a href="{{url('carousel')}}" class="slide-item" >Carousel</a></li>
									<li><a href="{{url('collapse')}}" class="slide-item">Collapse</a></li>
									<li><a href="{{url('modals')}}" class="slide-item">Modals</a></li>
									<li><a href="{{url('timeline')}}" class="slide-item">Timeline</a></li>
									<li><a href="{{url('sweet-alert')}}" class="slide-item">Sweet Alert</a></li>
									<li><a href="{{url('rating')}}" class="slide-item">Ratings</a></li>
									<li><a href="{{url('counters')}}" class="slide-item">Counters</a></li>
									<li><a href="{{url('search')}}" class="slide-item">Search</a></li>
									<li><a href="{{url('userlist')}}" class="slide-item"> Userlist</a></li>
									<li class="sub-menu-sub"><a href="#">Sub Menu</a>
										<ul class="sub-menu">
											<li><a href="#" >Submenu 1</a></li>
											<li class="sub-menu-sub">
												<a href="#" >Submenu 2</a>
												<ul class="sub-menu">
													<li><a href="#" >Submenu 2.1</a></li>
													<li><a href="#" >Submenu 2.2</a></li>
													<li><a href="#" >Submenu 2.3</a></li>
												</ul>
											</li>
											<li><a href="#" >Submenu 3</a></li>
										</ul>
									</li>
								</ul>
							</li>
							<li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div><svg class="hor__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M6.26 9L12 13.47 17.74 9 12 4.53z" opacity=".3"/><path d="M19.37 12.8l-7.38 5.74-7.37-5.73L3 14.07l9 7 9-7zM12 2L3 9l1.63 1.27L12 16l7.36-5.73L21 9l-9-7zm0 11.47L6.26 9 12 4.53 17.74 9 12 13.47z"/></svg> Utilities <i class="fe fe-chevron-down horizontal-icon"></i></a>
								<ul class="sub-menu">
									<li><a href="{{url('background')}}" class="slide-item">Background</a></li>
									<li><a href="{{url('border')}}" class="slide-item">Border</a></li>
									<li><a href="{{url('display')}}" class="slide-item">Display</a></li>
									<li><a href="{{url('flex')}}" class="slide-item">Flex</a></li>
									<li><a href="{{url('height')}}" class="slide-item">Height</a></li>
									<li><a href="{{url('margin')}}" class="slide-item">Margin</a></li>
									<li><a href="{{url('padding')}}" class="slide-item">Padding</a></li>
									<li><a href="{{url('position')}}" class="slide-item">Position</a></li>
									<li><a href="{{url('width')}}" class="slide-item">Width</a></li>
									<li><a href="{{url('extras')}}" class="slide-item">Extras</a></li>
								</ul>
							</li>
							<li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div><svg class="hor__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M13 4H6v16h12V9h-5V4zm3 14H8v-2h8v2zm0-6v2H8v-2h8z" opacity=".3"/><path d="M8 16h8v2H8zm0-4h8v2H8zm6-10H6c-1.1 0-2 .9-2 2v16c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg> Pages <i class="fe fe-chevron-down horizontal-icon"></i></a>
								<ul class="sub-menu">
									<li><a href="{{url('profile')}}" class="slide-item">Profile</a></li>
									<li><a href="{{url('editprofile')}}" class="slide-item">Edit-profile</a></li>
									<li class="sub-menu-sub"><a href="#">Mail <span class="badge badge-pink side-badge">5</span></a>
										<ul class="sub-menu">
											<li><a href="{{url('mail-inbox')}}" class="slide-item">Mail-inbox</a></li>
											<li><a href="{{url('mail-compose')}}" class="slide-item">Mail-compose</a></li>
											<li><a href="{{url('mail-read')}}" class="slide-item">Mail-read</a></li>
											<li><a href="{{url('mail-settings')}}" class="slide-item">Mail-settings</a></li>
											<li><a href="{{url('chat')}}" class="slide-item">Chat</a></li>
										</ul>
									</li>
									<li class="sub-menu-sub"><a href="#">Forms <span class="badge badge-info side-badge">6</span></a>
										<ul class="sub-menu">
											<li><a class="slide-item" href="{{url('form-elements')}}">Form Elements</a></li>
											<li><a class="slide-item" href="{{url('form-advanced')}}">Advanced Forms</a></li>
											<li><a class="slide-item" href="{{url('form-layouts')}}">Form Layouts</a></li>
											<li><a class="slide-item" href="{{url('form-validation')}}">Form Validation</a></li>
											<li><a class="slide-item" href="{{url('form-wizards')}}">Form Wizards</a></li>
											<li><a class="slide-item" href="{{url('form-editor')}}">Form Editor</a></li>
											<li><a class="slide-item" href="{{url('form-elements-sizes')}}">Form Sizes</a></li>
											<li><a class="slide-item" href="{{url('form-inputmask')}}">Input Mask</a></li>
											<li><a class="slide-item" href="{{url('form-upload')}}">File Uploads</a></li>
										</ul>
									</li>
									<li class="sub-menu-sub"><a href="#">Blog</a>
										<ul class="sub-menu">
											<li><a class="slide-item" href="{{url('bloglist01')}}">Blog list 01</a></li>
											<li><a class="slide-item" href="{{url('bloglist02')}}">Blog list 02</a></li>
											<li><a class="slide-item" href="{{url('bloglist03')}}">Blog list 03</a></li>
											<li><a class="slide-item" href="{{url('bloglist04')}}">Blog list 04</a></li>
											<li><a class="slide-item" href="{{url('bloglist05')}}">Blog list 05</a></li>
											<li><a class="slide-item" href="{{url('blog')}}">Blog Details</a></li>
										</ul>
									</li>
									<li class="sub-menu-sub"><a href="#">Icons</a>
										<ul class="sub-menu">
											<li><a class="slide-item" href="{{url('icons')}}">Fontawesome</a></li>
											<li><a class="slide-item" href="{{url('icons2')}}">Simple line</a></li>
											<li><a class="slide-item" href="{{url('icons3')}}">Feather</a></li>
											<li><a class="slide-item" href="{{url('icons4')}}">Line Awesome</a></li>
											<li><a class="slide-item" href="{{url('icons5')}}">Themify</a></li>
											<li><a class="slide-item" href="{{url('icons6')}}">Typicon Icons</a></li>
										</ul>
									</li>
									<li><a href="{{url('invoice')}}" class="slide-item">Invoice</a></li>
									<li><a href="{{url('todotask')}}" class="slide-item">Todotask</a></li>
									<li><a href="{{url('pricing')}}" class="slide-item">Pricing</a></li>
									<li><a href="{{url('gallery')}}" class="slide-item">Gallery</a></li>
									<li><a href="{{url('faq')}}" class="slide-item">Faqs</a></li>
									<li><a href="{{url('emptypage')}}" class="slide-item">Empty Page</a></li>
								</ul>
							</li>
							<li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div><svg class="hor__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.55 11l2.76-5H6.16l2.37 5z" opacity=".3"/><path d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg> E-Commerce <i class="fe fe-chevron-down horizontal-icon"></i></a>
								<ul class="sub-menu">
									<li><a href="{{url('products')}}" class="slide-item"> Products</a></li>
									<li><a href="{{url('product-details')}}" class="slide-item">Product-Details</a></li>
									<li><a href="{{url('product-cart')}}" class="slide-item">Shopping Cart</a></li>
									<li><a href="{{url('product-checkout')}}"  class="slide-item">Product Checkout</a></li>
								</ul>
							</li>
							<li><a href="#" class="sub-icon"><div class="side-angle1"></div><div class="side-angle2"></div><div class="side-arrow"></div><svg class="hor__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 19h14V5H5v14zm7-13c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3zM6 16.47c0-2.5 3.97-3.58 6-3.58s6 1.08 6 3.58V18H6v-1.53z" opacity=".3"/><path d="M12 12c1.65 0 3-1.35 3-3s-1.35-3-3-3-3 1.35-3 3 1.35 3 3 3zm0-4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm7-5H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-1-2.53c0-2.5-3.97-3.58-6-3.58s-6 1.08-6 3.58V18h12v-1.53zM8.31 16c.69-.56 2.38-1.12 3.69-1.12s3.01.56 3.69 1.12H8.31z"/></svg> Authenication <i class="fe fe-chevron-down horizontal-icon"></i></a>
								<ul class="sub-menu">
									<li><a href="{{url('signin')}}" class="slide-item">Sign In</a></li>
									<li><a href="{{url('signup')}}" class="slide-item">Sign Up</a></li>
									<li><a href="{{url('forgot')}}" class="slide-item">Forgot Password</a></li>
									<li><a href="{{url('reset')}}" class="slide-item">Reset Password</a></li>
									<li><a href="{{url('lockscreen')}}" class="slide-item">Lock screen</a></li>
									<li><a href="{{url('underconstruction')}}" class="slide-item">UnderConstruction</a></li>
									<li><a href="{{url('error404')}}" class="slide-item">404 Error</a></li>
									<li><a href="{{url('error500')}}" class="slide-item">500 Error</a></li>
								</ul>
							</li> -->
						</ul>
					</nav>
					<!--Nav-->
				</div>
			</div>
		</div>
		<!--Horizontal-main -->
