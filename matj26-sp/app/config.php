<?php
    /*
     * Připojení k databázi
     */
    $db = new PDO('dsn', 'username', 'password');

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);