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

$(function() {

    /**relativo ao menu */
    $('#menu-toggle').click(function(e){
        e.preventDefault();
        $('#wrapper').toggleClass("menuDisplayed");    
    });
    //redireciona em caixas de mensagem
    $("#message-button").on('click', function() {
        var url = $(this).attr('data-url');
        window.location = url;
    });


    /**forms relativos a negociação */
    $('select[name="situacao_id"]').on('change', function() {

        if ($(".extra_data").length) {
            $(".extra_data").slideUp(300, function () {
                $(this).remove(); 
            });
        }

        var template = "app/templates/fragments/";
        $('[name="form_negociacao"] > div:last').after( '<div class="extra_data border"></div>' );
        var content = $( ".extra_data" );

        switch ($(this).val()) {
            case '2':
                template += 'form_cancelamento.html';
                content.css("display", "none").slideDown(300, function() {
                             $("html, body").stop().animate({scrollTop: $(this).offset().top}, 300);
                         }).load( template );                
                break;
                         
            case '6':
                template += 'form_retencao.html';
                content.css("display", "none").slideDown(300, function() {
                                $("html, body").stop().animate({scrollTop: $(this).offset().top}, 300);
                            }).load( template );                
                break;

            case '7':
                template += 'form_reversao.html';
                content.css("display", "none").slideDown(300, function() {
                                $("html, body").stop().animate({scrollTop: $(this).offset().top}, 300);
                            }).load( template );                
                break;

            default:
                if (content.length) {
                    content.slideUp(300, function () {
                         $(this).remove(); 
                    });
                 }
                break;
        }
        //input[name="situacao_id"]' -> hidden
        $('form[name="form_negociacao"] input[name="situacao_id"]').val($(this).val());
    });


    // finalizando negociação
    $('input[name="finalizar_negociação"]').click(function (e) {
        e.preventDefault();

        var situacao = $('select[name="situacao_id"]');
        //console.log(situacao.children("option:selected").val());
        if (situacao.val() == null) {
            if ($('.alert').length) {
                $('.alert').remove();
            }
            $('.container-fluid nav').prepend(__alert("danger", 'Selecione uma opção válida!'))
            return;
        }

        var baseURL = 'https://localhost/project-default/rest.php';
        var form_finalizar = $('form[name="form_negociacao"]');
        var form_data = form_finalizar.serialize();

        $.ajax({
            url:baseURL + '?class=NegociacaoForm&method=save',
            type:'POST',
            data:form_data,
            dataType: 'JSON',
            beforeSend: function (xhr) {   

                $('.container-fluid').prepend(__spinner_loader('info'));
                if ($('.alert').length) {
                    $('.alert').remove();
                }
                
           },
           success:function (response, textStatus, jqXHR) {
            //$('.container-fluid nav').prepend(__alert("success", errorThrow));
                if (response.status == 'error') {
                    $('.container-fluid nav').prepend(__alert("danger", response.data))
                } else {
                    //$('.container-fluid nav').prepend(__alert("success", response.data))
                    console.clear();
                    console.log(response.data);
                }
           },
           error: function (jqXHR, textStatus, errorThrow) {
                $('.container-fluid nav').prepend(__alert("danger", errorThrow));
           },
           complete: function (jqXHR, textStatus) {
                $('.container-fluid').find('.loader').fadeOut(400, function () {
                    $(this).remove();
                });
           }
       });
        
    })


})


function __alert (type, message) {
    var component = '';
    component += '<div class="alert alert-' + type + '" alert-dismissible fade show" role="alert">';
    component +=    message;
    component += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
    component +=    '<span aria-hidden="true">&times;</span>';
    component += '</button>';
    component += '</div>';
    return component;
}

function __spinner_loader(type) {
    var spinner = '';

    spinner +=      '<div class="spinner-border text-'+ type +' loader" role="status">';
    spinner +=          '<span class="sr-only">Loading...</span>';
    spinner +=      '</div>';

    return spinner;
}

// $(document).ready(function () {

//     $('form[name="form_negociacao"]').submit(function (e) {
//         e.preventDefault();
//         e.stopPropagation()

//         return false;
//     });

//     $('input[name="finalizar_negociação"]').click(function (e) {
//         e.preventDefault();
//         e.stopPropagation()
//         alert('click');
//     })

// })

function confirm(param, url, activeRecord) {
    $('#ModalConfirm').find('.modal-body').html('<strong>Tem certeza que deseja excluir o registro?</strong>');
    if($('#ModalConfirm').find('#btn_yes').length == false){
        $('#ModalConfirm').find('.modal-footer').prepend('<button id="btn_yes" type="button" class="btn btn-primary" onclick="del(' + param + ', ' + "'" + url + "'" + ', ' + "'" + activeRecord + "'" + ')">Sim</button>')
    }    
    $('#ModalConfirm').modal('show');
    
}

function del(id, url, activeRecord) 
{
    $.ajax({
        url: url,
        type:'GET',
        data:{id:id, activeRecord:activeRecord},
        success:function () {
            $('#ModalConfirm').modal('hide');
            window.location.href = window.location.href;
        }
    });
}


function negociacao(param, url, activeRecord) 
{
    $('#ocorrencia_id').attr('value', param);
    $("select").val($("select option:first").val());
    $('#ModalNegociacao').modal('show');
}