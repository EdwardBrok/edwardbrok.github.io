<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление статьи</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: "bah", Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .menu-main {
            background-color: #333;
            color: #fff;
            padding: 1em 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .vertical-menu {
            display: flex;
            flex-direction: column;
            width: 200px;
            margin-left: 20px;
        }

        .vertical-menu a {
            background-color: #eee;
            color: black;
            display: block;
            padding: 12px;
            text-decoration: none;
            margin-bottom: 5px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .vertical-menu a:hover {
            background-color: #ddd;
        }

        .vertical-menu a.active {
            background-color: #4CAF50;
            color: white;
        }

        #clock, #current_date {
            color: #fff;
            margin-right: 20px;
        }

        .tools1 {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #glaw {
            margin-bottom: 20px;
        }

        .blok1 {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .blok1 p {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .in {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Important: Include padding and border in the element's total width and height */
        }


        .search-results {
            width: 80%; /* Adjust as needed */
            margin: 20px auto; /* Center the results */
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            font-family: sans-serif;
        }

        .result-item {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .result-item strong {
            font-weight: bold;
            margin-right: 5px;
        }

        .no-results {
            text-align: center;
            font-style: italic;
            color: #777;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .menu-main {
                flex-direction: column;
                align-items: flex-start;
            }

            .vertical-menu {
                width: 100%;
                margin-left: 0;
            }

            .vertical-menu a {
                margin-bottom: 10px;
            }

            #clock, #current_date {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
<?php require_once('header.inc') ?>
<div class="tools1">
    <div id="glaw">
        <div class="blok1">
        <p>Форма заполнения информации</p>
            <form method="POST" action="">
                <input name="name" type="text" placeholder="Название" class="in"/>
                <input name="anons" type="text" placeholder="Краткое описание" class="in"/>
                <textarea name="article_body" type="text" placeholder="Текст статьи" class="in"></textarea>
                <input name="author_username" type="text" placeholder="Автор статьи" class="in" />
                <select name="category" placeholder="Категория" class="in">
                    <!-- <option value="0">Без категории</option> -->
                    <?php
                    require(".conn");
        
                    $conn->real_query("SELECT id, name FROM category;");
                    $result = $conn->use_result();
                    echo '<!--';
                    var_dump($result);
                    echo '-->';
                    if ($result ?? false)
                    {
                        foreach ($result as $key=>$ctg)
                        {
                            echo '<option value="'.$ctg['id'].'">'.$ctg['name'].'</option>';
                        }
                    }
                    // $result->close();
                    ?>
                </select>
                <input type="submit" value="Отправить" class="inp"/>
            </form>
            <?php
                require(".conn");

                if ($_POST ?? false)
                {
                    $gen_article_filename = bin2hex(random_bytes(8));
                    
                    $name = $_POST['name'];
                    $anons = $_POST['anons'];
                    $body = $_POST['article_body'];
                    $author_username = $_POST['author_username'];
                    $category = $_POST['category'];
                    // var_dump($anons);
                    // var_dump($category);
                    
                    // $article_file = fopen("articles/".$gen_article_filename, "w");
                    // // var_dump($body);
                    // // var_dump($gen_article_filename);echo "<BR>";
    
                    // fwrite($article_file, $body);
    
                    $query = 'INSERT INTO article (name, anons, text_filename, author_username, category_id, text) VALUES ("'.$name.'", "'.$anons.'", NULL, "'.$author_username.'", '.$category.', "'.$body.'");';
                    // var_dump($query);
                    $conn->real_query($query);
                }
            ?>
        </div>
    </div>
</div>

</body>
</html>