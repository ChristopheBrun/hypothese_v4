$(function () {
    // Traduction du path saisi dans le text input
    $('#path-windows-submit').on('click', function () {
        let path = $('#path-windows').val();
        let out = path.replace(/\\/g, '/');
        if (path.includes(' ')) {
            out = '"' + out + '"';
        }

        $('#path-windows-resultat').val(out);
    });

    // Copie du résultat dans le presse-papier
    $('#path-windows-copy').on('click', function () {
        let copyText = document.getElementById("path-windows-resultat");
        copyText.select();
        copyText.setSelectionRange(0, 99999) // pour les mobiles
        document.execCommand("copy");
    });

});

// noinspection ES6ModulesDependencies,JSUnresolvedVariable
hljs.initHighlightingOnLoad();
