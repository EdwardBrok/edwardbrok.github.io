<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Вход/Регистрация</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: "bah", Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center; 
        }
                .article a {
            font-size: 17px;
            text-decoration: none;
            color: #555; 
            background-color: #ffe082; 
            border-radius: 4px;
            padding: 5px 8px; 
            box-shadow: 0 2px 0 #cc9966; 
            transition: all 0.2s ease;
            display: inline-block;
        }
                    .frm, .frm2 {
            position: fixed; 
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            opacity: 0; 
            -webkit-transition: opacity 200ms ease-in; 
            -moz-transition: opacity 200ms ease-in;
            transition: opacity 200ms ease-in; 
            pointer-events: none;
        }
        .frm:target, .frm2:target {
            opacity: 1;
            pointer-events: auto;     overflow-y: auto;
        }.frm-dialog, .frm-dialog2 {
            position: relative;    width: auto;
        }
        @media (min-width: 367px) {
            .frm-dialog, .frm-dialog2 {
                max-width: 30%;
                margin-left: auto;
                margin-right: auto;
                
            }
        }
        .frm-content, .frm-content2{
            margin-top: 30px;
            background: #ffffff;
            border-radius: 2%;
            box-shadow: hsla(0, 0%, 12%, 0.336);
        }
        .frm-body{
            padding: 2%;
            margin: 20px;
        }
        .close {
            float: right;
            font-size: 24px;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            opacity: 1;
            text-decoration: none;
            padding: 3%;
        }
        .frm-header{
            padding-top: 10px;
            padding-left: 3%;
            font-size: 20pt;
        }
        .pause{
            padding: 5%;

        }
        </style>
    <script>
        function verifyPassword() {
            let pass1 = document.getElementById("password").value;
            let pass2 = document.getElementById("retyped_password").value;
            let match = true;
            if (pass1 !== pass2) {
                //alert("Passwords Do not match");
                document.getElementById("password").style.borderColor = "#ff0000";
                document.getElementById("retyped_password").style.borderColor = "#ff0000";
                event.preventDefault();
            }
        }
        document.getElementById('registrate_me').onsubmit = verifyPassword;
    </script>
</head>
<body>
    <?php require('header.inc') ?>
    <div class="main" style='display:flex;flex-grow:1;'> 
        <article class="article" style='width:100%;display:flex;flex-grow:1;flex-direction:column;justify-content:center;'>
            <h2>Впервые на сайте?</h2>
            <p><a href="#openfrm">Я новичок</a><b class="pause"></b><a href="#openfrm2">Я уже был тут</a></p>
        </article>
        <div id="openfrm" class="frm">
            <div class="frm-dialog">
                <div class="frm-content">
                    <a href="#close" title="Close" class="close">×</a>
                    <form method="POST" action="Lcab.php">
                        <div class="frm-header">
                            <h3 class="frm-title">Регистрация</h3>
                        </div>
                        <div class="frm-body">
                            <input class="pole" name = "fio" type = "text" placeholder="ФИО" required><br>
                            <input class="pole" name = "username" type = "text" placeholder="Никнейм (не менее 6 симв.)" required minlength='6'><br>
                            <input class="pole" name = "about_me" type = "text" placeholder="Обо мне"><br>
                            <input class="pole" name = "password" type = "password" placeholder="Пароль (не менее 8 симв.)" minlength='8'><br>
                            <input class="pole" name = "retype_password" type = "password" placeholder="Повторите пароль" minlength='8'><br><br>
                            <input class="butt" type="submit" value="Отправить"id='registrate_me'>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <?php
            require('.conn');
            
            if ($_POST ?? false)
            {
                $fio = $_POST['fio'];
                $username = $_POST['username'];
                $about_me = $_POST['about_me'];
                $password = $_POST['password'];
                
                $query = "SELECT * FROM `author` WHERE username = '$username' LIMIT 1;";
                // var_dump($query);
                $conn->real_query($query);
                $result = $conn->store_result();
    
                if ($result ?? false) {
                    echo "Пользователь с таким именем уже существует.";

                }
                else {
                    $result->close();
    
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $query1 = "INSERT INTO `author` (fio, username, about_me, avathar_filename, password_hash) VALUES ($fio, $fio, $about_me, $avathar_filename, $hashed_password)";
                    $result1 = $conn->real_query($query1);
    
                    if ($result ?? false) {
                        echo "Регистрация прошла успешно!";
                        // echo "<br><a href='login.php'>Перейти на страницу входа</a>";
                        session_start();
                        header("Location: Lcab.php");
                    } else {
                        echo "Ошибка при регистрации. Пожалуйста, попробуйте снова.";
                    }
                }
            }
        ?>
        <div id="openfrm2" class="frm2">
            <div class="frm-dialog2">
                <div class="frm-content2">
                    <a href="#close" title="Close" class="close">×</a>
                    <div class="frm-header">
                        <form method="POST" action="">
                            <h3 class="frm-title">Авторизация</h3>
                        </form>
                    </div>
                    <div class="frm-body">
                        <input class="pole" name = "username" type = "text" placeholder="Никнейм" required><br>
                        <input class="pole" name = "password" type = "text" placeholder="Пароль"><br>
                        <input class="butt" type="submit" value="Войти"id='auth_me'>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>