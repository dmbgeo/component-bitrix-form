$(document).ready(function () {
    $('#' + document.FormParam.SUBMIT_ID).on('click', function () {
        checkInputs($(this).closest('form'));
    });


    document.FormParam.MASK.forEach(element => {
        $('#' + document.FormParam.SUBMIT_ID).closest('form').find('.' + element.name).mask(element.value);
    });
});