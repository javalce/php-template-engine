<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title ?></title>
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato">
        <style>
        #content {
            margin: auto;
            width: 400px;
            font-family: "Lato";
        }
        </style>
    </head>

    <body>
        <?= $this->block('header'); ?>
        <div id="content"><?= $this->block('content'); ?></div>
        <?= $this->block('modals'); ?>
    </body>

</html>