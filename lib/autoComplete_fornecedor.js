$("#nomefornecedor").autocomplete({

    source: function (request, response) {
        $.ajax({
            url: "consulta_fornecedor.php",
            dataType: "json",
            data: {
                parametro: $('#nomefornecedor').val()

            },
            success: function (data) {

                var arr = [];

                // Armazena no array
                $(data).each(function (key, value) {

                    arr.push({id: value.fk_pessoa, label: value.nome}); //Guardo apenas o nome, 
                    //porém preciso passar o id para posterior resgate
                });
                response(arr);

            }
        });
    },
    minLength: 2,
    select: function (event, ui) {
        $("#fornecedor").val(ui.item.id);
        $("#nomefornecedor").val(ui.item.nome);
    }
});