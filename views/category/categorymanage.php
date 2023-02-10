<div class="container">
<h2>Category management page</h2>
<?php if (isset($_SESSION['name'])): ?>
    <p style='font-weight: bold;font-style: oblique;'><?php echo "User logged:  ".$_SESSION['name']." ".$_SESSION['surname'] ?></p>
<?php endif ?>


<?php if (isset($params['message'])): ?>
<div class='alert alert-warning'>
<strong><?php echo $params['message']; ?></strong>
</div>
<?php endif ?>
<?php

//display list in a table.


$list = $params['list'] ?? null;

// Variables that store the results of a deletion operation.
$deletionResult = $params["deletionResult"] ?? null;
$deletedId = $params["deletedId"] ?? null;

if (isset($list)) {
    if (count($list) < 1) {
        echo '<p class="text-danger mt-4">No data were found.</p>';
    }else{
        if (isset($deletionResult) && isset($deletedId)) {
            if ($deletionResult === true) {
                $deletionMessage = '<p class="text-succes"style="font-style: oblique;">Category ' . $deletedId . ' has been deleted successfully.</p>';
            } else {
                $deletionMessage = '<p class="text-danger" style="font-style: oblique;">Could not delete category ' . $deletedId . '.</p>';
            }
        }
 
        if (isset($deletionResult) && isset($deletedId)){
           echo" <div> <p>{$deletionMessage}</p></div>";
        }
    
        echo <<<EOT
        <table class="table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm">
        <caption>List of categories</caption>
        <thead class='table-dark'>
        <tr>
            <th>Code</th>
            <th>Description</th>
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
            if (isset($_SESSION["userrole"])) {
                
                    echo <<<EOT
                    <tr>
                        <td><a href="index.php?action=category/edit&id={$elem->getId()}">{$elem->getCode()}</a></td>
                        <td>{$elem->getDescription()}</td>
                        <td class="d-flex justify-content-center">
                            <form action="" method="post">
                                <input type="hidden" name="categoryId" value="{$elem->getId()}">
                                <button class="btn btn-danger" type="submit" name="action" value="category/removeConfirmation">Delete</button>
                            </form>
                        </td>
                    </tr>
                    EOT;
                
            } else {
                echo <<<EOT
                <tr>
                    <td>{$elem->getCode()}</td>
                    <td>{$elem->getDescription()}</td>
                </tr>
                EOT;
            }
        }
        echo "</tbody>";
        echo "</table>";
        echo "<div class='alert alert-info' role='alert'>";
        echo count($list), " elements found.";
        echo "</div>";
        echo "</div>";
        
    }

} else {
    echo "No data found";
}
    

?>
