<?php


require_once 'lib/Renderer.php';
require_once 'model/Warehouse.php';

use proven\store\model\Warehouse;

echo "<p>Warehouse detail page</p>";
$addDisable = "";
$editDisable = "disabled";

if ($params['mode']!='add') {
    $addDisable = "disabled";
    $editDisable = "";
}

$mode = "warehouse/{$params['mode']}";
$message = $params['message'] ?? "";

$warehouse = $params['warehouse'] ?? new Warehouse();
echo "<div class='container'>";
echo "<h2>Warehouse  edition</h2>";
if (isset($_SESSION['name'])){
    echo "<p style='font-weight: bold;font-style: oblique;'>User logged:  ".$_SESSION['name']." ".$_SESSION['surname']."</p>";
}
printf("<p>%s</p>", $message);
if (isset($params['mode'])) {
    printf("<p>mode: %s</p>", $mode);
}
echo "<form method='post' action=\"index.php\">";
echo proven\lib\views\Renderer::renderWarehouseFields($warehouse);
echo "<br>";
echo "<button class='btn btn-warning' type='submit' name='action' value='warehouse/modify' $editDisable>Modify</button>";
echo "</form>";
echo "</div>";