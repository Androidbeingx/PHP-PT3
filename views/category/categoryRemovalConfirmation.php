<?php

if (isset($params)) {
    echo <<<EOT
    <div class="container">
    <form class="row" action="index.php" method="post">
        <div class="row mt-5 d-flex justify-content-center">
            <p class="h4 text-center">Are you sure you want to delete category {$params["category"]->getCode()}?</p>
        </div>
        <br>
        <br>
        <div class="row d-flex justify-content-center">
                    <input type="hidden" name="categoryId" value="{$params["category"]->getId()}">
                <span class="col-auto">
                    <button class="btn btn-danger" name="action" value="category/remove" type="submit" >Yes</button>
                </span>
                <span class="col-auto">
                    <button class="btn btn-secondary" name="action" value="category/cancelRemove" type="submit" >No</button>
                </span>
        </div>
    </form>

    </div>
    EOT;
} else {
    echo <<<EOT
    <div class="container">
    <div class="row d-flex justify-content-center">
        <p class="text-center">Wrong page!</p>
    </div>
    </div>
    EOT;
}
