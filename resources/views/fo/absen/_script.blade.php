<script>
    // flatpickr(".date", {
    //     enableTime: true,
    //     dateFormat: "{{ config('a1.datejs.datetime') }}"
    // });
    //CKEDITOR.replace( 'description' );

function exportToPDF(frmName, history_media,) {

    // event.preventDefault();
    var historyMedia = document.getElementById(history_media);
    historyMedia.value = "pdf";

    document.getElementById(frmName).submit();

}

$(document).ready(function() {

    // flatpickr(".date");
    flatpickr(".date", {
        enableTime: false,
        dateFormat: "{{ config('a1.datejs.date') }}"
    });
    $('.select2').select2();

    $('#btnView').click(function() {
        event.preventDefault();

        var historyMedia = document.getElementById('history_media');
        historyMedia.value = "view";

        document.getElementById('frmData').submit();
    })

    // $('#btnPDF').click(function() {

    //     exportToPDF('frmData', 'history_media');

    // })

});

</script>
