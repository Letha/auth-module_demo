<?php
    use App\Components\TextEditor;
    require_once ROOT . 'src/views/layouts/header.php';
    $profileSign = TextEditor::mbUcfirst($words['profile']);
    $loginSign = TextEditor::mbUcfirst($words['login']);
    $nameSign = TextEditor::mbUcfirst($words['name']);
    $surnameSign = TextEditor::mbUcfirst($words['surname']);
    $birthDateSign = TextEditor::mbUcfirst($words['birth_date']);
?>

<div class='b-info b-info_form_1'>
    <h1 class='b-info__header'><?=$profileSign?></h1>
    <?php if (isset($userData['hasPersonalPhoto']) && $userData['hasPersonalPhoto']): ?>
        <div class='b-info__image-frame'>
            <img class='b-info__image' src='/ajax/image/personal-photo'>
        </div>
    <?php endif; ?>
    <p class='b-info__line'><?=$loginSign?>: <?=$userData['login']?></p>
    <p class='b-info__line'><?=$nameSign?>: <?=$userData['name']?></p>
    <p class='b-info__line'><?=$surnameSign?>: <?=$userData['surname']?></p>
    <p class='b-info__line'><?=$birthDateSign?>: <?=$userData['birthDate']?></p>
    <a class='f-exit b-info__link b-info__link_side b-link b-link_palette_purple' href='#'><?=$words['do_exit']?></a>
</div>

<script>
    dynamic = {};
    dynamic.dictionary = {};
    dynamic.dictionary.error500 = '<?=$words['500_message']?>';
</script>

<?php
    require_once ROOT . 'src/views/layouts/footer.php';
?>