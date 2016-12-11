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
    private $qty;
    
    public function __construct() {

        $this->id = -1;
        $this->name = '';
        $this->description = '';
        $this->price = 0;
        $this->qty = 0;
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
    
    public function getQty() {
        return $this->Qty;
    }
    
    public function setName($newName) {
        $this->name = $newName;
    }
    
    public function setDescription($newDescription) {
        $this->description = $newDescription;
    }
    
    public function setPrice($newPrice) {
        $this->price = $newPrice;
    }
    
    public function setQty($newQty) {
        $this->qty = $newQty;
    }



}
