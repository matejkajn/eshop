<?php
    /*
     * Stránka reprezentující košík s vypsanými jednotlivými produkty
     * Uživatel zde může vysypat košík nebo produkty libovolně přidávat/odebírat
     */
    $currentPage = 'cart';
    session_start();

    require 'config.php';
    require 'operations.php';

    if (!isset($_SESSION['cart'])){
        $_SESSION['cart'] = [];
    }

    if (isset($_GET['action'])) {
        //Přidání do košíku
        if ($_GET['action'] == "add" && !empty($_GET['id'])) {
            addToCart($_GET['id']);
        }

        //Odebrání z košíku
        if ($_GET['action'] == "remove" && !empty($_GET['id'])) {
            removeFromCart($_GET['id']);
        }

        //Vymazání z košíku
        if ($_GET['action'] == "delete" && !empty($_GET['id'])) {
            deleteFromCart($_GET['id']);
        }

        //Vysypání košíku
        if ($_GET['action'] == "clear"){
            unset($_SESSION['cart']);
        }
    }

    $totalPrice = 0;
?>
<!Doctype html>
<html lang="cs">
    <?php include 'layouts/head.php'; ?>
    <body>
        <?php
            include 'layouts/navbar.php';
            include 'layouts/footer.php';
        ?>
        <div class="header">
            <div class="container">
                <h1 class="display-6">Nakupovací koš</h1>
            </div>
        </div>
        <div class="container" style="padding-top: 20px;">
            <?php if (!empty($_SESSION['cart'])) { ?>
                <div class="row">
                    <main class="col-md-9">
                        <div class="card">
                            <table class="table table-borderless">
                                <thead  class="table table-borderless">
                                <tr  class="table table-borderless">
                                    <th scope="col"></th>
                                    <th scope="col">Název</th>
                                    <th scope="col">Množství</th>
                                    <th scope="col">Celková Cena</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($_SESSION['cart'] as $item) { ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <?php if (file_exists("uploads/".$item['image']) && $item['image'] != ''){ ?>
                                                <img src="uploads/<?php echo $item['image']?>" width="120px" height="120px" alt="<?php echo $item['id']?>">
                                            <?php }else{
                                                echo 'Obrázek není k dispozici';
                                            }?>
                                        </td>
                                        <td><?php echo $item['name'] ?></td>
                                        <td><?php echo $item['quantity'] ?></td>
                                        <td><?php echo ($item['price'] * $item['quantity'])?> Kč</td>
                                        <td>
                                            <a type="button" class="btn btn-primary" href="cart.php?action=add&id=<?php echo htmlspecialchars($item['id'])?>">+</a>
                                            <a type="button" class="btn btn-primary" href="cart.php?action=remove&id=<?php echo htmlspecialchars($item['id'])?>">-</a>
                                            <a type="button" class="btn btn-danger" href="cart.php?action=delete&id=<?php echo htmlspecialchars($item['id'])?>">x</a>
                                        </td>
                                    </tr>
                                    <?php
                                    $totalPrice+=($item['price'] * $item['quantity']);
                                } ?>
                                </tbody>
                            </table>
                        </div>
                    </main>
                    <aside class="col-md-3">
                        <div class="card" style="text-align: right;">
                            <div class="card-body">
                                <dl class="dlist-align">
                                    <dt>Celková cena</dt>
                                    <dd class="text-right  h5"><strong><?php echo $totalPrice ?> Kč</strong></dd>
                                </dl>
                                <dl class="dlist-align">
                                    <dd><a class="btn btn-primary" href="confirm_order.php">Objednat</a></dd>
                                    <dd><a class="btn btn-danger" href="cart.php?action=clear">Vysypat košík</a></dd>
                                </dl>
                            </div>
                    </aside>
                </div>
            <?php }else{
                echo '<div class="alert alert-danger" role="alert" style="text-align: center;">
                        Ale ne, Váš košík je prázdný :(<br/>
                        Zkuste se podívat jestli si nevyberete něco z naší nabídky na kartě <a href="index.php">produkty.</a>
                      </div>';
            } ?>
        </div>
