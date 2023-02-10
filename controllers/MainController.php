<?php

namespace proven\store\controllers;

require_once 'lib/ViewLoader.php';
require_once 'lib/Validator.php';

require_once 'model/StoreModel.php';
require_once 'model/User.php';

use proven\store\model\StoreModel as Model;
use proven\lib\ViewLoader as View;

use proven\lib\views\Validator as Validator;

/**
 * Main controller
 * @author ProvenSoft & Andrea
 */
class MainController {
    /**
     * @var ViewLoader
     */
    private $view;
    /**
     * @var Model 
     */
    private $model;
    /**
     * @var string  
     */
    private $action;
    /**
     * @var string  
     */
    private $requestMethod;

    public function __construct() {
        //instantiate the view loader.
        $this->view = new View();
        //instantiate the model.
        $this->model = new Model();
    }

    /* ============== HTTP REQUEST FUNCTIONS ============== */

    /**
     * processes requests from client, regarding action command.
     */
    public function processRequest() {
        $this->action = "";
        //retrieve action command requested by client.
        if (\filter_has_var(\INPUT_POST, 'action')) {
            $this->action = \filter_input(\INPUT_POST, 'action');
        } else {
            if (\filter_has_var(\INPUT_GET, 'action')) {
                $this->action = \filter_input(\INPUT_GET, 'action');
            } else {
                $this->action = "home";
            }
        }

        //retrieve request method.
        if (\filter_has_var(\INPUT_SERVER, 'REQUEST_METHOD')) {
            $this->requestMethod = \strtolower(\filter_input(\INPUT_SERVER, 'REQUEST_METHOD'));
        }

        //process action according to request method.
        switch ($this->requestMethod) {
            case 'get':
                $this->doGet();
                break;
            case 'post':
                $this->doPost();
                break;
            default:
                $this->handleError();
                break;
        }
    }

    /**
     * processes get requests from client.
     */
    private function doGet() {
        //process action.
        switch ($this->action) {
            case 'home':
                $this->doHomePage();
                break;
            case 'user':
                $this->doUserMng();
                break;
            case 'user/edit':
                $this->doUserEditForm("edit");
                break;
            case 'category/edit':
                $this->doCategoryEditForm("edit");
                break;
            case 'warehouse/edit':
                $this->doWarehouseEditForm("edit");
                break;            
            case 'category':
                $this->doCategoryMng();
                break;
            case 'product':
                $this->doProductMng();
                break;
            case 'warehouse':
                $this->doWareHouseMng();
                break;
            case 'loginform':
                $this->doLoginForm();
                break;
            case 'user/logout';
                $this->doLogout();
                break;    
            default:  //processing default action.
                $this->handleError();
                break;
        }
    }

    /**
     * processes post requests from client.
     */
    private function doPost() {
        //process action.
        switch ($this->action) {
            case 'user/login':
                $this->doSearchUser();
                break;
            case 'user/role':
                $this->doListUsersByRole();
                break;
            case 'user/form':
                $this->doUserEditForm("add");
                break;
            case 'user/add': 
                $this->doUserAdd();
                break;
            case 'user/modify': 
                $this->doUserModify();
                break;
            case 'user/remove': 
                $this->doUserRemove();
                break;
            //CATEGORY    
            case 'category/modify': 
                $this->doCategoryModify();
                break;
            case "category/removeConfirmation":
                $this->doCategoryRemovalConfirmation();
                break;
            case "category/cancelRemove":
                $this->doCategoryMng();
                break;
            case "category/remove":
                $this->doCategoryRemove();
                break;
            //PRODUCTS    
            case 'product/category':
                $this->doListProductsByCategory();
                break;
            case 'product/form':
                $this->doProductEditForm("add");
                break;
            case 'product/add': 
                $this->doProductAdd();
                break;
            case "product/stock":
                $this->doProductStockInfo();
                break;
            case "product/searchCode":
                $this->doStockProduct();
                break;            
            case 'product/edit':
                $this->doProductEditForm("edit");
                break;  
            case 'product/modify': 
                $this->doProductModify();
                break;
            case "product/removeConfirmation":
                $this->doProductRemovalConfirmation();
                break;
            case "product/cancelRemove":
                $this->doProductMng();
                break;
            case "product/remove":
                $this->doProductRemove();
                break;
            //WAREHOUSE    
            case "warehouse/stock":
                $this->doWarehouseStockInfo();
                break; 
            case 'warehouse/modify': 
                $this->doWarehouseModify();
                break;                                          
            default:  //processing default action.
                $this->doHomePage();
                break;
        }
    }

    /* ============== NAVIGATION CONTROL METHODS ============== */

    /**
     * handles errors.
     */
    public function handleError() {
        $this->view->show("message.php", ['message' => 'Something went wrong!']);
    }

    /**
     * displays home page content.
     */
    public function doHomePage() {
        $this->view->show("home.php", []);
    }

    /* ============== SESSION CONTROL METHODS ============== */

    /**
     * displays login form page.
     */
    public function doLoginForm() {
        $this->view->show("login/loginform.php", []);  //initial prototype version;
    }

    /**
     * Destroys session
     */
    public function doLogout (){
        if (isset($_SESSION["name"])) {  
            session_destroy();
            header("Location: index.php");   

        }
    }

   /**
     * Search user in the database with username and password
     * Do login
     */ 

     private function doSearchUser(){

        //Get parameters from form
        $username = htmlspecialchars(trim($_POST['user']));
        $password = htmlspecialchars(trim($_POST['pass']));
        

        $validata = ($username !== false ) && ($password !== false);
        
        if ($validata){
            //search by username, checks matching password
            $userfound = $this->model->searchUser($username, $password);
            
            if ($userfound){
                header("Location: index.php");
                $_SESSION['id'] = $userfound->getId();
                $_SESSION['name'] = $userfound->getFirstname();
                $_SESSION['surname'] = $userfound->getLastname();
                $_SESSION['userrole'] = $userfound->getRole();
                

            }else if (is_null($userfound)){
                
                $data['message'] = "User not found";
                $this->view->show("login/loginform.php", $data);
            }else if ($userfound === false){
                $data['message'] = "Password incorrect";
                $this->view->show("login/loginform.php", $data);
            }
        } else{
            $data['message'] = "Invalid credentials";
            $this->view->show("login/loginform.php", $data);
        }


    }

    /* ============== USER MANAGEMENT CONTROL METHODS ============== */

    /**
     * displays user management page.
     */
    public function doUserMng() {
        if (isset($_SESSION['userrole'])){
            if ($_SESSION['userrole'] == 'staff'){
                $this->view->show("user/usermanage.php", ['message' => "You must be admin to acces"]); 
            }else{
                //get all users.
                $result = $this->model->findAllUsers();
                //pass list to view and show.
                $this->view->show("user/usermanage.php", ['list' => $result]);        
                
            }
        }else{
            $this->view->show("user/usermanage.php", ['message' => "You must be logged to acces"]);
        }
        
    }

    /**
     * Search useres give role
     * @return array of users if found
     */

    public function doListUsersByRole() {
        //get role sent from client to search.
        $roletoSearch = \filter_input(INPUT_POST, "search");
        if ($roletoSearch !== false) {
            //get users with that role.
            $result = $this->model->findUsersByRole($roletoSearch);
            //pass list to view and show.
            $this->view->show("user/usermanage.php", ['list' => $result]);   
        }  else {
            //pass information message to view and show.
            $this->view->show("user/usermanage.php", ['message' => "No data found"]);   
        }
    }

    /**
     * Display User edit form
     */
    public function doUserEditForm(string $mode) {
        $data = array();
        if ($mode != 'user/add') {
            //fetch data for selected user
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            
            if (($id !== false) && (!is_null($id))) {
                $user = $this->model->findUserById($id);
                if (!is_null($user)) {
                    $data['user'] = $user;
                }
             }
             $data['mode'] = $mode;
        }
        $this->view->show("user/userdetail.php", $data);  //initial prototype version.
    }


    /**
     * Add a User in the database
     */
    public function doUserAdd() {
        //get user data from form and validate
        $user = Validator::validateUser(INPUT_POST);
        //add user to database
        if (!is_null($user)) {
            $result = $this->model->addUser($user);
            $message = ($result > 0) ? "Successfully added":"Error adding";
            $this->view->show("user/userdetail.php", ['mode' => 'add', 'message' => $message]);
        } else {
            $message = "Invalid data";
            $this->view->show("user/userdetail.php", ['mode' => 'add', 'message' => $message]);
        }
    }

    /**
     * Modify an user from database
     */
    public function doUserModify() {
        //get user data from form and validate
        $user = Validator::validateUser(INPUT_POST);
        //add user to database
            if (!is_null($user)) {
                if ($user->getId() == $_SESSION['id']){
                    $message = "CANNOT modify the actual user";
                    $this->view->show("user/userdetail.php", ['mode' => 'edit', 'message' => $message]);
                }else{
                    $result = $this->model->modifyUser($user);
                    $message = ($result > 0) ? "Successfully modified":"Error modifying";
                    $this->view->show("user/userdetail.php", ['mode' => 'edit', 'message' => $message]);
                }
                
            } else {
                $message = "Invalid data";
                $this->view->show("user/userdetail.php", ['mode' => 'edit', 'message' => $message]);
            }
        
    }

    /**
     * Delete an User from database
     */
    public function doUserRemove() {
        //get user data from form and validate
        $user = Validator::validateUser(INPUT_POST);
        //delete user to database
       
            if (!is_null($user)) {
                if ($user->getId() == $_SESSION['id']){
                    $message = "CANNOT delete the actual user";
                    $this->view->show("user/userdetail.php", ['mode' => 'edit', 'message' => $message]);
                }else{
                    $result = $this->model->removeUser($user);
                    $message = ($result > 0) ? "Successfully removed":"Error removing";
                    $this->view->show("user/userdetail.php", ['mode' => 'edit', 'message' => $message]);
                }
                
            } else {
                $message = "Invalid data";
                $this->view->show("user/userdetail.php", ['mode' => 'edit', 'message' => $message]);
            }
        
        
    } 
    
    /* ============== CATEGORY MANAGEMENT CONTROL METHODS ============== */

    /**
     * displays category management page.
     */
    public function doCategoryMng() {
        
        $result = $this->model->findAllCategories();
        if (count($result) >= 1 ){
            $this->view->show("category/categorymanage.php", ['list' => $result]);
        }else{
            $this->view->show("category/categorymanage.php", ['message' => "No data found"]);
        }
        
        
    }
    /**
     * Displays CAtegory edit form
     */
    public function doCategoryEditForm(string $mode) {
        $data = array();
        if ($mode != 'category/add') {
            //fetch data for selected category
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (($id !== false) && (!is_null($id))) {
                $category = $this->model->findCategoryById($id);
                if (!is_null($category)) {
                    $data['category'] = $category;
                }
             }
             $data['mode'] = $mode;
        }
        $this->view->show("category/categorydetail.php", $data);  //initial prototype version.
    }
 
    /**
     * Modify a category
     */
    public function doCategoryModify() {
        //get user data from form and validate
        $category = Validator::validateCategory(INPUT_POST);
        //add user to database
        if (!is_null($category)) {
            $result = $this->model->modifyCategory($category);
            $message = ($result > 0) ? "Successfully modified":"Error modifying";
            $this->view->show("category/categorydetail.php", ['mode' => 'add', 'message' => $message]);
        } else {
            $message = "Invalid data";
            $this->view->show("category/categorydetail.php", ['mode' => 'add', 'message' => $message]);
        }
    } 

    /* Redirects the user to the view of the confirmation of deleting a category.
     * @return void
     * */
    public function doCategoryRemovalConfirmation() {
        if (isset($_SESSION["userrole"])) {
             
                $id = filter_input(INPUT_POST, "categoryId", FILTER_VALIDATE_INT);
                $categoryToDelete = $this->model->findCategoryById($id);
                $this->view->show("category/categoryRemovalConfirmation.php", [
                    "category" => $categoryToDelete,
                ]);
           
        } else {
            $this->view->show("message.php", ["message" => "Must be logged to enter this page!"]);
        }
    }

    /**
     * Removes an existing category.
     */

    public function doCategoryRemove() {
        if (isset($_SESSION["userrole"])) {

                $affectedRowNum = 0;
                $deletionResult = false;

                // Find the category
                $id = filter_input(INPUT_POST, "categoryId", FILTER_VALIDATE_INT );
                $categoryToDelete = $this->model->findCategoryById($id);

                if (!is_null($categoryToDelete)) {
                    $affectedRowNum = $this->model->removeCategory(
                        $categoryToDelete
                    );
                }

                if ($affectedRowNum > 0) {
                    $deletionResult = true;
                }

                $allCategories = $this->model->findAllCategories();

                $data = ["list" => $allCategories, "deletionResult" => $deletionResult, "deletedId" => $id];

                $this->view->show("category/categorymanage.php", $data);
            
        } else {
            $this->view->show("message.php", [
                "message" => "Don't have permission to visit this page!",
            ]);
        }
    
    }

    /* ============== PRODUCT MANAGEMENT CONTROL METHODS ============== */

    /**
     * displays product management page.
     */
    public function doProductMng() {

        $result = $this->model->findAllProducts();
        $this->view->show("product/productmanage.php", ['list' => $result]);
    }

    /**
     * Filter Products by category give by input
     */
    public function doListProductsByCategory() {
        //get category sent from client to search.
        $categorytoSearch = \filter_input(INPUT_POST, "search");
        
        if ($categorytoSearch !== false) {
            $result = array();
            //get category with code in the input
            $foundCategory = $this->model->findCategoryByCode($categorytoSearch);
            //get products with that category.
            if ($foundCategory) {
                $result = $this->model->findProductByCategory($foundCategory);
            }    
            //pass list to view and show.
            $this->view->show("product/productmanage.php", ['list' => $result]);   
        }elseif ($categorytoSearch === ""){
            $this->doProductMng();
        }else {
            //pass information message to view and show.
            $this->view->show("product/productmanage.php", ['message' => "No data found"]);   
        }
    }

    /**
     * Display Form to edit or add Product
     */
    public function doProductEditForm(string $mode) {
        $data = array();
        if ($mode != 'product/add') {
            //fetch data for selected user
            $id = filter_input(INPUT_POST, 'productId', FILTER_VALIDATE_INT);
            
            if (($id !== false) && (!is_null($id))) {
                $product = $this->model->findProductById($id);
                if (!is_null($product)) {
                    $data["product"] = $product;
                }
             }
             $data['mode'] = $mode;
        }
        $this->view->show("product/productdetail.php", $data);  
    }   
    
  /**
   * If the data is correct add Product
   */
    public function doProductAdd() {
        //get user data from form and validate
        $product= Validator::validateProduct(INPUT_POST);
        //add user to database
        if (!is_null($product)) {
            $result = $this->model->addProduct($product);
            $message = ($result > 0) ? "Successfully added":"Error adding";
            $this->view->show("product/productdetail.php", ['mode' => 'add', 'message' => $message]);
        } else {
            $message = "Invalid data";
            $this->view->show("product/productdetail.php", ['mode' => 'add', 'message' => $message]);
        }
    }

    /* 
    * Gets table stocks by warehouse
     * */
    private function formatTableData(
        array $warehouses,
        array $productStockRegisters
    ): array {
        $tableData = [];

        // First get those warehouses, which have the product in stock.
        foreach ($warehouses as $warehouse) {
            foreach ($productStockRegisters as $stock) {
                if (
                    (int) $stock->getWarehouseId() === (int) $warehouse->getId()
                ) {
                    \array_push($tableData, [
                        "id" => $warehouse->getId(),
                        "code" => $warehouse->getCode(),
                        "address" => $warehouse->getAddress(),
                        "stock" => $stock->getStock(),
                    ]);
                }
            }
        }

        
        $missingProductWarehouses = array();
        $stockid = array();
        $allwarehouseid = array();
        $notstockid = array();

        foreach ($tableData as $t) {
            array_push($stockid, $t['id']);
        }

        foreach ($warehouses as $warehouse){
            array_push($allwarehouseid, $warehouse->getId());
        }


        foreach ($allwarehouseid as $idp){
            $in = in_array($idp, $stockid);
            if (!$in) {
                array_push($notstockid, $idp);
            }
        }

       foreach ($warehouses as $warehouse){
            foreach ($notstockid as $id){
                if ($warehouse->getId() == $id){
                    array_push($missingProductWarehouses, $warehouse);
                }
            }
       }
       
        // The rest of werehouse without stock with parameter 0
        foreach ($missingProductWarehouses as $warehouse) {
            \array_push($tableData, [
                "id" => $warehouse->getId(),
                "code" => $warehouse->getCode(),
                "address" => $warehouse->getAddress(),
                "stock" => 0,
            ]);
        }
        return $tableData;
    }

        
    /**
     * Modify a product
     */
    public function doProductModify() {
        //get user data from form and validate
        $product = Validator::validateProduct(INPUT_POST);
        //add user to database
        if (!is_null($product)) {
            $result = $this->model->modifyProduct($product);
            $message = ($result > 0) ? "Successfully modified":"Error modifying";
            $this->view->show("product/productdetail.php", ['mode' => 'add', 'message' => $message]);
        } else {
            $message = "Invalid data";
            $this->view->show("product/productdetail.php", ['mode' => 'add', 'message' => $message]);
        }
    }


    /* Redirects the user to the view of deletion
    * confirmation of a product.
    * @return void
    * */
   public function doProductRemovalConfirmation()
   {
       if (isset($_SESSION["userrole"])) {
          
               $id = filter_input(INPUT_POST, "productId", FILTER_VALIDATE_INT );
               $searchedCategoryCode = filter_input(INPUT_POST, "searchedCategoryCode" );

               $productToDelete = $this->model->findProductById($id);
               
               $this->view->show("product/productRemovalConfirmation.php", [
                   "product" => $productToDelete,
                   "searchedCategoryCode" => $searchedCategoryCode,
               ]);
               
       } else {
           $this->view->show("message.php", [
               "message" => "Don't have permission to visit this page!",
           ]);
       }
   }

   /* Removes a product from the database.
    * @return void
    * */
   public function doProductRemove()
   {
       if (isset($_SESSION["userrole"])) {
           
               $affectedRowNum = 0;
               $deletionResult = false;
               $products = null;

               $id = filter_input(INPUT_POST,"productId",  FILTER_VALIDATE_INT );
               $searchedCategoryCode = filter_input( INPUT_POST,   "searchedCategoryCode"  );

               // Remove the product if it's found by id.
               $productToDelete = $this->model->findProductById($id);
               if (!is_null($productToDelete)) {
                   $affectedRowNum = $this->model->removeProduct(
                       $productToDelete
                   );
               }

               // Check if the deletion was successful,
               if ($affectedRowNum > 0) {
                   $deletionResult = true;
               }

               // Get products associated with the same
               // category code with which they were searched.
               $foundCategory = $this->model->findCategoryByCode(
                   $searchedCategoryCode
               );
               print_r($foundCategory);
               if ($foundCategory) {
                   $products = $this->model->findProductByCategory(
                       $foundCategory
                   );
               }

               // Pass the result datas to view.
               $data = [ "list" => $products, "deletionResult" => $deletionResult, "deletedId" => $id ];

               $this->view->show("product/productmanage.php", $data);
           
       } else {
           $this->view->show("message.php", [
               "message" => "Don't have permission to visit this page!",
           ]);
       }
   }
    /* Retrives the data for a product
     * from WarehouseProductDao and WarehouseDao.
     * @return void
     * */
    public function doProductStockInfo()
    {
        $data = [];
        $data["tableData"] = null;

        //fetch data for selected product
        $id = filter_input(INPUT_POST, "productId", FILTER_VALIDATE_INT);

        if ($id !== false && !is_null($id)) {
            // Get product
            $product = $this->model->findProductById($id);
            if (!is_null($product)) {
                $data["product"] = $product;
            }

            // Get product-warehouse infos.
            $productStockRegisters = $this->model->findStocksByProduct($product );   
            if (!is_null($productStockRegisters)) {
                $data["productStockRegisters"] = $productStockRegisters;
            }

            // Get warehouse infos.
            $warehouses = $this->model->findAllWarehouses();
            if (!is_null($warehouses)) {
                $data["warehouses"] = $warehouses;
            }

            if (!is_null($warehouses) && !is_null($productStockRegisters)) {
                $data["tableData"] = $this->formatTableData($warehouses, $productStockRegisters );
            }
        }

        $this->view->show("product/productStock.php", $data);
    }

    
     /* Gets an input (product code) from a search form,
     * gets the warehouse and stock data by it, then returns it to the view.
     * @return void
     * */
    public function doStockProduct()
    {
        $data = [];
        //get role sent from client to search.
        $productToSearch = \filter_input(INPUT_POST, "search");
        
        if ($productToSearch !== false) {
            //get users with that role.
            var_dump($productToSearch);
            $foundProduct = $this->model->findProductByCode($productToSearch);

            if (!is_null($foundProduct)) {
                $data["product"] = $foundProduct;
                // Get product-warehouse infos.
                $productStockRegisters = $this->model->findStocksByProduct(
                    $foundProduct
                );
                if (!is_null($productStockRegisters)) {
                    $data["productStockRegisters"] = $productStockRegisters;
                }

                // Get warehouse infos.
                $warehouses = $this->model->findAllWarehouses();
                if (!is_null($warehouses)) {
                    $data["warehouses"] = $warehouses;
                }

                $data["tableData"] = $this->formatTableData($warehouses, $productStockRegisters);
            }
            //pass list to view and show.
            $this->view->show("product/productStock.php", $data);
        } else {
            //pass information message to view and show.
            $this->view->show("product/productmanage.php", [
                "message" => "No data found",
            ]);
        }
    }
    
    /* ============== WAREHOUSE MANAGEMENT CONTROL METHODS ============== */


    /**
     * displays product management page.
     */
    public function doWarehouseMng() {

        if (!isset($_SESSION['userrole'])){
            $this->view->show("warehouse/warehousemanage.php", ['message' => "You must be logged to acces"]);  
        }else{
            $result = $this->model->findAllWarehouses();
        $this->view->show("warehouse/warehousemanage.php", ['list' => $result]);
        }

        
    }

    /* Searches for a warehouse by it's id
     * and passes an Warehouse object to the view.
     * @return void
     * */
    
    public function doWarehouseEditForm(string $mode) {
        $data = array();
        if ($mode != 'warehouse/add') {
            //fetch data for selected warehouse
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (($id !== false) && (!is_null($id))) {
                $warehouse = $this->model->findWarehouseById($id);
                if (!is_null($warehouse)) {
                    $data['warehouse'] = $warehouse;
                }
             }
             $data['mode'] = $mode;
        }
        $this->view->show("warehouse/warehousedetail.php", $data);  //initial prototype version.
    }
 
    /**
     * Modify warehouse entry
     */
    public function doWarehouseModify() {
        //get user data from form and validate
        $warehouse = Validator::validateWarehouse(INPUT_POST);
        //add user to database
        if (!is_null($warehouse)) {
            $result = $this->model->modifyWarehouse($warehouse);
            $message = ($result > 0) ? "Successfully modified":"Error modifying";
            $this->view->show("warehouse/warehousedetail.php", ['mode' => 'add', 'message' => $message]);
        } else {
            $message = "Invalid data";
            $this->view->show("warehouse/warehousedetail.php", ['mode' => 'add', 'message' => $message]);
        }
    } 

     /* Formats the array<Products> and array<WarehouseProduct>
     * data into a more organized
     * assoc. array for the view to display it in a table format.
     * @return array<array<str, str | int>>
     * */
    private function formatTableDataWS(array $products, array $warehouseStockRegisters): array {
        $tableData = [];
        // First get those products, which have the product in stock.
        foreach ($products as $product) {
            foreach ($warehouseStockRegisters as $stock) {
                
                if ((int) $stock->getProductId() === (int) $product->getId()) {
                   
                    \array_push($tableData, [
                        "id" => $product->getId(),
                        "code" => $product->getCode(),
                        "description" => $product->getDescription(),
                        "price" => $product->getPrice(),
                        "category_id" => $product->getCategoryId(),
                        "stock" => $stock->getStock(),
                    ]);
                }
            }
            
        }
       
        
        $missingProductWarehouses = array();
        $stockid = array();
        $allproductsid = array();
        $notstockid = array();

        foreach ($tableData as $t) {
            array_push($stockid, $t['id']);
        }

        foreach ($products as $product){
            array_push($allproductsid, $product->getId());
        }


        foreach ($allproductsid as $idp){
            $in = in_array($idp, $stockid);
            if (!$in) {
                array_push($notstockid, $idp);
            }
        }

       foreach ($products as $product){
            foreach ($notstockid as $id){
                if ($product->getId() == $id){
                    array_push($missingProductWarehouses, $product);
                }
            }
       }


    // Finally fetch the filtered products
        // with stock value as 0.
        foreach ($missingProductWarehouses as $product) {
            \array_push($tableData, [
                "id" => $product->getId(),
                "code" => $product->getCode(),
                "description" => $product->getDescription(),
                "price" => $product->getPrice(),
                "category_id" => $product->getCategoryId(),
                "stock" => 0,
            ]);
        }

        return $tableData;
    }

    /* Retrives the data for a warehouse
     * from WarehouseProductDao and WarehouseDao.
     * @return void
     * */
    public function doWarehouseStockInfo()
    {
        $data = [];
        $data["tableData"] = null;

        //fetch data for selected product
        $id = filter_input(INPUT_POST, "warehouseId", FILTER_VALIDATE_INT);

        if ($id !== false && !is_null($id)) {
            // Get warehouse
            $warehouse = $this->model->findWarehouseById($id);
            if (!is_null($warehouse)) {
                $data["warehouse"] = $warehouse;
            }

            // Get warehouse-product infos.
            $warehouseStockRegisters = $this->model->findStocksByWarehouse($warehouse);
            
            if (!is_null($warehouseStockRegisters)) {
                $data["warehouseStockRegister"] = $warehouseStockRegisters;
            }

            // Get product infos.
            $products = $this->model->findAllProducts();
            
            if (!is_null($products)) {
                $data["products"] = $products;
            }

            if (!is_null($products) && !is_null($warehouseStockRegisters)) {
                $data["tableData"] = $this->formatTableDataWS($products,$warehouseStockRegisters);
            }
        }

        $this->view->show("warehouse/warehouseStock.php", $data);
    }
    
}
