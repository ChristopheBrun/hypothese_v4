$(function () {
    $('#base64-encode-submit').on('click', function () {
        let data = {'val': $('#base64-encode').val()};
        $.get('/ajax/base64-encode', data, function (result) {
            $('#base64-encode-resultat').val(result);
        });
    });

    // Copie du résultat dans le presse-papier
    $('#base64-encode-copy').on('click', function () {
        let copyText = document.getElementById("base64-encode-resultat");
        copyText.select();
        copyText.setSelectionRange(0, 99999) // pour les mobiles
        document.execCommand("copy");
    });

    $('#base64-decode-submit').on('click', function () {
        let data = {'val': $('#base64-decode').val()};
        $.get('/ajax/base64-decode', data, function (result) {
            $('#base64-decode-resultat').val(result);
        });
    });

    // Copie du résultat dans le presse-papier
    $('#base64-decode-copy').on('click', function () {
        let copyText = document.getElementById("base64-encode-resultat");
        copyText.select();
        copyText.setSelectionRange(0, 99999) // pour les mobiles
        document.execCommand("copy");
    });

});
