<?php
namespace proven\store\model;

require_once 'model/persist/UserDao.php';
require_once 'model/User.php';

require_once 'model/persist/ProductDao.php';
require_once 'model/Product.php';

require_once 'model/persist/CategoryDao.php';
require_once 'model/Category.php';

require_once 'model/persist/WarehouseDao.php';
require_once 'model/Warehouse.php';

require_once 'model/persist/WarehouseProductDao.php';
require_once 'model/WarehouseProduct.php';

use proven\store\model\persist\UserDao;
use proven\store\model\User;

use proven\store\model\persist\ProductDao;
use proven\store\model\Product;

use proven\store\model\persist\CategoryDao;
use proven\store\model\Category;

use proven\store\model\persist\WarehouseDao;
use proven\store\model\Warehouse;

use proven\store\model\persist\WarehouseProductDao;
use proven\store\model\WarehouseProduct;

/**
 * Service class to provide data.
 * @author ProvenSoft & Andrea
 */
class StoreModel {


    public function __construct() {
    }
    
    /* ============== USER FUNCTIONS METHODS ============== */
    public function findAllUsers(): array {
        $dbHelper = new UserDao();
        return $dbHelper->selectAll();
    }
    
    public function findUsersByRole(string $role): array {
        $dbHelper = new UserDao();
        return $dbHelper->selectWhere("role", $role);
    }

    public function addUser(User $user): int {
        $dbHelper = new UserDao();
        return $dbHelper->insert($user);
    }

    public function modifyUser(User $user): int {
        $dbHelper = new UserDao();
        if ($_SESSION['id'] == $user->getId()){
            return 0;
        }
        return $dbHelper->update($user);
    }

    public function removeUser(User $user): int {
        $dbHelper = new UserDao();
        if ($_SESSION['id'] == $user->getId()){
            return 0;
        }
        return $dbHelper->delete($user);
    }
    
    public function findUserById(int $id): ?User {
        $dbHelper = new UserDao();
        $u = new User($id);
        return $dbHelper->select($u);
    }

    /**
     * Searches user given a string 
     * @param string username
     * @param string password
     * @return null if not found user, object User if found, false if password don't match
     */

     public function searchUser(string $user, string $pass): mixed {

        $dbHelper = new UserDao();
        $data = $dbHelper->selectWhere('username', $user);
        $result = null;

        if (!is_null($data)){
            if (count($data) > 0){
                //return $data[0];
                $result = $data[0];
                if ($pass === $result->getPassword()){
                    $result = $data[0];
                }else{
                    $result = false;
                }
            }
        }else{
            $result = null;
        }

        return $result;

    }

    /* ==============  PRODUCTS FUNCTIONS METHODS ============== */
       
    public function findAllProducts(): array {
        $dbHelper = new ProductDao();
        return $dbHelper->selectAll();
    }

    public function findProductById(int $id): ?Product {
        $dbHelper = new ProductDao();
        $p = new Product($id);
        return $dbHelper->select($p);
    }

    public function addProduct(Product $product): int {
        $dbHelper = new ProductDao();
        return $dbHelper->insert($product);
    }

    public function modifyProduct(Product $product): int {
        $dbHelper = new ProductDao();
        return $dbHelper->update($product);
    }

    /* Removes a product to the database.
     * @param product Product
     * @return int The number of rows affected by the SQL query.
     * */
    public function removeProduct(Product $product): int
    {
        // Init category data access object.
        $P = new ProductDao();
        $W = new WarehouseProductDao();

        $result = $W->selectByProduct($product);

        // Collect the corresponding stock entities for this $product.
        $stockProduct = [];
        if (!is_null($result)) {
            foreach ($result as $stock) {
                array_push($stockProduct, $stock);
            }
        }
        //TRY to delelete in warehouseproduct table
        if (!empty($stockProduct)) {
            $tmpRowCounter = 0;
            foreach ($stockProduct as $stock) {
                $tmpRowCounter += (int) $W->delete($stock);
            }

            // At this point if $stockEntities wasn't empty,
            // but after trying to delete them from the warehousesproducts table,
            // and 0 rows have been affected, then something has failed. => We will return 0 as value.
            if ($tmpRowCounter === 0) {
                return 0;
            }
        }

        return $P->delete($product);
    }

    public function findProductByCategory(Category $category): array {
        $dbHelper = new ProductDao();
        return $dbHelper->selectAllCategory($category);
    }

    public function findProductByCode(string $code) {
        $dbHelper = new ProductDao();
        $P = new Product(0, $code);
        return $dbHelper->selectByCode($P);
    }
    
    public function findStocksByProduct(Product $product): array {
        $dbHelper = new WarehouseProductDao();
        return $dbHelper->selectWhere("product_id", $product->getId());
    }

    
    /* Finds all stock registers corresponding with the given warehouse.
     * @param warehouse Warehouse
     * @return array<Warehouse> | null
     * */
    public function findStocksByWarehouse(Warehouse $warehouse): ?array
    {
        $WP = new WarehouseProductDao();
        return $WP->selectByWarehouse($warehouse);
    }

   


    /* ==============  CATEGORIES FUNCTIONS METHODS ============== */
    
    public function findAllCategories(): array {
        $dbHelper = new CategoryDao();
        return $dbHelper->selectAll();
    }

    public function findCategoryById(int $id): ?Category {
        $dbHelper = new CategoryDao();
        $c = new Category($id);
        return $dbHelper->select($c);
    }

    public function findCategoryByCode(string $code): ?Category {
        $dbHelper = new CategoryDao();
        $c = new Category(0, $code);
        return $dbHelper->selectByCode($c);
    }

    public function modifyCategory(Category $category): int {
        $dbHelper = new CategoryDao();
        return $dbHelper->update($category);
    }

    /**
     * Removes category from database
     * Remove all products associated with it.(1.Warehouseproduct, 2.Product, 3.Category)
     * @param Category object
     * @return int number of rows deletes in the database. 
     */
    public function removeCategory(Category $category): int {
        
        //Inits
        $dbHelper = new CategoryDao();
        $P = new ProductDao();
        $W = new WarehouseProductDao();

        //Find products in this category.
        $productsCategory = $this->findProductByCategory($category);

        //If there is products asigned to this category
        if (count($productsCategory) > 0) {
            $stockProducts = array();
            foreach ($productsCategory as $product) {
                //Check with the warehouse database
                $result = $W->selectByProduct($product);

                if (!is_null($result)) {
                    foreach ($result as $stock) {
                        array_push($stockProducts, $stock);
                    }
                }
            }

            // Try to delete first int the warehouseproduct table
            if (count($stockProducts) > 0) {
                $wptCounter = 0;
                foreach ($stockProducts as $stock) {
                    $wptCounter += (int) $W->delete($stock);
                }

                //If the WarehouseProduct table Counter still remain 0 means no deleted rows, stop the function.
                if ($wptCounter === 0) {
                    return 0;
                }
            }
        
            //If the code above runs right, next step delete in the product table.
            $ptCounter = 0;
            foreach ($productsCategory as $product) {
                $ptCounter += (int) $P->delete($product);
            }
            
            //If the counter product table remains 0, means no deleted rows, stop the function
            if ($ptCounter === 0) {
                return 0;
            }
        }

        //If everithing above goes well then delete the category.
        return $dbHelper->delete($category);
    }

    /* ==============  WAREHOUSE FUNCTIONS METHODS ============== */
     
    public function findAllWarehouses(): array {
        $dbHelper = new WarehouseDao();
        return $dbHelper->selectAll();
    }

    /* Finds a warehouse by the given id in the database.
     * @param id int
     * @return Warehouse | null
     * */
    public function findWarehouseById(int $id): ?Warehouse
    {
        $dbHelper = new WarehouseDao();
        $W = new Warehouse($id);
        return $dbHelper->select($W);
    }

    /* Modifies a warehouse in the database.
     * @param warehouse Warehouse
     * @return int The number of rows affected by the SQL query.
     * */
    public function modifyWarehouse(Warehouse $warehouse): int
    {
        $dbHelper = new WarehouseDao();
        return $dbHelper->update($warehouse);
    }
}

