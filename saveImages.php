<?php
if ($_FILES && isset($_FILES["image"])) {
    foreach ($_FILES["image"]["error"] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES["image"]["name"][$key], PATHINFO_EXTENSION);
            $name = rand(1000000,9999999) . "_small." .  "$extension";
            $tmp_name = $_FILES["image"]["tmp_name"][$key];
            $upload_directory = 'upload/';
            $target_path = $upload_directory . $name;
            if (move_uploaded_file($tmp_name, $target_path)) {
                $imagePaths["images"][] = $target_path;
            } else {
                echo "Ошибка при перемещении файла $name в папку upload.";
            }
        } else {
            echo "Произошла ошибка при загрузке файла: " . $_FILES["image"]["error"][$key];
        }
    }
    echo json_encode($imagePaths);
}