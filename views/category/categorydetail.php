<?php
require_once 'lib/Renderer.php';
require_once 'model/Category.php';
use proven\store\model\Category;

echo "<p>Category detail page</p>";
$addDisable = "";
$editDisable = "disabled";
if ($params['mode']!='add') {
    $addDisable = "disabled";
    $editDisable = "";
}

$mode = "category/{$params['mode']}";
$message = $params['message'] ?? "";

$category = $params['category'] ?? new Category();
echo "<div class='container'>";
echo "<h2>Category  edition</h2>";
if (isset($_SESSION['name'])){
    echo "<p style='font-weight: bold;font-style: oblique;'>User logged:  ".$_SESSION['name']." ".$_SESSION['surname']."</p>";
}
printf("<p>%s</p>", $message);
if (isset($params['mode'])) {
    printf("<p>mode: %s</p>", $mode);
}
echo "<form method='post' action=\"index.php\">";
echo proven\lib\views\Renderer::renderCategoryFields($category);
echo "<br>";
echo "<button class='btn btn-warning' type='submit' name='action' value='category/modify' $editDisable>Modify</button>";
echo "</form>";
echo "</div>";