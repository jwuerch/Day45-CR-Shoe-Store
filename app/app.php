<?php
    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/../src/Brand.php';
    require_once __DIR__.'/../src/Store.php';

    $app = new Silex\Application();
    $server = 'mysql:host=localhost;dbname=shoes';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app = new Silex\Application();

    $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'));



    //** All information for STORE class
    //**
    //**
    //**
    //**

    $app->get('/', function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });

    $app->get('/stores', function() use ($app) {
        return $app['twig']->render('stores.html.twig', array('all_stores' => Store::getAll()));
    });

    $app->get('/search_stores', function() use ($app) {
        $results = Store::searchByName($_GET['search_term']);
        return $app['twig']->render('stores.html.twig', array('all_stores' => Store::getAll(), 'results' => $results));
    });

    $app->get('/store/{id}', function($id) use ($app) {
        $store = Store::find($id);
        $brands = $store->getBrands();
        return $app['twig']->render('store.html.twig', array('store' => $store, 'all_brands' => Brand::getAll(), 'brands' => $brands));
    });

    $app->post('/add_store', function() use ($app) {
        $name = $_POST['store_name'];
        $location = $_POST['store_location'];
        $new_store = new Store($name, $location);
        $new_store->save();
        return $app['twig']->render('stores.html.twig', array('all_stores' => Store::getAll()));
    });

    $app->post('/delete_all_stores', function() use ($app) {
        Store::deleteAll();
        return $app['twig']->render('stores.html.twig', array('all_stores' => Store::getAll(), 'all_brands' => Brand::getAll()));
    });

    $app->post('/store_add_brand', function() use ($app) {
        $store = Store::find($_POST['store_id']);
        $brand = Brand::find($_POST['brand_id']);
        $store->addBrand($brand);
        $brands = $store->getBrands();
        return $app['twig']->render('store.html.twig', array('store' => $store, 'all_brands' => Brand::getAll(), 'brands' => $brands));
    });

    $app->patch('/update_store', function() use ($app) {
        $store = Store::find($_POST['store_id']);
        $new_name = $_POST['new_name'];
        $new_location = $_POST['new_location'];
        $store->update($new_name, $new_location);
        $brands = $store->getBrands();
        return $app['twig']->render('store.html.twig', array('store' => $store, 'brands' => $brands, 'all_brands' => Brand::getAll()));
    });

    $app->delete('/delete_store', function() use ($app) {
        $store = Store::find($_POST['store_id']);
        $store->delete();
        return $app['twig']->render('stores.html.twig', array('all_stores' => Store::getAll()));
    });

    $app->delete('/drop_brand', function() use ($app) {
        $brand = Brand::find($_POST['brand_id']);
        $store = Store::find($_POST['store_id']);
        $store->dropBrand($brand);
        $brands = $store->getBrands();
        return $app['twig']->render('store.html.twig', array('store' => $store, 'brands' => $brands, 'all_brands' => Brand::getAll()));
    });


    //** All information for BRAND class
    //**
    //**
    //**
    //**
    $app->get('/brands', function() use ($app) {
        return $app['twig']->render('brands.html.twig', array('all_brands' => Brand::getAll()));
    });

    $app->get('/brand/{id}', function($id) use ($app) {
        $brand = Brand::find($id);
        $stores = $brand->getStores();
        return $app['twig']->render('brand.html.twig', array('brand' => $brand, 'stores' => $stores, 'all_stores' => Store::getAll()));
    });

    $app->post('/add_brand', function() use ($app) {
        $name = $_POST['name'];
        $new_brand = new Brand($name);
        $new_brand->save();
        return $app['twig']->render('brands.html.twig', array('all_brands' => Brand::getAll()));
    });

    $app->post('/delete_all_brands', function() use ($app) {
        Brand::deleteAll();
        return $app['twig']->render('brands.html.twig', array('all_brands' => Brand::getAll()));
    });

    $app->post('/brand_add_store', function() use ($app) {
        $store = Store::find($_POST['store_id']);
        $brand = Brand::find($_POST['brand_id']);
        $brand->addStore($store);
        $stores = $brand->getStores();
        return $app['twig']->render('brand.html.twig', array('brand' => $brand, 'all_stores' => Store::getAll(), 'stores' => $stores));
    });

    $app->patch('/update_brand', function() use ($app) {
        $brand = Brand::find($_POST['brand_id']);
        $new_name = $_POST['new_name'];
        $brand->update($new_name);
        $stores = $brand->getStores();
        return $app['twig']->render('brand.html.twig', array('brand' => $brand, 'stores' => $stores, 'all_stores' => Store::getAll()));
    });

    $app->delete('/delete_brand', function() use ($app) {
        $brand = Brand::find($_POST['brand_id']);
        $brand->delete();
        return $app['twig']->render('brands.html.twig', array('all_brands' => Brand::getAll()));
    });


    return $app;

 ?>
