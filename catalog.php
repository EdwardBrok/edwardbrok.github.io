<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог - Атмосфера Знаний</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php

    function get_article_tile2(string $id)
    {
        require('.conn');

        $conn->real_query('SELECT * FROM article WHERE id = '.$id.';');
        $res = $conn->store_result()->fetch_row();
        return ('<article class="article-card"><div class="article-content"><h3>'.$res[1].'</h3><p class="date">Опубликовано: '.$res[7].'</p><p>'.$res[3].'<BR><BR><a href="article.php?id='.$id.'">Читать далее...</a></p></div></article>');
    }

    require_once('.conn');
    require_once('header.inc');

    ?>
    <div class="main-card">
        <section class="articles-grid">
            <?php

            if ($_GET['category_id'] ?? false)
            {
                $category_id = $_GET['category_id'];
                $query = 'SELECT id FROM article WHERE category_id = '.$category_id.';';
            }
            else
            {
                $query = 'SELECT id FROM article;';
            };

            $conn->real_query($query);
            echo $conn->error;
            $result = $conn->store_result();

            foreach ($result as $key=>$article_id)
            {
                echo get_article_tile2($article_id['id']);
            };
            
            ?>
            <!-- <article class="article-card">
                <img src="pion.jpg" alt="Изображение к статье 1">
                <div class="article-content">
                    <h3>Заголовок Статьи 1</h3>
                    <p class="date">Опубликовано: 2023-10-27</p>
                    <p>Краткое описание статьи.  Здесь может быть несколько предложений,  рассказывающих о чем эта статья.  <a href="#">Читать далее...</a></p>
                </div>
            </article>

            <article class="article-card">
                <img src="pion.jpg" alt="Изображение к статье 2">
                <div class="article-content">
                    <h3>Заголовок Статьи 2</h3>
                    <p class="date">Опубликовано: 2023-10-26</p>
                    <p>Краткое описание второй статьи. <a href="#" class="">Читать далее...</a></p>
                </div>
            </article>

            <article class="article-card">
                <img src="pion.jpg" alt="Изображение к статье 3">
                <div class="article-content">
                    <h3>Заголовок Статьи 3</h3>
                    <p class="date">Опубликовано: 2023-10-25</p>
                    <p>Краткое описание третьей статьи. <a href="#">Читать далее...</a></p>
                </div>
            </article> -->

        </section>
    </div>
    <?php require_once('footer.inc'); ?>
</body>
</html>