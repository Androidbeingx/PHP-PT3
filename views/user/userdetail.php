<?php
require_once 'lib/Renderer.php';
require_once 'model/User.php';
use proven\store\model\User;

echo "<p>User detail page</p>";
$addDisable = "";
$editDisable = "disabled";
if ($params['mode']!='add') {
    $addDisable = "disabled";
    $editDisable = "";
}

$mode = "user/{$params['mode']}";
$message = $params['message'] ?? "";


$user = $params['user'] ?? new User();
echo "<div class='container'>";
echo "<h2>User  edition</h2>";
if (isset($_SESSION['name'])){
    echo "<p style='font-weight: bold;font-style: oblique;'>User logged:  ".$_SESSION['name']." ".$_SESSION['surname']."</p>";
}
printf("<p>%s</p>", $message);
if (isset($params['mode'])) {
    printf("<p>mode: %s</p>", $mode);
}
echo "<form method='post' action=\"index.php\">";
echo proven\lib\views\Renderer::renderUserFields($user);
echo "<br>";
echo "<button class='btn btn-success' type='submit' name='action' value='user/add' $addDisable>Add</button>";
echo "<button class='btn btn-warning' type='submit' name='action' value='user/modify' $editDisable>Modify</button>";
echo "<button class='btn btn-danger' type='submit' name='action' value='user/remove' $editDisable>Remove</button>";
echo "</form>";