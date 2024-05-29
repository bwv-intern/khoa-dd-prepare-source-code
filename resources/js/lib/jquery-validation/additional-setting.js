/**
 * Common Jquery validate
 */

// Form track changes
$.fn.extend({
    trackChanges: function () {
        $(this).data('serialize', $(this).serialize());
    },
    isChanged: function () {
        return $(this).serialize() != $(this).data('serialize');
    },
    preventDoubleSubmission: function () {
        $(this).on('submit', function (e) {
            var $form = $(this);

            if ($form.data('submitted') === true) {
                // Previously submitted - don't submit again
                e.preventDefault();
            } else {
                // Mark it so that the next submit can be ignored
                // ADDED requirement that form be valid
                if ($form.valid()) {
                    $form.data('submitted', true);
                }
            }
        });
        // Keep chainability
        return this;
    },
});

// Change timepicker default options
if ($.fn.timepicker) {
    $.extend($.fn.timepicker.defaults, {
        showMeridian: false,
        defaultTime: false,
    });
}

// Change validator messages method
$.extend(jQuery.validator, {
    messages: {
        required: function (p, e) {
            return $.validator.format('{0}は必須項目です。', [
                $(e).data('label'),
            ]);
        },
        maxlength: function (p, e) {
            return $.validator.format(
                '{0}は「{1}」文字以下で入力してください。（現在{2}文字）',
                [$(e).data('label'), p, $(e).val().length]
            );
        },
        minlength: function (p, e) {
            return $.validator.format(
                '{0}は「{1}」文字以上で入力してください。（現在{2}文字）',
                [$(e).data('label'), p, $(e).val().length]
            );
        },
        checkNumeric: function (p, e) {
            return $.validator.format('{0}は半角数字で入力してください。', [
                $(e).data('label'),
            ]);
        },
        checkKatakana2ByteAndCharacter: function (p, e) {
            return $.validator.format('{0}は全角カナで入力してください。', [
                $(e).data('label'),
            ]);
        },
        checkCapital1Byte: function (p, e) {
            return $.validator.format('{0}は半角英大文字で入力してください。', [
                $(e).data('label'),
            ]);
        },
        date_month: function (p, e) {
            return $.validator.format('{0}は年月を正しく入力してください。', [
                $(e).data('label'),
            ]);
        },
        greaterThanDate: $.validator.format('{0}は{1}以降の日時を選択してください。'),
        lessThanDate: $.validator.format('{0}は{1}以降の日時を選択してください。'),
        checkExceedMonthByFromTo: $.validator.format('{0}と{1}は24ヶ月以内に設定してください。'),
        checkHiragana2Byte: function (p, e) {
            return $.validator.format('{0}は全角ひらがなで入力してください。', [
                $(e).data('label'),
            ]);
        },
        checkValidEmailRFC: function (p, e) {
            return $.validator.format('メールアドレスを正しく入力してください。');
        },
        checkCharacterlatin: function (p, e) {
            return $.validator.format('{0}は半角英数で入力してください。', [
                $(e).data('label'),
            ]);
        },
        passwordEqualTo: $.validator.format('新しいパスワードと確認用パスワードが一致しません。'),
        checkKatakana2Byte: function (p, e) {
            return $.validator.format('{0}は全角カナで入力してください。', [
                $(e).data('label'),
            ]);
        },
        checkDateOfBirth: $.validator.format('未成年(18歳未満の方)は申込することができません。'),
        checkCustomerUnique: $.validator.format('入力された内容と一致するアカウントが既に存在します。同一人物で複数のアカウントの作成は出来かねます。'),
        checkCustomerUniqueByMail: $.validator.format('入力されたメールアドレスは既に使用されています。別のメールアドレスを入力してください。'),
        checkAllExplainConfirmImportant: $.validator.format('重要事項は必ず説明を行なってください。'),
        checkExplainConfirmImportant: $.validator.format('上記いずれかの確認を必ず行ってください。'),
        checkCustomerCanceledContract: $.validator.format('一度解約された場合、同じ物件での再申し込みはできかねます。'),
        checkBuildingUnique: $.validator.format('入力された内容に一致する建物が既に存在します。同じ建物は複数登録できません。'),
        checkPasswordIsNotSameCustomerId: $.validator.format('パスワードとお客様IDが同じです。違うパスワードを入力してください。'),
    },
});

$.validator.setDefaults({
    errorClass: 'error-message',
    errorElement: 'div',
    ignore: ':hidden:not(.chosen-select)',
    // add default behaviour for on focus out
    onfocusout: function (element) {
        this.element(element);
    },
    submitHandler: function (form) {
        _common.showLoading();
        form.submit();
    },
    errorPlacement: function (error, element) {
        if ($(element).hasClass('chosen-select')) {
            $(element).parent('div').append(error);
        } else {
            // if an i tag is present and parent is a span
            // then this likely has an icon dependent on the size of that span
            // insert after the span to avoid misplacing the icon
            if ($(element).siblings("i").length >= 1 && $(element).parent("span").length >= 1) {
                error.insertAfter($(element).parent());
            }
            else {
                error.insertAfter(element);
            }
        }
    },
});

//=================================================//
// Override check length method for compatibility with PHP
$.validator.methods.minlength = function (value, element, param) {
    var length = $.isArray(value)
        ? value.length
        : customGetLength(value, element);
    return this.optional(element) || length >= param;
};

$.validator.methods.maxlength = function (value, element, param) {
    var length = $.isArray(value)
        ? value.length
        : customGetLength(value, element);
    return this.optional(element) || length <= param;
};

$.validator.methods.exactlength = function (value, element, param) {
    var length = $.isArray(value)
        ? value.length
        : customGetLength(value, element);
    return this.optional(element) || length === param;
};

function customGetLength(value, element) {
    if (element) {
        switch (element.nodeName.toLowerCase()) {
            case 'select':
                return $('option:selected', element).length;
            case 'input':
                if (checkable(element)) {
                    return this.findByName(element.name).filter(':checked')
                        .length;
                }
        }
    }
    // Look for any "\n" occurences
    var matches = value.match(/\n/g);
    // Duplicate count for break line (for matching with PHP)
    var addLength = matches ? matches.length : 0;
    return value.length + addLength;
}

function checkable(element) {
    return /radio|checkbox/i.test(element.type);
}
//=================================================//

var lastLimit = new Date('2200/12/31');
var firstLimit = new Date('1700/01/01');
$.validator.methods.date = function (value, element, param) {
    var inputDate = new Date(value);
    return (
        value == '' ||
        (moment(value, 'YYYY/MM/DD', true).isValid() &&
            firstLimit <= inputDate &&
            inputDate <= lastLimit)
    );
};

$.validator.addMethod('datetime', function (value, element, params) {
    var inputDate = new Date(value);
    return (
        value == '' ||
        (moment(value, 'YYYY/MM/DD H:mm:ss', true).isValid() &&
            firstLimit <= inputDate &&
            inputDate <= lastLimit) ||
        (moment(value, 'YYYY/MM/DD H:mm', true).isValid() &&
            firstLimit <= inputDate &&
            inputDate <= lastLimit) ||
        (moment(value, 'YYYY/MM/DD', true).isValid() &&
            firstLimit <= inputDate &&
            inputDate <= lastLimit)
    );
});

$.validator.addMethod('date_time', function (value, element, params) {
    return (
        value == '' ||
        moment(value, 'YYYY/MM/DD H:mm:ss', true).isValid() ||
        moment(value, 'YYYY/MM/DD H:mm', true).isValid()
    );
});

$.validator.addMethod('date_month', function (value, element, params) {
    return (
        value == '' ||
        moment(value, 'YYYY/MM', true).isValid() ||
        moment(value, 'YYYY/MM', true).isValid()
    );
});

$.validator.addMethod('futureDate', function (value, element, params) {
    return (
        value.length > 0 &&
        moment().startOf('date').isSameOrBefore(moment(value, 'YYYY/MM/DD'))
    );
});

$.validator.addMethod('latin', function (value, element) {
    return (
        this.optional(element) ||
        /^[a-zA-Z0-9~`!@#$%^&*()-_=+<>?,./:;"'{}]*$/.test(value)
    );
});

$.validator.addMethod('mail_valid', function (value, element, dependent) {
    if (
        ($(dependent).val() != '' && value == '') ||
        ($(dependent).val() == '' && value != '')
    ) {
        return false;
    }
    var email = $(dependent).val().concat('@');
    email = email.concat(value);
    return (
        this.optional(element) ||
        /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/i.test(
            email
        )
    );
});

//custom validation method for file size
$.validator.addMethod('filesize', function (value, element, param) {
    return (
        this.optional(element) || element.files[0].size <= param * 1024 * 1024
    );
});

$.validator.addMethod('fixedFileSize', function (value, element, param) {
    return value
        ? this.optional(element) || element.files[0].size <= 10 * 1024 * 1024
        : true;
});

$.validator.addMethod('check2Byte', function (value, element) {
    //return ! value.match(/^[^\u3000-\u303f\u3040-\u309f\u30a0-\u30ff\uff00-\uff9f\u4e00-\u9faf\u3400-\u4dbf]+$/);
    if (value.length > 0)
        return value.match(/^[^\x01-\x7E\xA1-\xDF]+$/)
            ? value.match(/^[ｱ-ﾝﾞﾟｧ-ｫｬ-ｮｰ｡｢｣､]+$/)
                ? false
                : true
            : false;
    else return true;
});

$.validator.addMethod('check2ByteHfS', function (value, element) {
    for (var i = 0; i < value.length; i++) {
        var unicode = value.charCodeAt(i);
        if (unicode >= 0xff61 && unicode <= 0xff9f) {
            //hankaku kana
            return false;
        } else if (
            (unicode >= 0x4e00 && unicode <= 0x9fcf) || // CJK統合漢字
            (unicode >= 0x3400 && unicode <= 0x4dbf) || // CJK統合漢字拡張A
            (unicode >= 0x20000 && unicode <= 0x2a6df) || // CJK統合漢字拡張B
            (unicode >= 0xf900 && unicode <= 0xfadf) || // CJK互換漢字
            (unicode >= 0x2f800 && unicode <= 0x2fa1f) ||
            (unicode >= 0x30a0 && unicode <= 0x30ff) || //check kana 2 byte.
            (unicode >= 0x3040 && unicode <= 0x309f) || //check hiragana 2 byte.
            unicode == 0x0020 || //space 1 byte
            unicode == 0x3000 || //space 2 byte
            (unicode >= 0xff00 && unicode <= 0xfff0) //alphabet 2 byte
        ) {
        } else {
            return false;
        }
    }
    return true;
});

$.validator.addMethod('rangeEmail', function (value, element, param) {
    //!#$%&'*+-/=?^_`{|}~.
    return (
        this.optional(element) ||
        /^[0-9a-zA-Z\#\!\$\%\(\)\*\+\-\.\/\:\;\?\'\=\`\|\&\@\^\[\]\_\{\}\~]{6,75}$/i.test(
            value
        )
    );
});

$.validator.addMethod('checkKanji', function (value, element) {
    for (var i = 0; i < value.length; i++) {
        var unicode = value.charCodeAt(i);
        if (
            (unicode >= 0x4e00 && unicode <= 0x9fcf) || // CJK統合漢字
            (unicode >= 0x3400 && unicode <= 0x4dbf) || // CJK統合漢字拡張A
            (unicode >= 0x20000 && unicode <= 0x2a6df) || // CJK統合漢字拡張B
            (unicode >= 0xf900 && unicode <= 0xfadf) || // CJK互換漢字
            (unicode >= 0x2f800 && unicode <= 0x2fa1f) ||
            (unicode >= 0x30a0 && unicode <= 0x30ff) || //check kana 2 byte.
            (unicode >= 0x3040 && unicode <= 0x309f) //check hiragana 2 byte.
        ) {
        } else {
            return false;
        }
    }
    return true;
});

$.validator.addMethod('checkKatakana', function (value, element) {
    for (var i = 0; i < value.length; i++) {
        var unicode = value.charCodeAt(i);
        if (
            (unicode >= 0x30a0 && unicode <= 0x30ff) ||
            unicode == 0x0020 || //space 1 byte
            unicode == 0x3000
        ) {
        } else {
            return false;
        }
    }
    return true;
});

$.validator.addMethod('checkKatakanaV2', function (value, element) {
    for (var i = 0; i < value.length; i++) {
        var unicode = value.charCodeAt(i);
        if (
            (unicode >= 0x30a0 && unicode <= 0x30ff) ||
            unicode == 0x0020 || //space 1 byte
            unicode == 0x3000 ||
            unicode == 65288 ||
            unicode == 65289 // 2 byte - char: （）
        ) {
        } else {
            return false;
        }
    }
    return true;
});

$.validator.addMethod('checkKatakana1Byte2Byte', function (value, element) {
    var result = true;
    if (value.length > 0) {
        result = value.match(/^[\uFF65-\uFF9F\u30A0-\u30FF.\)\(\/\-\　]+$/)
            ? true
            : false;
    }
    return result;
});

$.validator.addMethod(
    'checkKatakana2ByteAndCharacter',
    function (value, element) {
        var result = true;
        if (value.length > 0) {
            result = value.match(/^[\u30A0-\u30FF]+$/) ? true : false;
        }
        return result;
    }
);

$.validator.addMethod('checkCharacterlatin', function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]*$/.test(value);
});

$.validator.addMethod('checkAlphabet', function (value, element) {
    return this.optional(element) || /^[a-zA-Z]*$/.test(value);
});

$.validator.addMethod('checkNumeric', function (value, element) {
    return this.optional(element) || /^[0-9]*$/.test(value);
});

$.validator.addMethod('digitsCustom', function (value, element) {
    return this.optional(element) || /^[0-9-]*$/.test(value);
});

$.validator.addMethod('checkValidEmailRFC', function (value, element) {
    var matchRules = new RegExp(
        /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    );
    var latinRule = /^[a-zA-Z0-9~`!@#$%^&*()-_=+<>?,./:;"'{}]*$/.test(value);
    return this.optional(element) || (matchRules.test(value) && latinRule);
});

$.validator.addMethod('mail_valid_RFC', function (value, element, dependent) {
    if (
        ($(dependent).val() != '' && value == '') ||
        ($(dependent).val() == '' && value != '')
    ) {
        return false;
    }
    var email = $(dependent).val().concat('@');
    email = email.concat(value);
    var matchRules = new RegExp(
        /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    );
    var latinRule = /^[a-zA-Z0-9~`!@#$%^&*()-_=+<>?,./:;"'{}]*$/.test(email);
    return this.optional(element) || (matchRules.test(email) && latinRule);
});

$.validator.addMethod('greaterThanDate', function (value, element, params) {
    if ($(params).val().length > 0 && value.length > 0) {
        if (!/Invalid|NaN/.test(new Date(value))) {
            if (new Date(value) <= new Date($(params).val())) {
                if ($(params).hasClass('error-message')) {
                    $(params).removeClass('error-message');
                    $(params).next().remove();
                }
            }
            return new Date(value) <= new Date($(params).val());
        }

        return (
            (isNaN(value) && isNaN($(params).val())) ||
            Number(value) > Number($(params).val())
        );
    } else {
        return true;
    }
});

$.validator.addMethod(
    'greaterThanDateUpgrade',
    function (value, element, params) {
        if ($(params).val().length > 0 && value.length > 0) {
            if (
                moment(value, 'YYYY/MM/DD', true).isValid() &&
                moment($(params).val(), 'YYYY/MM/DD', true).isValid()
            ) {
                if (new Date(value) <= new Date($(params).val())) {
                    if ($(params).hasClass('error-message')) {
                        $(params).removeClass('error-message');
                        $(params).next().remove();
                    }
                }
                return new Date(value) <= new Date($(params).val());
            }
        }
        return true;
    }
);

$.validator.addMethod('lessThanDate', function (value, element, params) {
    if ($(params).val().length > 0 && value.length > 0) {
        if (
            moment(value, 'YYYY/MM/DD', true).isValid() &&
            moment($(params).val(), 'YYYY/MM/DD', true).isValid()
        ) {
            if (new Date(value) >= new Date($(params).val())) {
                if ($(params).hasClass('error-message')) {
                    $(params).removeClass('error-message');
                    $(params).next().remove();
                }
            }
            return new Date(value) >= new Date($(params).val());
        }
    }
    return true;
});

$.validator.addMethod('checkCapital1Byte', function (value, element) {
    return this.optional(element) || /^[A-Z]*$/.test(value);
});

$.validator.addMethod('checkExceedMonthByFromTo', function (value, element, params) {
    var exceedMonth = 25;
    if (typeof $(element).data('exceed-month') !== 'undefined') { // custom exceed month
        exceedMonth = $(element).data('exceed-month');
    }
    const paramValue = $(params).val();
    if (paramValue.length > 0 && value.length > 0) {
        if (
            moment(paramValue, 'YYYY/MM', true).isValid() &&
            moment(value, 'YYYY/MM', true).isValid()
        ) {
            const monthDiff = moment(paramValue, 'YYYY/MM').diff(moment(value, 'YYYY/MM'), 'months', true);
            return Math.abs(monthDiff) >= exceedMonth ? false : true;
        }
    }
    return true;
});

$.validator.addMethod('checkHiragana2Byte', function(value, element) {
    for(let i = 0; i < value.length; i++) {
        let unicode = value.charCodeAt(i);
        if (!(unicode >= 0x3040 && unicode <= 0x309f)) {
            return false;
        }
    }
    return true;
});

$.validator.addMethod('checkKatakana2Byte', function(value, element) {
    var result = true;
    if(value.length > 0){
        result = value.match(/^[・\u30a0-\u30ff　]*$/) ? true : false;
    }
    return result;
});

$.validator.addMethod('checkDateOfBirth', function(value, element) {
    const AGE = 18;
    var year = $('.datedd-year').val();
    var month = $('.datedd-month').val();
    var day = $('.datedd-day').val();
    if (moment(value, 'YYYY/MM/DD', true).isValid()) {
        var valueSplit = value.split('/');
        year = valueSplit[0];
        month = valueSplit[1];
        day = valueSplit[2];
    }
    if (year != '' && month != '' && day != '') {
        var birthdate = new Date();
        birthdate.setFullYear(year, month - 1, day);
        var currentDate = new Date();
        currentDate.setFullYear(currentDate.getFullYear() - AGE);
        return currentDate >= birthdate;
    }
    return true;
});
