<!--
Navigační lišta
Zobrazuje odkazy podle toho, jestli je někdo přihlášen, popřípadě je-li to admin
 -->
<nav class="navbar navbar-expand-xl navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand"><?php echo (empty($_SESSION['user_email']))? 'Jonáš Matějka LS2021':$_SESSION['user_email']?></a>
        <ul class="navbar-nav ms-auto">
            <li><a class="nav-link <?php echo $currentPage == 'index' ? 'active' : ''; ?>" href="index.php">Produkty</a></li>
            <li><a class="nav-link  <?php echo $currentPage == 'cart' ? 'active' : ''; ?>" href="cart.php">Košík</a></li>
            <?php
                if (empty($_SESSION['user_id'])){
                    if ($currentPage == 'login'){
                        echo '<li><a class="nav-link active" href="login.php">Přihlásit</a></li>';
                    }else{
                        echo '<li><a class="nav-link" href="login.php">Přihlásit</a></li>';
                    }
                }else{
                    if ($currentPage == 'history'){
                        echo '<li><a class="nav-link active" href="history.php">Historie</a>';
                    }else{
                        echo '<li><a class="nav-link" href="history.php">Historie</a>';
                    }
                    if ($_SESSION['user_role'] == 'admin') {
                        if ($currentPage == 'add_category'){
                            echo '<li><a class="nav-link active" href="add_category.php">Přidat Kategorii</a></li>';
                        }else{
                            echo '<li><a class="nav-link" href="add_category.php">Přidat Kategorii</a></li>';
                        }

                        if ($currentPage == 'add_good'){
                            echo '<li><a class="nav-link active" href="add_good.php">Přidat produkt</a></li>';
                        }else{
                            echo '<li><a class="nav-link" href="add_good.php">Přidat produkt</a></li>';
                        }
                    }
                    echo '</li><li><a class="nav-link" href="logout.php">Odhlásit</a></li>';
                }
            ?>
        </ul>
    </div>
</nav>