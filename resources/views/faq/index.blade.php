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
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>FAQ</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('faq.create') }}"> Create FAQ</a>
            </div>
        </div>
    </div>
    <br>
    <form id="searchform" name="searchform">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="pd-5 pd-sm-10 bg-gray-100">
                    <div class="input-group">
                        
                        <div class="col-md-4">
                            <input type="search"
                            class="form-control"
                            name="keyword"
                            value="{{ request('keyword') }}"
                            placeholder="Search Question ...">
                        </div>
                        <span class="input-group-btn">
                            <button type="submit" class="btn ripple btn-primary br-tl-0 br-bl-0" type="button">Submit</button>
                        </span>
                    </div>
                   
                    <!-- <div class="input-group">
                        
                        <div class="col-md-4">
                        
                       
                        <input type="text" name="question" value="{{request()->get('question','')}}" class="form-control" placeholder="Search Question"/>
                        @csrf
                        </div>
                        
                        
                        <span class="input-group-btn">
                        <a class='btn ripple btn-primary br-tl-0 br-bl-0' href='{{url("faq")}}' id='search_btn'>Search</a>
              
                        </span>
                    </div> -->
                    
                </div>
            </div>
        </div>
    </div>
    </form>
 
    @if (count($faq) == 0)
<div class="alert alert-danger mg-b-0" role="alert">
    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <p>Data Tidak Ditemukan</p>
</div>
@endif

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
   
            <div id="pagination_data">
              @include("faq.faq-pagination",["faq"=>$faq])
            </div>

   
      
@endsection


@section('scripts') 

<script>
    $(document).ready(function(){
      $(document).on("click", "#pagination a,#search_btn", function() {
        //alert('test');
        //get url and make final url for ajax 
        var url = $(this).attr("href");
        var append = url.indexOf("?") == -1 ? "?" : "&";
        var finalURL = url + append + $("#searchform").serialize();

        //set to current url
        window.history.pushState({}, null, finalURL);

        $.get(finalURL, function(data) {

          $("#pagination_data").html(data);

        });

        return false;
      })

    });
  </script>


<!-- <script>
$(document).ready(function(){
load_data('');
function load_data(full_text_search_query = '')
{
var _token = $("input[name=_token]").val();
$.ajax({
url:"{{ url('faq-search') }}",
method:"POST",
data:{full_text_search_query:full_text_search_query, _token:_token},
dataType:"json",
success:function(data)
{
var output = '';
if(data.length > 0)
{
for(var count = 0; count < data.length; count++)
{
output += '<tr>';
output += '<td>'+data[count].id+'</td>';
output += '<td>'+data[count].question+'</td>';
output += '<td>'+data[count].answer+'</td>';
output += '<td>'+
          '<form action="faq/'+data[count].id+'" method="POST">'+
          '<a class="btn btn-info" href="faq/'+data[count].id+'">Show</a>'+
          '<a class="btn btn-primary" href="faq/'+data[count].id+'/edit">Edit</a>'+
          '@csrf'+
          '@method("DELETE")'+
          '<button onclick="return confirm("Are you sure?")" type="submit" class="btn btn-danger" >Delete</button>'+
          '</form>'+
          '</td>';
output += '</tr>';
}
}
else
{
output += '<tr>';
output += '<td colspan="3">No Data Found</td>';
output += '</tr>';
}
$('tbody').html(output);
}
});
}
$('#search').click(function(){
    
var full_text_search_query = $('#full_text_search').val();
load_data(full_text_search_query);
});
});
</script> -->
@endsection