/*
// default way to begin
$(document).ready(function () {
});
// simple default way to begin
$(function () {
});
// before another lib
JQuery(function($){
});
*/
//import './config';



$(function() {

    /** menu-toggle */
    $('#menu-toggle').click(function(e){
        e.preventDefault();
        $('#wrapper').toggleClass("menuDisplayed");    
    });

    // form login enter key
    $('#password').on("keyup", function (e) {
        if (e.which == 13) {
            $('.buttonLogin').trigger('click'); 
        }
    });

})