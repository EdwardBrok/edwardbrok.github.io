<!-- 



-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все статьи - Атмосфера Знаний</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h3>Список статей</h3>

    <?php
        require('.conn');
        

        if ($_GET['category_id'] ?? false)
        {
            $categ_ids = $_GET['category_id'];
            $query = 'SELECT * from `article` where categories_ids LIKE "%'.$categ_ids.'%";';
        }
        else
        {
            $query = 'SELECT * FROM `article`';
        };


        if ($conn->real_query($query) === TRUE) {
            echo "Query executed successfully";
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
        

        $result = $conn->use_result();
        if ($result ?? false)
        {
            echo '<ul class="articles_list">';
            foreach ($result as $article)
            {
                // var_dump($article);
                echo '<li><a href="article.php?id='.$article['id'].'">';
                echo $article['name']; // имя статьи
                echo '</a> ------------- Автор: ';
                echo '<a href="author.php?username='.$article['author_username'].'">';
                echo $article['author_username']; //юзернейм - временно
                echo '</a></li>';
            }
            echo '</ul>';
        }
        else
        {
            echo "<i>Ни одной статьи не найдено</i>";
        }
        if ($result->num_rows === 0) {
            // Проверьте условия WHERE
            $test_sql = "SELECT COUNT(*) as cnt FROM table";
            $test_result = $conn->query($test_sql);
            $row = $test_result->fetch_assoc();
            echo "Всего строк в таблице: " . $row['cnt'];
        }
    ?>
</body>
</html>