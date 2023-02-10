<?php


require_once "lib/Renderer.php";
require_once "model/Product.php";

use proven\store\model\Product;


$addDisable = "";
$editDisable = "disabled";

if ($params["mode"] != "add") {
    $addDisable = "disabled";
    $editDisable = "";
}

$mode = "product/{$params["mode"]}";
$message = $params["message"] ?? "";


$product = $params["product"] ?? new Product();
echo "<div class='container'>";
echo "<h2>Product  edition</h2>";
if (isset($_SESSION['name'])){
    echo "<p style='font-weight: bold;font-style: oblique;'>User logged:  ".$_SESSION['name']." ".$_SESSION['surname']."</p>";
}
printf("<p>%s</p>", $message);
if (isset($params['mode'])) {
    printf("<p>mode: %s</p>", $mode);
}
echo "<form method='post' action=\"index.php\">";
echo proven\lib\views\Renderer::renderProductFields($product);
echo "<br>";
echo "<button class='btn btn-success' type='submit' name='action' value='product/add' $addDisable>Add</button>";
echo "<button class='btn btn-warning' type='submit' name='action' value='product/modify' $editDisable>Modify</button>";
echo "</form>";
echo "</div>";
