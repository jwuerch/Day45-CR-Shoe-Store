<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Brand.php";
    require_once "src/Store.php";

    $server = 'mysql:host=localhost;dbname=shoes_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BrandTest extends PHPUnit_Framework_TestCase {

        protected function teardown() {
            Brand::deleteAll();
            Store::deleteAll();
        }

        function testGetName() {
            //Arrange;
            $name = 'Adidas';
            $test_brand = new Brand($name);

            //Act;
            $result = $test_brand->getName();

            //Assert;
            $this->assertEquals($name, $result);
        }

        function testGetId() {
            //Arrange;
            $name = 'Adidas';
            $id = 1;
            $test_brand = new Brand($name, $id);

            //Act;
            $result = $test_brand->getId();

            //Assert;
            $this->assertEquals($id, $result);
        }

        function testSave() {
            //Arrange;
            $name = 'Adidas';
            $id = 1;
            $test_brand = new Brand($name, $id);

            //Act;
            $test_brand->save();
            $result = Brand::getAll();

            //Arrange;
            $this->assertEquals($test_brand, $result[0]);

        }

        function testGetAll() {
            //Arrange;
            $name = 'Adidas';
            $id = 1;
            $test_brand = new Brand($name, $id);
            $test_brand->save();

            $name2 = 'Nike';
            $id2 = 2;
            $test_brand2 = new Brand($name2, $id2);
            $test_brand2->save();

            //Act;
            $result = Brand::getAll();

            //Assert;
            $this->assertEquals([$test_brand, $test_brand2], $result);
        }

        function testDeleteAll() {
            //Arrange;
            $name = 'Adidas';
            $id = 1;
            $test_brand = new Brand($name, $id);
            $test_brand->save();

            $name2 = 'Nike';
            $id2 = 2;
            $test_brand2 = new Brand($name2, $id2);
            $test_brand2->save();

            //Act;
            Brand::deleteAll();
            $result = Brand::getAll();

            //Assert;
            $this->assertEquals([], $result);

        }

        function testUpdate() {
            //Arrange;
            $name = 'Nike';
            $test_brand = new Brand($name);
            $test_brand->save();

            //Act;
            $new_name = 'Adidas';         $test_brand->update($new_name);
            $result = [$test_brand->getName()];

            //Act;
            $this->assertEquals([$new_name], $result);
        }

        function testFind() {
            //Arrange;
            $name = 'Nike';
            $id = 2;
            $test_brand = new Brand($name, $id);
            $test_brand->save();

            //Act;
            $search_id = $test_brand->getId();
            $result = Brand::find($search_id);

            //Assert;
            $this->assertEquals($test_brand, $result);
        }

        function testAddStore() {
            //Arrange;
            $name = 'Nike';
            $id = 2;
            $test_brand = new Brand($name, $id);
            $test_brand->save();

            $name2 = 'Zapatos';
            $location = '111 SW St.';
            $test_store = new Store($name, $location);
            $test_store->save();

            //Assert;
            $test_brand->addStore($test_store);
            $result = $test_brand->getStores();

            //Act;
            $this->assertEquals([$test_store], $result);
        }

        function testGetStores() {
            //Arrange;
            $name = 'Nike';
            $id = 2;
            $test_brand = new Brand($name, $id);
            $test_brand->save();

            $name2 = 'Zapatos';
            $location = '111 SW St.';
            $test_store = new Store($name2, $location);
            $test_store->save();

            $name3 = 'Zapatos2';
            $location2 = '111 NW St.';
            $test_store2 = new Store($name3, $location2);
            $test_store2->save();

            $test_brand->addStore($test_store);
            $test_brand->addStore($test_store2);

            //Act;
            $result = $test_brand->getStores();

            //Assert;
            $this->assertEquals([$test_store, $test_store2], $result);

        }

    }


?>
