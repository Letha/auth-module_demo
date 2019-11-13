<?php
    use App\Components\TextEditor;
    require_once ROOT . 'src/views/layouts/header.php';
    // Reg form signs.
    $doRegSign = TextEditor::mbUcfirst($words['do_register']);
    $nameSign = TextEditor::mbUcfirst($words['name']);
    $surnameSign = TextEditor::mbUcfirst($words['surname']);
    $birthDateSign = TextEditor::mbUcfirst($words['birth_date']);
    $birthDateFormatSign = TextEditor::mbUcfirst($words['birth_date_format']);
    $personalPhotoSign = TextEditor::mbUcfirst($words['personal_photo']);
    $symbolSign = TextEditor::mbUcfirst($words['symbol']);
    $notMoreThanSign = TextEditor::mbUcfirst($words['not_more_than']);
    $notLessThanSign = TextEditor::mbUcfirst($words['not_less_than']);
    $acceptedSymbolsSign = TextEditor::mbUcfirst($words['accepted_symbols']);
    $passwordsDoNotMatchSign = TextEditor::mbUcfirst($words['passwords_do_not_match']);
    // Enter form signs.
    $doEnterSign = TextEditor::mbUcfirst($words['do_enter']);
    $loginSign = TextEditor::mbUcfirst($words['login']);
    $passwordSign = TextEditor::mbUcfirst($words['password']);
?>

<form class='b-form b-form_form_1' name='register' enctype='multipart/form-data'>
    <label class='b-form__label b-form__label_text'><?=$loginSign?>*
        <input class='b-form__input_text' type='text' name='login' required 
            pattern='^[a-zA-Z0-9]{1,30}$' maxlength='30'>
    </label>
    <label class='b-form__label b-form__label_text'><?=$passwordSign?>*
        <input class='b-form__input_text' type='password' name='password' required 
            pattern='^.{1,30}$' maxlength='30'>
    </label>
    <label class='b-form__label b-form__label_text'><?=$words['repeat_of_password']?>*
        <input class='b-form__input_text' type='password' name='passwordRepeat' required maxlength='30'>
    </label>
    <label class='b-form__label b-form__label_text'><?=$nameSign?>*
        <input class='b-form__input_text' type='text' name='name' required 
            pattern='^[a-zA-Zа-яА-я \-]{1,30}$' maxlength='30'>
    </label>
    <label class='b-form__label b-form__label_text'><?=$surnameSign?>*
        <input class='b-form__input_text' type='text' name='surname' required 
            pattern='^[a-zA-Zа-яА-я \-]{1,30}$' maxlength='30'>
    </label>
    <label class='b-form__label b-form__label_text'><?=$birthDateSign?>*
        <input class='b-form__input_text' type='text' name='birthDate' placeholder='<?=$words['birth_date_format']?>'
            required pattern='^(19|20)[0-9]{2}-(0[1-9]{1}|1[0-2]{1})-(0[1-9]{1}|[12]{1}[0-9]{1}|3[01]{1})$'>
    </label>

    <label class='b-form__label'><?=$personalPhotoSign?>
    <br>(png/jpg/jpeg/gif, <?=$words['not_more_than']?> 300<?=$words['kb']?>)
    <br>
        <input type='hidden' name='MAX_FILE_SIZE' value='307200'>
        <input class='b-form__input_file' type='file' name='personalPhoto' accept='.png, .jpeg, .jpg, .gif'>
    </label>
    
    <p class='b-form__notice'><?=$symbolSign?> * <?=$words['means_required_fields']?>.</p>
    <button class='b-form__button b-button b-button_form_1 b-button_palette_purple' type='submit' disabled>
        <?=$doRegSign?>
    </button>
    <a class='b-form__link b-form__link_side b-link b-link_palette_purple' href='#'><?=$words['do_enter']?></a>
</form>

<form class='b-form b-form_form_1' name='enter' enctype='multipart/form-data'>
    <label class='b-form__label b-form__label_text'><?=$loginSign?>
        <input class='b-form__input_text' type='text' name='login' required pattern='^[a-zA-Z0-9]*$' maxlength='30'>
    </label>
    <label class='b-form__label b-form__label_text'><?=$passwordSign?>
        <input class='b-form__input_text' type='password' name='password' required maxlength='30'>
    </label>
    
    <button class='b-form__button b-button b-button_form_1 b-button_palette_purple' type='submit' disabled>
        <?=$doEnterSign?>
    </button>
    <a class='b-form__link b-form__link_side b-link b-link_palette_purple' href='#'><?=$words['do_register']?></a>
</form>

<script>
    dynamic = {};
    dynamic.dictionary = {};
    dynamic.dictionary.loginRules = '<?=$acceptedSymbolsSign?>: a-z, A-Z, 0-9. <?=$notMoreThanSign?> 30.';
    dynamic.dictionary.passwordRules = '<?=$notMoreThanSign?> 30.';
    dynamic.dictionary.passwordsDoNotMatch = '<?=$passwordsDoNotMatchSign?>.';
    dynamic.dictionary.nameRules = 
        '<?=$acceptedSymbolsSign?>: a-z, A-Z, а-я, А-я, -, <?=$words['space']?>. <?=$notMoreThanSign?> 30.';
    dynamic.dictionary.birthDateRules = '<?=$birthDateFormatSign?>. <?=$notLessThanSign?> 1900.';
    dynamic.dictionary.wrongFileExtention = '<?=$words['wrong_file_extention']?>';
    dynamic.dictionary.wrongFileSize = '<?=$words['wrong_file_size']?>';
    dynamic.dictionary.wrongLoginData = '<?=$words['wrong_authorization_data']?>';
    dynamic.dictionary.error500 = '<?=$words['500_message']?>';
    dynamic.dictionary.birthDateMoreThanCurrent = '<?=$words['birth_date_more_current']?>';
    dynamic.dictionary.loginConflict = '<?=$words['login_conflict']?>';
</script>

<?php
    require_once ROOT . 'src/views/layouts/footer.php';
?>