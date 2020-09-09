window.thisPage = window.thisPage || {};
window.thisPage.isDirty = false;

window.thisPage.closeEditorWarning = function (event) {
    if (window.thisPage.isDirty)
        return 'It looks like you have been editing something' +
               ' - if you leave before saving, then your changes will be lost.'
    else
        return undefined;
};

$('form[name="form_negociacao"]').on('keyup', // You can use input[type=text] here as well.
             function () { 
                 window.thisPage.isDirty = true; 
             });


$('form[name="form_negociacao').submit(function () {
    QC.thisPage.isDirty = false;
});
window.onbeforeunload = window.thisPage.closeEditorWarning;