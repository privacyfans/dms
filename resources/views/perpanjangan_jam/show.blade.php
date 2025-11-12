@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<!-- <div class="left-content">
						<h4 class="content-title mb-1"> Perpanjangan Jam Layanan</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Master Branch</a></li>
								<li class="breadcrumb-item active" aria-current="page">Show</li>
							</ol>
						</nav>
					</div> -->

@endsection('breadcrumb')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Perpanjangan Jam Layanan</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('perpanjangan_jam.index') }}"> Back</a>
            </div>
        </div>
    </div>
   <br>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="pd-30 pd-sm-40 bg-gray-100">
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-3">
                                <label class="form-label mg-b-0">Kantor Cabang</label>
                            </div>
                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                {{ $perpanjangan_jam->branchlist->branch_name }}
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-3">
                                <label class="form-label mg-b-0">Usulan Perpanjangan Jam Layanan </label>
                            </div>
                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                               <b> {{ $perpanjangan_jam->jam_layanan }}</b>
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-3">
                                <label class="form-label mg-b-0">Perpanjangan Jam Layanan Yang Disetujui</label>
                            </div>
                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                               <b> {{ $perpanjangan_jam->approve_time }}</b>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-bordered table-hover  mb-0 text-md-nowrap border">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Loan App No</th>
                                    <th>Nama Debitur</th>
                                    <th>Catatan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                $no=1;
                                @endphp
                                @foreach($perpanjangan_jam_detail as $jam)
                               
                                <tr>
                                    <td>{{$no}}</td>
                                    <td>{{ $jam->loan_app_no }}</td>
                                    <td>{{ $jam->nama_debitur }}</td>
                                    <td>{{ $jam->note}}</td>
                                    
                                </tr>
                                @php
                                $no++;
                                @endphp
                                @endforeach
                                </tbody>
                                </table>
                        </div>
                        <hr></hr>
                        
                       
                        <div class="row">
                            
                            <!-- <div class="col-md-6">
                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-5">
                                        <label class="form-label mg-b-0"><b>Status Persetujuan PC</b></label>
                                    </div>
                                    <div class="col-md-7">
                                        @if($perpanjangan_jam->flag_spv == 0)
                                        : <span class="badge badge-pill badge-dark">Pending</span>
                                        @elseif($perpanjangan_jam->flag_spv == 1)
                                            : <span class="badge badge-pill badge-success">Approve</span>
                                        @else
                                        : <span class="badge badge-pill badge-danger">Reject</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-5">
                                        <label class="form-label mg-b-0"><b>Catatan PC</b></label>
                                    </div>
                                    <div class="col-md-7">
                                        : {{ $perpanjangan_jam->note_spv}}
                                    </div>
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-5">
                                        <label class="form-label mg-b-0"><b>Tanggal Persetujuan PC</b></label>
                                    </div>
                                    <div class="col-md-7">
                                        : {{ $perpanjangan_jam->date_flag_spv}}
                                    </div>
                                </div>
                            </div> -->
                            
                            
                            <div class="col-md-6">
                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-5">
                                        <label class="form-label mg-b-0"><b>Status Persetujuan Kadept</b></label>
                                    </div>
                                    <div class="col-md-7">
                                        @if($perpanjangan_jam->flag_spv1== 0)
                                        : <span class="badge badge-pill badge-dark">Pending</span>
                                        @elseif($perpanjangan_jam->flag_spv1 == 1)
                                            : <span class="badge badge-pill badge-success">Approve</span>
                                        @else
                                        : <span class="badge badge-pill badge-danger">Reject</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-5">
                                        <label class="form-label mg-b-0"><b>Catatan Kadept</b></label>
                                    </div>
                                    <div class="col-md-7">
                                        : {{ $perpanjangan_jam->note_spv1}}
                                    </div>
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-5">
                                        <label class="form-label mg-b-0"><b>Tanggal Persetujuan Kadept</b></label>
                                    </div>
                                    <div class="col-md-7">
                                        : {{ $perpanjangan_jam->date_flag_spv1}}
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <hr></hr>
                        
                        @if( Session('role')=='spv4' && $perpanjangan_jam->flag_spv1 == 0  )
                       
                        <form action="{{ route('perpanjangan_jam.approveSubmit') }}" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="id_perubahan" id="id_perubahan" value="{{$perpanjangan_jam->id}}"/>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-3">
                                <label class="form-label mg-b-0"><b>Approve</b></label>
                            </div>
                            <div class="col-md-2 mg-t-2 mg-md-t-0">
                                    <select name="approve_flag" class="form-control SlectBox SumoUnder">
										<!--placeholder-->
										<option value="1">Approve</option>
										<option value="2">Reject</option>
									</select>
                            </div>
                        </div>
                        @if( (Session('role')=='spv4' && $perpanjangan_jam->flag_spv1 == 0  ))
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-3">
                                <label class="form-label mg-b-0"><b>Approve Time</b></label>
                            </div>
                            <div class="col-md-2 mg-t-2">
                                <input class="form-control" id="approve_time" name ="approve_time" placeholder="00:00:00" type="text" value="{{$perpanjangan_jam->jam_layanan}}">
                            </div>
                        </div>
                        @endif
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-3">
                                <label class="form-label mg-b-0"><b>Catatan</b></label>
                            </div>
                            <div class="col-md-6 mg-t-6 mg-md-t-0">
                                 <input type="text" name="note_spv" id="note_spv" placeholder="Catatan" class="form-control name_list" />
                            </div>
                        </div>


                        <button type="submit" class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">Submit</button>
                        </form>
                      

                       
                        @endif
                    </div>
                    
                       

                   
            </div>
        </div>
    </div>
    <!-- /row -->

@endsection