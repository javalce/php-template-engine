<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'php-template-engine/loader.php';

$context = array(
    'title' => 'PHP Template Engine',
    'date' => date('1 jS \of F Y')
);

$env = new \SimpleTemplateEngine\Environment('templates/public');
echo $env->render('home.php', $context);