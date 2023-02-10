<?php
require_once "lib/Debug.php";
use proven\lib\debug;
debug\Debug::iniset();

require_once "model/persist/CategoryDao.php";
require_once "model/Category.php";

use proven\store\model\persist\CategoryDao;
use proven\store\model\Category;

$dao = new CategoryDao();
debug\Debug::display($dao->selectAll()); //checked
//debug\Debug::display($dao->selectWhere("code", "catcode03")); //checked
//echo($dao->insert(new Category(0, 'catcode06', 'catdesc06'))); //checked
//echo($dao->update(new Category(6, "catcode66", "catdesc66"))); //checked
//echo($dao->delete(new Category(6))); //checked