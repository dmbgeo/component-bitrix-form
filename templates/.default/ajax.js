
// new js script


function checkInputs(form) {
    var valid = true;
    var values = {};
    if (form.find('input[name="valid"]').is(':checked') || form.find('input[name="valid"]').length === 0) {
        form.find('input[name="valid"]').siblings('label').removeClass("form-error");
    }
    else {
        valid = false;
        form.find('input[name="valid"]').siblings('label').addClass("form-error");
    }

    $(form).find('input').each(function () {
        var required = $(this).attr("required");
        if (typeof required !== typeof undefined && required !== false) {
            if (check($(this).attr('name'), $(this).val())) {
                $(this).removeClass("form-error");
                values[$(this).attr('name')] = $(this).val();
            }
            else {
                valid = false;
                $(this).addClass("form-error");
            }
        }
        else {
            if ($(this).val() !== "") {
                if (check($(this).attr('name'), $(this).val())) {
                    $(this).removeClass("form-error");
                    values[$(this).attr('name')] = $(this).val();
                }
                else {
                    valid = false;
                    $(this).addClass("form-error");
                }
            }
            else {
                $(this).removeClass("form-error");
            }
        }

    });

    $(form).find('.b-textarea').each(function () {
        var required = $(this).attr("required");
        if (typeof required !== typeof undefined && required !== false) {
            if (check($(this).attr('name'), $(this).val())) {
                $(this).removeClass("form-error");
                values[$(this).attr('name')] = $(this).val();
            }
            else {
                valid = false;
                $(this).addClass("form-error");
            }
        }
        else {
            if ($(this).val() !== "") {
                if (check($(this).attr('name'), $(this).val())) {
                    $(this).removeClass("form-error");
                    values[$(this).attr('name')] = $(this).val();
                }
                else {
                    valid = false;
                    $(this).addClass("form-error");
                }
            }
            else {
                $(this).removeClass("form-error");
            }
        }
    });
    if (valid) {
        values['IBLOCK_ID'] = document.FormParam.IBLOCK_ID;
        values['SEND_EMAIL'] = document.FormParam.SEND_EMAIL;
        $.post(document.FormParam.ACTION, values, function (data) {
            console.log(data);
            if (data == 1) {
                $(form).find('input').each(function () {
                    $(this).val("");
                });
                $(form).find('.b-textarea').each(function () {
                    $(this).val("");
                });
                $.fancybox.close();
                $('#form-messege').addClass("tn-box-active");
                setTimeout(function () {
                    $('#form-messege').removeClass("tn-box-active");
                }, 5200);
            }
            else {
                $('#form-messege-error').addClass("tn-box-active");
                setTimeout(function () {
                    $('#form-messege-error').removeClass("tn-box-active");
                }, 5200);
            }

        });
        return true;
    }
    else
        return false;
}


function check(type, prop) {

    return true;
}



$(document).ready(function () {
    var popup = '<div id="form-messege" class="tn-box tn-box-color"><p>'+document.FormParam.MESSAGE_SUCCESS+'</p><div class="tn-progress"></div></div><div id="form-messege-error" class="tn-box tn-box-color-error"><p>'+document.FormParam.MESSAGE_ERROR+'</p><div class="tn-progress"></div></div>';
    $('body').append(popup);
});

