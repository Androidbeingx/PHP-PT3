

<?php if (isset($params["message"])): ?>
    <div class='alert alert-warning'>
    <strong><?php echo $params["message"]; ?></strong>
    </div>
<?php endif; ?>


<!-- ------------------------------------------------------------ PRODUCT INFO ---------------------------------------------------------- -->
<?php
require_once "lib/Renderer.php";
require_once "model/Warehouse.php";

use proven\store\model\Warehouse;
echo "<div class='container'>";
echo "<h2>Stock</h2>";
if (isset($_SESSION['name'])){
    echo "<p style='font-weight: bold;font-style: oblique;'>User logged:  ".$_SESSION['name']." ".$_SESSION['surname']."</p>";
}
echo "<h4>Warehouse details</h4>";

$message = $params["message"] ?? "";

$warehouse = $params["warehouse"] ?? new Warehouse();
echo "<div>";
echo proven\lib\views\Renderer::renderWarehouseInfos($warehouse);
echo "</div>";
?>

<!-- -------------------------------------------------------------- STOCK TABLE ---------------------------------------------------------- -->
<?php
// Display warehouses and their informations in a table.
$products = $params["products"] ?? null;
$WStockFields = $params["warehouseStockRegister"] ?? null;
$tableData = $params["tableData"] ?? null;

if (isset($products) && isset($WStockFields)) {
    echo <<<EOT
        <h4 class="mt-3" >Stock information</h4>
        <div class="table-wrapper-scroll-y my-custom-scrollbar">
        <table class="table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm">
        <thead class='table-dark'>
        <tr>
            <th>Code</th>
            <th>Stock</th>
        </tr>
        </thead>
        <tbody>
        EOT;
    
    foreach ($tableData as $register) {
        echo <<<EOT
            <tr>
                <td>{$register["code"]}</td>
                <td>{$register["stock"]}</td>
            </tr>
            EOT;
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
    
    
} else {
    
    echo '<p  style="font-weight: bold;font-style: oblique;" class="text-danger display-6">This warehouse has no products.</p>';
    echo "</div>";
    
}

?>
