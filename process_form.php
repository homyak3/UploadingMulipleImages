<?php
// Подключаем файл с классом Anime
require_once('classAnime.php');
$target_path = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $yearOfRelease = $_POST['yearOfRelease'];
    $genre = $_POST['genre'];
    $type = $_POST['type'];
    $numberOfEpisodes = $_POST['numberOfEpisodes'];
    $rating = $_POST['rating'];
    $description = $_POST['description'];
    if (!empty($name) && !empty($yearOfRelease) && !empty($genre) && !empty($type) && !empty($numberOfEpisodes) && !empty($rating) && !empty($description)) {
        if ($_FILES && isset($_FILES["image"])) {
            foreach ($_FILES["image"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $extension = pathinfo($_FILES["image"]["name"][$key], PATHINFO_EXTENSION);
                    $name_img = rand(1000000, 9999999) . "_small." . "$extension";
                    $tmp_name = $_FILES["image"]["tmp_name"][$key];
                    $upload_directory = 'mainUpload/';
                    $path = $upload_directory . $name_img;
                    array_push($target_path, $path);
                    move_uploaded_file($tmp_name, $path);
                }
            }
        }
        $image = $target_path;

        // Создаем объект класса Anime
        $anime = new Anime($name, $yearOfRelease, $genre, $type, $numberOfEpisodes, $rating, $description, $image);

        // Вызываем метод NewAnime() для добавления данных в базу данных
        $anime->NewAnime();
    }
}
?>