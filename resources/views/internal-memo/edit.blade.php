@extends('layouts.app')

@section('styles')
	<!--- Internal Select2 css-->
    <link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">

    <!--Internal  Quill css -->
    <link href="{{URL::asset('assets/plugins/quill/quill.snow.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/quill/quill.bubble.css')}}" rel="stylesheet">
@endsection

@section('breadcrumb')

					<div class="left-content">
						<h4 class="content-title mb-1">Internal Memo</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Internal Memo</a></li>
								<li class="breadcrumb-item active" aria-current="page">Edit</li>
							</ol>
						</nav>
					</div>

@endsection('breadcrumb')

@section('content')

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

@if ($message = Session::get('success'))
<div class="alert alert-success" role="alert">
    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
       <span aria-hidden="true">&times;</span>
  </button>
  <p>{{ $message }}</p>
</div>
@endif

<form id="formInternalMemo" action="{{ route('internal-memo.update', $internalMemo->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        Edit Internal Memo
                    </div>
                    <div class="pd-30 pd-sm-40 bg-gray-100">
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Title</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <input type="text" name="title" class="form-control" placeholder="Title" value="{{ $internalMemo->title }}">
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Deskripsi</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <div class="ql-wrapper ql-wrapper-demo bg-gray-100">
									<div id="descEditor">
									</div>
								</div>
                            </div>
                        </div>

                        <!-- Existing Files -->
                        @if($internalMemo->files->count() > 0)
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Existing Files</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>File Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($internalMemo->files as $file)
                                            <tr>
                                                <td>
                                                    <a href="/uploads/{{ $file->file_path }}" target="_blank">{{ $file->file_name }}</a>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" onclick="deleteFile({{ $file->id }})" class="btn btn-sm btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Upload New Files (PDF, PNG, JPG, JPEG - Max 2MB each)</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <input name="files[]" type="file" class="form-control" multiple accept=".pdf,.png,.jpg,.jpeg">
                                <small class="text-muted">You can select multiple files to add</small>
                            </div>
                        </div>
                        <div class="row row-xs align-items-center mg-b-20">
                            <div class="col-md-12">
                                <label class="form-label mg-b-0">Status</label>
                            </div>
                            <div class="col-md-12 mg-t-5">
                                <select class="form-control select2-no-search" name="status">
                                    <option value="active" {{ $internalMemo->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $internalMemo->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">Update</button>
                        <a href="{{ route('internal-memo.index') }}" class="btn btn-secondary pd-x-30 mg-t-5">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->

</form>
@endsection

@section('scripts')

		<!--Internal  Select2 js -->
		<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>

		<!--Internal quill js -->
		<script src="{{URL::asset('assets/plugins/quill/quill.min.js')}}"></script>

		<!--Internal  Perfect-scrollbar js -->
		<script src="{{URL::asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>

		<!-- Internal Form-editor js -->
		{{-- <script src="{{URL::asset('assets/js/form-editor.js')}}"></script> --}}
    <script>
        function initQuill(html) {
            var l = Quill.import("ui/icons");
            l.bold = '<i class="la la-bold" aria-hidden="true"></i>', l.italic = '<i class="la la-italic" aria-hidden="true"></i>', l.underline = '<i class="la la-underline" aria-hidden="true"></i>', l.strike = '<i class="la la-strikethrough" aria-hidden="true"></i>', l.list.ordered = '<i class="la la-list-ol" aria-hidden="true"></i>', l.list.bullet = '<i class="la la-list-ul" aria-hidden="true"></i>', l.link = '<i class="la la-link" aria-hidden="true"></i>', l.image = '<i class="la la-image" aria-hidden="true"></i>', l.video = '<i class="la la-film" aria-hidden="true"></i>', l["code-block"] = '<i class="la la-code" aria-hidden="true"></i>';
            var i = [
                [{
                    header: [1, 2, 3, 4, 5, 6, !1]
                }],
                ["bold", "italic", "underline", "strike"],
                [{
                    list: "ordered"
                }, {
                    list: "bullet"
                }],
                ["link", "image", "video"]
            ];

            var quill = new Quill('#descEditor', {
                modules: {
                    toolbar: i
                },
                theme: "snow"
            });

            var delta = quill.clipboard.convert(html);
            quill.setContents(delta, 'silent');
        }

        function deleteFile(fileId) {
            if (confirm('Are you sure you want to delete this file?')) {
                // Create a temporary form for delete
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '/internal-memo-file/' + fileId;

                var csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                var methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        $(document).ready(function(){
            var html = `{!! $internalMemo->deskripsi !!}`;
            initQuill(html);

            $("#formInternalMemo").on("submit", function () {
                var myEditor = document.querySelector('#descEditor');
                var html = myEditor.children[0].innerHTML;
                $(this).append("<textarea name='deskripsi' style='display:none'>"+html+"</textarea>");
            });
        });
    </script>
@endsection
