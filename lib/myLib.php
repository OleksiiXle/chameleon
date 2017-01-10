<?php
if(count($_FILES) > 0) {
    $uploaddir = '..\preview\new\\';
    if (file_exists($uploaddir)){
        foreach (glob($uploaddir. '*') as $file) unlink($file);
    }
 //   $dstName = $uploaddir.$_FILES['Books']['newPreview']['name'];
   // if (move_uploaded_file($_FILES['Books']['newPreview']['tmp_name'], $dstName)) {
    $dstName = $uploaddir.$_FILES['Books']['name']['imageFile'];
    if (move_uploaded_file($_FILES['Books']['tmp_name']['imageFile'], $dstName)) {
        //copy($uploaddir.$_FILES['newPreview']['name'], $uploaddir . $dstName);
        //unlink($uploaddir.$_FILES['newPreview']['name']);
      //  echo ($uploaddir . $dstName);
        echo ($dstName);
    } else {
        //echo ('ERRORS UPLOAD FILE');
        echo var_dump($_FILES);
    }
    exit;
}
?>