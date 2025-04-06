<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторы</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="." method="get">
        <h3>Поиск автора (по фамилии)</h3>
        <input type="search" name="fio" id="">
        <input type="submit" value="Поиск">
    </form>
    <?php

    require_once('.conn');
    
    if ($_GET['fio'] ?? false)
    {
        $fio = $_GET['fio'];
        $query = 'SELECT * FROM author;';
    }
    else
    {
        $query = 'SELECT * from author where fio like "%$fio%";';
    };

    $conn->query($query);
    $result = $conn->use_result();
    if ($result)
    {
        echo '<ul class="authors_list">';
        foreach ($result as $author)
        {
            echo '<li><a href="author.php?';
            echo $author['username'];
            echo '">';
            echo $author['fio'];
            echo '</a></li>';
        }
    }
    else
    {
        echo "<i>Ни одного пользователя не найдено</i>";
    }
    ?>
</body>
</html>