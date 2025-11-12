@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Perpanjangan Jam Layanan</h4>
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
   
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="row row-sm">
            @if ($error!="")
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="card ">
                    <div class="card-body iconfont text-left">
                        
                            <div class="alert alert-danger mg-b-0" role="alert">
                                <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <p><b>{{ $error }}</b></p>
                            </div>
                        
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
   
<form action="{{ route('perpanjangan_jam.store') }}" method="POST" name="add_name" id="add_name">
    @csrf
  
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        Permohonan Perpanjangan Waktu Layanan
                    </div>
                    {{-- <p class="mg-b-20 text-muted">It is Very Easy to Customize and it uses in your website apllication.</p> --}}
                    <div class="pd-30 pd-sm-40 bg-gray-100">
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-2">
                                <label class="form-label mg-b-0">Cabang</label>
                            </div>
                            <div class="col-md-5 mg-t-5">
                            <!-- <select class="livesearch-branch form-control p-3" name="cabang"></select> -->
                            
                            <input class="form-control" id="cabang" name ="cabang" type="text" value="{{Session('branch_name')}}" readonly >
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-2">
                                <label class="form-label mg-b-0">Jam Layanan</label>
                            </div>
                            <div class="col-md-2 mg-t-2">
                                <input class="form-control" id="jam_layanan" name ="jam_layanan" placeholder="00:00:00" type="text" value="">
                            </div>
                        </div>
                        <div class="table-responsive">  
                            <table class="table table-bordered" id="dynamic_field">  
                                <tr>
                                    <th>Loan App No</th>
                                    <th>Nama Debitur</th>
                                    <th>Catatan</th>
                                    <th></th>
                                </tr>
                                <tr>  
                                    <td width="15%"><select class="livesearch-loan_app_no form-control p-3" name="loan_app_no[]" id="load_row_1" searh-loan></select></td>
                                    <!-- <td><input type="text" name="loan_app_no[]" placeholder="Loan App No" class="form-control name_list" /></td>   -->
                                    <td><input type="text" name="nama_debitur[]" id="nama_debitur_1" placeholder="Nama Debitur" class="form-control name_list" readonly/></td> 
                                    <td><input type="text" name="note[]" placeholder="Catatan" class="form-control name_list" /></td> 
                                    <td><button type="button" name="add" id="add" class="btn btn-success"><i class="fe fe-plus-circle"></button></td>  
                                </tr>  
                            </table>  
                            <!-- <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />   -->
                        </div>
                        
                        @if ($error!="")
                        <button type="submit" class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5" disabled>Submit</button>
                        @else
                        <button type="submit" class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">Submit</button>
                        @endif
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
    $('.livesearch-branch').select2({
        placeholder: 'Select Branch',
        ajax: {
            url: '/branch-search',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.branch_name,
                            id: item.branch_code
                        }
                    })
                };
            },
            cache: true
        }
    });

    $('body').on('focus',"[searh-loan]", function(){
            $('.livesearch-loan_app_no').select2({
        minimumInputLength: 4,
        placeholder: 'Search',
        ajax: {
            url: '/loanappno-search-all',
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
        var url = '/dataloan-search-all/' + id;
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
                    })
                };
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Gagal memperoleh data");
              }
            
        });
        
    });
        });


    $(document).ready(function(){      
        $("#jam_layanan").mask("99:99:99");
      var postURL = "<?php echo url('addmore'); ?>";
      var i=1;  


      $('#add').click(function(){  
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td width="15%"><select class="livesearch-loan_app_no form-control p-3" name="loan_app_no[]" id="load_row_'+i+'" searh-loan></select></td>  <td><input type="text" name="nama_debitur[]" id="nama_debitur_'+i+'" placeholder="Nama Debitur" class="form-control name_list" readonly/></td>             <td><input type="text" name="note[]" placeholder="Catatan" class="form-control name_list" /></td>           <td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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