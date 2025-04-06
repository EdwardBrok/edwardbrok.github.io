<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание категории</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- <script>
        if document.getElementById("name").
    </script> -->
    <form action="create_category.php" method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td>
                    <label for="name">Имя категории</label>
                </td>
                <td>
                    <input type="text" name="name" id="name" maxlength="20" pattern="[a-zA-Z0-9,.-\s]+" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="parent_slug">SLUG родительской категории</label>
                </td>
                <td>
                    <select name="parent_slug" id="">
                        <option value="NULL">Нет (сирота)</option>
                        <?php
                        // ini_set('error_reporting', E_ALL);ini_set('display_errors', 1);ini_set('display_startup_errors', 1);
                        require(".conn");
            
                        $conn->query("SELECT name, slug FROM category;");
                        $result = $conn->use_result();
                        if ($result){foreach ($result as $ctg)
                        {
                            echo '<option value="'.$ctg['slug'].'">'.$ctg['name'].'</option>';
                        }}
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="description">Описание</label>
                </td>
                <td>
                    <input type="text" name="description" id="" maxlength="255"><BR>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="picture">Изображение категории</label>
                </td>
                <td>
                    <input type="file" name="picture" id=""><BR>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="Создать">
                </td>
            </tr>
        </table>
    </form>
    <?php
    // phpinfo();
    // die();

    if ($_POST ?? false)
    {
        $name = $_POST['name'];
        $gen_slug = slugify($name);
        $description = $_POST['description'];
        $parent_slug = $_POST['parent_slug'];

        if ($parent_slug != "NULL")
        {$parent_slug = "'".$parent_slug."'";}
        
        if (!($description ?? false))
        {$description = "NULL";}
        else
        {$description = "'".$description."'";};

        if ($_FILES['picture'] ?? false)
        {   
            $gen_picture_filename = bin2hex(random_bytes(8));
            $filename = $_FILES['picture']['tmp_name'];
            $extension = strtolower(pathinfo($_FILES['picture']['full_path'], PATHINFO_EXTENSION));
            $image = '';

            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $image = imageCreateFromJpeg($filename);
                break;
                
                case 'gif':
                    $image = imagecreatefromgif($filename);
                break;
                
                case 'png':
                    $image = imagecreatefrompng($filename);
                break;
            }
            imagepng($image, "images/category_pictures/".$gen_picture_filename.".png");
        }
                        
        var_dump($extension);echo "---";
        var_dump($gen_picture_filename);echo "---";
        var_dump($parent_slug);echo "---";
        var_dump($description);echo "---";
        var_dump($gen_slug);

        $query = "INSERT INTO category (name, slug, description, parent_category_slug, picture_filename)
                                VALUES ('$name', '$gen_slug', $description, $parent_slug, $gen_picture_filename);";
        
        
        // $conn->query($query);


        echo "<BR>";
        echo "<BR>";
        echo "<BR>";
        var_dump($_POST);
        echo "<HR>";
        var_dump($_FILES);
    }
    ?>
</body>
</html>