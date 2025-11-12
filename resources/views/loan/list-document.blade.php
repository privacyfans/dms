@extends('layouts.app')
@section('content')





<div class="row ">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div>
                    <h6 class="card-title mb-1">List Dokumen - {{$datafile->produk}}</h6>
                    {{-- <p class="tx-12 text-muted card-sub-title">It is Very Easy to Customize and it uses in website apllication.</p> --}}
                </div>
                @php
                $counter1 = 1;
                $counter2 = 8;
                $counter3 = 15;
                @endphp
                <div class="pd-30 pd-sm-40 bg-gray-100">
                    <div class="row row-xs">
                        <div class="col-lg-4 col-md-4">
                            <div class="card">
                                <div class="card-body">

                                    <div aria-multiselectable="true" class="accordion" id="accordion" role="tablist">
                                        
                                        @foreach ($map_product->skip(0)->take(7) as $map)
                                        
                                        <div class="card mb-0">
                                            <div class="card-header-{{ get_warna($datafile->loan_app_no,$map->nama_dokumen )}}" id="headingOne" role="tab">
                                                <a aria-controls="collapseOne{{$counter1}}" aria-expanded="true" data-toggle="collapse" href="#collapseOne{{$counter1}}">{{ $counter1.'. '.$map->nama_dokumen }}</a>
                                            </div>
                                                    @if(get_warna($datafile->loan_app_no,$map->nama_dokumen ) == 'success')
                                                    <div aria-labelledby="headingOne" class="collapse" data-parent="#accordion" id="collapseOne{{$counter1}}" role="tabpanel">
                                                        <div class="card-body">
                                                            <ul>
                                                            @foreach ($detailfile as $item)
                                                                @if ($map->nama_dokumen == $item->alias)
                                                                    <li><a href="http://172.28.97.131/dms/document/indexed/{{$item->file}}" target="_blank">{{getlastnamefile($item->file)}}</a></li>
                                                                @endif
                                                            @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
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
                        <div class="col-lg-4 col-md-4">
                            <div class="card">
                                <div class="card-body">

                                    <div aria-multiselectable="true" class="accordion" id="accordion1" role="tablist">
                                        
                                        @foreach ($map_product->skip(7)->take(7)  as $map)
                                       
                                        <div class="card mb-0">
                                            <div class="card-header-{{ get_warna($datafile->loan_app_no,$map->nama_dokumen )}}" id="headingOne" role="tab">
                                                <a aria-controls="collapseOne{{$counter2}}" aria-expanded="true" data-toggle="collapse" href="#collapseOne{{$counter2}}">{{ $counter2.'. '.$map->nama_dokumen }}</a>
                                            </div>
                                                    @if(get_warna($datafile->loan_app_no,$map->nama_dokumen ) == 'success')
                                                    <div aria-labelledby="headingOne" class="collapse" data-parent="#accordion1" id="collapseOne{{$counter2}}" role="tabpanel">
                                                        <div class="card-body">
                                                            <ul>
                                                            @foreach ($detailfile as $item)
                                                                @if ($map->nama_dokumen == $item->alias)
                                                                    <li><a href="http://172.28.97.131/dms/document/indexed/{{$item->file}}" target="_blank">{{getlastnamefile($item->file)}}</a></li>
                                                                @endif
                                                            @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
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
                        <div class="col-lg-4 col-md-4">
                            <div class="card">
                                <div class="card-body">

                                    <div aria-multiselectable="true" class="accordion" id="accordion2" role="tablist">
                                        
                                        @foreach ($map_product->skip(14)->take(7)  as $map)
                                       
                                        <div class="card mb-0">
                                            <div class="card-header-{{ get_warna($datafile->loan_app_no,$map->nama_dokumen )}}" id="headingOne" role="tab">
                                                <a aria-controls="collapseOne{{$counter3}}" aria-expanded="true" data-toggle="collapse" href="#collapseOne{{$counter3}}">{{ $counter3.'. '.$map->nama_dokumen }}</a>
                                            </div>
                                                    @if(get_warna($datafile->loan_app_no,$map->nama_dokumen ) == 'success')
                                                    <div aria-labelledby="headingOne" class="collapse" data-parent="#accordion2" id="collapseOne{{$counter3}}" role="tabpanel">
                                                        <div class="card-body">
                                                            <ul>
                                                            @foreach ($detailfile as $item)
                                                                @if ($map->nama_dokumen == $item->alias)
                                                                    <li><a href="http://172.28.97.131/dms/document/indexed/{{$item->file}}" target="_blank">{{getlastnamefile($item->file)}}</a></li>
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
                                    
                                    </div><!-- accordion -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    
@endsection
<!-- End Row -->