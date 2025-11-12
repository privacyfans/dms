
<?php 

if(str_contains($urlfile,'.jpg') || str_contains($urlfile,'.jpeg')){
?>
<div id="div1">
<img src="{{ asset('indexed'.$urlfile) }}" ref="{{ asset('indexed'.$urlfile) }}" alt="" width="100%" height="100%">
<!--<img src="https://172.28.97.167:1000/file{{$urlfile}}" ref="https://172.28.97.167:1000/file{{$urlfile}}" alt="" width="100%" height="100%">-->
</div>
<script>
     $(document).ready(function () {
        // Isolate ezoom to prevent global interference
        try {
            // Initialize ezoom with strict isolation
            ezoom.onInit($('#div1 img'), {
                onClose: function (result) {
                    //alert(result);
                },
                // Prevent global event capture
                stopPropagation: true,
                // Limit event scope to the image container only
                eventScope: '#div1',
                // Prevent interference with other elements
                preventDefaults: true
            });

            // Override any global ezoom events that might interfere
            setTimeout(function() {
                $(document).off('mousedown.ezoom mousemove.ezoom click.ezoom');
                // Re-add events only to the specific image
                $('#div1 img').on('mousedown.ezoom mousemove.ezoom click.ezoom', function(e) {
                    e.stopPropagation();
                });
            }, 100);
        } catch(e) {
            console.log('Ezoom initialization error:', e);
        }

        // Cleanup function for when modal closes
        window.cleanupEzoom = function() {
            try {
                // Remove all ezoom-related events
                $(document).off('.ezoom');
                $(window).off('.ezoom');
                $('#div1 img').off('.ezoom');

                // Remove ezoom DOM elements
                $('.ezoom-overlay').remove();
                $('.ezoom-magnifier').remove();
                $('.ezoom-lens').remove();

                // Reset any ezoom data attributes
                $('#div1 img').removeData('ezoom');

                // Clear any global ezoom variables
                if (window.ezoom && window.ezoom.destroy) {
                    window.ezoom.destroy();
                }

                // Remove any event listeners that might interfere with textarea
                $(document).off('keydown.ezoom keyup.ezoom click.ezoom mousedown.ezoom mouseup.ezoom mousemove.ezoom');
                $('body').off('.ezoom');
                $('#div1').off('.ezoom');

                // Force cleanup any remaining ezoom interference
                setTimeout(function() {
                    // Remove any ezoom-injected CSS that might affect textareas
                    $('style[data-ezoom], .ezoom-styles').remove();

                    // Reset body and document styles that ezoom might have changed
                    $('body').css({
                        'user-select': '',
                        'pointer-events': '',
                        'overflow': ''
                    });

                    // Enable all textareas on page with force
                    $('textarea[name="comment"]').each(function() {
                        this.disabled = false;
                        this.readOnly = false;
                        this.style.setProperty('pointer-events', 'auto', 'important');
                        this.style.setProperty('user-select', 'auto', 'important');
                    });

                    console.log('Force enabled textareas after ezoom cleanup');
                }, 50);

                console.log('Ezoom cleanup completed');
            } catch(e) {
                console.log('Ezoom cleanup error:', e);
            }
        };
     });
</script>


<?php
}else{
?>
<script>
$(document).ready(function() {
    // Isolate PDFObject initialization to prevent global event interference
    try {
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

        // Gunakan fullUrl untuk PDFObject with isolated scope
        PDFObject.embed(fullUrl, "#filepdf", {
            height: "35rem",
            // Prevent PDFObject from capturing global events
            suppressConsole: true,
            forcePDFJS: false
        });
    } catch(e) {
        console.log('PDFObject initialization error:', e);
        // Fallback if PDFObject fails
        $('#filepdf').html('<p>Error loading PDF. <a href="' + fullUrl + '" target="_blank">Click here to view in new tab</a></p>');
    }

    // Cleanup function for PDFObject when modal closes
    window.cleanupPDFObject = function() {
        try {
            // Clear PDFObject events and elements
            $('#filepdf').empty();
            $(document).off('.pdfobject');
            // Remove any PDF.js event listeners if they exist
            if (window.PDFViewerApplication && window.PDFViewerApplication.cleanup) {
                window.PDFViewerApplication.cleanup();
            }
        } catch(e) {
            console.log('PDFObject cleanup error:', e);
        }
    };
});

//PDFObject.embed("{{ asset('indexed'.$urlfile) }}","#filepdf",{height: "35rem"});
</script>
<?php
}
?>
<div id="filepdf">