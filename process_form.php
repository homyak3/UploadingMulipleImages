<?php
// Подключаем файл с классом Anime
require_once('classAnime.php');

// Проверяем, была ли отправлена форма методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_FILES && isset($_FILES["image"])) {
        foreach ($_FILES["image"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $extension = pathinfo($_FILES["image"]["name"][$key], PATHINFO_EXTENSION);
                $name = rand(1000000,9999999) . "_small." .  "$extension";
                $tmp_name = $_FILES["image"]["tmp_name"][$key];
                $upload_directory = 'upload/';
                $target_path = $upload_directory . $name;
            }
        }
    }
    $name = $_POST['name'];
    $yearOfRelease = $_POST['yearOfRelease'];
    $genre = $_POST['genre'];
    $type = $_POST['type'];
    $numberOfEpisodes = $_POST['numberOfEpisodes'];
    $rating = $_POST['rating'];
    $description = $_POST['description'];
    $image = $target_path;

    // Создаем объект класса Anime
    $anime = new Anime($name, $yearOfRelease, $genre, $type, $numberOfEpisodes, $rating, $description, $image);

    // Вызываем метод NewAnime() для добавления данных в базу данных
    if ($anime->NewAnime()) {
        // Перенаправляем пользователя на страницу с результатом
        header("Location: index.php?success=true");
        exit();
    } else {
        // Перенаправляем пользователя на страницу с результатом
        header("Location: index.php?success=false");
        exit();
    }
}
?>