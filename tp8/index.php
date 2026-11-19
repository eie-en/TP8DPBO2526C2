<?php

include_once 'views/Template.php';
include_once 'models/DB.php';
include_once 'controllers/DosenController.php';

// Instansiasi controller utama
$controller = new DosenController();

// Routing aksi berdasarkan request POST atau GET
if (isset($_POST['add'])) {
    $controller->add($_POST);
} elseif (isset($_POST['update'])) {
    $controller->update($_POST);
} elseif (!empty($_GET['id_hapus'])) {
    $controller->delete((int) $_GET['id_hapus']);
} elseif (!empty($_GET['id_edit'])) {
    $controller->edit((int) $_GET['id_edit']);
} else {
    $controller->index();
}
