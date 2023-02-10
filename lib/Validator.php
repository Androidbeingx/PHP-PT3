<?php
namespace proven\lib\views;
require_once 'model/User.php';
use proven\store\model\User;
require_once 'model/Product.php';
use proven\store\model\Product;
require_once 'model/Category.php';
use proven\store\model\Category;
require_once 'model/Warehouse.php';
use proven\store\model\Warehouse;

class Validator {

    public static function validateUser(int $method): ?User  {
        $obj = null;
        $id = static::cleanAndValidate($method, 'id', FILTER_VALIDATE_INT); 
        $username = static::cleanAndValidate($method, 'username'); 
        $password = static::cleanAndValidate($method, 'password'); 
        $firstname = static::cleanAndValidate($method, 'firstname'); 
        $lastname = static::cleanAndValidate($method, 'lastname'); 
        $role = static::cleanAndValidate($method, 'role'); 
        $obj = new User($id, $username, $password, $firstname, $lastname, $role);
        return $obj;        
    }

    public static function validateCategory(int $method): ?Category  {
        $obj = null;
        $id = static::cleanAndValidate($method, 'id', FILTER_VALIDATE_INT); 
        $code = static::cleanAndValidate($method, 'code'); 
        $description = static::cleanAndValidate($method, 'description'); 
        $obj = new Category($id, $code, $description);
        return $obj;        
    }

    public static function validateProduct(int $method): ?Product  {
        $obj = null;
        $id = static::cleanAndValidate($method, 'id', FILTER_VALIDATE_INT); 
        $code = static::cleanAndValidate($method, 'code'); 
        $description = static::cleanAndValidate($method, 'description'); 
        $price = static::cleanAndValidate($method, 'price', FILTER_VALIDATE_INT); 
        $categoryId = static::cleanAndValidate($method, 'categoryId', FILTER_VALIDATE_INT);  
        $obj = new Product($id, $code, $description, $price, $categoryId);
        return $obj;        
    }

    public static function validateWarehouse(int $method): ?Warehouse {
        $obj = null;
        $id = static::cleanAndValidate($method, 'id', FILTER_VALIDATE_INT); 
        $code = static::cleanAndValidate($method, 'code'); 
        $adress = static::cleanAndValidate($method, 'adress'); 
        $obj = new Warehouse($id, $code, $adress);
        return $obj;        
    }

    public static function cleanAndValidate(int $method, string $variable, int $filter=\FILTER_SANITIZE_FULL_SPECIAL_CHARS) {
        $clean = null;
        if (\filter_has_var($method, $variable)) {
            $clean = \filter_input($method, $variable, $filter); 
        }
        return $clean;
    }
    
}