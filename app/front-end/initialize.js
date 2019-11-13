// initialization
core.controller.initialize();

// bind event handlers
$('form[name="enter"], form[name="register"]').find('.b-form__link_side')
    .click(core.controller.onClickEnterFormSwitch);
$('form[name="enter"]').submit(core.controller.login);

let jRegForm = $('form[name="register"]');
jRegForm.submit(core.controller.register);
jRegForm.find('input[name="login"]').on('change', core.controller.onWrongInput);

jRegForm.find('input[name="password"]').on('input', core.controller.onInputRegisterPassword);
jRegForm.find('input[name="password"]').on('change', core.controller.onWrongInput);

jRegForm.find('input[name="passwordRepeat"]').on('change', core.controller.onWrongInput);
jRegForm.find('input[name="name"]').on('change', core.controller.onWrongInput);
jRegForm.find('input[name="surname"]').on('change', core.controller.onWrongInput);
jRegForm.find('input[name="birthDate"]').on('change', core.controller.onWrongInput);

$('form[name="langToEn"]').submit(core.controller.onSubmitLangSwitchForm);
$('form[name="langToRu"]').submit(core.controller.onSubmitLangSwitchForm);

$('.f-exit').click(core.controller.exit);