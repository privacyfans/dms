@extends('layouts.app')

@section('styles') 
<link href="{{URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" type="text/css"/>

		<!---Internal Fancy uploader css-->
		<link href="{{URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css')}}" rel="stylesheet" />
        
        
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Data Loan</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Data</a></li>
								<li class="breadcrumb-item active" aria-current="page">Loan</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Loan App No # {{ $datafile->loan_app_no }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route(checkJenisProduk($datafile->produk)) }}"> Back</a>
            </div>
        </div>
    </div>
   <br>
   <div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
            
                <div class="panel panel-primary tabs-style-2">
                    <div class=" tab-menu-heading">
                        <div class="tabs-menu1">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs main-nav-line" id="myTab">
                                <li><a href="#tab1" class="nav-link" data-toggle="tab">Base Data</a></li>
                                <li><a href="#tab2" class="nav-link active" data-toggle="tab">Document</a></li>
                                <li><a href="#tab3" class="nav-link" data-toggle="tab">Review</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body main-content-body-right border">
                        <div class="tab-content">
                            <div class="tab-pane" id="tab1">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                               <div class="pd-10 pd-sm-10 bg-gray-100">
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Loan App No</label>
                                                        </div>
                                                        
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->loan_app_no }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">No CIF </label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->no_cif }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Kode Cabang</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->kode_cabang }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Nama Debitur</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->nama_debitur }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">No KTP</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->no_ktp }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Tgl Lahir</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ changeDate($datafile->ttl) }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Status Pernikahan</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ getStatusPernikahan($datafile->status_pernikahan) }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Pekerjaan</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->pekerjaan }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Alamat Rumah</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->alamat_rumah }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Instansi</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->instansi }}
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Alamat Kantor</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->alamat_kantor }}
                                                        </div>
                                                    </div>
                                                    
                                                <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">No Tlp Kantor</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->no_tlp_kantor }}
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="pd-10 pd-sm-10 bg-gray-100">

                                              
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Produk</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->produk }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Plafond</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ number_format($datafile->plafond,2,",",".") }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Jangka Waktu</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->jangka_waktu }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Rate</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->rate.' %' }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Angsuran</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ number_format($datafile->angsuran,2,",",".") }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Tgl Jatuh Tempo</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->tanggal_jatuh_tempo }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Fasilitas</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->fasilitas }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">User Input</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->user_input }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Branch Input</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->branch_input }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Date Input</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {{ $datafile->date_input }}
                                                        </div>
                                                    </div>
                                                    <div class="row row-xs align-items-center mg-b-20">
                                                        <div class="col-md-3">
                                                            <label class="form-label mg-b-0">Status Processed</label>
                                                        </div>
                                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                            {!! getStatusProcessed($datafile->processed) !!}
                                                        </div>
                                                    </div>
                                                    
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                @if($datafile->processed == 1)
                                    <a class="btn btn-success" href="{{ url('processverify').'/'.$datafile->loan_app_no }}"><i class="la la-check-circle text-white"></i>&nbsp;Processed</a>
                                @endif
                                <!-- /row -->
                            </div>
                           
                            <div class="tab-pane active" id="tab2">
                                <div class="row ">
                                    <div class="col-lg-12 col-md-12">
                                        @if(checkEmptyLabel($datafile->loan_app_no)>=1)
                                        <div class="card">
                                            <div class="card-body">
                                                <div>
                                                    <h6 class="card-title mb-1">Empty Label</h6>
                                                    
                                                </div>
                                                <form id="frmEmptyLabel" method="POST" action="{{ route('datafile.emptyLabel') }}">
                                                  @csrf
                                                  <input name="loan_app_no" type="hidden" value=" {{ $datafile->loan_app_no }}">
                                                <div class="pd-10 pd-sm-10 bg-gray-100">
                                                    
                                                        <?php
                                                        $no_urut=1;
                                                        
                                                        foreach ($detailfile as $item){
                                                           if ($item->alias == null){
                                                           ?>
                                                            <div class="row row-xs">
                                                                <div class="col-md-5">
                                                                    <a class="text-secondary" data-toggle="modal" id="mediumButton" data-target="#mediumModal"
                                                                    data-attr="{{ route('datafile.showpdf', $item->file) }}">
                                                                    {{$no_urut.'. '.getlastnamefile($item->file)}}
                                                                    </a>
                                                                    <a href="{{ asset('indexed'.$item->file) }}" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <select  class="form-control SlectBox SumoUnder" name="no[]">
                                                                        @foreach ($map_product as $item_map)
                                                                        <option  value="{{$item->id.'-'.$item_map->nama_dokumen}}">{{$item_map->nama_dokumen}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            
                                                           <?php
                                                           $no_urut++;
                                                           }
                                                           
                                                        }
                                                        ?>
                                                    
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="submit" value="Submit" class="btn btn-primary">
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="card">
                                            <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                 <h6 class="card-title mb-1">List Dokumen - {{$datafile->produk}}</h6>
                                                </div>
                                                <div>
                                                    <a class="btn btn-primary" href="{{ route('datafile.downloadZip', $datafile->loan_app_no) }}"> Download All Document</a>
                                                </div>
                                            </div>
                                                
                                                
                                                @php
                                                $jml = count($map_product);
                                                $jml_isi_kolom=round($jml/2);
                                                $counter1 = 1;
                                                $counter2 = $jml_isi_kolom+1;
                                                $dokumenbelumlengkap=0;
                                                @endphp
                                                <div class="pd-10 pd-sm-10 bg-gray-100">
                                                    <div class="row row-xs">
                                                        <div class="col-lg-6 col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                
                                                                    <div aria-multiselectable="true" class="accordion" id="accordion" role="tablist">
                                                                        
                                                                        @foreach ($map_product->skip(0)->take($jml_isi_kolom) as $map)
                                                                        
                                                                        <?php
                                                                       
                                                                        if(get_warna($datafile->loan_app_no,$map->nama_dokumen) =="error"  && getBadgeMandatory($map->nama_dokumen,$map->mandatory,$datafile->status_pernikahan,$datafile->pekerjaan,$datafile->fasilitas) !=""){
                                                                            $dokumenbelumlengkap=$dokumenbelumlengkap+1;
                                                                        }
                                                                        ?>
                                                                        <div class="card mb-0">
                                                                        <div class="d-flex flex-row justify-content-between bg-gray-100 mg-b-10">
                                                                            <div class="pd-1 bg-gray-100">
                                                                                <div class="card-header-{{ get_warna($datafile->loan_app_no,$map->nama_dokumen )}}" id="headingOne" role="tab">
                                                                                    <a aria-controls="collapseOne{{$counter1}}" aria-expanded="true" data-toggle="collapse" href="#collapseOne{{$counter1}}">{{ $counter1.'. '.$map->nama_dokumen }} </a>
                                                                                </div>
                                                                            </div>
                                                                           
                                                                            <div class="pd-1 bg-gray-100">
                                                                                <div class="card-header-{{ get_warna($datafile->loan_app_no,$map->nama_dokumen )}}" id="headingOne" role="tab">
                                                                                <a aria-controls="collapseOne{{$counter1}}" aria-expanded="true" data-toggle="collapse" href="#collapseOne{{$counter1}}">{!! getBadgeMandatory($map->nama_dokumen,$map->mandatory,$datafile->status_pernikahan,$datafile->pekerjaan,$datafile->fasilitas) !!} </a>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        
                                                                            <!-- <div class="card-header-{{ get_warna($datafile->loan_app_no,$map->nama_dokumen )}}" id="headingOne" role="tab">
                                                                                <a aria-controls="collapseOne{{$counter1}}" aria-expanded="true" data-toggle="collapse" href="#collapseOne{{$counter1}}">{{ $counter1.'. '.$map->nama_dokumen }} {!! getBadgeMandatory($map->nama_dokumen,$map->mandatory,$datafile->status_pernikahan,$datafile->pekerjaan,$datafile->fasilitas) !!}</a>
                                                                            </div> -->
                                                                                    @if(get_warna($datafile->loan_app_no,$map->nama_dokumen ) == 'success')
                                                                                    <div aria-labelledby="headingOne" class="collapse" data-parent="#accordion" id="collapseOne{{$counter1}}" role="tabpanel">
                                                                                        <div class="card-body">
                                                                                            <ul>
                                                                                            @foreach ($detailfile as $item)
                                                                                               
                                                                                                @if ($map->nama_dokumen == $item->alias)
                                                                                                    <li>
                                                                                                        <div class="d-flex flex-row justify-content-between bg-gray-100 mg-b-20">
                                                                                                            <div class="pd-1">
                                                                                                                
                                                                                                                <a class="text-secondary" data-toggle="modal" id="mediumButton" data-target="#mediumModal"
                                                                                                                data-attr="{{ route('datafile.showpdf', $item->file) }}">
                                                                                                                {{getlastnamefile($item->file)}}
                                                                                                            </a>
                                                                                                            <a href="{{ asset('indexed'.$item->file) }}" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                                                                                            </div>
                                                                                                            @if(checkCanDelete($datafile->loan_app_no))
                                                                                                            <a href="deletedetailfile/{{$datafile->loan_app_no}}/{{$item->id}}" target="_self">
                                                                                                            <div class="pd-1 bg-danger">
                                                                                                                <!-- <i class="fas fa-trash"  style="color:white"></i>X --><span style="color:white">X</span>
                                                                                                            </div>
                                                                                                            @endif
                                                                                                            </a>
                                                                                                        </div>
                                                                                                    </li>
                                                                                                @endif
                                                                                            @endforeach
                                                                                            </ul>
                                                                                        </div>
                                                                                        <br>
                                                                                    </div>
                                                                                    
                                                                                    @endif
                                                                                    @if(Session("role")=="staff")
                                                                                    <div id="MsgErrorUpload{{$counter1}}"></div>
                                                                                            <form id="fileUploadFormKK{{$counter1}}" method="POST" action="{{ route('datafile.uploadfile') }}" enctype="multipart/form-data">
                                                                                                    @csrf
                                                                                                                                                    
                                                                                                <div class="pd-10 pd-sm-10 bg-gray-100">
                                                                                                    <!-- <p class="tx-12 text-muted card-sub-title">Silahkan Upload file dalam format <i style="color:red">.pdf dan max size 2MB</i></p> -->
                                                                                                    <div class="row row-xs">
                                                                                                        
                                                                                                        <!-- <div class="col-md-5">
                                                                                                            <select  class="form-control SlectBox SumoUnder" name="type">
                                                                                                                @foreach ($map_product as $item)
                                                                                                                <option  value="{{$item->nama_dokumen}}">{{$item->nama_dokumen}}</option>
                                                                                                                @endforeach
                                                                                                            </select>
                                                                                                        </div> -->
                                                                                                        <div class="col-md-9">
                                                                                                            <input name="loan_app_no" type="hidden" value=" {{ $datafile->loan_app_no }}">
                                                                                                            <input type="hidden" id="type" name="type" value="{{$map->nama_dokumen}}">
                                                                                                        <input name="file" type="file" class="form-control">
                                                                                                        </div>
                                                                                                        <div class="col-md-1">
                                                                                                            <input type="submit" value="Upload" class="btn btn-primary">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row row-xs">
                                                                                                        <div class="col-md-12">
                                                                                                            <br>
                                                                                                            <div id="list_kk">
                                                                                                            <div id="progress{{$counter1}}">
                                                                                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger kk" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                                                                                            </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            
                                                                                            </form>  
                                                                                            <br>   
                                                                                        @endif  
                                                                            @php
                                                                            $counter1++;
                                                                            @endphp
                                                                        </div>
                                                                        
                                                                        @endforeach
                                                                    
                                                                    </div><!-- accordion -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                
                                                                    <div aria-multiselectable="true" class="accordion" id="accordion1" role="tablist">
                                                                       
                                                                        @foreach ($map_product->skip($jml_isi_kolom)->take($jml)  as $map)
                                                                        <?php
                                                                       
                                                                        if(get_warna($datafile->loan_app_no,$map->nama_dokumen) =="error"  && getBadgeMandatory($map->nama_dokumen,$map->mandatory,$datafile->status_pernikahan,$datafile->pekerjaan,$datafile->fasilitas) !=""){
                                                                            $dokumenbelumlengkap=$dokumenbelumlengkap+1;
                                                                        }
                                                                        ?>
                                                                        <div class="card mb-0">
                                                                            <div class="d-flex flex-row justify-content-between bg-gray-100 mg-b-10">
                                                                                <div class="pd-1 bg-gray-100">
                                                                                    <div class="card-header-{{ get_warna($datafile->loan_app_no,$map->nama_dokumen )}}" id="headingOne" role="tab">
                                                                                    <a aria-controls="collapseOne{{$counter2}}" aria-expanded="true" data-toggle="collapse" href="#collapseOne{{$counter2}}">{{ $counter2.'. '.$map->nama_dokumen }}</a>
                                                                                    </div>
                                                                                </div>
                                                                            
                                                                                <div class="pd-1 bg-gray-100">
                                                                                    <div class="card-header-{{ get_warna($datafile->loan_app_no,$map->nama_dokumen )}}" id="headingOne" role="tab">
                                                                                    <a aria-controls="collapseOne{{$counter2}}" aria-expanded="true" data-toggle="collapse" href="#collapseOne{{$counter2}}">{!! getBadgeMandatory($map->nama_dokumen,$map->mandatory,$datafile->status_pernikahan,$datafile->pekerjaan,$datafile->fasilitas) !!}</a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <!-- <div class="card-header-{{ get_warna($datafile->loan_app_no,$map->nama_dokumen )}}" id="headingOne" role="tab">
                                                                                <a aria-controls="collapseOne{{$counter2}}" aria-expanded="true" data-toggle="collapse" href="#collapseOne{{$counter2}}">{{ $counter2.'. '.$map->nama_dokumen }} {!! getBadgeMandatory($map->nama_dokumen,$map->mandatory,$datafile->status_pernikahan,$datafile->pekerjaan,$datafile->fasilitas) !!}</a>
                                                                            </div> -->
                                                                                   @if(get_warna($datafile->loan_app_no,$map->nama_dokumen ) == 'success')
                                                                                    <div aria-labelledby="headingOne" class="collapse" data-parent="#accordion1" id="collapseOne{{$counter2}}" role="tabpanel">
                                                                                        <div class="card-body">
                                                                                            <ul>
                                                                                            @foreach ($detailfile as $item)
                                                                                                @if ($map->nama_dokumen == $item->alias)
                                                                                                <li>
                                                                                                    <div class="d-flex flex-row justify-content-between bg-gray-200 mg-b-20">
                                                                                                   
                                                                                                        <div class="pd-10">
                                                                                                            <!-- <a href="{{ asset('indexed'.$item->file) }}" target="_blank">{{getlastnamefile($item->file)}}</a> -->
                                                                                                            <a class="text-secondary" data-toggle="modal" id="mediumButton" data-target="#mediumModal"
                                                                                                                data-attr="{{ route('datafile.showpdf', $item->file) }}">
                                                                                                                {{getlastnamefile($item->file)}}
                                                                                                            </a>
                                                                                                            <a href="{{ asset('indexed'.$item->file) }}" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                                                                                        </div>
                                                                                                        @if(checkCanDelete($datafile->loan_app_no))
                                                                                                        <a href="deletedetailfile/{{$datafile->loan_app_no}}/{{$item->id}}" target="_self">
                                                                                                        <div class="pd-10 bg-danger">
                                                                                                            <!-- <i class="fas fa-trash"  style="color:white"></i>X --><span style="color:white">X</span>
                                                                                                        </div>
                                                                                                        </a>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                </li>
                                                                                                @endif
                                                                                            @endforeach
                                                                                            </ul>
                                                                                        </div>
                                                                                        <br>
                                                                                    </div>
                                                                                    @endif
                                                                                    @if(Session("role")=="staff")
                                                                                    <div id="MsgErrorUpload{{$counter2}}"></div>
                                                                                            <form id="fileUploadFormKK{{$counter2}}" method="POST" action="{{ route('datafile.uploadfile') }}" enctype="multipart/form-data">
                                                                                                    @csrf
                                                                                                                                                    
                                                                                                <div class="pd-10 pd-sm-10 bg-gray-100">
                                                                                                    <!-- <p class="tx-12 text-muted card-sub-title">Silahkan Upload file dalam format <i style="color:red">.pdf dan max size 2MB</i></p> -->
                                                                                                    <div class="row row-xs">
                                                                                                        
                                                                                                        <!-- <div class="col-md-5">
                                                                                                            <select  class="form-control SlectBox SumoUnder" name="type">
                                                                                                                @foreach ($map_product as $item)
                                                                                                                <option  value="{{$item->nama_dokumen}}">{{$item->nama_dokumen}}</option>
                                                                                                                @endforeach
                                                                                                            </select>
                                                                                                        </div> -->
                                                                                                        <div class="col-md-9">
                                                                                                            <input name="loan_app_no" type="hidden" value=" {{ $datafile->loan_app_no }}">
                                                                                                            <input type="hidden" id="type" name="type" value="{{$map->nama_dokumen}}">
                                                                                                        <input name="file" type="file" class="form-control">
                                                                                                        </div>
                                                                                                        <div class="col-md-1">
                                                                                                            <input type="submit" value="Upload" class="btn btn-primary">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row row-xs">
                                                                                                        <div class="col-md-12">
                                                                                                            <br>
                                                                                                            <div id="list_kk">
                                                                                                            <div id="progress{{$counter2}}">
                                                                                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger kk" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                                                                                            </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            
                                                                                            </form>  
                                                                                            <br>
                                                                                    @endif     
                                                                            @php
                                                                            $counter2++;
                                                                            @endphp
                                                                        </div>
                                                                        
                                                                        @endforeach
                                                                    
                                                                    </div><!-- accordion -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{--<div class="col-lg-4 col-md-4">
                                                            <div class="card">
                                                                <div class="card-body">
                                
                                                                    <div aria-multiselectable="true" class="accordion" id="accordion2" role="tablist">
                                                                        
                                                                        @foreach ($map_product->skip(14)->take(7)  as $map)
                                                                       
                                                                        <div class="card mb-0">
                                                                            <div class="card-header-{{ get_warna($datafile->loan_app_no,$map->nama_dokumen )}}" id="headingOne" role="tab">
                                                                                <a aria-controls="collapseOne{{$counter3}}" aria-expanded="true" data-toggle="collapse" href="#collapseOne{{$counter3}}">{{ $counter3.'. '.$map->nama_dokumen }} {!! getBadgeMandatory($map->nama_dokumen,$map->mandatory,$datafile->status_pernikahan,$datafile->pekerjaan) !!}</a>
                                                                            </div>
                                                                                    @if(get_warna($datafile->loan_app_no,$map->nama_dokumen ) == 'success')
                                                                                    <div aria-labelledby="headingOne" class="collapse" data-parent="#accordion2" id="collapseOne{{$counter3}}" role="tabpanel">
                                                                                        <div class="card-body">
                                                                                            <ul>
                                                                                            @foreach ($detailfile as $item)
                                                                                                @if ($map->nama_dokumen == $item->alias)
                                                                                                <li>
                                                                                                    <div class="d-flex flex-row justify-content-between bg-gray-200 mg-b-20">
                                                                                                   
                                                                                                        <div class="pd-10">
                                                                                                            <a href="{{ asset('indexed'.$item->file) }}" target="_blank">{{getlastnamefile($item->file)}}</a>
                                                                                                        </div>
                                                                                                        @if(checkCanDelete($datafile->loan_app_no))
                                                                                                        <a href="deletedetailfile/{{$datafile->loan_app_no}}/{{$item->id}}" target="_self">
                                                                                                        <div class="pd-10 bg-danger">
                                                                                                            <i class="fas fa-trash"  style="color:white"></i>
                                                                                                        </div>
                                                                                                        </a>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                </li>
                                                                                                @endif
                                                                                            @endforeach
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                    @endif
                                                                                
                                                                            @php
                                                                            $counter3++;
                                                                            @endphp
                                                                        </div>
                                                                        
                                                                        @endforeach
                                                                    
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                              
                                @if(Session("role")=="staff")
                                <div class="panel panel-primary tabs-style-2">
                                    <div class=" tab-menu-heading">
                                        <div class="tabs-menu1">
                                            <!-- Tabs -->
                                            <ul class="nav panel-tabs main-nav-line">
                                                <!-- <li><a href="#tabupload" class="nav-link active" data-toggle="tab">Upload Document</a></li> -->
                                                <li><a href="#tabcopy" class="nav-link active" data-toggle="tab">Copy Document</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body tabs-menu-body main-content-body-right border">
                                        <div class="tab-content">
                                            <!-- <div class="tab-pane active" id="tabupload">
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-8">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="main-content-label mg-b-5">
                                                                    Upload Document
                                                                </div>
                                                                <div id="MsgErrorUpload"></div>
                                                                <form id="fileUploadFormKK" method="POST" action="{{ route('datafile.uploadfile') }}" enctype="multipart/form-data">
                                                                        @csrf
                                                                                                                         
                                                                    <div class="pd-10 pd-sm-10 bg-gray-100">
                                                                        <p class="tx-12 text-muted card-sub-title">Silahkan Upload file dalam format <i style="color:red">.pdf dan max size 2MB</i></p>
                                                                        <div class="row row-xs">
                                                                            
                                                                            <div class="col-md-5">
                                                                                <select  class="form-control SlectBox SumoUnder" name="type">
                                                                                    @foreach ($map_product as $item)
                                                                                    <option  value="{{$item->nama_dokumen}}">{{$item->nama_dokumen}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-5">
                                                                                <input name="loan_app_no" type="hidden" value=" {{ $datafile->loan_app_no }}">
                                                                            <input name="file" type="file" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-1">
                                                                                <input type="submit" value="Submit" class="btn btn-primary">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row row-xs">
                                                                            <div class="col-md-12">
                                                                                <br>
                                                                                <div id="list_kk">
                                                                                <div class="progress">
                                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger kk" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                                                                </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="tab-pane active" id="tabcopy">
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-8">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="main-content-label mg-b-5">
                                                                    Copy Document
                                                                </div>
                                                                <div id="MsgErrorCopy"></div>
                                                                    <form id="fileCopyFormKK" method="POST" action="{{ route('datafile.copyfile') }}" enctype="multipart/form-data">
                                                                        @csrf
                                                                                                                         
                                                                    <div class="pd-10 pd-sm-10 bg-gray-100">
                                                                        <p class="tx-12 text-muted card-sub-title">Silahkan masukan Loan App No.</p>
                                                                        <div class="row row-xs">
                                                                            <div class="col-md-5">
                                                                            <input name="loan_app_no_src" type="text" value="" placeholder="Loan App No" class="form-control">
                                                                            <input name="loan_app_no" type="hidden" value="{{ $datafile->loan_app_no }}">
                                                                            </div>
                                                                            <div class="col-md-1">
                                                                                <input type="submit" value="Copy" class="btn btn-primary">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row row-xs">
                                                                            <div class="col-md-12">
                                                                                <br>
                                                                                <div id="list_kk">
                                                                                <div class="progress">
                                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger kk" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                                                                </div>
                                                                                </div>
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
                                    </div>
                                </div>
                               @endif
                            </div>
                            <div class="tab-pane" id="tab3">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <!-- <div class="card"> -->
                                            <!-- <div class="card-body"> -->
                                                <div class="main-content-label mg-b-5">
                                                    List Review
                                                </div>
                                                <div class="pd-12 pd-sm-12 bg-gray-100">
                                                    <div id="ListReview">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="">
                                                                    <!-- <div class="card-body"> -->
                                                                    <div class="table-responsive">

                                                                        <table class="table table-bordered table-hover  mb-0 text-md-nowrap border">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>@sortablelink('id','Activity Review')</th>
                                                                                <th>@sortablelink('comment_date','REVIEW DATE')</th>
                                                                                <th>User</th>
                                                                                <th>Level SPV</th>
                                                                                <th>Status</th>
                                                                                <th>Note</th>
                                                                                <th>TBO Date</th>
                                                                                <th>Reason</th>
                                                                                
                                                                            </tr>
                                                                            <thead>
                                                                            <?php
                                                                            $direction=app('request')->input('direction');
                                                                            if($direction=='desc'){
                                                                                $i=count($comment);
                                                                            }else{
                                                                                $i=1;
                                                                            }
                                                                            
                                                                            ?>
                                                                            <tbody>
                                                                            @foreach ($comment as $br)
                                                                            <tr>
                                                                                
                                                                                @if($direction=='desc')
                                                                                <td>{{ $i-- }}</td>
                                                                                @else
                                                                                <td>{{ $i++ }}</td>
                                                                                @endif
                                                                                <!-- <td>{{ $br->id }}</td> -->
                                                                                <td>{{ $br->comment_date }}</td>
                                                                                <td>{{ getnameuser($br->user_name) }}</td>
                                                                                <td>{{ getLevel($br->level_spv) }}</td>
                                                                                <td>{!! getFlagColor($br->flag_spv) !!}</td>
                                                                                <td>{{ $br->comment }}</td>
                                                                                @if($br->tbo_date != null)
                                                                                <td>{{ date_format(date_create($br->tbo_date),'Y-m-d') }}</td>
                                                                                @else
                                                                                <td></td>
                                                                                @endif
                                                                                <td>{{ $br->reason }}</td>
                                                                                
                                                                                
                                                                            </tr>
                                                                            @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                        </div>
                                                                        <!-- <div class="vtimeline">
                                                                       
                                                                            <?php
                                                                            $x=1;
                                                                            foreach ($comment as $key => $value) {
                                                                                
                                                                            $comment=$value->comment;
                                                                            $reason=$value->reason;
                                                                            $nik=$value->user_name;
                                                                            $tbo_date=$value->tbo_date;
                                                                            $flag_spv=$value->flag_spv;
                                                                            $level_spv=$value->level_spv;
                                                                            $flag_name="";
                                                                            
                                                                            $comment_date =$value->comment_date;

                                                                            $inverted='';
                                                                            if($x % 2 == 0){
                                                                                $inverted="timeline-inverted";
                                                                            }

                                                                            // if($level_spv=='spv1'){
                                                                            //     $lbl_level_spv='Spv Branch - ';
                                                                            // }else if($level_spv=='spv2'){
                                                                            //     $lbl_level_spv='DCRM REVIEWER - ';
                                                                            // }else if($level_spv=='spv3' || $level_spv=='spv4'){
                                                                            //     $lbl_level_spv='DCRM TEAM LEADER - ';
                                                                            // }else if($level_spv=='pcp'){
                                                                            //     $lbl_level_spv='PCP - ';
                                                                            // }else if($level_spv=='pc'){
                                                                            //     $lbl_level_spv='PC - ';
                                                                            // }

                                                                            if($level_spv=='staff'){
                                                                                $lbl_level_spv='Branch - ';
                                                                            }else if($level_spv=='spv3' || $level_spv=='spv4'){
                                                                                $lbl_level_spv='TEAM DCRM - ';
                                                                            }else{
                                                                                $lbl_level_spv='';
                                                                            }

                                                                            if($flag_spv == 1){
                                                                                $flag_name="<div style='color:green;'> ".$lbl_level_spv."Verify</div>";
                                                                            }else if($flag_spv == 2){
                                                                                $flag_name="<div style='color:orange;'> ".$lbl_level_spv."Not Verify</div>";
                                                                            }else if($flag_spv == 3){
                                                                                $flag_name="<div style='color:blue;'> ".$lbl_level_spv."TBO (To Be Obtained)</div>";
                                                                            }else if($flag_spv == 4){
                                                                                $flag_name="<div style='color:red;'> ".$lbl_level_spv."Reject</div>";
                                                                            }

                                                                           $tag_reason="";
                                                                           $tag_comment="";
                                                                           $tag_tbo_date="";
                                                                           if($reason==""){
                                                                                $tag_reason="";
                                                                           }else{
                                                                                $tag_reason='<p><b>Reason:</b> &nbsp;'.$reason.'</p>';
                                                                           }

                                                                           if($comment==""){
                                                                                $tag_comment="";
                                                                           }else{
                                                                                $tag_comment='<p><b>Note:</b> &nbsp;'.$comment.'</p>';
                                                                           }
                                                                           if($tbo_date==""){
                                                                                $tag_tbo_date="";
                                                                           }else{
                                                                                $tag_tbo_date='<p><b>TBO Date:</b> &nbsp;'.$tbo_date.'</p>';
                                                                           }
                                                                           //dd(getnameuser("1"));
                                                                                
                                                                            echo '
                                                                            <div class="timeline-wrapper '.$inverted.' timeline-wrapper-info">
                                                                                <div class="timeline-badge"></div>
                                                                                <div class="timeline-panel">
                                                                                    <div class="timeline-heading">
                                                                                        <h6 class="timeline-title">'.$flag_name.'</h6>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        '.$tag_reason.$tag_comment.$tag_tbo_date.'
                                                                                    </div>
                                                                                    <div class="timeline-footer d-flex align-items-center flex-wrap">
                                                                                        <span></span>
                                                                                        <span class="ml-auto"><i class="fe fe-calendar text-muted mr-1"></i>'. $comment_date.' by '.getnameuser($nik).'</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            ';
                                                                            $x++;
                                                                            }
                                                                            ?>

                                                                            
                                                                            
                                                                        </div>
                                                                        -->
                                                                            
                                                                        <!-- </div> -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                    <input type="hidden" value= "{{ $dokumenbelumlengkap }}" name="jumlah_dokumen_belum_lengkap">
                                                    @if(Session("role")=='spv1' || Session("role")=='spv2' || Session("role")=='spv3' || Session("role")=='spv4' || Session("role")=='pc' || Session("role")=='pcp')
                                                    
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="card">
                                                                <?php /*
                                                                <div class="card-body">
                                                                    <div class="main-content-label mg-b-5">
                                                                        Add Review
                                                                    </div>
                                                                    <div id="MsgError"></div>
                                                                    
                                                                    @if ($dokumenbelumlengkap!=0)
                                                                   
                                                                                    <div class="alert alert-danger mg-b-0" role="alert">
                                                                                        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                        <p><b>Perhatian: Masih Ada Dokumen Mandatori Yang Belum Diupload, Silahkan lengkapi terlebih dahulu.</b></p>
                                                                                    </div>
                                                                                
                                                                   
                                                                    @else
                                                                    <div class="pd-10 pd-sm-10 bg-gray-100">
                                                                        <form id="addReview" method="POST" action="{{ route('datafile.updateflag') }}" enctype="multipart/form-data">
                                                                            @csrf
                                                            
                                                                        <div class="mb-12">
                                                                            <label for="flag_spv" class="form-label">Status Document</label>
                                                                            <select class="flag_spv form-control" name="flag_spv" id="flag_spv" style="width: 30%">
                                                                                {{-- <option hidden>Choose Status Document</option>
                                                                                @foreach ($flag_spv as $item)
                                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                @endforeach --}}
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-12" id="field_reason" style="display:none;">
                                                                            <input name="loan_app_no" type="hidden" value=" {{ $datafile->loan_app_no }}">
                                                                            <label for="reason" class="form-label">Reason</label>
                                                                            <select class="reason form-control pd-30" name="reason[]" id="reason" multiple="multiple" style="width: 50%">
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-12">
                                                                            <label for="reason" class="form-label">Add Note</label>
                                                                            <textarea name="comment" col=  placeholder="Add Note" class="form-control" rows="3"></textarea>
                                                                        </div>
                                                                        <div class="mb-12" id="field_tbo" style="display:none;">
                                                                            <label for="tbo_date" class="form-label">TBO Date</label>
                                                                        {{-- <p class="mg-b-20">Datetimepicker style variant on top of AmazeUI Datetimepicker.</p> --}}
                                                                            <div class="row row-sm">
                                                                                <div class="input-group col-md-4">
                                                                                    <div class="input-group-prepend">
                                                                                        <div class="input-group-text">
                                                                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                                                        </div>
                                                                                    </div><input class="form-control" id="tbo_date" type="text" name="tbo_date" >
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mb-12">
                                                                            <br>
                                                                            <input type="submit" value="Submit" class="btn btn-primary">
                                                                        </div>
                                                                        <div class="row row-xs">
                                                                            <div class="col-md-12">
                                                                                <br>
                                                                                <div id="list_kk">
                                                                                <div class="progress">
                                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger kk" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                                                                </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                                */?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    @endif
                                                </div>
                                            <!-- </div> -->
                                        <!-- </div> -->
                                    </div>
                                </div>
                            </div>
               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   </div>

<!-- medium modal -->
<div class="modal fade mediumModal-modal-lg" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="mediumBody">
                    <div>
                        <!-- the result to be displayed apply here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
   

@endsection

@section('scripts')

<script>
   
   $(document).on('click', '#mediumButton', function(event) {
                event.preventDefault();
                let href = $(this).attr('data-attr');
                console.log(href);
                $.ajax({
                    url: href,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#mediumModal').modal("show");
                        $('#mediumBody').html(result).show();
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page " + href + " cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            });

   $(function () {
            
            $(document).ready(function () {
               

            

                $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                    localStorage.setItem('activeTab', $(e.target).attr('href'));
                });
                var activeTab = localStorage.getItem('activeTab');
                if(activeTab){
                    $('#myTab a[href="' + activeTab + '"]').tab('show');
                }
               
                //$('#tbo_date').datepicker({format: 'yyyy-mm'});
                $('#tbo_date').datepicker({
                    format: "yyyy-mm-dd",
                    todayBtn: "linked"
                });

               var i=1;
               @for ($i = 1; $i <= $jml; $i++)
               $('#fileUploadFormKK{{$i}}').ajaxForm(
                    {
                    beforeSend: function () {
                        var percentage = '0';
                        console.log("1. i adalah ="+{{$i}});
                        $("#progress1 .progress-bar").css("width", percentage+'%', function() {
                          return $(this).attr("aria-valuenow", percentage) + "%";
                        });
                    },
                    uploadProgress: function (event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        console.log("2. i adalah ={{$i}}");
                        $("#progress{{$i}} .progress-bar").css("width", percentage+'%', function() {
                          return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    complete: function (xhr) {
                        var obj = JSON.parse(xhr.responseText);
                        
                        if(obj.hasOwnProperty('message')){
                            console.log("3. i adalah ={{$i}}");
                            $('#MsgErrorUpload{{$i}}').empty();
                            var bodyerror=obj.errors;
                            
                            var msg="";
                            var msghead="<div class=\"alert alert-danger\"><strong>Whoops!</strong> "+obj.message+"<br><br><ul>";
                            var msgbody="";
                            var msgfoot="</ul></div>";
                            
                            $.each(bodyerror, function(key, msgerr){     
                                 msgbody+="<li>"+msgerr+"</li>";
                            });
                            msg=msghead+msgbody+msgfoot;  
                            console.log("4. i adalah ={{$i}}");
                            $('#MsgErrorUpload{{$i}}').html(msg);
                            $('#progress{{$i}} .progress-bar').text('File upload Unsuccessfuly');
                        }else{     
                            $('#progress{{$i}} .progress-bar').text('File has uploaded');
                            location.reload();
                        }
                       
                        //location.reload();
                       
                        //$('#list_kk').html(res);
                    }
                });
               @endfor
              
             

            // $('#fileUploadFormKK1').ajaxForm({
            //         beforeSend: function () {
            //             var percentage = '0';
            //             $('#progress1 .progress-bar').css("width", percentage+'%', function() {
            //               return $(this).attr("aria-valuenow", percentage) + "%";
            //             });
            //         },
            //         uploadProgress: function (event, position, total, percentComplete) {
            //             var percentage = percentComplete;
            //             $('#progress1 .progress-bar').css("width", percentage+'%', function() {
            //               return $(this).attr("aria-valuenow", percentage) + "%";
            //             })
            //         },
            //         complete: function (xhr) {
            //             var obj = JSON.parse(xhr.responseText);
                        
            //             if(obj.hasOwnProperty('message')){
            //                 $('#MsgErrorUpload1').empty();
            //                 var bodyerror=obj.errors;
                            
            //                 var msg="";
            //                 var msghead="<div class=\"alert alert-danger\"><strong>Whoops!</strong> "+obj.message+"<br><br><ul>";
            //                 var msgbody="";
            //                 var msgfoot="</ul></div>";
                            
            //                 $.each(bodyerror, function(key, msgerr){     
            //                      msgbody+="<li>"+msgerr+"</li>";
            //                 });
            //                 msg=msghead+msgbody+msgfoot;  
            //                 $('#MsgErrorUpload1').html(msg);
            //                 $('#progress1 .progress-bar').text('File upload Unsuccessfuly');
            //             }else{     
            //                 $('#progress1 .progress-bar').text('File has uploaded');
            //                 location.reload();
            //             }
                       
            //             //location.reload();
                       
            //             //$('#list_kk').html(res);
            //         }
            //     });


            //     $('#fileUploadFormKK2').ajaxForm({
            //         beforeSend: function () {
            //             var percentage = '0';
            //             $('#progress2 .progress-bar').css("width", percentage+'%', function() {
            //               return $(this).attr("aria-valuenow", percentage) + "%";
            //             });
            //         },
            //         uploadProgress: function (event, position, total, percentComplete) {
            //             var percentage = percentComplete;
            //             $('#progress2 .progress-bar').css("width", percentage+'%', function() {
            //               return $(this).attr("aria-valuenow", percentage) + "%";
            //             })
            //         },
            //         complete: function (xhr) {
            //             var obj = JSON.parse(xhr.responseText);
                        
            //             if(obj.hasOwnProperty('message')){
            //                 $('#MsgErrorUpload2').empty();
            //                 var bodyerror=obj.errors;
                            
            //                 var msg="";
            //                 var msghead="<div class=\"alert alert-danger\"><strong>Whoops!</strong> "+obj.message+"<br><br><ul>";
            //                 var msgbody="";
            //                 var msgfoot="</ul></div>";
                            
            //                 $.each(bodyerror, function(key, msgerr){     
            //                      msgbody+="<li>"+msgerr+"</li>";
            //                 });
            //                 msg=msghead+msgbody+msgfoot;  
            //                 $('#MsgErrorUpload2').html(msg);
            //                 $('#progress2 .progress-bar').text('File upload Unsuccessfuly');
            //             }else{     
            //                 $('#progress2 .progress-bar').text('File has uploaded');
            //                 location.reload();
            //             }
                       
            //             //location.reload();
                       
            //             //$('#list_kk').html(res);
            //         }
            //     });

                $('#fileCopyFormKK').ajaxForm({
                    beforeSend: function () {
                        var percentage = '0';
                        $('.progress .progress-bar').css("width", percentage+'%', function() {
                          return $(this).attr("aria-valuenow", percentage) + "%";
                        });
                    },
                    uploadProgress: function (event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $('.progress .progress-bar').css("width", percentage+'%', function() {
                          return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    complete: function (xhr) {
                        var res=xhr.responseText;
                        //reset();
                        //$('.progress-bar').text('File has uploaded');
                        //location.reload();
                        var obj = JSON.parse(xhr.responseText);
                        
                        if(obj.hasOwnProperty('message')){
                            $('#MsgErrorCopy').empty();
                            var bodyerror=obj.errors;
                            
                            var msg="";
                            var msghead="<div class=\"alert alert-danger\"><strong>Whoops!</strong> "+obj.message+"<br><br><ul>";
                            var msgbody="";
                            var msgfoot="</ul></div>";
                            
                            $.each(bodyerror, function(key, msgerr){     
                                 msgbody+="<li>"+msgerr+"</li>";
                            });
                            msg=msghead+msgbody+msgfoot;  
                            $('#MsgErrorCopy').html(msg);
                            $('.progress-bar').text('File upload Unsuccessfuly');
                        }else{     
                            $('.progress-bar').text('File has uploaded');
                            location.reload();
                        }
                    }
                });

                $('#addReview').ajaxForm({
                    beforeSend: function () {
                        var percentage = '0';
                        $('.progress .progress-bar').css("width", percentage+'%', function() {
                          return $(this).attr("aria-valuenow", percentage) + "%";
                        });
                    },
                    uploadProgress: function (event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $('.progress .progress-bar').css("width", percentage+'%', function() {
                          return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    complete: function (xhr) {
                    
                        var obj = JSON.parse(xhr.responseText);
                        
                        if(obj.hasOwnProperty('message')){
                            $('#MsgError').empty();
                            var bodyerror=obj.errors;
                            
                            var msg="";
                            var msghead="<div class=\"alert alert-danger\"><strong>Whoops!</strong> "+obj.message+"<br><br><ul>";
                            var msgbody="";
                            var msgfoot="</ul></div>";
                            
                            $.each(bodyerror, function(key, msgerr){     
                                 msgbody+="<li>"+msgerr+"</li>";
                            });
                            msg=msghead+msgbody+msgfoot;  
                            $('#MsgError').html(msg);
                            $('.progress-bar').text('Review Document Unsuccessfuly');
                        }else{     
                            $('#MsgError').empty();
                            $('.progress-bar').text('Review Document Done');
                            var idloan=$('#loan_app_no').val();
                            $.ajax({
                                url: '/getReview/'+obj.loan_app_no,
                                type: "GET",
                                data : {"_token":"{{ csrf_token() }}"},
                                dataType: "json",
                                success:function(data)
                                {
                                    location.reload();
                                    // if(data){

                                        
                                    //     $('#ListReview').empty();
                                    //     var head_timeline="<div class=\"row\"><div class=\"col-lg-12\"><div class=\"\"><div class=\"card-body\"><div class=\"vtimeline\">";
                                    //     var body_timeline="";
                                    //     var foot_timeline="</div></div></div></div></div>";
                                    //     var x=1;
                                        
                                    //     $.each(data, function(key, review){
                                    //         var comment=review.comment;
                                    //         var reason=review.reason;
                                    //         var nik=review.user_name;
                                    //         var tbo_date=review.tbo_date;
                                    //         var flag_spv=review.flag_spv;
                                    //         var level_spv=review.level_spv;
                                    //         var flag_name="";
                                    //         var name=review.name;
                                            
                                    //         var comment_date =review.comment_date;

                                    //         var inverted='';
                                    //         if(x % 2 == 0){
                                    //             inverted="timeline-inverted";
                                    //         }

                                    //         if(level_spv=='spv1'){
                                    //             lbl_level_spv='Spv Branch - ';
                                    //         }else if(level_spv=='spv2'){
                                    //             lbl_level_spv='DCRM REVIEWER - ';
                                    //         }else if(level_spv=='spv3'){
                                    //             lbl_level_spv='DCRM TEAM LEADER - ';
                                    //         }
                                    //         if(flag_spv == 1){
                                    //             flag_name="<div style='color:green;'> "+lbl_level_spv+"Verify</div>";
                                    //         }else if(flag_spv == 2){
                                    //             flag_name="<div style='color:orange;'> "+lbl_level_spv+"Not Verify</div>";
                                    //         }else if(flag_spv == 3){
                                    //             flag_name="<div style='color:blue;'> "+lbl_level_spv+"TBO (To Be Obtained)</div>";
                                    //         }else if(flag_spv == 4){
                                    //             flag_name="<div style='color:red;'> "+lbl_level_spv+"Reject</div>";
                                    //         }
                                    //         if(reason ==""){
                                    //             tag_reason="";
                                    //         }else{
                                    //             tag_reason="<p><b>Reason: &nbsp;</b>" + reason + "\</p>";
                                    //         }
                                    //         if(comment ==""){
                                    //             tag_comment="";
                                    //         }else{
                                    //             tag_comment="<p><b>Note: &nbsp;</b>" + comment + "\</p>";
                                    //         }
                                    //         if(tbo_date ==null){
                                    //             tag_tbo_date="";
                                    //         }else{
                                    //             tag_tbo_date="<p><b>TBO Date: &nbsp;</b>" + tbo_date + "\</p>";
                                    //         }
                                    //         body_timeline+="<div class=\"timeline-wrapper "+inverted+" timeline-wrapper-info\"><div class=\"timeline-badge\"></div><div class=\"timeline-panel\"><div class=\"timeline-heading\"><h6 class=\"timeline-title\">"+flag_name+"</h6></div><div class=\"timeline-body\">"+tag_reason+" "+tag_comment+" "+tag_tbo_date+"</div><div class=\"timeline-footer d-flex align-items-center flex-wrap\"><span></span><span class=\"ml-auto\"><i class=\"fe fe-calendar text-muted mr-1\"></i>" +comment_date+ "\ by " + name +"\</span></div></div></div>";
                                    //         x=x+1;
                                    //     });
                                    //     $('#ListReview').html(head_timeline+body_timeline+foot_timeline);
                                    // }else{
                                    //     $('#ListReview').empty();
                                    // }
                                }
                            });
                        }    
                        //location.reload();
                       
                        //$('#list_kk').html(res);
                    }
                });


            });

            $('#flag_spv').on('change', function() {
               var flag_spv = $(this).val();
                console.log(flag_spv);
              
                if(flag_spv==1){
                    $("#field_reason").hide();
                    $("#field_tbo").hide();
                }else if(flag_spv==2){
                    $("#field_reason").show();
                    $("#field_tbo").hide();
                }else if(flag_spv==3){
                    $("#field_reason").show();
                    $("#field_tbo").show();
                }else if(flag_spv==4){
                    $("#field_reason").show();
                    $("#field_tbo").hide();
                }else{
                    $("#field_reason").hide();
                    $("#field_tbo").hide();
                }
                

               if(flag_spv) {
                   $.ajax({
                       url: '/getReason/'+flag_spv,
                       type: "GET",
                       data : {"_token":"{{ csrf_token() }}"},
                       dataType: "json",
                       success:function(data)
                       {
                         if(data){
                            $('#reason').empty();
                            $('#reason').append('<option hidden>Choose Reason</option>'); 
                            $.each(data, function(key, reason){
                                $('select[name="reason[]"]').append('<option value="'+ reason.reason +'">' + reason.reason+ '</option>');
                            });
                        }else{
                            $('#reason').empty();
                        }
                     }
                   });
               }else{
                 $('#reason').empty();
               }
            });


            $('.flag_spv').select2({
                placeholder: 'Select Status Document',
                width: 'resolve',
                ajax: {
                    url: '/getFlag',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $('.reason').select2({
                placeholder: "Select Reason",
                width: 'resolve',
                allowClear: true
            });

        });

    </script>
        
   
    
@endsection