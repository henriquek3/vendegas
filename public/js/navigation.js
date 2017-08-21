$(document).ready(function () {
    $('.submenu').click(function () {
        let urli = $(this).attr('href');

        if (urli === '#') {
            console.log(urli);
        } else {
            let request = $.ajax({
                method: 'GET',
                url: urli,
                dataType: 'html'
            });
            request.success(function (response) {
                $('#content-pages').html(response);
            });

            request.error(function () {
                console.log('Arquivo não encontrado');
            })
        }
        return false;
    });
});