<?php
echo $this->extend('layout');

echo $this->section('title');
echo $title;
echo $this->endSection('title');

echo $this->section('title-website');
echo $title_website;
echo $this->endSection('title-website');

echo $this->section('title-menu');
echo $title_menu;
echo $this->endSection('title-menu');

echo $this->section('navbar');
echo $navbar;
echo $this->endSection('navbar');

echo $this->section('sidebar');
echo $sidebar;
echo $this->endSection('sidebar');

echo $this->section('content');
echo $content;
echo $this->endSection('content');

?>

<?= $this->section('footer') ?>
<strong>Copyright &copy; <?= date('Y') ?> <?= $footer['judul'] ?></strong>
<?= $this->endSection('footer'); ?>