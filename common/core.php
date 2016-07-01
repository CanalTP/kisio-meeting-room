<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require('vendor/autoload.php');

$twigLoader = new Twig_Loader_Filesystem('template');
$twig = new Twig_Environment($twigLoader);
