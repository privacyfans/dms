@extends('layouts.app')

@section('styles') 
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Users</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Master User</a></li>
								<li class="breadcrumb-item active" aria-current="page">Index</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit User</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
            </div>
        </div>
    </div>
   <br>
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
  
    <form action="{{ route('users.update',$user->nik) }}" method="POST">
        @csrf
        @method('PUT')
   

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="main-content-label mg-b-5">
                            Edit User
                        </div>
                        {{-- <p class="mg-b-20 text-muted">It is Very Easy to Customize and it uses in your website apllication.</p> --}}
                        <div class="pd-30 pd-sm-40 bg-gray-100">
                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-12">
                                    <label class="form-label mg-b-0">NIK</label>
                                </div>
                                <div class="col-md-12 mg-t-5">
                                    <input type="text" name="nik" value="{{ $user->nik }}" class="form-control" placeholder="NIK">
                                </div>
                            </div>
                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-12">
                                    <label class="form-label mg-b-0">Name</label>
                                </div>
                                <div class="col-md-12 mg-t-5">
                                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" placeholder="Name">
                                </div>
                            </div>
                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-12">
                                    <label class="form-label mg-b-0">Email</label>
                                </div>
                                <div class="col-md-12 mg-t-5">
                                    <input type="text" name="email" value="{{ $user->email }}" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-12">
                                    <label class="form-label mg-b-0">Branch</label>
                                </div>
                                <div class="col-md-12 mg-t-5">
                                    {{-- <input type="text" name="cabang" value="{{ $user->cabang }}" class="form-control" placeholder="Branch"> --}}
                                    <select class="livesearch-branch form-control p-3" name="cabang" id="branch">
                                        @foreach($branchlist as $branch)
                                            @if(in_array($user->cabang, $branchall))
                                            <option value="{{ $branch->branch_code }}" selected="true">{{ $branch->branch_name }}</option>
                                            @else
                                            <option value="{{ $branch->branch_code }}">{{ $branch->branch_name }}</option>
                                            @endif 
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row row-xs align-items-center mg-b-20">
                                <div class="col-md-12">
                                    <label class="form-label mg-b-0">Role</label>
                                </div>
                                <div class="col-md-12 mg-t-5">
                                    {{-- <input type="text" name="role" value="{{ $user->role }}" class="form-control" placeholder="Role"> --}}
                                    <select class="livesearch-role form-control p-3" name="role">
                                        @foreach($rolelist as $role)
                                        @if(in_array($user->cabang, $roleall))
                                        <option value="{{ $role->id }}" selected="true">{{ $role->name }}</option>
                                        @else
                                        <option value="{{ $role->id }}">{{ $role->id }}</option>
                                        @endif 
                                    @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Block</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                     <select class="form-control select2-no-search" name="blocked_at">
                                         <?php 
                                            $blocked='';
                                            $unblocked='';
                                            if($user->blocked_at==null){
                                                $unblocked="selected";
                                            }else{
                                                $blocked="selected";
                                            }
                                            
                                         ?>
											<option {{ $unblocked }} value="unblocked">
												UNBLOCKED
											</option>
											<option {{ $blocked }} value="blocked">
												BLOCKED
											</option>
											
                                    </select>
                            </div>
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

    $('.livesearch-role').select2({
        placeholder: 'Select Role',
        ajax: {
            url: '/role-search',
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
</script>
@endsection