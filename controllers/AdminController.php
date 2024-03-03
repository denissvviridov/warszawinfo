<?php


use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminController extends Container
{

    protected $twig;
    protected $pdo;

    public function __construct()
    {
        $this->twig = $this->twig();
        $this->pdo = $this->connectDB();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function index(): void
    {

        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            $this->panel();
        } else {
            echo $this->twig->render('admin/layouts/login.twig');
        }

    }

    public function panel(): void
    {
        $twig = $this->twig();

        $pdo = $this->pdo->prepare('select * from posts order by id desc');
        $pdo->execute();
        $posts = $pdo->fetchAll(PDO::FETCH_ASSOC);

        $pdo = $this->pdo->prepare('select * from categories');
        $pdo->execute();
        $categories = $pdo->fetchAll(PDO::FETCH_ASSOC);


        if (isset($_POST['create_post'])) {

            $post_title = $_POST['post_title'];
            $post_description = $_POST['post_description'];
            $publish_date = date('Y-m-d _ h-m');
            $category = $_POST['select_category'];

            if ($_FILES['image']) {
                if ($_FILES['image']['type'] == 'image/jpeg' || $_FILES['image']['type'] == 'image/png' || $_FILES['image']['type'] == 'image/jpg') {
                    $res = explode('.', $_FILES['image']['name']);
                    $res = array_reverse($res);
                    $filename = md5(uniqid()) . "." . $res[0];
                    move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $filename);
                }
            }


            $is_checked = isset($_POST['top']) ? 1 : 0;
            $zero = 0;

            $pdo = $this->pdo->prepare('insert into posts (title,description,img_path,publish_date,check_top,category) values (?,?,?,?,?,?)');

            $pdo->bindParam(1, $post_title);
            $pdo->bindParam(2, $post_description);
            $pdo->bindParam(3, $filename);
            $pdo->bindParam(4, $publish_date);

            if (isset($_POST['top'])) {
                $pdo->bindParam(5, $is_checked);
            } else {
                $pdo->bindParam(5, $zero);

            }

            $pdo->bindParam(6, $category);

            if ($pdo->execute()) {
                $this->success('Пост успешно создан');
            }

        } else {
            $content = $twig->render('admin/content/addPost.twig', ['posts' => $posts, 'categories' => $categories]);
            $panel = $twig->render('admin/layouts/panel.twig', ['content' => $content]);
            echo $panel;
        }

    }


    public function category(): void
    {

        if (isset($_POST['add_category'])) {
            $category_en = $_POST['category_en'];
            $category_ru = $_POST['category_ru'];

            $pdo = $this->pdo->prepare('insert into categories (category_en,category_ru) values (?,?)');
            $pdo->bindParam(1, $category_en);
            $pdo->bindParam(2, $category_ru);

            if ($pdo->execute()) {
                $this->success('Категория  успешно добавлена');
            }

        }


        $content = $this->twig->render('admin/content/addCategory.twig');
        $panel = $this->twig->render('admin/layouts/panel.twig', ['content' => $content]);
        echo $panel;
    }

    public function telegram(): void
    {


        if (isset($_POST['add_on_telegram'])) {

            $botToken = '5850968354:AAE3FTtcnX0SiZblNbjRY-zhExs9RD_tX10';
            $chatId = '-1001954456296';


            $postTitle = $_POST['title'];
            $postContent = $_POST['content'];
            $link = $_POST['link'];

            $fileName = $_FILES['image']['tmp_name'];

            $telegramApiUrl = "https://api.telegram.org/bot$botToken/sendPhoto";

            $message = "<b>$postTitle</b>\n\n$postContent\n\n<i>Читайте продолжение: $link</i>";

            $data = array(
                'chat_id' => $chatId,
                'photo' => new CURLFile($fileName),
                'caption' => $message,
                'parse_mode' => 'HTML',
            );


            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $telegramApiUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            if ($response === false) {
                $this->success('Ошибка отправки поста в телеграм');
            } else {
                $this->success('Пост в телеграм успешно отправлен');
            }

        } else {

            $pdo = $this->pdo->prepare('select * from posts');
            $pdo->execute();
            $posts = $pdo->fetchAll(PDO::FETCH_ASSOC);

            $content = $this->twig->render('admin/content/telegram.twig',['posts'=>$posts]);
            $panel = $this->twig->render('admin/layouts/panel.twig', ['content' => $content]);
            echo $panel;
        }

    }


    public function login(): void
    {
        $login = $_POST['login'];

        $pdo = $this->pdo->prepare('select * from admin where login = ?');
        $pdo->bindParam(1, $login);
        $pdo->execute();
        $array = $pdo->fetch(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            $_SESSION['admin'] = true;
            $this->index();
        } else {

            $this->redirect('/admin');
        }

    }


    public function redirect($url)
    {
        header("location: $url");
    }

    public function exit(): void
    {
        unset($_SESSION['admin']);
        $this->redirect('/');
    }



    public function success($text): void
    {
        $content = $this->twig->render('admin/content/success.twig', ['message' => $text]);
        $panel = $this->twig->render('admin/layouts/panel.twig', ['content' => $content]);
        echo $panel;
    }
}


