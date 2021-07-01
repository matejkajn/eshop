<?php
    /*
     * Přidání produktu do košíku z hlavní stránky
     */
    session_start();
    require 'operations.php';

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($_GET['action'] == "add" && !empty($_GET['id'])){
        addToCart($_GET['id']);
    }

    header('Location: index.php');

