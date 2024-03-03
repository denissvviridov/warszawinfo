<?php


class MainController extends Container
{
    protected PDO $pdo;
    protected \Twig\Environment $twig;


    public function __construct()
    {
        $this->pdo = $this->connectDB();
        $this->twig = $this->twig();
    }

    public function index(): void
    {

        $pdo = $this->pdo->prepare("select * from posts left join categories on posts.category = categories.category_en order by posts.id desc limit 0, 5");
        $pdo->execute();
        $posts = $pdo->fetchAll(PDO::FETCH_ASSOC);

        $pdo = $this->pdo->prepare("select * from posts where check_top = 1 order by id desc limit 5 ");
        $pdo->execute();
        $top_posts = $pdo->fetchAll(PDO::FETCH_ASSOC);

        $pdo = $this->pdo->prepare('select * from posts order by id desc limit 5');
        $pdo->execute();
        $promo = $pdo->fetchAll(PDO::FETCH_ASSOC);

        $pdo = $this->pdo->prepare('select * from categories');
        $pdo->execute();
        $categories = $pdo->fetchAll(PDO::FETCH_ASSOC);

        $twig = $this->twig();
        $html = $twig->render('client/layouts/header.twig');
        $html .= $twig->render('client/content/main.twig', ['top' => $top_posts, 'posts' => $posts, 'promo' => $promo, 'categories' => $categories]);
        $html .= $twig->render('client/layouts/footer.twig');
        echo $html;
    }

    public function singlePost(): void
    {

         $post_id = ARGUMENT;

        $pdo = $this->pdo->prepare('SELECT * FROM posts JOIN categories on posts.category = categories.category_en  where posts.id = ?');
        $pdo->bindParam(1, $post_id);
        $pdo->execute();
        $post = $pdo->fetch(PDO::FETCH_ASSOC);

        $pdo = $this->pdo->prepare('select * from categories');
        $pdo->execute();
        $categories = $pdo->fetchAll(PDO::FETCH_ASSOC);

        $pdo = $this->pdo->prepare('select * from comments  where post_id = ? order by comments.id desc limit 0,2');
        $pdo->bindParam(1, $post_id);
        $pdo->execute();
        $comments = $pdo->fetchAll(PDO::FETCH_ASSOC);


        $pdo = $this->pdo->prepare('select * from posts join categories on posts.category = categories.category_en order by posts.id desc limit 6');
        $pdo->execute();
        $list = $pdo->fetchAll(PDO::FETCH_ASSOC);

        $html = $this->twig->render('client/layouts/header.twig');
        $html .= $this->twig->render('client/content/singlePost.twig', ['post' => $post, 'list' => $list, 'categories' => $categories, 'comments' => $comments]);
        $html .= $this->twig->render('client/layouts/footer.twig');
        echo $html;

    }




    public function postsShow(): void
    {
        $category = $_GET['category'];

        $pdo = $this->pdo->prepare("select *  from posts left join categories on posts.category = categories.category_en  where category = ? order by posts.id desc");
        $pdo->bindParam(1, $category);
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($result);

    }

    public function postsOnCategory(): void
    {

        $category = ARGUMENT;

        $pdo = $this->pdo->prepare('select * from posts left join categories on posts.category = categories.category_en where category = ? order by posts.id desc limit 0, 2');
        $pdo->bindParam(1, $category);
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);

        $btn_show_more = '';

        if (count($result) > 1) {
            $btn_show_more = "<div class='btn_wrap'>
                            <button id='btn-show_more-first' data-category=$category class='btn btn-success show_more-first'>Показать еще...</button>
                 </div>";
        }

        $pdo = $this->pdo->prepare('select * from categories where category_en = ?');
        $pdo->bindParam(1, $category);
        $pdo->execute();
        $category_name = $pdo->fetch(PDO::FETCH_ASSOC);


        $html = $this->twig->render('client/layouts/header.twig');
        $html .= $this->twig->render('client/content/allPostsCategory.twig', ['posts' => $result, 'category' => $category_name, 'btn' => $btn_show_more]);
        $html .= $this->twig->render('client/layouts/footer.twig');
        echo $html;

    }


    public function handlerLike(): void
    {
        $post_id = $this->getIdPost();
        $updateLikesCount = $this->incrementLikesCount($post_id);

        header('Content-Type: application/json');
        echo json_encode(['postId' => $post_id, 'likesCount' => $updateLikesCount]);
    }

    public function getIdPost(): string
    {
        return $_GET['post_id'];
    }

    public function incrementLikesCount($postId): int
    {

        $pdo = $this->pdo->prepare('update posts set likes = likes + 1 where id = :postId');
        $pdo->bindParam(':postId', $postId, PDO::PARAM_INT);
        $pdo->execute();

        return $this->getCurrentLikesCount($postId);

    }

    public function getCurrentLikesCount($postId): int
    {
        $pdo = $this->pdo->prepare('SELECT likes FROM posts WHERE id = :postId');
        $pdo->bindParam(':postId', $postId, PDO::PARAM_INT);
        $pdo->execute();
        $result = $pdo->fetch(PDO::FETCH_ASSOC);

        return isset($result['likes']) ? (int)$result['likes'] : 0;
    }

    public function showMoreCategoryPost(): void
    {

        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 2;

        $category = $_GET['category'];

        $pdo = $this->pdo->prepare("select * from posts left join categories on posts.category = categories.category_en where categories.category_en = ?  order by posts.id desc limit $offset, $limit");
        $pdo->bindParam(1, $category);
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($result);

    }


    public function createComment()
    {
        $post_id = $_GET['post_id'];
        $comment = $_POST['comment'];

        $pdo = $this->pdo->prepare('insert into comments (comment, post_id) values (?,?)');
        $pdo->bindParam(1, $comment);
        $pdo->bindParam(2, $post_id);
        $pdo->execute();

        echo 3;

    }

    public function showMoreComments()
    {
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 2;
        $post_id = $_GET['post_id'];

        $pdo = $this->pdo->prepare("SELECT * FROM comments WHERE post_id = ? limit $offset,$limit");
        $pdo->execute([$post_id]);
        $comments = $pdo->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($comments);

    }


    public function error(): void
    {
        $twig = $this->twig();
        $html = $twig->render('errorPage.twig');
        echo $html;
    }


}