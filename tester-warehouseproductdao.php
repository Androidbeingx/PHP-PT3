<?php
require_once "lib/Debug.php";
use proven\lib\debug;
debug\Debug::iniset();

require_once "model/persist/WarehouseProductDao.php";
require_once 'model/WarehouseProduct.php';

use proven\store\model\persist\WarehouseProductDao;
use proven\store\model\WarehouseProduct;

$dao = new WarehouseProductDao();
debug\Debug::vardump($dao->selectAll()); //checked
//debug\Debug::vardump($dao->selectWhere('product_id', '2')); checked
//debug\Debug::vardump($dao->selectWhere('warehouse_id', '2')); checked
//echo($dao->insert(new WarehouseProduct(2,1,23))); checked
//echo($dao->update(new WarehouseProduct(2,1,24))); checked
//echo($dao->delete(new WarehouseProduct(2,1))); checked


