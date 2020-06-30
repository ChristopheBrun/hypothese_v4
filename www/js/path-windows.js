$(function () {
    $('#path-windows-submit').on('click', function () {
        let path = $('#path-windows').val();
        let out = path.replace(/\\/g, '/');
        if (path.includes(' ')) {
            out = '"' + out + '"';
        }

        $('#path-windows-resultat').val(out);
    });
});