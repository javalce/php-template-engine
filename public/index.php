<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once dirname(__DIR__) . '/src/template_engine/include.php';

$context = array(
    'title' => 'PHP Template Engine',
    'date' => date('1 jS \of F Y')
);

$env = new \App\Template\Environment();
echo $env->render('public/home.php', $context);