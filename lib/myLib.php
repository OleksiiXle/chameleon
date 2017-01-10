<?php
if(count($_FILES) > 0) {
    $errorStr = '';
    $types = array('image/gif', 'image/png', 'image/jpeg');
    if (!in_array($_FILES['Books']['type']['imageFile'], $types))
        $errorStr = 'error: неверный тип файла ' . $_FILES['Books']['name']['imageFile'] . ' *** ';
    if ($_FILES['Books']['size']['imageFile'] > 3500000)
        $errorStr =$errorStr . 'error: файл ' . $_FILES['Books']['name']['imageFile'] .
            ' недопустимого размера - ' . $_FILES['Books']['size']['imageFile'] . ' (max 3500000)';
    if (strlen($errorStr) > 0) {
        echo $errorStr;
        exit;
    }

    $uploaddir = '..\preview\new\\';
    if (file_exists($uploaddir)){
        foreach (glob($uploaddir. '*') as $file) unlink($file);
    }
    $dstName = $uploaddir.$_FILES['Books']['name']['imageFile'];
    if (move_uploaded_file($_FILES['Books']['tmp_name']['imageFile'], $dstName)) {
        echo ($dstName);
    } else {
        echo var_dump($_FILES);
    }
    exit;
}
?>