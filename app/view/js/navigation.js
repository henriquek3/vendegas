$(document).ready(function () {
    $('#main-menu a').click(function () {
        $('a').removeClass('active-menu');
        $(this).addClass('active-menu');
        var urli = $(this).attr('href');
        console.log(urli);

        var request = $.ajax({
            method: 'GET',
            url: urli,
            dataType: 'html'
        });

        request.done(function (response) {
            console.log('request done!');
            $('#page-inner').html(response);
        });


        return false;
    });


});