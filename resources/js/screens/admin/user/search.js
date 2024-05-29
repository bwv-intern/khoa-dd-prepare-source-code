$(function () {
    $(".delete-user-btn").click(function () {
        const userId = $(this).data("user-id");
        if (confirm($.validator.format(specMessages["I018"], userId))) {
            location.href = $(this).data("link");
        }
    });

    $("#usr01-form").validate({
        rules: {
            email: {
                checkValidEmailRFC: true,
            },
            date_of_birth: {
                validDateDMY: true,
            },
            phone: {
                digits: true,
            }
        }
    });

    $("#date-of-birth").datepicker({
        dateFormat: 'dd/mm/yy'
    });

    $("#date-of-birth-icon").click(function () {
        $("#date-of-birth").datepicker("show");
    });
})