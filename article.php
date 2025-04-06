<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require(".conn");
    require("header.inc");


    if ($_GET['id'] ?? false)
    {
        $article_id = $_GET['id'];
    }
    else
    {
        header("Location: index.php");
    }


    $query = "SELECT * FROM `article` WHERE `id` = $article_id LIMIT 1;";
    $conn->real_query($query);
    $result = $conn->use_result()->fetch_row();


    // var_dump($article_id); echo "<BR>";
    // var_dump($query); echo "<BR>";
    // var_dump($result); echo "<BR>";
    
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>
        <?php
        if (!$result)
        {
            header("Location: 404.php");
        }
        else
        {
            echo $result[1];
        }
        ?>
    </title>
</head>
<body><main style="flex-direction:column;">
    <?php
    if ($result ?? false)
    {
        echo '<h1 style="margin-bottom:10px;">'.$result[1].'</h1>';
        echo '<i>'.$result[3].'</i>';
        // echo '<hr>';
        echo '<p class="date" style="margin-top:10px;">От '.$result[7].'</p>';
        
        // $article_file = fopen('articles/'.$result[4], 'r');
        // echo fread($article_file, filesize('articles/'.$result[4]));

        echo $result[9];
        // var_dump($result);
    }
    ?>
    <p style='text-align:right;margin:50px;'>
        <?php 
    
        echo $result[5];
    
        ?>
    </p>
</main></body>
</html>