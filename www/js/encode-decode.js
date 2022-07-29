$(function () {
    // Encode
    $('#encode-submit').on('click', function () {
        let data = {
            'val': $('#encode').val(),
            'fct': $('#list-encode  option:selected').val()
        };
        console.log(data);
        $.get('/ajax/encode', data, function (result) {
            $('#encode-resultat').val(result);
        });
    });

    // Copie du résultat dans le presse-papier
    $('#encode-copy').on('click', function () {
        let copyText = document.getElementById("encode-resultat");
        copyText.select();
        copyText.setSelectionRange(0, 99999) // pour les mobiles
        document.execCommand("copy");
    });

    // Decode
    $('#decode-submit').on('click', function () {
        let data = {
            'val': $('#decode').val(),
            'fct': $('#list-decode  option:selected').val()
        };
        console.log(data);
        $.get('/ajax/decode', data, function (result) {
            $('#decode-resultat').val(result);
        });
    });

    // Copie du résultat dans le presse-papier
    $('#decode-copy').on('click', function () {
        let copyText = document.getElementById("decode-resultat");
        copyText.select();
        copyText.setSelectionRange(0, 99999) // pour les mobiles
        document.execCommand("copy");
    });

});
