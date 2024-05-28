$(function() {
    $('#login-form').validate({
        rules: {
            email: {
                required: true,
                checkValidEmailRFC: true,
            },
            password: {
                required: true,
            },
        },
    });
});
