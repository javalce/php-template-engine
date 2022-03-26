<? $this->extend('base.php'); ?>

<? $this->start('header'); ?>
<h1>Header</h1>
<? $this->end() ?>

<? $this->start('content'); ?>
<h2>PHP Template Engine</h2>

<p>Hello, today's date is <?= $date ?>.</p>
<p>This is a simple example page to get you started!</p>
<p>Enjoy!</p>
<h3>Learn more</h3>
<p>Refer to the README file.</p>
<? $this->end(); ?>


<? $this->start('modals'); ?>
<? include 'modal.php'; ?>
<? $this->end(); ?>