<?php
    /*
     * Kontrola zda je přihlášen admin
     */
    session_start();

    //Kontrola zda je uživatel v SESSION a jeho role je admin
    if (empty($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin'){
        echo '<a href="index.php">Chci se vrátit.</a> ';
        die("Zde může být jen admin!");
    }
