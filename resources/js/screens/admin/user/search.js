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

    $("#admin-user-import-form").validate({
        rules: {
            "import_file": {
                required: true,
                extension: "csv",
                maxFileSize: 5
            }
        },
        ignore: [],
        submitHandler: function (form) {
            $("#import-error-box").html("");
            form.submit();
            _common.showLoading(true);
        },
        errorPlacement: function (error, element) {
            $("#import-error-box").html(error);
        },
        onfocusout: false,
        onkeyup: false,
    })

    $("#import-btn").click(function () {
        $("#import-file").click();
    })

    $("#import-file").change(function (e) {
        $("#admin-user-import-form").submit();
    })
})