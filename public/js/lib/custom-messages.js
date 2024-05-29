const specMessages = {
    "E001": "{0} is required field.",
    "E002": "{0} must be less than or equal to {1} characters. (Currently {2} characters)",
    "E003": "{0} must be more than or equal to {1} characters. (Currently {2} characters)",
    "E004": "Please enter your email address correctly.",
    "I005": "There is no result.",
    "E006": "The file size limit {0} has been exceeded.",
    "E007": "File extension is incorrect. Please use {0}.",
    "E008": "CSV format is incorrect. Please check header information.",
    "E009": "{0} is duplicated.",
    "E010": "Email or Password incorrect.",
    "E011": "Re-password is not the same as Password.",
    "E012": "{0} format is not correct. Please enter {1} only.",
    "I013": "Saved successfully.",
    "E014": "Save failed.",
    "E015": "{0} does not exist.",
    "E016": "Permission denied.",
    "E017": "{0} must be less than {1}.",
    "I018": "Are you sure you want to delete the record with id {0}?",
}

jQuery.extend(jQuery.validator.messages, {
    required: function (param, field) { return $.validator.format(specMessages["E001"], $(field).data("label")) },
    maxlength: function (param, field) { return $.validator.format(specMessages["E002"], $(field).data("label"), param, field.value.length) },
    minlength: function (param, field) { return $.validator.format(specMessages["E003"], $(field).data("label"), param, field.value.length) },
    dateITA: function (param, field) { return $.validator.format(specMessages["E012"], $(field).data("label"), "date") },
    equalTo: function (param, field) {
        if ($(field).data("label") != "Re-password") {
            return "Please enter the same value again.";
        }
        return $.validator.format(specMessages["E011"]);
    },
    digits: function (param, field) { return $.validator.format(specMessages["E012"], $(field).data("label"), "number") },
    extension: function (param, field) { return $.validator.format(specMessages["E007"], param) },
    accept: function (param, field) { return $.validator.format(specMessages["E007"], param.split("/")[1]) },
    checkValidEmailRFC: function (param, field) { return $.validator.format(specMessages["E004"]); },
    validDateDMY: function (param, field) { return $.validator.format(specMessages["E012"], $(field).data("label"), "date"); },
    validDateYMD: function (param, field) { return $.validator.format(specMessages["E012"], $(field).data("label"), "date"); },
    // email: "Please enter your email address correctly.",
    // maxFileSize: "The file size limit {0} has been exceeded.",
    // fileExtension: "File extension is incorrect. Please use {0}.",
    // csvFormat: "CSV format is incorrect. Please check header information.",
    // exist: "{0} is duplicated.",
    // lessThan: "{0} must be less than {1}.",
    // dateRange: "End date must be greater than Start date.",
    // numberRange: "Please enter {0} greater than {1}.",

});