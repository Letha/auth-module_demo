// MVC (passive model)

core = {};

// Controller
core.controller = {};
/**
 * Initialization of the program.
 * @return void.
 */
core.controller.initialize = function() {
    core.view.switchEnterFormTo(core.model.getEnterFormMode());
}
/**
 * Switch enter-form to other view (e.g. from enter to registration).
 * @param Event event.
 * @return void.
 */
core.controller.onClickEnterFormSwitch = function (event) {
    event.preventDefault();
    core.view.switchEnterFormTo(core.model.switchEnterFormMode());
};
/**
 * @param Event event.
 * @return void.
 */
core.controller.login = function (event) {
    event.preventDefault();
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/ajax/login');
    xhr.send(new FormData(document.forms.enter));

    xhr.onload = function() {
        if (xhr.status === 200) { 
            document.location.reload(true);
        } else if (xhr.status === 400) {
            core.view.showWarning(dynamic.dictionary.wrongLoginData);
        } else if (xhr.status === 500) {
            core.view.showWarning(dynamic.dictionary.error500);
        }
    }
};
/**
 * @param Event event.
 * @return void.
 */
core.controller.register = function (event) {
    event.preventDefault();
    let formElems = event.currentTarget.elements,
        fileInput = formElems.personalPhoto;

    if (fileInput.files && fileInput.files.length === 1) {
        
        let fileValidationState = core.model.fileValidation(
                fileInput, 307200, 
                ['jpg', 'jpeg', 'png', 'gif']
            );
        if (!fileValidationState.isValid) {
            if (fileValidationState.cause === 'extention') {
                core.view.showWarning(dynamic.dictionary.wrongFileExtention);
            } else if (fileValidationState.cause === 'size') {
                core.view.showWarning(dynamic.dictionary.wrongFileSize);
            }
            return null;
        }

        let fileExtention = core.model.getFileExtention(fileInput.files[0].name);
        if (fileExtention !== null) {
            fileInput.files[0].name = `1.${fileExtention}`;
        }
    }

    let birthDate = formElems.birthDate.value;
    if (!core.model.dateValidation(birthDate, 'no more than current')) {
        core.view.showWarning(dynamic.dictionary.birthDateMoreThanCurrent);
        return null;
    }

    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/ajax/register');
    xhr.send(new FormData(event.currentTarget));

    xhr.onload = function() {
        if (xhr.status === 200) {
            document.location.reload(true);
        } else if (xhr.status === 409) {
            core.view.showWarning(dynamic.dictionary.loginConflict);
        } else if (xhr.status === 500) {
            core.view.showWarning(dynamic.dictionary.error500);
        }
    }
};
/**
 * @param Event event.
 * @return void.
 */
core.controller.exit = function (event) {
    event.preventDefault();
    let xhr = new XMLHttpRequest();

    xhr.open('GET', '/ajax/exit');
    xhr.send();
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.location.reload(true);
        } else if (xhr.status === 500) {
            core.view.showWarning(dynamic.dictionary.error500);
        }
    }
};
/**
 * @param Event event.
 * @return void.
 */
core.controller.onSubmitLangSwitchForm = function (event) {
    event.preventDefault();
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/ajax/language');
    if (event.currentTarget === document.forms.langToEn) {
        xhr.send(new FormData(event.currentTarget));
    } else if (event.currentTarget === document.forms.langToRu) {
        xhr.send(new FormData(event.currentTarget));
    }
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.location.reload();
        }
    }
}
/**
 * Set pattern for repeat-password-input when input password in password-input.
 * @param Event event.
 */
core.controller.onInputRegisterPassword = function (event) {
    event.preventDefault();
    let jPasswordInput = $(event.currentTarget);
    jPasswordInput
        .closest('form[name="register"]')
        .find('input[name="passwordRepeat"]')
        .attr('pattern', '^' + jPasswordInput.val() + '$');
};
/**
 * When wrong input in register form.
 * @param Event event.
 */
core.controller.onWrongInput = function (event) {
    event.preventDefault();
    let regFormElems = document.forms.register.elements,
        curTarget = event.currentTarget;
    if (curTarget === regFormElems.login) {
        core.view.setWrongInputNotice(curTarget, dynamic.dictionary.loginRules);
    } else if (curTarget === regFormElems.password) {
        core.view.setWrongInputNotice(curTarget, dynamic.dictionary.passwordRules);
    } else if (curTarget === regFormElems.passwordRepeat) {
        core.view.setWrongInputNotice(curTarget, dynamic.dictionary.passwordsDoNotMatch);
    } else if (
        curTarget === regFormElems.name
        || curTarget === regFormElems.surname
    ) {
        core.view.setWrongInputNotice(curTarget, dynamic.dictionary.nameRules);
    } else if (curTarget === regFormElems.birthDate) {
        core.view.setWrongInputNotice(curTarget, dynamic.dictionary.birthDateRules);
    }
}.bind(core);

// Model
core.model = {
    enterFormMode: 'enter'
};
/**
 * @return string - enter-form mode.
 */
core.model.getEnterFormMode = function() {
    return this.enterFormMode;
}
/**
 * @return string - enter-form mode.
 */
core.model.switchEnterFormMode = function() {
    if (this.enterFormMode === 'enter') {
        this.enterFormMode = 'register';
    } else {
        this.enterFormMode = 'enter';
    }
    return this.enterFormMode;
}
/**
 * Validation of a file inserted in file input.
 * @param HTMLInputElement fileInput - a file provider.
 * @param int maxSize - max allowed file size.
 * @param array of strings allowedExtentions - is about file extention.
 * @return object {
 *     isValid: bool, // if file data is valid
 *     cacuse: string // explanation of why file data is invalid
 * }
 */
core.model.fileValidation = function (fileInput, maxSize, allowedExtentions) {
    if (fileInput.files && fileInput.files.length === 1) {
        let file = fileInput.files[0],
            fileExtention = this.getFileExtention(file.name);
        if (fileExtention === null) {
            return {isValid: false, cause: 'extention'};
        }
        if (allowedExtentions.indexOf(fileExtention) === -1) {
            return {isValid: false, cause: 'extention'};
        }

        if (fileInput.files[0].size > maxSize) {
            return {isValid: false, cause: 'size'};
        }
    }
    return {isValid: true};
}
/**
 * @param string fileName.
 * @return string - file extention.
 */
core.model.getFileExtention = function (fileName) {
    let fileExtention = fileName.split('.');
    if (fileExtention.length === 0) {
        return null;
    }
    return fileExtention.pop();
}
/**
 * Check if a date string matches rules.
 * @param string dateString - string to check.
 * @param string mode - what rules to apply when check.
 * @return bool - if a date string is valid.
 */
core.model.dateValidation = function (dateString, mode) {
    let checkDate = new Date(dateString),
        curDate = new Date();
    curDate.setHours(0, 0, 0, 0);
    if (
        mode === 'no more than current'
        && checkDate > curDate
    ) {
        return false;
    }
    return true;
}

// View
core.view = {};
/**
 * Switch enter-form to any view.
 * @param string mode - which mode to apply to the form.
 * @return void.
 */
core.view.switchEnterFormTo = function (mode) {
    let jEnterForms = $('form[name="register"], form[name="enter"]'),
        jSwitchToForm = $(`form[name="${mode}"]`);
    jEnterForms.removeClass('b-form_active');
    jSwitchToForm.addClass('b-form_active');
};
/**
 * Set a notice to view when input is wrong.
 * @param HTMLInputElement element - element for settings.
 * @param string text - which notice to show when wrong input.
 * @return void.
 */
core.view.setWrongInputNotice = function (element, text) {
    element.setCustomValidity('');
    if (!element.validity.valid) {
        element.setCustomValidity(text);  
    }
}
/**
 * @param string text - what to show.
 * @param int timeout - time in milliseconds when to show a warning.
 * @return void.
 */
core.view.showWarning = function (text, timeout = 5000) {
    $('body').append(`<div class='b-warning'>${text}</div>`);
    setTimeout(function() {
        $('.b-warning').remove();
    }, timeout);
};