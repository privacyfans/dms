@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Perpanjangan Tanggal TBO</h4>
						<!-- <nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Master Branch</a></li>
								<li class="breadcrumb-item active" aria-current="page">Index</li>
							</ol>
						</nav> -->
					</div>

@endsection('breadcrumb')

@section('content')
<!-- <div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Add New Branch</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('branch.index') }}"> Back</a>
        </div>
    </div>
</div> -->
   
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
   
<form action="{{ route('perpanjangan_tbo.store') }}" method="POST" name="add_name" id="add_name">
    @csrf
  
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        Permohonan Perpanjangan Tanggal TBO
                    </div>
                    {{-- <p class="mg-b-20 text-muted">It is Very Easy to Customize and it uses in your website apllication.</p> --}}
                    <div class="pd-30 pd-sm-40 bg-gray-100">
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-2">
                                <label class="form-label mg-b-0">Kantor Cabang</label>
                            </div>
                            <div class="col-md-5 mg-t-5">
                            <!-- <select class="livesearch-branch form-control p-3" name="cabang"></select> -->
                            
                            <!-- <input class="form-control" id="cabang" name ="cabang" type="text" value="{{Session('branch_name')}}" readonly > -->
                            : {{Session('branch_name')}}
                            </div>

                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-2">
                                <label class="form-label mg-b-0">Yang Mengajukan</label>
                            </div>
                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                : {{Session('name') }}
                            </div>
                        </div>

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-2">
                                <label class="form-label mg-b-0">Tanggal Pengajuan</label>
                            </div>
                            <div class="col-md-8 mg-t-5 mg-md-t-0">
                                : {{ date('Y-m-d') }}
                            </div>
                        </div>

                        <div class="table-responsive">  
                            <table class="table table-bordered" id="dynamic_field">  
                                <tr>
                                    <th>Loan App No</th>
                                    <th>Nama Debitur</th>
                                    <th>Dokumen TBO</th>
                                    <th>Tgl Sebelum Perubahan</th>
                                    <th>Tgl Sesudah Perubahan</th>
                                    <th>Perubahan Ke</th>
                                    <th>Alasan</th>
                                    <th></th>
                                </tr>
                                <tr>  
                                    <td width="15%"><select class="livesearch-loan_app_no form-control p-3" name="loan_app_no[]" id="load_row_1" searh-loan></select></td>
                                    <!-- <td><input type="text" name="loan_app_no[]" placeholder="Loan App No" class="form-control name_list" /></td>   -->
                                    <td><input type="text" name="nama_debitur[]" id="nama_debitur_1" placeholder="Nama Debitur" class="form-control name_list" readonly/></td> 
                                    <td><input type="text" name="dokumen_tbo[]" placeholder="Dokumen TBO" class="form-control name_list" /></td> 
                                    <td><input type="text" name="tgl_sebelum_perubahan[]" id="tgl_sebelum_perubahan1" placeholder="yyyy-mm-dd" class="form-control name_list" readonly/></td> 
                                    <td><input type="text" name="tgl_sesudah_perubahan[]" id="tgl_sesudah_perubahan1" placeholder="yyyy-mm-dd" class="form-control name_list"  data-datepicker /></td> 
                                    <td><input type="text" name="perubahan_ke[]" id="perubahan_ke_1" placeholder="Perubahan Ke" class="form-control name_list" readonly/></td> 
                                    <td><input type="text" name="note[]" placeholder="Alasan" class="form-control name_list" /></td> 
                                    <td><button type="button" name="add" id="add" class="btn btn-success"><i class="fe fe-plus-circle"></button></td>  
                                </tr>  
                            </table>  
                            <!-- <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />   -->
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
@section('scripts')
<script src="{{URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js')}}"></script>
<script type="text/javascript">
   

  


    $(document).ready(function(){      
       
        // $('#tgl_sebelum_perubahan1,#tgl_sebelum_perubahan2,#tgl_sebelum_perubahan3').datepicker({
        //             format: "yyyy-mm-dd",
        //             todayBtn: "linked"
        //         });
        // $('#tgl_sesudah_perubahan1,#tgl_sesudah_perubahan2,#tgl_sesudah_perubahan3').datepicker({
        //     format: "yyyy-mm-dd",
        //     todayBtn: "linked"
        // });
        $('body').on('focus',"[searh-loan]", function(){
            $('.livesearch-loan_app_no').select2({
        minimumInputLength: 4,
        placeholder: 'Search',
        ajax: {
            url: '/loanappno-search',
            dataType: 'json',
            //delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.loan_app_no,
                            id: item.loan_app_no
                        }
                    })
                };
            },
            cache: true
        }
    }).on('change', function(e){
        var id = $(this).val();
        var url = '/dataloan-search/' + id;
        var row=this.id.replace('load_row_','');
        console.log(row);
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            success: function(data){
                return {
                    results: $.map(data, function (item) {
                        $('[id="nama_debitur_'+row+'"]').val(item.nama_debitur);
                        $('[id="tgl_sebelum_perubahan'+row+'"]').val(item.tbo_date);
                        $('[id="perubahan_ke_'+row+'"]').val(item.perubahan_ke);
                    })
                };
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Gagal memperoleh data");
              }
            
        });
        
    });
        });

        $('body').on('focus',"[data-datepicker]", function(){
                 $(this).datepicker({
                    format: "yyyy-mm-dd",
                     todayBtn: "linked"
                 });
        });

       

      var postURL = "<?php echo url('addmore'); ?>";
      var i=1;  


      $('#add').click(function(){  
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td width="15%"><select class="livesearch-loan_app_no form-control p-3" name="loan_app_no[]" id="load_row_'+i+'" searh-loan></select></td><td><input type="text" name="nama_debitur[]" id="nama_debitur_'+i+'" placeholder="Nama Debitur" class="form-control name_list" readonly /></td><td><input type="text" name="dokumen_tbo[]" placeholder="Dokumen TBO" class="form-control name_list" /></td><td><input type="text" name="tgl_sebelum_perubahan[]"  id="tgl_sebelum_perubahan'+i+'" placeholder="yyyy-mm-dd" class="form-control name_list"  readonly/></td><td><input type="text" name="tgl_sesudah_perubahan[]" id="tgl_sesudah_perubahan'+i+'" placeholder="yyyy-mm-dd" class="form-control name_list"  data-datepicker /></td><td><input type="text" name="perubahan_ke[]" id="perubahan_ke_'+i+'" placeholder="Perubahan Ke" class="form-control name_list" readonly/></td> <td><input type="text" name="note[]" placeholder="Alasan" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  


      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  


      $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });


      $('#submit').click(function(){            
           $.ajax({  
                url:postURL,  
                method:"POST",  
                data:$('#add_name').serialize(),
                type:'json',
                success:function(data)  
                {
                    if(data.error){
                        printErrorMsg(data.error);
                    }else{
                        i=1;
                        $('.dynamic-added').remove();
                        $('#add_name')[0].reset();
                        $(".print-success-msg").find("ul").html('');
                        $(".print-success-msg").css('display','block');
                        $(".print-error-msg").css('display','none');
                        $(".print-success-msg").find("ul").append('<li>Record Inserted Successfully.</li>');
                    }
                }  
           });  
      });  


      function printErrorMsg (msg) {
         $(".print-error-msg").find("ul").html('');
         $(".print-error-msg").css('display','block');
         $(".print-success-msg").css('display','none');
         $.each( msg, function( key, value ) {
            $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
         });
      }
    });  

    
</script>

@endsection