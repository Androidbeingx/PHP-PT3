
<div class='container'>
<h2>Product management page</h2>
<?php if (isset($_SESSION['name'])): ?>
    <p style='font-weight: bold;font-style: oblique;'><?php echo "User logged:  ".$_SESSION['name']." ".$_SESSION['surname'] ?></p>
<?php endif ?>


<?php if (isset($params['message'])): ?>
<div class='alert alert-warning'>
<strong><?php echo $params['message']; ?></strong>
</div>
<?php endif ?>



<?php
$deletionResult = $params["deletionResult"] ?? null;
$deletedId = $params["deletedId"] ?? null;
$searchedCategoryCode = $params["searchedCategoryCode"] ?? null;

//display list in a table.
$list = $params['list'] ?? null;

echo <<<EOT
    <form method="post">
    <div class="row g-3 align-items-center">
    <span class="col-auto">
        <label for="search" class="col-form-label">Category to search</label>
    </span>
    <span class="col-auto">
        <input type="text" id="search" name="search" class="form-control" aria-describedby="searchHelpInline">
    </span>
    <span class="col-auto">
        <button class="btn btn-primary" type="submit" name="action" value="product/category">Search</button>
    </span>
    EOT;
    if (isset($_SESSION["userrole"])) {
    echo <<<EOT
    <span class="col-auto">
    <button class="btn btn-primary" type="submit" name="action" value="product/form">Add</button>
    </span>
    EOT;
    } 
    echo "</div>";
    echo "</form>";
    echo "<br>";


if (isset($list)) {
  if (count($list) < 1) {
      echo '<p class="text-danger mt-4">No data were found.</p>';
      echo "</div>";
  } else {
    if (isset($deletionResult) && isset($deletedId)) {
          if ($deletionResult === true) {
            $deletionMessage = '<p class="text-succes"style="font-style: oblique;">Product ' . $deletedId . ' has been deleted successfully.</p>';
              
          } else {
            $deletionMessage = '<p class="text-danger" style="font-style: oblique;">Could not delete product ' . $deletedId . '.</p>';
          }
      }


   
    if (isset($deletionResult) && isset($deletedId)){
        echo" <div> <p>{$deletionMessage}</p></div>";
    }

    echo <<<EOT
        <caption>List of products</caption>
        <div class="table-wrapper-scroll-y my-custom-scrollbar">
        <table class="table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm">
        <thead class='table-dark'>
        <tr>
            <th>Code</th>
            <th>Description</th>
            <th>Price</th>
        EOT;
    if (isset($_SESSION["userrole"])) {
            
        echo "<th>Action</th>";
            
    }
    echo <<<EOT
        </tr>
        </thead>
        <tbody>
        EOT;

      // $params contains variables passed in from the controller.
      foreach ($list as $elem) {
          echo <<<EOT
                <tr>
                    <td>{$elem->getCode()}</td>
                    <td>{$elem->getDescription()}</td>
                    <td>{$elem->getPrice()} €</td>
                EOT;
          if (isset($_SESSION["userrole"])) {
              
                  echo <<<EOT
                        <td class="d-flex justify-content-center">
                            <form action="" method="post">
                            <input type="hidden" name="productId" value="{$elem->getId()}">
                            <input type="hidden" name="searchedCategoryCode" value="{$searchedCategoryCode}">
                            <button class="btn btn-success" type="submit" name="action" value="product/stock">Stock</button>
                            <button class="btn btn-warning" type="submit" name="action" value="product/edit">Modify</button>
                            <button class="btn btn-danger" type="submit" name="action" value="product/removeConfirmation">Remove</button>
                            </form>
                        </td>
                        EOT;
              
        }
        echo "</tr>";
      }
      echo "</tbody>";
      echo "</table>";
      echo "</div>";
      echo "<br>";
      echo "<div class='alert alert-info' role='alert'>";
      echo count($list), " elements found.";
      echo "</div>";
      echo "</div>";
  }
} else {
  echo "<p class='text-success'>Removed correctly, search a category to display results.</p>";
  echo "</div>";
}

?>