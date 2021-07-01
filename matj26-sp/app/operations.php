<?php
    /*
     * Zde jsou operace pro přídání/odebrání/vymazání produktu z košíku
     */

    //Přidá položku do košíku
    function addToCart($id){
        if (array_key_exists($id, $_SESSION['cart'])){
            convertDataBaseItemToSession($id);
            $_SESSION['cart'][$id]['quantity']++;
        }else{
            convertDataBaseItemToSession($id);
            $_SESSION['cart'][$id]['quantity'] = 1;
        }
    }

    //Odstraní jednu položku košíku
    function removeFromCart($id){
        if (array_key_exists($id, $_SESSION['cart'])){
            if ($_SESSION['cart'][$id]['quantity'] <= 1){
                unset($_SESSION['cart'][$id]);
            }else{
                $_SESSION['cart'][$id]['quantity']--;
            }
        }
    }

    //Vymaže produkt z košíku
    function deleteFromCart($id){
        if (array_key_exists($id, $_SESSION['cart'])){
            unset($_SESSION['cart'][$id]);
        }
    }

    //Uloží do Session produkt načtený z databáze
    function convertDataBaseItemToSession($id){
        require 'config.php';
        $goodQuery = $db->prepare("SELECT * FROM goods WHERE good_id = ?;");
        $goodQuery->execute([$id]);
        $goodQuery = $goodQuery->fetch();
        /*Přidám-li pole, zápis produktu v SESSION bude složitější
         * $_SESSION['cart'][$_GET['id']][$_GET['id']] = array(
            'id' => $goodQuery['good_id'],
            'name' => $goodQuery['name'],
            'description' => $goodQuery['description'],
            'price' => $goodQuery['price'],
            'category_id' => $goodQuery['category_id'],
        );*/
        $_SESSION['cart'][$id]['id'] = $goodQuery['good_id'];
        $_SESSION['cart'][$id]['name'] = $goodQuery['name'];
        $_SESSION['cart'][$id]['description'] = $goodQuery['description'];
        $_SESSION['cart'][$id]['price'] = $goodQuery['price'];
        $_SESSION['cart'][$id]['category_id'] = $goodQuery['category_id'];
        $_SESSION['cart'][$id]['image'] = $goodQuery['image'];
    }
