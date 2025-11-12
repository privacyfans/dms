
<?php 

if(str_contains($urlfile,'.jpg') || str_contains($urlfile,'.jpeg')){
?>
<div id="div1">
<img src="{{ asset('indexed'.$urlfile) }}" ref="{{ asset('indexed'.$urlfile) }}" alt="" width="100%" height="100%">
<!--<img src="https://172.28.97.167:1000/file{{$urlfile}}" ref="https://172.28.97.167:1000/file{{$urlfile}}" alt="" width="100%" height="100%">-->
</div>
<script>
     $(document).ready(function () {

 
        ezoom.onInit($('#div1 img'), {
            onClose: function (result) {
                //alert(result);
            }
        });


            

     });
</script>


<?php
}else{
?>
<script>
var baseUrl = "{{ asset('indexed') }}"; // berisi http://172.28.140.200/indexed
var filePath = "{{$urlfile}}"; // berisi /351/2024/03/05/ID024027359-SLIK Checking-1709616710.pdf

// Pisahkan filePath menjadi direktori dan nama file
var lastSlashIndex = filePath.lastIndexOf('/');
var directoryPath = filePath.substring(0, lastSlashIndex); // Ambil bagian direktori
var fileName = filePath.substring(lastSlashIndex + 1); // Ambil nama file setelah slash terakhir

// Encode hanya nama file
var encodedFileName = encodeURIComponent(fileName);

// Gabungkan kembali untuk mendapatkan URL lengkap yang sudah di-encode nama filenya
var fullUrl = baseUrl + directoryPath + "/" + encodedFileName;
console.log(fullUrl);
// Gunakan fullUrl untuk PDFObject
PDFObject.embed(fullUrl, "#filepdf", {height: "35rem"});


//PDFObject.embed("{{ asset('indexed'.$urlfile) }}","#filepdf",{height: "35rem"});
</script>
<?php
}
?>
<div id="filepdf">