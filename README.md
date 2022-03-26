# Simple Template Engine

A simple and lightweight PHP templating engine using pure PHP syntax.
Simple Template Engine add on PHP's templating capabilities by introducing blocks and template inheritance.

It's easy to learn and is useful for small websites or in conjunction with microfameworks.

Requires PHP version 5.3+

## Setup

To use the template engine, include `include.php`, create an `Environment` object, and render away!
Ten Environment's `render()` functions takes the path to a template, renders it and returns its contents as a string. Environment uses the `templates` and root directory as a default templates path.

```php
//include the include file
require_once 'path/to/include.php';

$env = new \App\Template\Environment('path/to/templates/directory');
echo $env->render('template.php');
```

You can pass variables to your template via an array

```php
//index.php
$context = array(
    'name' => $value,
    'fruit' => 'banana'
);
echo $env->render('template.php', $context);
```

You can then access the variable `$fruit` in your template, and it's value will be apple.

```php
//template.php
My favourite fruit is <?= $fruit ?>
```

## Blocks

Blocks are sections of layout that you can define and the use later.
You can define blocks by enclosing text in `$this->start('name here')` and `$this->end()`:

```php
<?php $this->start('title'); ?>
Welcome to my site!
<?php $this->end(); ?>
```

This will create a Block object that you can access later through `$this` by using their name.

```php
<title><?= $this->block('title') ?></title>
```

## Output Escaping

You can escape blocks of output easily:

```php
echo $this->block('title')->escape();
// OR this shorthand
echo $this->start('title')->e();
```

## Template inheritance

Blocks are useful because we can define blocks in one template, then _extend_ another one and use it there!
This allows us to reuse a template such as a layout multiple times with different blocks. Extending a template is done using the `extend` function:

```php
<?php $this->extend('layout.php'); ?>

<?php $this->start('scripts'); ?>
<script  src="jquery.js"></script>
<?php $this->end(); ?>
```

When you extend a parent template, any non-block code in the child template will not be rendered.
In the above code, we defined a scripts block. Now we can use it in our extended layout. In layout.php, we can output our scripts block and title variable with `$this->block('scripts')` and `$title`.

```php
<!-- my layout -->
<html>
    <head>
    <title><?= $title ?></title>
    </head>
    <body>
        <?= $this->block('scripts') ?>
    </body>
</html>
```

## That's all!

Now you have everithing you need to create a great website!
