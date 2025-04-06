<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статьи - Мой Сайт</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .mainMob a {
            font-size: 30px;
            text-decoration: none;
            color: #555; 
            background-color: #ffe082; 
            border-radius: 4px;
            padding: 5px 8px; 
            box-shadow: 0 2px 0 #cc9966; 
            transition: all 0.2s ease;
            display: inline-block;
            margin: 20px;
        }
        
        main {
            display: flex;
            max-width: 960px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .mainMob {
            display: flex;
            justify-content: center;
        }

        .avatarFoto {
            height: 300px;
            width: 200px;
            border: 2px solid rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: #eeeeee;
        }

        .information {
            margin-left: 100px;
        }

        .inf2 {
            margin-left: 100px;
            width: 370px;
        }

        .opis {
            max-width: 960px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) { /* For smaller screens */
            main {
                flex-direction: column; /* Stack sections vertically */
                display: flex;
            }
            #articles, aside {
                padding: 10px;
            }
            nav li {
                display: block;
                margin: 0.5em 0;
            }
            .avatarFoto {
                width: 150px;
                height: 200px;
            }
            .mainMob {
                display: flex;
            }

        }
    </style>
</head>
<body>
    <?php
    
    require_once('header.inc');
    
    ?>
    <main>
        <div class="mainMob">
            <div class="avatarFoto" style="min-width: 300px;">
            </div>
            <div class="information">
                <h1>username</h1>
                <h2>fio</h2>
            </div>
        </div>
        <div class="inf2">
            <p>about_me</p>
        </div>
    </main>
    <div class="create_article">
        <div class="mainMob">
            <a href="create_article.php">Создать свою статью</a>
        </div>
    </div>
    <br><br> 
    <footer>
        <p>&copy; 2023 Мой Сайт</p>
    </footer>
</body>
</html>