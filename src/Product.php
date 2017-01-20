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
        return $this->Qty;
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
    


}
