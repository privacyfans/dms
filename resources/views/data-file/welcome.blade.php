<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel 8 Bootstrap 5 Progress Bar Example</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5" style="max-width: 500px">
       
        <div class="alert alert-warning mb-4 text-center">
           <h2 class="display-6">Laravel Dynamic Ajax Progress Bar Example</h2>
        </div>  

        <form id="fileUploadFormKTP" method="POST" action="{{ url('/upload-doc-file') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <input name="type" type="hidden" value="ktp">
            </div>

            <div class="form-group mb-3">
                <input name="file" type="file" class="form-control">
            </div>

            <div class="d-grid mb-3">
                <input type="submit" value="Submit" class="btn btn-primary">
            </div>

            <div class="form-group">
                <div class="progress ktp">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger ktp" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>
            </div>
        </form>

        
        <form id="fileUploadFormKK" method="POST" action="{{ url('/upload-doc-file') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <input name="type" type="hidden" value="kk">
            </div>

            <div class="form-group mb-3">
                <input name="file" type="file" class="form-control">
            </div>

            <div class="d-grid mb-3">
                <input type="submit" value="Submit" class="btn btn-primary">
            </div>

            <div class="form-group">
                <div class="progress kk">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger kk" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>
            </div>
        </form>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

    <script>
        $(function () {
            $(document).ready(function () {
               
                function reset(){
                    var percentage = '0';
                    $('.progress .progress-bar .ktp').css("width", percentage+'%', function() {
                          return $(this).attr("aria-valuenow", percentage) + "%";
                        });
                };
                $('#fileUploadFormKTP').ajaxForm({
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
                        //console.log('File has uploaded');
                        //reset();
                        $('.progress-bar').text('File has uploaded');
                       
                    }
                });

                $('#fileUploadFormKK').ajaxForm({
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
                        //console.log('File has uploaded');
                        reset();
                        $('.progress-bar').text('File has uploaded');
                        
                    }
                });
            });
        });
    </script>
</body>

</html>