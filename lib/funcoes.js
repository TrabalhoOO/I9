$(document).ready(function () {
    var linha = $('.linha');
    linha.find('.quantidade').each(function () {
        valor = parseInt($(this).text());
        if (valor > 5) {
           $(this).parent().addClass('success');
        } else {
            $(this).parent().addClass('danger');
        }
    });
});






