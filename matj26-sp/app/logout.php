<?php
    /*
    * Odhlášení uživatele z aplikace -> vymaže uživatele i košík ze SESSION
    * Přesměruje na hlavní stránku
    */
    session_start();
    session_destroy();

    header('Location: index.php');
