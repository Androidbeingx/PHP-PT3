<?php
require_once "lib/Debug.php";
use proven\lib\debug;
debug\Debug::iniset();

require_once "model/persist/ProductDao.php";
require_once "model/Product.php";

use proven\store\model\persist\ProductDao;
use proven\store\model\Product;

$dao = new ProductDao();
debug\Debug::display($dao->selectAll());
//debug\Debug::display($dao->selectWhere("code", "prodcode04")); checked
//echo($dao->insert(new Product(0, "prodcode07", "proddesc07", 105.00, 3))); checked
//echo($dao->update(new Product(7, "prodcode71", "proddesc71", 107.00, 3))); cheked
//echo($dao->delete(new Product(7))); checked