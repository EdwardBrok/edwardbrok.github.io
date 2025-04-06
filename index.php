<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная - Атмосфера Знаний</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    // phpinfo();
    function get_article_tile(string $id)
    {
        require('.conn');
    
        $conn->real_query('SELECT * FROM article WHERE id = '.$id.';');
        $res = $conn->store_result()->fetch_row();
        return ('<article class="article"><h3>'.$res[1].'</h3><p class="date">Опубликовано: '.$res[7].'</p><p>'.$res[3].'<BR><BR><a href="article.php?id='.$id.'">Читать далее...</a></p></article>');
    }    
    
    require_once(".conn");
    require_once("header.inc");
    // require_once("lib.php");
    ?>

    <main>
        <section id="articles">
            <h2>Последние Статьи</h2>
            <?php
            
            $query = 'SELECT id FROM article ORDER BY publish_date DESC LIMIT 5';
            $conn->real_query($query);
            echo $conn->error;

            $result = $conn->store_result();
            // var_dump($result);echo "<hr>";
            
            if ($result->num_rows > 0)
            {   
                foreach ($result as $key=>$article)
                {
                    echo get_article_tile($article['id']);
                };
            };

            ?>
        </section>

        <aside>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Поиск...">
                <button class="search-button">Поиск</button>
            </div>

            <h3>Категории</h3>
            <ul>
                <li><a href="catalog.php?category_id=1">Информатика</a></li>
                <li><a href="catalog.php?category_id=2">География</a></li>
                <li><a href="catalog.php?category_id=3">Физика</a></li>
                <li><a href="catalog.php?category_id=4">Экология</a></li>
                <li><a href="catalog.php?category_id=5">Астрономия</a></li>
                <li><a href="catalog.php?category_id=6">Инженерия</a></li>
            </ul>

            <h3>Популярные Статьи</h3>
            <ol>
                <li><a href="#">Лики нашей планеты</a></li>
                <li><a href="#">В поисках теории всего</a></li>
                <li><a href="#">Баланс жизни</a></li>
            </ol>
            <BR>
            <h3>Наш Telegram-бот</h3>
            <a href="https://t.me/News_Technolog1_bot">
                <img src="qr-to-bot.png" alt="" style="aspect-ratio:1/1;height:75px">
            </a>
            <BR>
            <h3>Решение кейса на GitHub</h3>
            <a href="https://github.com/EdwardBrok/edwardbrok.github.io/">
                <img src="github.png" alt="" style="aspect-ratio:1/1;height:75px">
            </a>

        </aside>
    </main> 

<?php require_once('footer.inc'); ?>
</body>
</html>