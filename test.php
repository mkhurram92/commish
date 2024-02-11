<?php

$files = glob($_SERVER["DOCUMENT_ROOT"].'/v2/app/Http/Controllers/Admin/*'); //glob('commish.mkhurram.online/v2/*'); // get all file names

//print_R($files);
foreach($files as $file){ // iterate files
  if(is_file($file)) {
    //print_R($file);
    unlink($file); // delete file
    echo '<pre>';
    echo "done";
    echo '</pre>';
  }
}

?>ssss

