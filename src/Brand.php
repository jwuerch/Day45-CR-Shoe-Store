<?php

    class Brand {
        private $name;
        private $id;

        public function __construct($name, $id = null) {
            $this->name = $name;
            $this->id = $id;
        }

        //Setters;
        public function setName($new_name) {
            $this->name = $new_name;
        }

        //Getters;
        public function getName() {
            return $this->name;
        }

        public function getId() {
            return $this->id;
        }

        public function save() {
            $GLOBALS['DB']->exec("INSERT INTO brands (name) VALUES ('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        public function update($new_name) {
            $GLOBALS['DB']->exec("UPDATE brands SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        public function addStore($store) {
            $GLOBALS['DB']->exec("INSERT INTO stores_brands (store_id, brand_id) VALUES ({$store->getId()}, {$this->getId()});");
        }

        public function getStores() {
            $returned_stores = $GLOBALS['DB']->query("SELECT stores.* FROM brands
            JOIN stores_brands ON (brands.id = stores_brands.brand_id)
            JOIN stores ON (stores_brands.store_id = stores.id)
            WHERE brand_id = {$this->getId()}");
            $stores = array();
            foreach ($returned_stores as $store) {
                $name = $store['name'];
                $id = $store['id'];
                $location = $store['location'];
                $new_store = new Store($name, $location, $id);
                array_push($stores, $new_store);
            }
            return $stores;
        }

        static function getAll() {
            $returned_brands = $GLOBALS['DB']->query("SELECT * FROM brands;");
            $brands = array();
            foreach ($returned_brands as $brand) {
                $id = $brand['id'];
                $name = $brand['name'];
                $new_brand = new Brand($name, $id);
                array_push($brands, $new_brand);
            }
            return $brands;
        }

        static function deleteAll() {
            $GLOBALS['DB']->exec("DELETE FROM brands;");
        }


        static function find($search_id) {
            $all_brands = Brand::getAll();
            $found_brand = null;
            foreach ($all_brands as $brand) {
                if ($search_id == $brand->getId()) {
                    $found_brand = $brand;
                }
            }
            return $found_brand;
        }
    }

 ?>
