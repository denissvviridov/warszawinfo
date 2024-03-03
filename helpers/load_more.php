<?php


//$servername = "localhost";
//$username = "root";
//$password = "qwerty1234";
//$dbname = "news";


$servername = 'mysql8';
$username = '37700246_news';
$dbname = '37700246_news';
$password = 'qwerty1234!';


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;

$sql = "SELECT * FROM posts left join categories on posts.category = categories.category_en order by posts.id desc LIMIT $offset, $limit";
$result = $conn->query($sql);
$posts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = array(
            'title' => $row['title'],
            'id' => $row['id'],
            'category_ru' => $row['category_ru'],
            'description' =>$row['description'],
            'img_path' => $row['img_path'],
            'publish_date' =>$row['publish_date']
        );
    }
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($posts);