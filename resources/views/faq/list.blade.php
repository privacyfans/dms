@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">FREQUENTLY ASKED QUESTIONS</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">FAQ</a></li>
								<li class="breadcrumb-item active" aria-current="page">Index</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')

    <br>
    

@if ($message = Session::get('success'))
<div class="alert alert-success" role="alert">
    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
       <span aria-hidden="true">&times;</span>
  </button>
  <p>{{ $message }}</p>
</div>
@endif
@if ($message = Session::get('error'))

<div class="alert alert-danger mg-b-0" role="alert">
    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <p>{{ $message }}</p>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div>
                    <h6 class="card-title mb-1">Frequently Asked Questions</h6>
                    <p class="tx-12 text-muted card-sub-title">Silahkan baca dengan seksama.</p>
                </div>
                <form action="{{ url()->current() }}"
                    method="get">
                
                            <div class="pd-30 pd-sm-40 bg-gray-100">
                                <div class="input-group">
                                    
                                    <div class="col-md-4">
                                        <input type="text"
                                        class="form-control"
                                        name="keyword"
                                        value="{{ request('keyword') }}"
                                        placeholder="Search Question ...">
                                    </div>
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn ripple btn-primary br-tl-0 br-bl-0" type="button">Submit</button>
                                    </span>
                                </div>
                            </div>
                </form>
                <div aria-multiselectable="true" class="accordion" id="accordion" role="tablist">
                    <?php
                    $x=1;
                    ?>
                    @foreach ($faq as $br)
                    

                    <div class="card mb-0">
                        <div class="card-header" id="heading{{ ++$i }}" role="tab">
                            <a aria-controls="collapse{{ $i }}" aria-expanded="true" data-toggle="collapse" href="#collapse{{ $i }}">{{$x}}.&nbsp;{!! $br->question !!}</a>
                        </div>
                        <div aria-labelledby="heading{{ $i }}" class="collapse" data-parent="#accordion" id="collapse{{ $i }}" role="tabpanel">
                            <div class="card-body">
                                {!! $br->answer !!}
                            </div>
                        </div>
                    </div>
                    <?php
                    $x++;
                    ?>
                    @endforeach
                </div><!-- accordion -->
            </div>
        </div>
    </div>
</div>

{!! $faq->links() !!}
      
@endsection