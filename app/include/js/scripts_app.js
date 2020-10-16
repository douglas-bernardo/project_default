const base_url_api = 'http://localhost/project-default/rest.php?';
const base_url_app = 'http://localhost/project-default/?';


$( document ).ready(function() {

    if (sessionStorage.getItem('negociacao_register') != null) {
    $('.container-fluid nav:first').prepend(
        __alert(
            "success", 
            sessionStorage.getItem('negociacao_register')
            )
        );        
    sessionStorage.removeItem('negociacao_register');
    }

    if (sessionStorage.getItem('negociacao_finaliza') != null) {
        $('.container-fluid nav:first').prepend(
            __alert(
                "success", 
                sessionStorage.getItem('negociacao_finaliza')
                )
            );        
        sessionStorage.removeItem('negociacao_finaliza');
    }

});


function confirm(param, url, activeRecord) {
    $('#ModalConfirm').find('.modal-body')
    .html('<strong>Tem certeza que deseja excluir o registro?</strong>');
    if($('#ModalConfirm').find('#btn_yes').length == false){
        $('#ModalConfirm')
        .find('.modal-footer')
        .prepend(`<button id="btn_yes" 
                          type="button" 
                          class="btn btn-primary" 
                          onclick="del(' + param + ', 
                                       ' + "'" + url + "'" + ', 
                                       ' + "'" + activeRecord + "'" + ')">Sim
                                       </button>`)
    }    
    $('#ModalConfirm').modal('show');    
}

function del(id, url, activeRecord) {
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

/**
 * Abre uma janela modal antes de registrar uma ocorrência como uma 
 * nova negociação. Nessa janela serão solicitadas informações como
 * Origem da ocorrência e tipo de solicitação 
 */
function modal_reg_negociacao_open(param, url, activeRecord) {
    // limpa alertas
    if ($('.alert').length) {
        $('.alert').remove();
    }

    // limpa alertas de inputs
    __clear_alert_validation_input('select[name="origem_id"]');
    __clear_alert_validation_input('select[name="tipo_solicitacao_id"]');

    var select_tp_sol = $('select[name="origem_id"]');
    if(select_tp_sol.hasClass('is-invalid')){
        $(select_tp_sol).removeClass('is-invalid');
    }

    url = base_url_api+'class=OcorrenciaService&method=getOcorrencia&id='+ param;
    $.ajax({

        url: url,
        type:'GET',
        dataType: 'json',
        success: function (response) {
            $('.modal-body .card').remove();
            var data = response.data;
            var card_info = '<div class="card border-light mb-3">';
            card_info += '<div class="card-body">';
            card_info += '<b>Cliente:</b> ' + data.nome_cliente + '<br>';
            card_info += '<b>Número da ocorrência:</b> ' + data.numero_ocorrencia + '<br>';
            card_info += '<b>Data de abertura:</b> ' + moment(data.dtocorrencia).format('DD/MM/YYYY') + '<br>';
            card_info += '<b>Contrato:</b> ' + data.numeroprojeto + '-' + data.numerocontrato + '<br>';
            card_info += '<b>Produto:</b> ' + data.nomeprojeto + '<br>';
            card_info += '</div>';
            card_info += '</div>';    
            $('.modal-body').prepend(card_info);

            $('form[name="form_negociacao_register"] #idusuario_resp').attr(
                'value', 
                data.idusuario_resp
            );
        }
    });

    $('#ocorrencia_id').attr('value', param);
    $("select").val($("select option:first").val()); //define index 0
    $('#ModalNegociacao').modal('show');
}

$('form[name="form_negociacao_register"] .form-control').on('change', 
    function() {
        if ($('.alert').length) {
            $('.alert').remove();
        }
        __clear_to_valid($(this));
    }
);

/**
 * Registra uma nova negociação de acordo com a ocorrência recebida
 * Controller: OcorrenciasList
 * Method: registraNegociacao 
 */
$('input[id="registrar_negociacao"]').click(function () {

    if ($('.alert').length) {
        $('.alert').remove();
    }

    var origem = $('select[name="origem_id"]');    
    if (origem.val() == null) {
        $('.modal-body').prepend(__alert("danger", 'Informe a origem!'));
        origem.addClass('is-invalid');
        return;
    }

    var tipo_solicitacao = $('select[name="tipo_solicitacao_id"]');    
    if (tipo_solicitacao.val() == null) {
        $('.modal-body').prepend(
            __alert("danger", 'Informe o tipo de solicitação!')
        );
        tipo_solicitacao.addClass('is-invalid');
        return;
    }

    var form_register = $('form[name="form_negociacao_register"]');
    var form_data = form_register.serialize();

    $.ajax({

        url:base_url_app + 'class=OcorrenciasList&method=registraNegociacao',
        type:'POST',
        data:form_data,
        dataType: 'JSON',
        beforeSend: function (xhr) {
            $('.modal-footer')
            .prepend('<img src="app/include/svg/Spinner-1s-64px.gif" width="40" height="40"/>');
        },
        success:function (response, textStatus, jqXHR) {  
            if (response.status == 'error') {
                $('.container-fluid nav')
                .prepend(__alert("danger", response.data));
            } else {
                $('#ModalNegociacao').modal('hide');
                sessionStorage.setItem('negociacao_register', response.data );
                location.reload();
            }
        },

        error: function (jqXHR, textStatus, errorThrow) {},
        complete: function (jqXHR, textStatus) {}
   });

});

/**
 * Manipula o formulário de negociação a partir da escolha da situação
 * getScript - inclui na rotina um script de confirmação caso o  usuário
 * começe a editar o form form_negociacao e feche a operação sem concluir
 */
//$.getScript("app/include/js/page_confirm_leave.js", function () {
    $('select[name="situacao_id"]').on('change', function() {

            if ($('.alert').length) {
                $('.alert').remove();
            }
            var template = "app/templates/fragments/";
            var content = null;

            if ($(".extra_data").length) {
                $(".extra_data").slideUp(300, function () {
                    $(this).remove(); 
                });
            }

            switch ($(this).val()) {
                case '2':
                    $('[name="form_negociacao"] > div:last')
                    .after( '<div class="extra_data border"></div>' );
                    content = $( ".extra_data" );
                    template += 'form_cancelamento.html';
                    content.css("display", "none").slideDown(300, function() {
                                    $("html, body").stop()
                                    .animate(
                                        {scrollTop: $(this).offset().top}, 300
                                        );
                                }).load( template );                
                    break;
                                
                case '6':
                    $('[name="form_negociacao"] > div:last')
                    .after( '<div class="extra_data border"></div>' );
                    content = $( ".extra_data" );
                    template += 'form_retencao.html';
                    content.css("display", "none").slideDown(300, function() {
                                    $("html, body").stop()
                                    .animate({scrollTop: $(this).offset().top}, 300);
                                }).load( template );                
                    break;

                case '7':
                    $('[name="form_negociacao"] > div:last')
                    .after( '<div class="extra_data border"></div>' );
                    content = $( ".extra_data" );
                    template += 'form_reversao_novo.html';
                    content.css("display", "none").slideDown(300, function() {
                                    $("html, body").stop()
                                    .animate({scrollTop: $(this)
                                        .offset().top}, 300);
                                }).load( template, function () {
                                    loadProjetos();
                                } );
                    break;
            }

            $('form[name="form_negociacao"] input[name="situacao_id"]')
            .val($(this).val());            
    }); 

//});

function loadProjetos() {    
    $.ajax({
        url:base_url_api + 'class=ProjetoTsService&method=getAll',
        dataType: 'JSON',
        success:function (response) {        
            if (response.status == 'error') {
                $('.container-fluid nav')
                .prepend(__alert("danger", response.data));
            } else {               
                response.data.forEach( proj => {
                    $('select[name="reversao_projeto_id"]')
                    .append(`<option value="${proj.id}">${proj.info}</option>`);
                });
            }
        },
   });
}

/**
 * Obtem informações do projeto escolhido no momento da reversão
 */
$(document).on('change', 'form[name="form_negociacao"] select[name="reversao_projeto_id"]', function() {

    var projeto = $('select[name="reversao_projeto_id"]');
    if (projeto.hasClass("is-invalid")) {
        projeto.removeClass("is-invalid");
        projeto.addClass('is-valid');
    }

    if ($('#projeto_error').length) {
        $('#projeto_error').remove();
    }

    var projeto_id = $(this).val();

    $.ajax({
        url:base_url_api + 'class=ProjetoTsService&method=getProjetoById',
        type:'GET',
        data: {projeto_id:projeto_id},
        dataType: 'JSON',
        success:function (response) {     
            if (response.status == 'error') {
                $('.container-fluid nav')
                .prepend(__alert("danger", response.data));
            } else {     
                if ($('#produto_nome').length) {
                    $('#produto_nome').text('Produto: ' + response.data.nomeprojeto);
                } else {       
                $('form[name="form_negociacao"] .divider_form')
                    .append(__alert('warning', 'Produto: ' + response.data.nomeprojeto, false, "produto_nome"));
                }
            }
        },
   });

});

// limpa os alertas de validação
$(document).on('change', 'form[name="form_negociacao"] .extra_data input', function() {
    __clear_to_valid($(this));
});

$('.card-footer .form-control').on('change', function() {
    __clear_to_valid($(this));
});

// captura a data de finalização no momento da escolha
$('input[name="data_finalizacao_footer"]').on('change', function() {
    if($('input[name="data_finalizacao_footer"]').val()) {
        var data_finalizacao = $('input[name="data_finalizacao_footer"]').val();
        $('form[name="form_negociacao"] input[name="data_finalizacao"]')
        .val(data_finalizacao);            
    }
});

/**
 * Validação e finalização de negociação
 * Controller: NegociacaoForm
 * Method: finalizaNegociacao
 */
$('input[id="finaliza_negociacao"]').click(function (e) {
    e.preventDefault();
    if ($('.alert').length) {
        $('.alert').remove();
    }

    if ( $('select[name="reversao_projeto_id"]').length) {
        var projeto = $('select[name="reversao_projeto_id"]'); 
        if (projeto.val() == null) {
            $('.container-fluid nav').prepend(__alert("danger", 'Informe o projeto!', true, "projeto_error"));
            $("html, body").stop().animate({scrollTop:0}, 300, 'swing');
            projeto.addClass('is-invalid');
            return;
        }
    }

    if ($('.extra_data').length) {
        if (!__valida_form_neg('form[name="form_negociacao"] .extra_data input')) {
            $('.container-fluid nav')
            .prepend(
                __alert("danger", 'Um ou mais campos obrigatórios não foram preenchidos!')
                );
            $("html, body").stop().animate({scrollTop:0}, 300, 'swing');
            return;
        }
    }

    var situacao = $('select[name="situacao_id"]');        
    if (situacao.val() == null) {
        $('.container-fluid nav')
        .prepend(__alert("danger", 'Selecione uma opção de finalização válida!'));
        $("html, body").stop().animate({scrollTop:0}, 300, 'swing');
        situacao.addClass('is-invalid');
        return;
    }

    var data_finalizacao_footer = $('input[name="data_finalizacao_footer"]');
    if (!data_finalizacao_footer.val()) {
        $('.container-fluid nav')
        .prepend(__alert("danger", 'Informe a data da finalização!'));
        $("html, body").stop().animate({scrollTop:0}, 300, 'swing');
        data_finalizacao_footer.addClass('is-invalid');
        return;
    }

    var form_finalizar = $('form[name="form_negociacao"]');
    var form_data = form_finalizar.serialize();

    $.ajax({

        url:base_url_app + 'class=NegociacaoForm&method=finalizaNegociacao',
        type:'POST',
        data:form_data,
        dataType: 'JSON',
        beforeSend: function (xhr) {   
            $('.container-fluid')
            .prepend(`<img class="loader" 
                           src="app/include/svg/Spinner-1s-64px.gif" 
                           width="40" 
                           height="40"/>`).delay(400);
            if ($('.alert').length) {
                $('.alert').remove();
            }                
        },
        success:function (response, textStatus, jqXHR) {        
            if (response.status == 'error') {
                $('.container-fluid nav')
                .prepend(__alert("danger", response.data));
                $("html, body").stop().animate({scrollTop:0}, 300, 'swing');
            } else {
                sessionStorage.setItem('negociacao_finaliza', response.data );
                let url = base_url_app+'class=NegociacaoList';
                window.location.href = url;
            }
        },
        error: function (jqXHR, textStatus, errorThrow) {
            $('.container-fluid nav')
            .prepend(__alert("danger", errorThrow));
        },
        complete: function (jqXHR, textStatus) {
            $('.container-fluid')
            .find('.loader')
            .fadeOut(400, function () {
                $(this).remove();
            });
        }
    });    
})

/** jQuery Mask Plugin */
$(document).on('keypress', '.money2', function() {
    $(this).mask("#.##0,00", {reverse: true});
});
$(document).on('keypress', '.number', function() {
    $(this).mask("0#");
});


/**
 * #######################
 * ####### Dialogs ####### 
 * #######################
 */
function __hello(){
    $('#toast-place').append(
        `<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000">
            <div class="toast-header">
            <img src="app/include/img/info.png" class="rounded mr-2" alt="...">
            <strong class="mr-auto">Bootstrap</strong>
            <small>11 mins ago</small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="toast-body">
            Hello, world! This is a toast message.
            </div>
        </div>`);
    
    $('.toast').toast('show');

    $('.toast').on('hidden.bs.toast', e => {
        $(e.currentTarget).remove();
    });
}

function __alert (type, message, close = true, id = "alert") {
    var component = '';
    component += `<div class="alert alert-${type}" alert-dismissible fade show" role="alert" id="${id}">`;
    component +=    message;
    if (close) {
        component += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        component +=    '<span aria-hidden="true">&times;</span>';
        component += '</button>';
    }
    component += '</div>';
    return component;
}

function __spinner_loader(type) {
    var spinner = '';
    spinner += '<div class="spinner-border text-'+ type +'" role="status">';
    spinner += '<span class="sr-only">Loading...</span>';
    spinner += '</div>';
    return spinner;
}

/**
 * Validations
 */

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

function __clear_to_valid(input_object){
    if($(input_object).prop('required')){
        if ($(input_object).hasClass('is-invalid')) {
            $(input_object).removeClass('is-invalid');
            $(input_object).addClass('is-valid');  
        }      
    }
}

function __clear_alert_validation_input(input_object){
    if ($(input_object).hasClass('is-invalid')) {
        $(input_object).removeClass('is-invalid');
    } else if ($(input_object).hasClass('is-valid')) {
        $(input_object).removeClass('is-valid');
    }
}
