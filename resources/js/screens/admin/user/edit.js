$(function () {
    $("#admin-user-edit-form").validate({
        rules: {
            email: {
                required: true,
                checkValidEmailRFC: true,
                maxlength: 50,
            },
            name: {
                required: true,
                maxlength: 50,
            },
            password: {
                required: true,
            },
            "repeat_password": {
                required: true,
                equalTo: "#password",
            },
            date_of_birth: {
                validDateYMD: true,
            },
            phone: {
                digits: true,
                maxlength: 20,
            },
        }
    });

    $("#date-of-birth").datepicker({
        dateFormat: 'yy/mm/dd',
    });

    $("#date-of-birth-icon").click(function () {
        $("#date-of-birth").datepicker("show");
    });
})