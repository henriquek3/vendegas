$(document).ready(function () {
    $('.submenu').click(function () {
        let urli = $(this).attr('href');
        console.log(urli);

        if (urli == '#') {
            console.log(urli);
        } else {
            let request = $.ajax({
                method: 'GET',
                url: urli,
                dataType: 'html',
                type: 'HEAD'
            });
            request.success(function (response) {
                console.log('request done!');
                $('#content-pages').html(response);
            });

            request.error(function () {
                console.log('Arquivo não encontrado');
            })
        }
        return false;
    });
});