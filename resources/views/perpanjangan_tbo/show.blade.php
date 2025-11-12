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
                <h2> Perpanjangan Tanggal TBO</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('perpanjangan_tbo.index') }}"> Back</a>
            </div>
        </div>
    </div>
   <br>
   <form  id="myForm" action="{{ route('perpanjangan_tbo.approveSubmit') }}" method="POST">
    @csrf
    @method('POST')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="pd-30 pd-sm-40 bg-gray-100">
                        <h4> Perpanjangan Tanggal TBO</h4>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-3">
                                <label class="form-label mg-b-0">Kantor Cabang</label>
                            </div>
                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                : {{ $perpanjangan_tbo->branchlist->branch_name }}
                            </div>
                        </div>

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-3">
                                <label class="form-label mg-b-0">Yang Mengajukan</label>
                            </div>
                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                : {{ $perpanjangan_tbo->userlist->name }}
                            </div>
                        </div>

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-3">
                                <label class="form-label mg-b-0">Tanggal Pengajuan</label>
                            </div>
                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                : {{ $perpanjangan_tbo->date_input }}
                            </div>
                        </div>
                       
                        
                        <div class="table-responsive">
                            <table id="perpanjanganTable"  class="table table-bordered table-hover  mb-0 text-md-nowrap border">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Loan App No</th>
                                    <th>Nama Debitur</th>
                                    <th>Dokumen TBO</th>
                                    <th>Tgl Sebelum Perubahan</th>
                                    <th>Tgl Sesudah Perubahan</th>
                                    <th>Alasan</th>
                                    <th>Approve</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                $no=1;
                                @endphp
                                @foreach($perpanjangan_tbo_detail as $jam)
                                
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $jam->loan_app_no }}</td>
                                    <td>{{ $jam->nama_debitur }}</td>
                                    <td>{{ $jam->dokumen_tbo }}</td>
                                    <td>{{ $jam->tgl_sebelum_perubahan }}</td>
                                    <td>{{ $jam->tgl_sesudah_perubahan }}</td>
                                    <td>{{ $jam->note}}</td>
                                    <td style='text-align:center'><input type="checkbox" name="chk[]"
                                            @if ($jam->approve == 0 || $jam->approve == 1)
                                                checked
                                                title="Approve"
                                            @elseif ($jam->approve == 2)
                                                disabled
                                                title="Reject"
                                            @endif
                                    ></td>
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
                            <h4>List Approval</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover  mb-0 text-md-nowrap border">
                                    <thead>
                                        <tr>
                                            <th style='width:10px'>No.</td>
                                            <th style='width:20px'>Jabatan</th>
                                            <th>Nama</th>
                                            <th style='width:200px'>Status Persetujuan</th>
                                            <th style='width:200px'>Tgl. Persetujuan</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1.</td>
                                            <td>PC</td>
                                            <td>{{ getnameuser($perpanjangan_tbo->user_spv)}}</td>
                                            <td>
                                                @if($perpanjangan_tbo->flag_spv == 0)
                                                <span class="badge badge-pill badge-dark">Pending</span>
                                                @elseif($perpanjangan_tbo->flag_spv == 1)
                                                <span class="badge badge-pill badge-success">Approve</span>
                                                @else
                                                <span class="badge badge-pill badge-danger">Reject</span>
                                                @endif
                                            </td>
                                            <td>{{ $perpanjangan_tbo->date_flag_spv}}</td>
                                            <td>{{ $perpanjangan_tbo->note_spv}}</td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>Ka. Dept</td>
                                            <td>{{ getnameuser($perpanjangan_tbo->user_spv1)}}</td>
                                            <td>
                                                @if($perpanjangan_tbo->flag_spv1== 0)
                                                <span class="badge badge-pill badge-dark">Pending</span>
                                                @elseif($perpanjangan_tbo->flag_spv1 == 1)
                                                <span class="badge badge-pill badge-success">Approve</span>
                                                @else
                                                <span class="badge badge-pill badge-danger">Reject</span>
                                                @endif
                                            </td>
                                            <td>{{ $perpanjangan_tbo->date_flag_spv1}}</td>
                                            <td>{{ $perpanjangan_tbo->note_spv1}}</td>
                                        </tr>
                                        <tr>
                                            <td>3.</td>
                                            <td>Ka. Div</td>
                                            <td>{{ getnameuser($perpanjangan_tbo->user_spv2)}}</td>
                                            <td>
                                                @if($perpanjangan_tbo->flag_spv2== 0)
                                                <span class="badge badge-pill badge-dark">Pending</span>
                                                @elseif($perpanjangan_tbo->flag_spv2 == 1)
                                                <span class="badge badge-pill badge-success">Approve</span>
                                                @else
                                                <span class="badge badge-pill badge-danger">Reject</span>
                                                @endif
                                            </td>
                                            <td>{{ $perpanjangan_tbo->date_flag_spv2}}</td>
                                            <td>{{ $perpanjangan_tbo->note_spv2}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <hr></hr>
                        
                        @if( (Session('role')=='pc' && $perpanjangan_tbo->flag_spv == 0) || (Session('role')=='spv4' && $perpanjangan_tbo->flag_spv1 == 0) || Session('role')=='spv5' && $perpanjangan_tbo->flag_spv2 == 0  )
                        
                        <input type="hidden" name="id_perubahan" id="id_perubahan" value="{{$perpanjangan_tbo->id}}"/>
                        <input type="hidden" name="chkData" id="chkData">
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

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-3">
                                <label class="form-label mg-b-0"><b>Catatan</b></label>
                            </div>
                            <div class="col-md-6 mg-t-6 mg-md-t-0">
                                 <input type="text" name="note_spv" id="note_spv" placeholder="Catatan" class="form-control name_list" />
                            </div>
                        </div>


                        <!-- <button type="submit" class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">Submit</button> -->
                        <button onclick="submitForm()" class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">Submit</button>
                        @endif
                    </div>
                    
                       

                   
            </div>
        </div>
    </div>
    </form>
    <!-- /row -->

@endsection

@section('scripts')
<script>
function submitForm() {
    const table = document.getElementById('perpanjanganTable');
    const checkboxes = table.querySelectorAll('input[type="checkbox"]');
    const data = [];

    checkboxes.forEach((checkbox) => {
        const loanAppNo = checkbox.parentNode.parentNode.cells[1].textContent; // Assuming Loan App No is in the second column (index 1)
        console.log(loanAppNo);
        const value = checkbox.checked ? 1 : 0;
        data.push({ loanAppNo, value });
    });

    // Convert the array to a JSON string and store it in the hidden input field to be submitted with the form
    document.getElementById('chkData').value = JSON.stringify(data);
    
    // Submit the form
    document.getElementById('myForm').submit();
}
</script>
@endsection