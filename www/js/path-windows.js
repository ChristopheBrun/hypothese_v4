$(function () {
    $('#path-windows-submit').on('click', function () {
        console.log(2);
        let path = $('#path-windows').val();
        console.log(path);

        let out = path.replace(/\\/, '/');
        if (path.includes(' ')) {
            out = '"' + out + '"';
        }

        $('#path-windows-resultat').val(out);
    });
});