<?php
require_once "lib/Debug.php";
use proven\lib\debug;
debug\Debug::iniset();

require_once "model/persist/WarehouseDao.php";
require_once "model/Warehouse.php";

use proven\store\model\persist\WarehouseDao;
use proven\store\model\Warehouse;

$dao = new WarehouseDao();
debug\Debug::vardump($dao->selectAll()); //checked
//debug\Debug::vardump($dao->selectWhere('id', 3));//checked
//echo($dao->insert(new Warehouse(6 ,'warhcode06', 'address6'))); //checked
//echo($dao->delete(new Warehouse(6 ))); //checked
