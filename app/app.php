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

    $app->get('/store/{id}', function($id) use ($app) {
        $store = Store::find($id);
        return $app['twig']->render('store.html.twig', array('store' => $store));
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
        return $app['twig']->render('stores.html.twig', array('all_stores' => Store::getAll()));
    });


    //** All information for BRAND class
    //**
    //**
    //**
    //**


    return $app;

 ?>
