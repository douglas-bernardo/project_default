$(function(){

    // form login enter key
    $('#password').on("keyup", function (e) {
        if (e.which == 13) {
            $('.buttonLogin').trigger('click'); 
       }
    });

})