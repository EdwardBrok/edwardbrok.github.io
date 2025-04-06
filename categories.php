<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторы</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h3>Список категорий</h3>

    <?php
        require_once('.conn');
        
        if ($_GET['parent_category_id'] ?? false)
        {
            $pcid = $_GET['parent_category_id'];

            $query = 'SELECT * from category where parent_category_id = "$pcid";';
        }
        else
        {
            $query = 'SELECT * FROM category where parent_category_id = NULL;';
        };

        $conn->query($query);
        $result = $conn->use_result();
        if ($result)
        {
            echo '<ul class="categories_list">';
            foreach ($result as $category)
            {
                echo '<li><a href="articles.php?category_slug='.$category['slug'].'">';
                echo $category['name'];
                echo '</a> ------------- ';
                echo '<a href="category.php?parent_category_id='.$category['parent_category_id'].'">';
                echo 'Просмотреть подкатегории';
                echo '</a></li>';
            }
        }
        else
        {
            echo "<i>Ни одной категории не найдено</i>";
        }
    ?>
</body>
</html>