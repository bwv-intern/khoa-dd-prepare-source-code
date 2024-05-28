$.validator.addMethod('greaterThan', function (value, element, param) {
    var $otherElement = $(param);
    var otherValue = $otherElement.val();

    if (!value || !otherValue) {
        return true;
    }
    return parseFloat(value) > parseFloat(otherValue);
});

$.validator.addMethod('greaterThanOrEqual', function (value, element, param) {
    var $otherElement = $(param);
    var otherValue = $otherElement.val();
    if (!value || !otherValue) {
        return true;
    }
    var current = new Date(value);
    var other = new Date(otherValue);

    return current >= other;
});

$.validator.addMethod('maxFileSize', function (value, element, param) {
    var maxSize = param * 1024 * 1024;
    var fileSize = $(element)[0].files[0].size;
    return (fileSize <= maxSize);
});


$.validator.addMethod('maxlength', function (value, element, params) {
    var maxLength = params;
    var actualLength = Array.from(value).length;

    return actualLength <= maxLength;
});

$.validator.addMethod("validDateDMY", function (value, element) {
    return this.optional(element) || (moment(value, "DD/MM/YYYY").isValid() && /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/.test(value));
});

$.validator.addMethod("validDateYMD", function (value, element) {
    return this.optional(element) || (moment(value, "DD/MM/YYYY").isValid() && /^[0-9]{4}\/[0-9]{2}\/[0-9]{2}$/.test(value));
});