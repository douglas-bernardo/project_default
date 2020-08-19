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
    // captura a situação esolhida
    $('select[name="situacao_id"]').on('change', function() {

        if ($('.alert').length) {
            $('.alert').remove();
        }

        // template de formulário relativo ao tipo de finalização (Rev, Ret, Canc, etc...)
        var template = "app/templates/fragments/";
        var content = null;
        if ($(".extra_data").length) {
            $(".extra_data").slideUp(300, function () {
                $(this).remove(); 
            });
        }

        switch ($(this).val()) {
            case '2':
                $('[name="form_negociacao"] > div:last').after( '<div class="extra_data border"></div>' );
                content = $( ".extra_data" );
                template += 'form_cancelamento.html';
                content.css("display", "none").slideDown(300, function() {
                             $("html, body").stop().animate({scrollTop: $(this).offset().top}, 300);
                         }).load( template );                
                break;
                         
            case '6':
                $('[name="form_negociacao"] > div:last').after( '<div class="extra_data border"></div>' );
                content = $( ".extra_data" );
                template += 'form_retencao.html';
                content.css("display", "none").slideDown(300, function() {
                                $("html, body").stop().animate({scrollTop: $(this).offset().top}, 300);
                            }).load( template );                
                break;

            case '7':
                $('[name="form_negociacao"] > div:last').after( '<div class="extra_data border"></div>' );
                content = $( ".extra_data" );
                template += 'form_reversao.html';
                content.css("display", "none").slideDown(300, function() {
                                $("html, body").stop().animate({scrollTop: $(this).offset().top}, 300);
                            }).load( template );                
                break;
        }

        $('form[name="form_negociacao"] input[name="situacao_id"]').val($(this).val());

    });

    //clear required alerts
    $(document).on('change', 'form[name="form_negociacao"] .extra_data input', function() {
        __clear($(this));
    });

    // clear required alerts on footer
    $('.card-footer .form-control').on('change', function() {
        __clear($(this));
    });

    // captura a data de finalização
    $('input[name="data_finalizacao_footer"]').on('change', function() {
        if($('input[name="data_finalizacao_footer"]').val()) {
            var data_finalizacao = $('input[name="data_finalizacao_footer"]').val();
            $('form[name="form_negociacao"] input[name="data_finalizacao"]').val(data_finalizacao);            
        }
    });

    // finalizando negociação
    $('input[name="finalizar_negociação"]').click(function (e) {
        e.preventDefault();
        if ($('.alert').length) {
            $('.alert').remove();
        }

        if ($('.extra_data').length) {
            if (!__valida_form_neg('form[name="form_negociacao"] .extra_data input')) {
                $('.container-fluid nav').prepend(__alert("danger", 'Um ou mais campos obrigatórios não foram preenchidos!'));
                $("html, body").stop().animate({scrollTop:0}, 300, 'swing');
                return;
            }
        }

        var situacao = $('select[name="situacao_id"]');        
        if (situacao.val() == null) {
            $('.container-fluid nav').prepend(__alert("danger", 'Selecione uma opção de finalização válida!'));
            $("html, body").stop().animate({scrollTop:0}, 300, 'swing');
            situacao.addClass('is-invalid');
            return;
        }

        var data_finalizacao_footer = $('input[name="data_finalizacao_footer"]');
        if (!data_finalizacao_footer.val()) {
            $('.container-fluid nav').prepend(__alert("danger", 'Informe a data da finalização!'));
            $("html, body").stop().animate({scrollTop:0}, 300, 'swing');
            data_finalizacao_footer.addClass('is-invalid');
            return;
        }

        var baseURL = 'https://localhost/project-default/rest.php';
        var form_finalizar = $('form[name="form_negociacao"]');
        var form_data = form_finalizar.serialize();

        $.ajax({

            url:baseURL + '?class=NegociacaoForm&method=finalizaNegociacao',
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
            
                if (response.status == 'error') {
                    $('.container-fluid nav').prepend(__alert("danger", response.data));
                } else {
                    $('.container-fluid nav').prepend(__alert("success", response.data));
                }

                // Redireciona para a lista de negociações
                $("html, body").stop().animate({scrollTop:0}, 300, 'swing');
                setTimeout(function () {
                    window.location.href = 'https://localhost/project-default/?class=NegociacaoList';
                },3000);

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


function __valida_form_neg(form_name) {
    var isFormValid = true;
    $(form_name).each(function(){
        if($(this).prop('required')){           
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isFormValid = false;
            }
        }
    });
    return isFormValid;
}

function __clear(input_object){
    if($(input_object).prop('required')){
        if ($(input_object).hasClass('is-invalid')) {
            $(input_object).removeClass('is-invalid');
            $(input_object).addClass('is-valid');  
        }      
    }
}

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