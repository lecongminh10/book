<?php

session_start();

require_once "./vendor/autoload.php";
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

require_once "./env.php";
require_once "./helpers.php";
require_once "./routes.php";


