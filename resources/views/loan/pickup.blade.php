@extends('layouts.app')

@section('styles') 
@endsection



@section('content')
    
    
    <form action="{{ url()->current() }}"
        method="post">
        @csrf

        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="row row-sm">

                    <div class="col-lg-12">
						<div class="card mg-b-20" style="background-color: #5F81A3 !important">
							<div class="card-body d-flex p-3">
								<div class="card-title mb-0 mg-t-8" style="color:white;">Pickup Loan App No</div>
							</div>
						</div>
					</div>

                    <div class="col-lg-12">
                            <div class="card"  style="background-color: #5F81A3 !important;">
                                    <a target='_blank' href="{{url('list-pickup')}}">
                                        <div class="card-body iconfont text-center">
                                            <div class="text-center">
                                                <h4 class="card-title mb-3" style="color:white !important;">Waiting List Pickup</h4>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-center">
                                                    <h1 class="mb-1 font-weight-bold" style="color:white !important;" id="total">{{$total}}</h1>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="card-footer text-center">
                                    <input type="hidden" value="{{ Session('nik')}}" name="nik" >
                                        <input type="hidden" value="{{ Session('role')}}" name="role" >
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn ripple btn-primary br-tl-0 br-bl-0" type="button" name='btnPickup' value='verify'>Pickup Loan App No</button>
                                        </span>
                                    </div>        
                            </div>
                    </div>	

                    

                </div>
            </div>
        </div>


   
</form>
  

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

@endsection

@section('scripts')

<script>
    $(document).ready(function () {
        // Function to update the $total value
        function updateTotal() {
            $.ajax({
                url: "{{ route('pickup_count') }}",
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    $('#total').text(response.total);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }

        // Call the updateTotal function initially to set the value on page load
        updateTotal();

        // Call the updateTotal function every 5 seconds to keep the value updated
        setInterval(updateTotal, 5000);
    });

    // Add click event to the button
    $("button[name='btnPickup']").click(function (e) {
            // Get the total value from the element
            var totalValue = parseInt($('#total').text());

            if (totalValue === 0) {
                e.preventDefault(); // Prevent the form from submitting
                alert('Tidak Ada Antrian'); // Display the alert
            }
        });
</script>
@endsection

