// initialization
core.controller.initialize();

// define variables
let regForm = document.forms.register,
    enterForm = document.forms.enter,
    langToEnForm = document.forms.langToEn,
    langToRuForm = document.forms.langToRu,
    jRegForm = $(regForm);

// bind event handlers
$([regForm, enterForm]).find('.b-form__link_side')
    .click(core.controller.onClickEnterFormSwitch);
$(enterForm).submit(core.controller.login);

jRegForm.submit(core.controller.register);
jRegForm.find('input[name="login"]').on('change', core.controller.onWrongInput);

jRegForm.find('input[name="password"]').on('input', core.controller.onInputRegisterPassword);
jRegForm.find('input[name="password"]').on('change', core.controller.onWrongInput);

jRegForm.find('input[name="passwordRepeat"]').on('change', core.controller.onWrongInput);
jRegForm.find('input[name="name"]').on('change', core.controller.onWrongInput);
jRegForm.find('input[name="surname"]').on('change', core.controller.onWrongInput);
jRegForm.find('input[name="birthDate"]').on('change', core.controller.onWrongInput);

$([langToEnForm, langToRuForm]).submit(core.controller.onSubmitLangSwitchForm);

$('.f-exit').click(core.controller.exit);

// enable submit buttons
$([regForm, enterForm, langToEnForm, langToRuForm]).find('button[type="submit"]').removeAttr('disabled');