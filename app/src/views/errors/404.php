<?php
    use App\Components\TextEditor;
    require_once ROOT . 'src/views/layouts/header.php';
    $errorWord = TextEditor::mbUcfirst($words['error']);
    $errorMessage = TextEditor::mbUcfirst($words['404_message']);
    echo "<p>$errorWord 404: $errorMessage.</p>";
    require_once ROOT . 'src/views/layouts/footer.php';
