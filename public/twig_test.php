<?php   // twig_test.php
//
require_once( __DIR__ . '/../vendor/autoload.php');

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
//
/*
$loader = new \Twig\Loader\ArrayLoader([
    'index' => 'Hello {{ name }}!',
    'hoge' => 'hogera {{ name }}!',
]);*/
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader);
//
$twig = new Environment(new FilesystemLoader(__DIR__ . '/../templates'));

//
echo $twig->render('twig_test.twig', ['name' => '<>"&']);
