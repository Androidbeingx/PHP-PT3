<?php
namespace proven\store\model\persist;

require_once 'model/persist/StoreDb.php';
require_once 'model/Product.php';
require_once "model/Category.php";

use proven\store\model\persist\StoreDb as DbConnect;
use proven\store\model\Product as Product;
use proven\store\model\Category as Category;

/**
 * User database persistence class.
 * @author ProvenSoft & Andrea
 */
class ProductDao {

    /**
     * Encapsulates connection data to database.
     */
    private DbConnect $dbConnect;
    /**
     * table name for entity.
     */
    private static string $TABLE_NAME = 'products';
    /**
     * queries to database.
     */
    private array $queries;
    
    /**
     * constructor.
     */
    public function __construct() { 
        $this->dbConnect = new DbConnect();
        $this->queries = array();
        $this->initQueries();    
    }

    /**
     * defines queries to database.
     */
    private function initQueries() {
        //query definition.
        $this->queries['SELECT_ALL'] = \sprintf(
                "select * from %s", 
                self::$TABLE_NAME
        );
        $this->queries['SELECT_WHERE_ID'] = \sprintf(
                "select * from %s where id = :id", 
                self::$TABLE_NAME
        );
        $this->queries['SELECT_WHERE_CODE'] = \sprintf(
            "select * from %s where code = :code", 
            self::$TABLE_NAME
        );
        $this->queries["SELECT_WHERE_CATEGORY_ID"] = \sprintf(
            "select * from %s where category_id = :category_id",
            self::$TABLE_NAME
        );
        $this->queries['INSERT'] = \sprintf(
                "insert into %s (code, description, price, category_id) values (:code, :description, :price, :category_id)", 
                self::$TABLE_NAME
        );
        $this->queries['UPDATE'] = \sprintf(
                "update %s set code = :code, description = :description, price = :price, category_id = :category_id where id = :id", 
                self::$TABLE_NAME
        );
        $this->queries['DELETE'] = \sprintf(
                "delete from %s where id = :id", 
                self::$TABLE_NAME
        );              
    }

    /**
     * fetches a row from PDOStatement and converts it into an entity object.
     * @param $statement the statement with query data.
     * @return entity object with retrieved data or false in case of error.
     */
    private function fetchToEntity($statement): mixed {
        $row = $statement->fetch();
        if ($row) {
            $id = intval($row['id']);
            $code = $row['code'];
            $description = $row['description'];
            $price = floatval($row['price']);
            $categoryId = intval($row['category_id']);
            return new Product($id, $code, $description, $price, $categoryId);
        } else {
            return false;
        }
    }    
    
    /**
     * selects an entity given its id.
     * @param entity the entity to search.
     * @return entity object being searched or null if not found or in case of error.
     */
    public function select(Product $entity): ?Product {
        $data = null;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection(); 
            //query preparation.
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_ID']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                if ($stmt->rowCount()>0) {
                    // //set fetch mode.
                    $stmt->setFetchMode(\PDO::FETCH_ASSOC);
                    // // get one row at the time
                    if ($u = $this->fetchToEntity($stmt)){
                        $data = $u;
                     }
                } else {
                    $data = null;
                }
            } else {
                $data = null;
            }

        } catch (\PDOException $e) {
            $data = null;
        }   
        return $data;
    }

     /**
     * selects an entity given its code.
     * @param entity the entity to search.
     * @return entity object being searched or null if not found or in case of error.
     */
    public function selectByCode(Product $entity): ?Product {
        $data = null;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection(); 
            //query preparation.
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_CODE']);
            $stmt->bindValue(':code', $entity->getCode(), \PDO::PARAM_STR);
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                if ($stmt->rowCount()>0) {
                    // //set fetch mode.
                    $stmt->setFetchMode(\PDO::FETCH_ASSOC);
                    // // get one row at the time
                    if ($u = $this->fetchToEntity($stmt)){
                        $data = $u;
                     }
                } else {
                    $data = null;
                }
            } else {
                $data = null;
            }

        } catch (\PDOException $e) {
            $data = null;
        }   
        return $data;
    }

    /**
     * selects all entitites in database.
     * return array of entity objects.
     */
    public function selectAll(): array {
        $data = array();
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection(); 
            //query preparation.
            $stmt = $connection->prepare($this->queries['SELECT_ALL']);
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                if ($stmt->rowCount()>0) {
                   //fetch in class mode and get array with all data.                   
                    $stmt->setFetchMode(\PDO::FETCH_ASSOC);
                    
                    while ($u = $this->fetchToEntity($stmt)){
                        
                    array_push($data,$u);
                        
                    }
                } else {
                    $data = array();
                }
            } else {
                $data = array();
            }
        } catch (\PDOException $e) {
            $data = array();
        }   
        return $data;   
    }

    /**
     * selects all entitites in database By Category_Id.
     * @param Category object
     * @return array of entity objects.
     */
    public function selectAllCategory(Category $category): array {
        $data = array();
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection(); 
            //query preparation.
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_CATEGORY_ID']);
            $stmt->bindValue(':category_id', $category->getId(), \PDO::PARAM_INT);
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                if ($stmt->rowCount()>0) {
                   //fetch in class mode and get array with all data.                   
                   $stmt->setFetchMode(
                    \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE,
                    Product::class
                    );
                    $data = $stmt->fetchAll();
                } else {
                    $data = array();
                }
            } else {
                $data = array();
            }
        } catch (\PDOException $e) {
            $data = array();
        }   
        return $data;   
    }

    /**
     * selects entitites in database where field value.
     * return array of entity objects.
     */
    public function selectWhere(string $fieldname, string $fieldvalue): array {
        $data = array();
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection(); 
            //query preparation.
            $query = sprintf("select * from %s where %s = '%s'", 
                self::$TABLE_NAME, $fieldname, $fieldvalue);
            $stmt = $connection->prepare($query);
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                if ($stmt->rowCount()>0) {
                    $stmt->setFetchMode(\PDO::FETCH_ASSOC);
                    
                    while ($u = $this->fetchToEntity($stmt)){
                        
                    array_push($data,$u);
                }
                } else {
                    $data = array();
                }
            } else {
                $data = array();
            }
        } catch (\PDOException $e) {
            $data = array();
        }   
        return $data;   
    }

    /**
     * inserts a new entity in database.
     * @param entity the entity object to insert.
     * @return number of rows affected.
     */
    public function insert(Product $entity): int {
        $numAffected = 0;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection(); 
            //query preparation.
            $stmt = $connection->prepare($this->queries['INSERT']);
            $stmt->bindValue(':code', $entity->getCode(), \PDO::PARAM_STR);
            $stmt->bindValue(':description', $entity->getDescription(), \PDO::PARAM_STR);
            $stmt->bindValue(':price', $entity->getPrice(), \PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $entity->getCategoryId(), \PDO::PARAM_INT);
            //query execution.
            $success = $stmt->execute(); //bool
            $numAffected = $success ? $stmt->rowCount() : 0;
        } catch (\PDOException $e) {
            $numAffected = 0;
            print "Error Code <br>".$e->getCode();
            print "Error Message <br>".$e->getMessage();
            print "Strack Trace <br>".nl2br($e->getTraceAsString());
        }
        return $numAffected;
    }

    /**
     * updates entity in database.
     * @param entity the entity object to update.
     * @return number of rows affected.
     */
    public function update(Product $entity): int {
        $numAffected = 0;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection(); 
            //query preparation.
            $stmt = $connection->prepare($this->queries['UPDATE']);
            $stmt->bindValue(':code', $entity->getCode(), \PDO::PARAM_STR);
            $stmt->bindValue(':description', $entity->getDescription(), \PDO::PARAM_STR);
            $stmt->bindValue(':price', $entity->getPrice(), \PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $entity->getCategoryId(), \PDO::PARAM_INT);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            //query execution.
            $success = $stmt->execute(); //bool
            $numAffected = $success ? $stmt->rowCount() : 0;
        } catch (\PDOException $e) {
            $numAffected = 0;
            print "Error Code <br>".$e->getCode();
            print "Error Message <br>".$e->getMessage();
            print "Strack Trace <br>".nl2br($e->getTraceAsString());
        }
        return $numAffected;  
    }

    /**
     * deletes entity from database.
     * @param entity the entity object to delete.
     * @return number of rows affected.
     */
    public function delete(Product $entity): int {
        $numAffected = 0;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection(); 
            //query preparation.            
            $stmt = $connection->prepare($this->queries['DELETE']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            $success = $stmt->execute(); //bool
            $numAffected = $success ? $stmt->rowCount() : 0;
        } catch (\PDOException $e) {
            $numAffected = 0;
            print "Error Code <br>".$e->getCode();
            print "Error Message <br>".$e->getMessage();
            print "Strack Trace <br>".nl2br($e->getTraceAsString());
        }
        return $numAffected;        
    }    
    
}
