<?php

/**
 * Description of Product
 *
 * @author kasia
 */
class Product {

    private $id;
    private $name;
    private $description;
    private $price;
    private $quantity;
    private $groupId;
    private $deleted;

    public function __construct() {

        $this->id = -1;
        $this->name = '';
        $this->description = '';
        $this->price = 0;
        $this->quantity = 0;
        $this->groupId = -1;
        $this->deleted = false;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getGroupId() {
        return $this->groupId;
    }

    public function isDeleted() {
        return $this->deleted;
    }

    public function setName($newName) {
        if (is_string($newName)) {
            $this->name = $newName;
        }
        return $this;
    }

    public function setDescription($newDescription) {
        if (is_string($newDescription)) {
            $this->description = $newDescription;
        }
        return $this;
    }

    public function setPrice($newPrice) {
        if (is_numeric($newPrice)) {
            $this->price = $newPrice;
        }
        return $this;
    }

    public function setQuantity($newQuantity) {
        if (is_numeric($newQuantity)) {
            $this->quantity = $newQuantity;
        }
        return $this;
    }

    public function setGroupId($newGroupId) {
        if (is_integer($newGroupId)) {
            $this->groupId = $newGroupId;
        }
        return $this;
    }

    public function setDeleted() {
        $this->deleted = true;
        return $this;
    }

    // wyswietlanie wszystkich produktow

    static public function loadAllProducts(mysqli $conn) {

        $sql = "SELECT * "
                . "FROM Product "
                . "WHERE deleted = false "
                . "ORDER BY groupId, name";
        $tab = [];

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $loadProduct = new Product();
                $loadProduct->id = $row['id'];
                $loadProduct->groupId = $row['groupId'];
                $loadProduct->name = $row['name'];
                $loadProduct->description = $row['description'];
                $loadProduct->price = $row['price'];
                $loadProduct->quantity = $row['quantity'];
                $loadProduct->deleted = $row['deleted'];
                $tab[] = $loadProduct;
            }
        }
        return $tab;
    }

    // wyswietlanie wszystkich produktow po id grupy

    static public function loadAllProductsByGroupId(mysqli $conn, $groupId) {
        
        $groupId = $conn->real_escape_string($groupId);
        if (!is_integer($groupId)) {
            return null;
        }
        $sql = "SELECT * "
                . "FROM Product "
                . "WHERE deleted = false and groupId = $groupId "
                . "ORDER BY name";
        $tab = [];

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $loadProduct = new Product();
                $loadProduct->id = $row['id'];
                $loadProduct->groupId = $row['groupId'];
                $loadProduct->name = $row['name'];
                $loadProduct->description = $row['description'];
                $loadProduct->price = $row['price'];
                $loadProduct->quantity = $row['quantity'];
                $loadProduct->deleted = $row['deleted'];
                $tab[] = $loadProduct;
            }
        }
        return $tab;
    }

    // wyswietlanie produktu po id

    static public function loadProductById(mysqli $conn, $id) {
        
        $id = $conn->real_escape_string($id);
        if (!is_integer($id)) {
            return null;
        }
        
        $sql =    "SELECT * "
                . "FROM Product "
                . "WHERE id=$id";

        $result = $conn->query($sql);

        if ($result && $result->num_rows == 1) {

            $row = $result->fetch_assoc();
            $loadProduct = new Product();
            $loadProduct->id = $row['id'];
            $loadProduct->groupId = $row['groupId'];
            $loadProduct->name = $row['name'];
            $loadProduct->price = $row['price'];
            $loadProduct->quantity = $row['quantity'];
            $loadProduct->description = $row['description'];
            $loadProduct->deleted = $row['deleted'];
            return $loadProduct;
        }

        return null;
    }
    
    // zapisywanie produktu do bazy danych
    
    public function saveProductToDB(mysqli $conn) {

        if ($this->id == -1) {


            $sql = "INSERT INTO Product(name, description, price,  quantity, groupId, deleted)
                    VALUES ('$this->name','$this->description', '$this->price', "
                 . " '$this->quantity', '$this->groupId', '$this->deleted' )";

            if ($conn->query($sql)==true) {
                $this->id = $conn->insert_id;
                return true; 
            }
            
        } else {
            
            $sql =    "UPDATE Product "
                    . "SET name='$this->name',"
                    . "description='$this->description',"
                    . "price='$this->price', "
                    . "quantity ='$this->quantity',"
                    . "groupId ='$this->groupId', "
                    . "deleted='$this->deleted'  "
                    . "WHERE id=$this->id";
       
            if ($conn->query($sql)==true) {
                return true;
            }
        }

        return false;
    }
    
    //usuwanie produktu
    
    public function deleteProduct (mysqli $conn) {
       
        if ($this->id != -1) {
            $sql = "DELETE FROM Product "
                    . "WHERE id=$this->id";
          
            if ($conn->query($sql) == true) {
                // nawet po usunięciu obiekt z właściwościami w bazie danych 
                // będzie istniał dlatego dajemy mu -1, strona się odświeża
                
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }
}
