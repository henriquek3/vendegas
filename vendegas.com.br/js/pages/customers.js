$('#sampleTable').DataTable();
$('#estadosTable').DataTable();
$('tr').click(function () {
    $('tr').css('backgroundColor', '');
    $(this).css('backgroundColor', '#f0ad4e');
});
$('.btn-danger').click(function () {
    swal({
            title: "Você tem certeza disso?",
            text: "Uma vez deletado, não há como desfazer!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim, delete isto!",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
        },
        function () {
            setTimeout(function () {
                swal("Deletado!", "Seu registro foi deletado.", "success");
            }, 2000)
        });
});
$('.btn-primary').click(function () {
    setTimeout(function () {
        $('#tab-cliente .form-control:first').css('backgroundColor', 'orange');
        $('#tab-cliente .form-control:first').focus();
    }, 1000)
});

function listEstados() {
    let loanding = `<tr><td colspan="3">Carregando...</td></tr>`,
        empty = `<tr><td colspan="3">Nenhum registro encontrado...</td></tr>`,
        tbody = $('#estadosTableBody')
    ;
    tbody.html(loanding);
    $.get('/api/estados', function (data) {
        data.length ? tbody.empty() : tbody.html(empty);
        for (let key in data) {
            let tr = $('<tr/>'),
                row = data[key],
                nome = $('<td/>').html(row.nome),
                uf = $('<td/>').html(row.uf_nome),
                regiao = $('<td/>').html(row.regiao);
            tr.append(nome)
                .append(uf)
                .append(regiao);
            tbody.append(tr);
        }
    })
}

listEstados();