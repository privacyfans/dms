@extends('layouts.app')

@section('styles')

		<!--  Owl-carousel css-->
		<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet" />

		<!-- Maps css -->
		<link href="{{URL::asset('assets/plugins/jqvmap/jqvmap.min.css')}}" rel="stylesheet">

		<!-- Jvectormap css -->
        <link href="{{URL::asset('assets/plugins/jqvmap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />
    <style>
      /* CSS untuk memastikan chart memiliki ruang cukup */
      .chartpickup_history-chart-container {
        width: 100%;
        height: 400px;
        position: relative;
        margin-right: 20px; /* Tambahkan margin di sebelah kanan container */
        overflow: visible !important; /* Penting: memastikan label tidak di-clip */
      }

      /* Pastikan canvas chart mengisi seluruh container */
      #chartpickup_history {
        width: 100% !important;
        height: 100% !important;
        overflow: visible !important; /* Penting: memastikan label tidak di-clip */
      }

      /* Memastikan card juga mengisi seluruh lebar dan tidak memotong konten */
      .card {
        width: 100%;
        overflow: visible !important;
      }

      /* Pastikan parent container juga tidak memotong konten */
      .card-body {
        overflow: visible !important;
      }

      /* Odd-Even table styling */
      .table-striped-custom thead tr th {
        background-color: #6cb3f3 !important;
        color: #ffffff !important;
        font-weight: bold !important;
      }

      .table-striped-custom tbody tr:nth-child(odd) {
        background-color: #ffffff !important;
      }

      .table-striped-custom tbody tr:nth-child(even) {
        background-color: #6cb3f3 !important;
      }

      /* Ensure text is visible on both backgrounds */
      .table-striped-custom tbody tr td {
        color: #333333;
      }

      /* Table footer styling */
      .table-striped-custom tfoot tr {
        background-color: #6cb3f3 !important;
        font-weight: bold !important;
      }

      .table-striped-custom tfoot tr th,
      .table-striped-custom tfoot tr td {
        color: #ffffff !important;
      }
      </style>
@endsection

@section('breadcrumb')
					<div class="left-content">
						<!-- <h4 class="content-title mb-2">Hi, Welcome Back! {{  session('name') }}</h4> -->
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item">Dashboard</li>
								<li class="breadcrumb-item active" aria-current="page"><a href="#">Chart</a></li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
        <!-- <h5>DATA {{$dari}} - {{$sampai}}</h5> -->

        <form action="{{ route('chart') }}" method="get">
        <div class="row row-sm">
					<div class="col-lg-12">
						<div class="row row-sm">
              <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
                 
                  <div class="row row-xs align-items-center mg-b-20">
										<div class="col-md-2">
											<label class="form-label mg-b-0">From Date</label>
										</div>
										<div class="col-md-3 mg-t-5">
											<input class="form-control" id="from_date" type="text" name="from_date" required="" value="{{$dari}}">
										</div>
                    <div class="col-md-2">
											<label class="form-label mg-b-0">End Date</label>
										</div>
										<div class="col-md-3 mg-t-5">
											<input class="form-control" id="end_date" type="text" name="end_date" required="" value="{{$sampai}}">
										</div>
                    <div class="col-md-2 mg-t-5">
                    <button class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">Submit</button>
                    </div>
									</div>
                  @if (session('warning'))
                
                  <div class="alert alert-danger mg-b-0" role="alert">
                      <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                          <span aria-hidden="true">&times;</span>
                      </button>
                      <p><b>{{ session('warning') }}</b></p>
                  </div>
              
        
  @endif
									</div>
							  </div>
               
              </div>

              
						</div>
          </div>
        </div>
      </form>
        <div class="row row-sm">
					<div class="col-lg-12">
						<div class="row row-sm">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
                  <div class="main-content-label mg-b-5" style="font-size: 16px; font-weight: bold;">
                  DCRM REVIEWER  Average of Daily Transaction Time 
                  </div>
                  <div class="table-responsive">
                    <table class="table table-bordered mg-b-0 text-md-nowrap" style="font-size: 16px; font-weight: bold;">
                    <thead>
                      <tr>
                          <th>08:00 - 10:00</th>
                          <th>10:00 - 12:00</th>
                          <th>12:00 - 13:00</th>
                          <th>13:00 - 15:00</th>
                          <th>15:00 - 17:00</th>
                          <th>17:00 - 19:00</th>
                          <th>&gt;19:00</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td style="text-align:center">{{ number_format($dailybpr1[0]->a,0,",",".")  }}</td>
                        <td style="text-align:center">{{ number_format($dailybpr1[0]->b,0,",",".")  }}</td>
                        <td style="text-align:center">{{ number_format($dailybpr1[0]->c,0,",",".")  }}</td>
                        <td style="text-align:center">{{ number_format($dailybpr1[0]->d,0,",",".")  }}</td>
                        <td style="text-align:center">{{ number_format($dailybpr1[0]->e,0,",",".")  }}</td>
                        <td style="text-align:center">{{ number_format($dailybpr1[0]->f,0,",",".")  }}</td>
                        <td style="text-align:center">{{ number_format($dailybpr1[0]->g,0,",",".")  }}</td>
                      </tr>
                    </tbody>
                    </table>
                    </div>
                    <div class="row justify-content-md-center">
                      
                      <div class="col-md-auto">
                        <canvas id="dailybpr1"></canvas>
                      </div>
                      
                    </div>
                   
                    
									</div>
							  </div>
              </div>

              <!-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
									<div class="main-content-label mg-b-5">
                  DCRM TEAM LEADER  Average of Daily Transaction Time 
                  </div>	
                  <div class="table-responsive">
                    <table class="table table-bordered mg-b-0 text-md-nowrap">
                      <thead>
                        <tr>
                          <th>08:00 - 10:00</th>
                          <th>10:00 - 12:00</th>
                          <th>12:00 - 13:00</th>
                          <th>13:00 - 15:00</th>
                          <th>15:00 - 17:00</th>
                          <th>17:00 - 19:00</th>
                          <th>&gt;19:00</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                        <td style="text-align:center">{{ number_format($dailybpr2[0]->a,0,",",".") }}</td>
                        <td style="text-align:center">{{ number_format($dailybpr2[0]->b,0,",",".") }}</td>
                        <td style="text-align:center">{{ number_format($dailybpr2[0]->c,0,",",".") }}</td>
                        <td style="text-align:center">{{ number_format($dailybpr2[0]->d,0,",",".") }}</td>
                        <td style="text-align:center">{{ number_format($dailybpr2[0]->e,0,",",".") }}</td>
                        <td style="text-align:center">{{ number_format($dailybpr2[0]->f,0,",",".") }}</td>
                        <td style="text-align:center">{{ number_format($dailybpr2[0]->g,0,",",".") }}</td>
                        </tr>
                      </tbody>
                      </table>
                    </div>
                    <div class="row justify-content-md-center">
                      
                      <div class="col-md-auto">
                        <canvas id="dailybpr2"></canvas>
                      </div>
                      
                    </div>
									</div>
								</div>
							</div> -->
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
									<div class="main-content-label mg-b-5" style="font-size: 16px; font-weight: bold;">
                  SLA ( Service Level Agreement )
                  </div>	
                  <div class="chat_sla_agregate-chart-container">
                    <canvas id="chat_sla_agregate"  width="400" height="290"></canvas>
                  </div>
										
									</div>
								</div>
							</div>
						</div>
          </div>
        </div>


        <div class="row  justify-content-md-center">
					<div class="col-lg-12">
						<div class="row row-sm">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
                  <div class="main-content-label mg-b-5" style="font-size: 16px; font-weight: bold;">
                    Hasil Review Berdasarkan Produk
                  </div>
                    <div class="totalbpr2-chart-container">
                   
                      <canvas id="totalproduk" width="400" height="100"></canvas>
                    </div>
										
									</div>
							  </div>
              </div>

             
						</div>
          </div>
        </div>

        <!-- <div class="row  justify-content-md-center">
					<div class="col-lg-12">
						<div class="row row-sm">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
                  <div class="main-content-label mg-b-5">
                    Hasil Review Berdasarkan Produk
                  </div>
                    <div class="totalbpr2-chart-container">
                   
                      <canvas id="totalproduk" width="400" height="180"></canvas>
                    </div>
										
									</div>
							  </div>
              </div>

              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
									<div class="main-content-label mg-b-5">
                  SLA ( Service Level Agreement )
                  </div>	
                  <div class="chat_sla_agregate-chart-container">
                    <canvas id="chat_sla_agregate"  width="400" height="180"></canvas>
                  </div>
										
									</div>
								</div>
							</div>
						</div>
          </div>
        </div> -->

     
        
        

        <div class="row row-sm">
					<div class="col-lg-12">
						<div class="row row-sm">
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
                  <div class="main-content-label mg-b-5" style="font-size: 16px; font-weight: bold;">
                  Total Review DCRM (Tanggal Input)
                  </div>
                    <div class="totalbpr1-chart-container">
                      <canvas id="totalbpr1"></canvas>
                    </div>
										
									</div>
							  </div>
              </div>

              <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
									<div class="main-content-label mg-b-5" style="font-size: 16px; font-weight: bold;">
                  Detail Review DCRM (Tanggal Input)
                  </div>	
                  <div class="chartbpr1-chart-container">
                    <canvas id="chartbpr1" width="400" height="180"></canvas>
                  </div>
										
									</div>
								</div>
							</div>
						</div>
          </div>
        </div>

        <?php /*
        <div class="row row-sm">
					<div class="col-lg-12">
						<div class="row row-sm">
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
                  <div class="main-content-label mg-b-5">
                  Total Review DCRM TEAM LEADER (Tanggal Input)
                  </div>
                    <div class="totalbpr2-chart-container">
                      <canvas id="totalbpr2"></canvas>
                    </div>
										
									</div>
							  </div>
              </div>

              <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
									<div class="main-content-label mg-b-5">
                  Detail Review DCRM TEAM LEADER (Tanggal Input)
                  </div>	
                  <div class="chartbpr2-chart-container">
                    <canvas id="chartbpr2"  width="400" height="180"></canvas>
                  </div>
										
									</div>
								</div>
							</div>
						</div>
          </div>
        </div>
       */ ?>

        <?php /*
        <div class="row row-sm">
					<div class="col-lg-12">
						<div class="row row-sm">
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
                  <div class="main-content-label mg-b-5">
                  Total Review DCRM REVIEWER (History)
                  </div>
                    <div class="totalbpr1_history-chart-container">
                      <canvas id="totalbpr1_history"></canvas>
                    </div>
										
									</div>
							  </div>
              </div>

              <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
									<div class="main-content-label mg-b-5">
                  Detail Review DCRM REVIEWER (History)
                  </div>	
                  <div class="chartbpr1_history-chart-container">
                    <canvas id="chartbpr1_history" width="400" height="180"></canvas>
                  </div>
										
									</div>
								</div>
							</div>
						</div>
          </div>
        </div>
        */ ?>

        <div class="row row-sm">
					<div class="col-lg-12">
						<div class="row row-sm">
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
                  <div class="main-content-label mg-b-5" style="font-size: 16px; font-weight: bold;">
                  Total Review DCRM (History)
                  </div>
                    <div class="totalbpr2_history-chart-container">
                      <canvas id="totalbpr2_history"></canvas>
                    </div>
										
									</div>
							  </div>
              </div>

              <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
									<div class="main-content-label mg-b-5" style="font-size: 16px; font-weight: bold;">
                  Detail Review DCRM (History)
                  </div>	
                  <div class="chartbpr2_history-chart-container">
                    <canvas id="chartbpr2_history"  width="400" height="180"></canvas>
                  </div>
										
									</div>
								</div>
							</div>
						</div>
          </div>
        </div>

<!-- 
        <div class="row  justify-content-md-center">
					<div class="col-lg-12">
						<div class="row row-sm">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
                  <div class="main-content-label mg-b-5" style="font-size: 16px; font-weight: bold;">
                    Request Perpanjangan Jam Layanan
                  </div>
                    <div class="totalbpr2-chart-container">
                   
                      <canvas id="perpanjanganjam" width="400" height="150"></canvas>
                    </div>
										
									</div>
							  </div>
              </div>

             
						</div>
          </div>
        </div> -->
        
        <div class="row row-sm">
					<div class="col-lg-12">
						<div class="row row-sm">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
                  <div class="main-content-label mg-b-5" style="font-size: 16px; font-weight: bold;">
                  Pengajuan Reject & Not Verify 
                  </div>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped-custom mg-b-0 text-md-nowrap" style="font-size: 16px; font-weight: bold;">
                    <thead>
                      <tr>
                        <th style="text-align:center;font-size: 16px; font-weight: bold;">#</th>
                        <th>Branch Name </th>
                        <th style="text-align:center;font-size: 16px; font-weight: bold;">Total Data</th>
                        <th style="text-align:center;font-size: 16px; font-weight: bold;">Reject</th>
                        <th style="text-align:center;font-size: 16px; font-weight: bold;">Reject (%) </th>
                        <th style="text-align:center;font-size: 16px; font-weight: bold;">Not Verify</th>
                        <th style="text-align:center;font-size: 16px; font-weight: bold;">Not Verify (%) </th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      $x=1;
                      foreach($datareject as $row){
                        //$avg+=$rowbpr1->sla;
                      ?>
                      <tr>
                        <td style="text-align:center">{{ $x }}</td>
                        <td>{{ $row->branch_name }}</td>
                        <td style="text-align:center">{{ $row->total_jumlah_data }}</td>
                        <td style="text-align:center">{{ $row->reject }}</td>
                        <td style="text-align:center">{{ round($row->persentase_reject) }} %</td>
                        <td style="text-align:center">{{ $row->not_verify }}</td>
                        <td style="text-align:center">{{ round($row->persentase_not_verify) }} %</td>
                      </tr>
                      <?php
                        $x++;
                      } 
                      ?>

                      </tbody>
                     
                    </tbody>
                    </table>
                    </div>
                    
									</div>
							  </div>
              </div>

            
						</div>
          </div>
        </div>

        
        <div class="row row-sm">
					<div class="col-lg-12">
						<div class="row row-sm">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
                  <div class="main-content-label mg-b-5 text-lg" style="font-size: 16px; font-weight: bold;">
                  Average SLA / Loan ID
                  </div>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped-custom mg-b-0 text-md-nowrap" style="font-size: 16px; font-weight: bold;">
                        <thead >
                            <tr>
                                <th style="text-align:center;font-size: 16px; font-weight: bold;">NAME OF DCRM</th>
                                <th style="text-align:center;font-size: 16px; font-weight: bold;">TOTAL DOCUMENT APPROVAL</th>
                                <th style="text-align:center;font-size: 16px; font-weight: bold;">TOTAL TIMES</th>
                                <th style="text-align:center;font-size: 16px; font-weight: bold;">AVERAGE SLA / Loan ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalSeconds = 0;
                            $totalDocs = 0;
                            $totalAverageSeconds = 0;
                            $count = 0;

                            function timeToSeconds($time) {
                                $parts = explode(':', $time);
                                return $parts[0] * 3600 + $parts[1] * 60 + (isset($parts[2]) ? $parts[2] : 0);
                            }

                            function secondsToTime($seconds) {
                                return sprintf('%02d:%02d:%02d', ($seconds / 3600), ($seconds / 60 % 60), $seconds % 60);
                            }

                            foreach ($slabpr1 as $row) {
                                $totalSeconds += timeToSeconds($row->sla);
                                $totalDocs += $row->total_doc;
                                $totalAverageSeconds += timeToSeconds($row->average);
                                $count++;
                            ?>

                                <tr>
                                    <td style="text-align:center">{{ $row->nik }}</td>
                                    <td style="text-align:center">{{ $row->total_doc }}</td>
                                    <td style="text-align:center">{{ $row->sla }}</td>
                                    <td style="text-align:center">{{ $row->average }}</td>
                                </tr>
                            <?php
                            }

                            $avgSeconds = $count > 0 ? $totalSeconds / $count : 0;
                            $avgDocs = $count > 0 ? $totalDocs / $count : 0;
                            $avgAverageSeconds = $count > 0 ? $totalAverageSeconds / $count : 0;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="text-align:center">AVERAGE</th>
                                <td style="text-align:center">{{ number_format($avgDocs, 0) }}</td>
                                <td style="text-align:center">{{ secondsToTime(round($avgSeconds)) }}</td>
                                <td style="text-align:center">{{ secondsToTime(round($avgAverageSeconds)) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                    
									</div>
							  </div>
              </div>

            
						</div>
          </div>
        </div>

        <div class="row row-sm">
					<div class="col-lg-12">
						<div class="row row-sm">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
								<div class="card">
                  
									<div class="card-body iconfont text-left">
                  <div class="main-content-label mg-b-5 text-lg" style="font-size: 16px; font-weight: bold;">
                  Total Document Pickup/User
                  </div>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped-custom mg-b-0 text-md-nowrap" style="font-size: 16px; font-weight: bold;">
                         <thead >
                            <tr>
                                <th style="text-align:center;font-size: 16px; font-weight: bold;">NAME OF DCRM</th>
                                <th style="text-align:center;font-size: 16px; font-weight: bold;">TOTAL DOCUMENT PICKUP</th>
                                <th style="text-align:center;font-size: 16px; font-weight: bold;">AVERAGE PROCESSING TIME</th>
                                <!-- <th style="text-align:center;font-size: 16px; font-weight: bold;">MIN PROCESSING TIME</th>
                                <th style="text-align:center;font-size: 16px; font-weight: bold;">MAX PROCESSING TIME</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                           

                            foreach ($slapcikup as $row) {
                                
                            ?>

                                <tr>
                                    <td style="text-align:center">{{ $row->name }}</td>
                                    <td style="text-align:center">{{ $row->total_loan_applications }}</td>
                                    <td style="text-align:center">{{ $row->average_processing_time }}</td>
                                    <!-- <td style="text-align:center">{{ $row->min_processing_time }}</td>
                                    <td style="text-align:center">{{ $row->max_processing_time }}</td> -->
                                </tr>
                            <?php
                            }

                         
                            ?>
                        </tbody>
                        
                    </table>
                    </div>
                    
									</div>
							  </div>
              </div>

            
						</div>
          </div>
        </div>

        <div class="row row-sm">
          <div class="col-lg-12">
            <div class="row row-sm">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                  <!-- Menghapus div pembatas lebar col-xl-8 -->
                  <div class="card-body iconfont text-left">
                    <div class="main-content-label mg-b-5" style="font-size: 16px; font-weight: bold;">
                      Chart Total Document Pickup/User
                    </div>
                    <div class="chartpickup_history-chart-container" style="width: 100%;">
                      <canvas id="chartpickup_history" width="100%"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        
        <?php /*
        <div class="row row-sm">
					<div class="col-lg-12">
						<div class="row row-sm">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body iconfont text-left">
                        <div class="main-content-label mg-b-5">
                            Average SLA / Transaction Level 2
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered mg-b-0 text-md-nowrap">
                                <thead>
                                    <tr>
                                        <th>NAME OF DCRM TEAM LEADER</th>
                                        <th>TOTAL TIMES</th>
                                        <th>TOTAL DOCUMENT APPROVAL</th>
                                        <th>AVERAGE SLA / TRX</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalSeconds = 0;
                                    $totalDocs = 0;
                                    $totalAverageSeconds = 0;
                                    $count = 0;

                                    // function timeToSeconds($time) {
                                    //     $parts = explode(':', $time);
                                    //     return $parts[0] * 3600 + $parts[1] * 60 + (isset($parts[2]) ? $parts[2] : 0);
                                    // }

                                    // function secondsToTime($seconds) {
                                    //     return sprintf('%02d:%02d:%02d', ($seconds / 3600), ($seconds / 60 % 60), $seconds % 60);
                                    // }

                                    foreach ($slabpr2 as $row) {
                                        $totalSeconds += timeToSeconds($row->sla);
                                        $totalDocs += $row->total_doc;
                                        $totalAverageSeconds += timeToSeconds($row->average);
                                        $count++;
                                    ?>

                                        <tr>
                                            <td style="text-align:center">{{ $row->nik }}</td>
                                            <td style="text-align:center">{{ $row->sla }}</td>
                                            <td style="text-align:center">{{ $row->total_doc }}</td>
                                            <td style="text-align:center">{{ $row->average }}</td>
                                        </tr>
                                    <?php
                                    }

                                    $avgSeconds = $count > 0 ? $totalSeconds / $count : 0;
                                    $avgDocs = $count > 0 ? $totalDocs / $count : 0;
                                    $avgAverageSeconds = $count > 0 ? $totalAverageSeconds / $count : 0;
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="text-align:center">AVERAGE</th>
                                        <td style="text-align:center">{{ secondsToTime(round($avgSeconds)) }}</td>
                                        <td style="text-align:center">{{ number_format($avgDocs, 0) }}</td>
                                        <td style="text-align:center">{{ secondsToTime(round($avgAverageSeconds)) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
							</div>
						</div>
          </div>
        </div>
        */ ?>


			

@endsection('content')

@section('scripts')


		<!--Internal  Chart.bundle js -->
		<script src="{{URL::asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/chart.js/chartjs-plugin-datalabels.min.js')}}"></script>

		
		<script src="{{URL::asset('assets/js/index.js')}}"></script>
		
    <script>
       $(function () {
            $(document).ready(function () {
                $('#from_date').datepicker({
                    format: "yyyy-mm-dd",
                    todayBtn: "linked"
                });
				$('#end_date').datepicker({
                    format: "yyyy-mm-dd",
                    todayBtn: "linked"
                });
			})
	});

  $(function(){
      //get the pie chart canvas
      var cData = JSON.parse(`<?php echo $data['chart_data']; ?>`);
      var cDatatotal = JSON.parse(`<?php echo $datatotal['chart_data_total']; ?>`);
      var ctx = $("#chartbpr1");
      var ctx_total = $("#totalbpr1");
      //pie chart data

      var data_total = {
        labels: cDatatotal.label,
        datasets: [
          {
            label: "Users Count",
            data: cDatatotal.data,
            backgroundColor: [
              "#00d48f",
              "#ff8c00",
              "#5066e0",
              "#dc143c",
              "#a9a9a9",
            ],
            borderWidth: [1, 1, 1, 1, 1]
          }
        ]
      };
      
      var data = {
            labels: cData.label,
            datasets: [{
                label: 'Verify',
                data: cData.verify,
                backgroundColor: "#00d48f",
                borderWidth: 1,
                fill: !0
            }, {
                label: 'Not Verify',
                data: cData.not_verify,
                backgroundColor: "#ff8c00",
                borderWidth: 1,
                fill: !0
            }, {
                label: 'TBO',
                data: cData.tbo,
                backgroundColor: "#5066e0",
                borderWidth: 1,
                fill: !0
            }, {
                label: 'Reject',
                data: cData.reject,
                backgroundColor: "red",
                borderWidth: 1,
                fill: !0
            },{
                label: 'Total',
                data: cData.total,
                backgroundColor: "#02d7ff",
                borderWidth: 1,
                fill: !0
            }]
        };
     
 
      //options
      var options= {
           // maintainAspectRatio: !1,
            barValueSpacing: 5,
           
            scales: {
              x: {
                stacked: false,
              },
              y: {
                stacked: false
              }
            },
            plugins: {
              legend: {
                position: 'bottom',
                display: 1,
                labels: {
                    display: 1
                }
            },
            }
        };


        var options_total = {
        responsive: true,
        title: {
          display: false,
          position: "top",
          text: "Last Week Registered Users -  Day Wise Count",
          fontSize: 18,
          fontColor: "#111"
        },
        
        tooltips: {
          enabled: false
      },
      plugins: {
        datalabels: {
            formatter: (value, ctx) => {
                let sum = 0;
                let dataArr = ctx.chart.data.datasets[0].data;
                dataArr.map(data => {
                    sum += data;
                });
                let percentage = (value*100 / sum).toFixed(2)+"%";
                return percentage;
            },
            color: '#fff',
        },
        legend: {
          display: true,
          position: "bottom",
          usePointStyle: true,
          labels: {
            fontColor: "#333",
            fontSize: 9
          }
        },
      }
    };

 
      //create Pie Chart class object
      var chart1 = new Chart(ctx, {
        type: "bar",
        data: data,
        options: options
      });

      

      var chart2 = new Chart(ctx_total, {
        type: "pie",
        data: data_total,
        options: options_total,
        plugins: [ChartDataLabels],
      });
 
      
  });

  $(function(){
      //get the pie chart canvas
      
      var cData_history = JSON.parse(`<?php echo $data_history['chart_data_history']; ?>`);
      //console.log(cData_history);
      var cDatatotal_history = JSON.parse(`<?php echo $datatotal_history['chart_data_total_history']; ?>`);
      var ctx_history = $("#chartbpr1_history");
      var ctx_total_history = $("#totalbpr1_history");
      //pie chart data

      var data_total_history = {
        labels: cDatatotal_history.label,
        datasets: [
          {
            label: "Users Count",
            data: cDatatotal_history.data,
            backgroundColor: [
              "#00d48f",
              "#ff8c00",
              "#5066e0",
              "#dc143c",
              "#a9a9a9",
            ],
            borderWidth: [1, 1, 1, 1, 1]
          }
        ]
      };
      
      var data_history = {
            labels: cData_history.label,
            datasets: [{
                label: 'Verify',
                data: cData_history.verify,
                backgroundColor: "#00d48f",
                borderWidth: 1,
                fill: !0
            }, {
                label: 'Not Verify',
                data: cData_history.not_verify,
                backgroundColor: "#ff8c00",
                borderWidth: 1,
                fill: !0
            }, {
                label: 'TBO',
                data: cData_history.tbo,
                backgroundColor: "#5066e0",
                borderWidth: 1,
                fill: !0
            }, {
                label: 'Reject',
                data: cData_history.reject,
                backgroundColor: "red",
                borderWidth: 1,
                fill: !0
            },{
                label: 'Total',
                data: cData_history.total,
                backgroundColor: "#02d7ff",
                borderWidth: 1,
                fill: !0
            }]
        };
     
 
      //options
      var options= {
           // maintainAspectRatio: !1,
            barValueSpacing: 5,
           
            scales: {
              x: {
                stacked: false,
              },
              y: {
                stacked: false
              }
            },
            plugins: {
              legend: {
                position: 'bottom',
                display: 1,
                labels: {
                    display: 1
                }
            },
            }
        };


        var options_total = {
        responsive: true,
        title: {
          display: false,
          position: "top",
          text: "Last Week Registered Users -  Day Wise Count",
          fontSize: 18,
          fontColor: "#111"
        },
        
        tooltips: {
          enabled: false
      },
      plugins: {
        datalabels: {
            formatter: (value, ctx_history) => {
                let sum = 0;
                let dataArr = ctx_history.chart.data.datasets[0].data;
                dataArr.map(data => {
                    sum += data;
                });
                let percentage = (value*100 / sum).toFixed(2)+"%";
                return percentage;
            },
            color: '#fff',
        },
        legend: {
          display: true,
          position: "bottom",
          usePointStyle: true,
          labels: {
            fontColor: "#333",
            fontSize: 9
          }
        },
      }
    };

 
      //create Pie Chart class object
      var chart6 = new Chart(ctx_history, {
        type: "bar",
        data: data_history,
        options: options
      });

      

      var chart7 = new Chart(ctx_total_history, {
        type: "pie",
        data: data_total_history,
        options: options_total,
        plugins: [ChartDataLabels],
      });
 
      
  });

  $(function(){
      //get the pie chart canvas
      var cData = JSON.parse(`<?php echo $dataslaagregate['chat_sla_agregate']; ?>`);
      var ctx = $("#chat_sla_agregate");
      //pie chart data

     
      
      // var data = {
      //       labels: cData.label,
      //       datasets: [{
      //           label: 'Rata-Rata Waktu ',
      //           data: cData.rata,
      //           backgroundColor: "#ff8c00",
      //           borderWidth: 1,
      //           fill: !0
      //       }, {
      //           label: 'Rata-Rata Waktu Ideal',
      //           data: cData.rata_ideal,
      //           backgroundColor: "#00d48f",
      //           borderWidth: 1,
      //           fill: !0
      //       }]
      //   };

        var data = {
            labels: cData.label,
            datasets: [{
                label: 'Rata-Rata Waktu Ideal',
                data: cData.rata_ideal,
                backgroundColor: "#00d48f",
                borderWidth: 1,
                fill: !0
            }]
        };
     
 
      //options
      var options= {
        indexAxis: 'y',
           // maintainAspectRatio: !1,
            barValueSpacing: 5,
           
            scales: {
              x: {
                stacked: false,
              },
              y: {
                stacked: false
              }
            },
            plugins: {
              datalabels: {
                color: '#616161',
                formatter: (value) => {
                  return value > 0 ? value : '';
                }
              },
              legend: {
                position: 'bottom',
                display: 1,
                labels: {
                    display: 1
                }
              },
              subtitle: {
                display: true,
                text: 'Satuan dalam Menit',
                color: 'blue',
                font: {
                  size: 12,
                  family: 'tahoma',
                  weight: 'normal',
                  style: 'italic'
                },
                align: 'end',
                padding: {
                  bottom: 10
                }
              }
            }
        };


        
 
        //create Pie Chart class object
        var chart3 = new Chart(ctx, {
          type: "bar",
          data: data,
          options: options
        });
 
});

  $(function(){
      //get the pie chart canvas
      var cData = JSON.parse(`<?php echo $databpr2['chart_data_bpr2']; ?>`);
      var cDatatotal = JSON.parse(`<?php echo $datatotalbpr2['chart_data_total_bpr2']; ?>`);
      var ctx = $("#chartbpr2");
      var ctx_total = $("#totalbpr2");
      //pie chart data

      var data_total = {
        labels: cDatatotal.label,
        datasets: [
          {
            label: "Users Count",
            data: cDatatotal.data,
            backgroundColor: [
              "#00d48f",
              "#ff8c00",
              "#5066e0",
              "#dc143c",
              "#a9a9a9",
            ],
            borderWidth: [1, 1, 1, 1, 1]
          }
        ]
      };
      
      var data = {
            labels: cData.label,
            datasets: [{
                label: 'Verify',
                data: cData.verify,
                backgroundColor: "#00d48f",
                borderWidth: 1,
                fill: !0
            }, {
                label: 'Not Verify',
                data: cData.not_verify,
                backgroundColor: "#ff8c00",
                borderWidth: 1,
                fill: !0
            }, {
                label: 'TBO',
                data: cData.tbo,
                backgroundColor: "#5066e0",
                borderWidth: 1,
                fill: !0
            }, {
                label: 'Reject',
                data: cData.reject,
                backgroundColor: "red",
                borderWidth: 1,
                fill: !0
            },{
                label: 'Total',
                data: cData.total,
                backgroundColor: "#02d7ff",
                borderWidth: 1,
                fill: !0
            }]
        };
     
 
      //options
      var options= {
        indexAxis: 'y',
           // maintainAspectRatio: !1,
            barValueSpacing: 5,
           
            scales: {
              x: {
                stacked: false,
              },
              y: {
                stacked: false
              }
            },
            plugins: {
              legend: {
                position: 'bottom',
                display: 1,
                labels: {
                    display: 1
                }
            },
            }
        };


        var options_total = {
        responsive: true,
        title: {
          display: false,
          position: "top",
          text: "Last Week Registered Users -  Day Wise Count",
          fontSize: 18,
          fontColor: "#111"
        },
        
        tooltips: {
          enabled: false
      },
      plugins: {
        datalabels: {
            formatter: (value, ctx) => {
                let sum = 0;
                let dataArr = ctx.chart.data.datasets[0].data;
                dataArr.map(data => {
                    sum += data;
                });
                let percentage = (value*100 / sum).toFixed(2)+"%";
                return percentage;
            },
            color: '#fff',
        },
        legend: {
          display: true,
          position: "bottom",
          usePointStyle: true,
          labels: {
            fontColor: "#333",
            fontSize: 9
          }
        },
    }
      };

 
      //create Pie Chart class object
      var chart3 = new Chart(ctx, {
        type: "bar",
        data: data,
        options: options
      });

      var chart4 = new Chart(ctx_total, {
        type: "pie",
        data: data_total,
        options: options_total,
        plugins: [ChartDataLabels],
      });
 
  });

  $(function(){
      //get the pie chart canvas
      var cData_history = JSON.parse(`<?php echo $databpr2_history['chart_data_bpr2_history']; ?>`);
      var cDatatotal_history = JSON.parse(`<?php echo $datatotalbpr2_history['chart_data_total_bpr2_history']; ?>`);
      var ctx_history = $("#chartbpr2_history");
      var ctx_total_history = $("#totalbpr2_history");
      //pie chart data

      var data_total_history = {
        labels: cDatatotal_history.label,
        datasets: [
          {
            label: "Users Count",
            data: cDatatotal_history.data,
            backgroundColor: [
              "#00d48f",
              "#ff8c00",
              "#5066e0",
              "#dc143c",
              "#a9a9a9",
            ],
            borderWidth: [1, 1, 1, 1, 1]
          }
        ]
      };
      
      var data_history = {
            labels: cData_history.label,
            datasets: [{
                label: 'Verify',
                data: cData_history.verify,
                backgroundColor: "#00d48f",
                borderWidth: 1,
                fill: !0
            }, {
                label: 'Not Verify',
                data: cData_history.not_verify,
                backgroundColor: "#ff8c00",
                borderWidth: 1,
                fill: !0
            }, {
                label: 'TBO',
                data: cData_history.tbo,
                backgroundColor: "#5066e0",
                borderWidth: 1,
                fill: !0
            }, {
                label: 'Reject',
                data: cData_history.reject,
                backgroundColor: "red",
                borderWidth: 1,
                fill: !0
            },{
                label: 'Total',
                data: cData_history.total,
                backgroundColor: "#02d7ff",
                borderWidth: 1,
                fill: !0
            }]
        };
     
 
      //options
      var options= {
        indexAxis: 'y',
           // maintainAspectRatio: !1,
            barValueSpacing: 5,
           
            scales: {
              x: {
                stacked: false,
              },
              y: {
                stacked: false
              }
            },
            plugins: {
              legend: {
                position: 'bottom',
                display: 1,
                labels: {
                    display: 1
                }
            },
            }
        };


        var options_total = {
        responsive: true,
        title: {
          display: false,
          position: "top",
          text: "Last Week Registered Users -  Day Wise Count",
          fontSize: 18,
          fontColor: "#111"
        },
        
        tooltips: {
          enabled: false
      },
      plugins: {
        datalabels: {
            formatter: (value, ctx_history) => {
                let sum = 0;
                let dataArr = ctx_history.chart.data.datasets[0].data;
                dataArr.map(data => {
                    sum += data;
                });
                let percentage = (value*100 / sum).toFixed(2)+"%";
                return percentage;
            },
            color: '#fff',
        },
        legend: {
          display: true,
          position: "bottom",
          usePointStyle: true,
          labels: {
            fontColor: "#333",
            fontSize: 9
          }
        },
    }
      };

 
      //create Pie Chart class object
      var chart8 = new Chart(ctx_history, {
        type: "bar",
        data: data_history,
        options: options
      });

      var chart9 = new Chart(ctx_total_history, {
        type: "pie",
        data: data_total_history,
        options: options_total,
        plugins: [ChartDataLabels],
      });
 
  });

  $(function(){
      //get the pie chart canvas
      
      var cDatatotal = JSON.parse(`<?php echo $dataperpanjangan['chart_data_perpanjangan']; ?>`);
      
      var ctx_total = $("#perpanjanganjam");
      //pie chart data

      var data_total = {
        labels: cDatatotal.label,
        datasets: [
          {
            label: "Jumlah",
            data: cDatatotal.data,
            backgroundColor: [
              "#00d48f",
              "#5066e0", 
              "#ff8c00",
              "#dc143c",
              "#a9a9a9",
              '#aeb229',
              '#ffa996',
              '#ffa996'
            ],
            borderWidth: [1, 1, 1, 1, 1,1,1,1],
            minBarLength: 5,
          }
        ]
      };
      
   
      var options = {
        indexAxis: 'y',
        scales: {
          x: {
            stacked: false,
            ticks: {
              font: {
                size: 14, // Ukuran font untuk label sumbu X
                weight: 'bold'
              }
            }
          },
          y: {
            stacked: false,
            minBarLength: 5,
            ticks: {
              font: {
                size: 14, // Ukuran font untuk label sumbu Y
                weight: 'bold'
              }
            }
          }
        },
        plugins: {
          legend: {
            position: 'bottom',
            display: false,
            labels: {
              font: {
                size: 14 // Ukuran font untuk legend
              }
            }
          },
          datalabels: {
            anchor: 'end',
            align: 'top',
            formatter: (value, ctx) => {
              return value;
            },
            color: '#031B4E',
            font: {
              size: 14, // Ukuran font untuk data labels
              weight: 'bold'
            }
          }
        }
      };

      var options_total = {
        responsive: true,
        title: {
          display: false,
          position: "top",
          text: "Last Week Registered Users -  Day Wise Count",
          fontSize: 18,
          fontColor: "#111"
        },
        tooltips: {
          enabled: false,
          callbacks: {
            label: function(tooltipItem, data) {
              return tooltipItem.yLabel;
            }
          }
        },
        plugins: {
          datalabels: {
            anchor: 'end',
            align: 'top',
            formatter: (value, ctx) => {
              return value;
            },
            color: '#444',
            font: {
              size: 14, // Ukuran font untuk data labels
              weight: 'bold'
            }
          },
          legend: {
            display: false,
            position: "bottom",
            usePointStyle: true,
            labels: {
              font: {
                size: 14 // Ukuran font untuk legend
              }
            }
          }
        }
      };

      var chart5 = new Chart(ctx_total, {
        type: "bar",
        data: data_total,
        options: options,
        //plugins: [ChartDataLabels],
      });
 
  });
  
  $(function(){
      //get the pie chart canvas
      
      var cDatatotal = JSON.parse(`<?php echo $datatotalproduk['chart_data_total_produk']; ?>`);
      
      var ctx_total = $("#totalproduk");
      //pie chart data

      var data_total = {
        labels: cDatatotal.label,
        datasets: [
          {
            label: "Jumlah",
            data: cDatatotal.data,
            backgroundColor: [
              "#00d48f",
              "#5066e0",
              "#ff8c00",
              "#dc143c",
              "#a9a9a9",
              '#aeb229',
              '#ffa996',
              '#6a329f'
            ],
            borderWidth: [1, 1, 1, 1, 1,1,1,1],
            minBarLength: 5,
          }
        ]
      };
      
   
      var options= {
        indexAxis: 'y',
           // maintainAspectRatio: !1,
            barValueSpacing: 5,
           
            scales: {
              x: {
                stacked: false,
              },
              y: {
                stacked: false,
                minBarLength: 5,
              }
            },
            plugins: {
              legend: {
                position: 'bottom',
                display: 0,
                labels: {
                    display: 1
                }
            },
            }
        };


        var options_total = {
        responsive: true,
        title: {
          display: false,
          position: "top",
          text: "Last Week Registered Users -  Day Wise Count",
          fontSize: 18,
          fontColor: "#111"
        },
        
        tooltips: {
          enabled: false
      },
      plugins: {
        datalabels: {
          anchor: 'end', // Align label at the end (top) of the bar
        align: 'top', // Position label above the bar
            formatter: (value, ctx) => {
                // let sum = 0;
                // let dataArr = ctx.chart.data.datasets[0].data;
                // dataArr.map(data => {
                //     sum += data;
                // });
                // let percentage = (value*100 / sum).toFixed(2)+"%";
                // return percentage;
                return value;
            },
            color: '#444',
            font: {
      weight: 'bold' // Make the font bold
    }
        },
       
        legend: {
          display: true,
          position: "bottom",
          usePointStyle: true,
          labels: {
            fontColor: "#333",
            fontSize: 9
          }
        },
    }
      };

 
     

      var chart5 = new Chart(ctx_total, {
        type: "bar",
        data: data_total,
        options: options,
        //plugins: [ChartDataLabels],
      });
 
  });


  $(function(){
      //get the pie chart canvas
      
      var cDatatotal = JSON.parse(`<?php echo $datadailybpr1['chart_data_daily_bpr1']; ?>`);
      
      var ctx_total = $("#dailybpr1");
      //pie chart data

      var data_total = {
        labels: cDatatotal.label,
        datasets: [
          {
            label: "Users Count",
            data: cDatatotal.data,
            backgroundColor: [
              "#00d48f",
              "#5066e0",
              "#ff8c00",
              "#dc143c",
              "#a9a9a9",
              "#33ecff",
              "#f9ff33"
            ],
            borderWidth: [1, 1, 1, 1, 1]
          }
        ]
      };
      
   
      var options= {
        indexAxis: 'y',
           // maintainAspectRatio: !1,
            barValueSpacing: 5,
           
            scales: {
              x: {
                stacked: false,
              },
              y: {
                stacked: false
              }
            },
            plugins: {
              legend: {
                position: 'bottom',
                display: 1,
                labels: {
                    display: 1
                }
            },
            }
        };


        var options_total = {
        responsive: true,
        title: {
          display: false,
          position: "top",
          text: "Last Week Registered Users -  Day Wise Count",
          fontSize: 18,
          fontColor: "#111"
        },
        
        tooltips: {
          enabled: false
      },
      plugins: {
        datalabels: {
            formatter: (value, ctx) => {
                let sum = 0;
                let dataArr = ctx.chart.data.datasets[0].data;
                dataArr.map(data => {
                    sum += data;
                });
                let percentage = (value*100 / sum).toFixed(2)+"%";
                return percentage;
            },
            color: '#fff',
        },
        legend: {
          display: true,
          position: "bottom",
          usePointStyle: true,
          labels: {
            fontColor: "#333",
            fontSize: 9
          }
        },
    }
      };

 
     

      var chart5 = new Chart(ctx_total, {
        type: "pie",
        data: data_total,
        options: options_total,
        plugins: [ChartDataLabels],
      });
 
  });

  $(function(){
      //get the pie chart canvas
      
      var cDatatotal = JSON.parse(`<?php echo $datadailybpr2['chart_data_daily_bpr2']; ?>`);
      
      var ctx_total = $("#dailybpr2");
      //pie chart data

      var data_total = {
        labels: cDatatotal.label,
        datasets: [
          {
            label: "Users Count",
            data: cDatatotal.data,
            backgroundColor: [
              "#00d48f",
              "#5066e0",
              "#ff8c00",
              "#dc143c",
              "#a9a9a9",
              "#33ecff",
              "#f9ff33"
            ],
            borderWidth: [1, 1, 1, 1, 1]
          }
        ]
      };
      
   
      var options= {
        indexAxis: 'y',
           // maintainAspectRatio: !1,
            barValueSpacing: 5,
           
            scales: {
              x: {
                stacked: false,
              },
              y: {
                stacked: false
              }
            },
            plugins: {
              legend: {
                position: 'bottom',
                display: 1,
                labels: {
                    display: 1
                }
            },
            }
        };


        var options_total = {
        responsive: true,
        title: {
          display: false,
          position: "top",
          text: "Last Week Registered Users -  Day Wise Count",
          fontSize: 18,
          fontColor: "#111"
        },
        
        tooltips: {
          enabled: false
      },
      plugins: {
        datalabels: {
            formatter: (value, ctx) => {
                let sum = 0;
                let dataArr = ctx.chart.data.datasets[0].data;
                dataArr.map(data => {
                    sum += data;
                });
                let percentage = (value*100 / sum).toFixed(2)+"%";
                return percentage;
            },
            color: '#fff',
        },
        legend: {
          display: true,
          position: "bottom",
          usePointStyle: true,
          labels: {
            fontColor: "#333",
            fontSize: 9
          }
        },
    }
      };

 
     

      var chart5 = new Chart(ctx_total, {
        type: "pie",
        data: data_total,
        options: options_total,
        plugins: [ChartDataLabels],
      });
 
  });

  $(function(){
  // Get the data
  var cDatatotal = JSON.parse(`<?php echo $datapickup['chart_data_pickup']; ?>`);
  
  var ctx_total = document.getElementById("chartpickup_history").getContext('2d');
  
  // Chart data
  var data_total = {
    labels: cDatatotal.label,
    datasets: [
      {
        label: "Jumlah",
        data: cDatatotal.data,
        backgroundColor: [
          "#00d48f",
          "#5066e0",
          "#ff8c00",
          "#dc143c",
          "#a9a9a9",
          '#aeb229',
          '#ffa996',
          '#6a329f'
        ],
        borderWidth: [1, 1, 1, 1, 1,1,1,1],
        minBarLength: 5,
      }
    ]
  };
  
  // Set chart options
  var options = {
    indexAxis: 'y',
    responsive: true,
    maintainAspectRatio: false,
    barValueSpacing: 5,
    layout: {
      padding: {
        right: 60  // Menambahkan padding di sisi kanan chart
      }
    },
    scales: {
      x: {
        stacked: false,
        // Pastikan skala X cukup lebar
        max: function(context) {
          // Tentukan nilai maksimum dengan margin tambahan
          var maxValue = Math.max(...cDatatotal.data);
          return maxValue * 1.15; // Tambahkan margin 15%
        }
      },
      y: {
        stacked: false,
        minBarLength: 5,
      }
    },
    plugins: {
      legend: {
        position: 'bottom',
        display: 0,
        labels: {
          display: 1
        }
      },
      // Konfigurasi untuk tooltip saat hover
      tooltip: {
        enabled: true,
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        titleFont: {
          size: 14,
          weight: 'bold'
        },
        bodyFont: {
          size: 13
        },
        padding: 10,
        cornerRadius: 4,
        displayColors: true,
        callbacks: {
          title: function(tooltipItems) {
            return tooltipItems[0].label; // Nama user
          },
          label: function(context) {
            var jumlah = context.raw;
            var average = cDatatotal.data_average[context.dataIndex];
            
            var lines = [];
            lines.push('Jumlah: ' + jumlah);
            lines.push('Rata-rata waktu: ' + average);
            
            return lines;
          }
        }
      },
      // Konfigurasi untuk data labels di atas bar
      datalabels: {
        anchor: 'end',
        align: 'right',  // Ubah ke right untuk memastikan label tetap di dalam area chart
        offset: 4,       // Menambahkan sedikit offset dari ujung bar
        clamp: true,     // Mencegah label keluar dari area chart
        formatter: function(value, context) {
          var averageTime = cDatatotal.data_average[context.dataIndex];
          //return value + " - " + averageTime;
          return "";
        },
        color: '#444',
        font: {
          weight: 'bold'
        }
      }
    }
  };

  // Create chart
  var chart5 = new Chart(ctx_total, {
    type: "bar",
    data: data_total,
    options: options,
    plugins: [ChartDataLabels],
  });

  // Resize handling dengan memastikan ada ruang cukup untuk label
  function resizeChart() {
    var container = document.querySelector('.chartpickup_history-chart-container');
    container.style.height = '400px';
    // Pastikan container cukup lebar
    container.style.width = '100%';
  }
  
  // Panggil fungsi resize saat halaman dimuat
  resizeChart();
  
  // Juga panggil ketika jendela browser diubah ukurannya
  window.addEventListener('resize', function() {
    resizeChart();
    chart5.update();  // Gunakan update() bukan resize()
  });
});


</script>

@endsection