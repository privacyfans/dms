@extends('layouts.app')

@section('styles')
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">INTERNAL MEMO</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Internal Memo</a></li>
								<li class="breadcrumb-item active" aria-current="page">Index</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Internal Memo</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('internal-memo.create') }}"> Create Internal Memo</a>
            </div>
        </div>
    </div>
    <br>
    <form action="{{ url()->current() }}" method="get">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="pd-5 pd-sm-10 bg-gray-100">
                    <div class="input-group">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="keyword" value="{{ request('keyword') }}" placeholder="Search Title ...">
                        </div>
                        <span class="input-group-btn">
                            <button type="submit" class="btn ripple btn-primary br-tl-0 br-bl-0" type="button">Submit</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@if (count($internalMemo) == 0)
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

    <table class="table table-bordered table-hover mb-0 text-md-nowrap border">
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Deskripsi</th>
            <th>Files</th>
            <th>Status</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($internalMemo as $item)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{!! $item->title !!}</td>
            <td>{!! $item->deskripsi !!}</td>
            <td>
                @if($item->files->count() > 0)
                    <span class="badge badge-info">{{ $item->files->count() }} file(s)</span>
                @else
                    <span class="badge badge-secondary">No files</span>
                @endif
            </td>
            <td>
                @if($item->status == 'active')
                    <span class="badge badge-success">Active</span>
                @else
                    <span class="badge badge-danger">Inactive</span>
                @endif
            </td>
            <td>
                <form action="{{ route('internal-memo.destroy', $item->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('internal-memo.show', $item->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('internal-memo.edit', $item->id) }}">Edit</a>
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    <br>
    {!! $internalMemo->links() !!}

@endsection
