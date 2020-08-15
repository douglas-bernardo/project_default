$(function(){
    //redirecionamento em caixas de mensagem
    $("#message-button").on('click', function() {
        var url = $(this).attr('data-url');
        window.location = url;
    });

    $('#password').on("keyup", function (e) {
        if (e.which == 13) {
            $('.buttonLogin').trigger('click'); 
       }
    });



})