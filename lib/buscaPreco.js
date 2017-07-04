$(document).ready(function () {
    $('#fidproduto').change(function (e) {
        var id = $(this).val();
        $.post('consulta_produtos.php', {parametro: id}, function (data) {
            $("#fpreco").val(parseFloat(data.preco_venda).toFixed(2));
        }, 'json');
    });
});


