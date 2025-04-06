<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="hash.php" method="post">
        <input type="text" name="1" id="">
        <!-- <input type="text" name="2" id=""> -->
        <input type="submit" value="hash">
    </form>
    <?php

    if ($_POST ?? false)
    {
        echo password_hash($_POST[1], PASSWORD_DEFAULT);echo "<HR>";
        echo password_hash($_POST[1], PASSWORD_DEFAULT);
    }
    ?>
</body>
</html>