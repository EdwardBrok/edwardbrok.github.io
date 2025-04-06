<?php 
function get_article_tile(string $id)
{
    require('.conn');

    $conn->real_query('SELECT * FROM article WHERE id = '.$id.';');
    $res = $conn->use_result();
    echo '<article class="article"><h3>'.$res[1].'</h3><p class="date">Опубликовано: '.$res[7].'</p><p>'.$res[3].'<a href="article.php?id='.$id.'">Читать далее...</a></p></article>';
}
?>