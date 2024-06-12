<?php
include_once "connection.php";
class Anime
{
    private $name;
    private $yearOfRelease;
    private $genre;
    private $type;
    private $numberOfEpisodes;
    private $rating;
    private $description;
    private $image;
    private $conn;

    public function __construct($name, $yearOfRelease, $genre, $type, $numberOfEpisodes, $rating, $description, $image)
    {
        $databaseService = new Connection();
        $this->conn = $databaseService->connect();
        $this->name = $name;
        $this->yearOfRelease = $yearOfRelease;
        $this->genre = $genre;
        $this->type = $type;
        $this->numberOfEpisodes = $numberOfEpisodes;
        $this->rating = $rating;
        $this->description = $description;
        $this->image = $image;
    }

    public function NewAnime()
    {
        try {
            $sql = "INSERT INTO `anime`(`name`, `yearOfRelease`, `genre`, `type`, `numberOfEpisodes`, `rating`, `description`) VALUES (?,?,?,?,?,?,?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $this->name);
            $stmt->bindParam(2, $this->yearOfRelease);
            $stmt->bindParam(3, $this->genre);
            $stmt->bindParam(4, $this->type);
            $stmt->bindParam(5, $this->numberOfEpisodes);
            $stmt->bindParam(6, $this->rating);
            $stmt->bindParam(7, $this->description);
            $stmt->execute();
            $animeId = $this->conn->lastInsertId();
            foreach ($this->image as $image) {
                $sql = "INSERT INTO `img_anime`(`path`, `id_img`) VALUES (?,?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $image);
                $stmt->bindParam(2, $animeId);
                $stmt->execute();
            }
            return true;
        } catch (PDOException $e) {
            error_log("Ошибка при выполнении запроса: " . $e->getMessage());
            return false;
        }
    }
}