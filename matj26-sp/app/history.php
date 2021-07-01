<?php
    /*
     * Historie objednávek, kterou může zobrazit pouze přihlášený uživatel a admin
     * Uživatel vidí jen své objednávky
     * Admin má možnost vidět vešekeré provedené objednávky
     */
    session_start();
    $currentPage = 'history';

    //Kontrola, zda je někdo přihlášen
    if (empty($_SESSION['user_id'])){
        echo '<a href="index.php">Chci se vrátit.</a> ';
        die("Zde může být jen přihlášený uživatel!");
    }

    require "config.php";

    //Výpis objednávek na základě role uživatele
    if ($_SESSION['user_role'] == 'admin'){
        $getOrders = $db->prepare("SELECT * FROM orders;");
        $getOrders->execute();
        $orders = $getOrders->fetchAll();
    }else{
        $getOrders = $db->prepare("SELECT * FROM orders WHERE user_id = ?;");
        $getOrders->execute([$_SESSION['user_id']]);
        $orders = $getOrders->fetchAll();
    }

    //Přepnutí stavu objednávky
    if (isset($_GET['action'])){
        if ($_GET['action'] == "stop" && !empty($_GET['id'])) {
            $updateOrder = $db->prepare("UPDATE orders SET state = 'pozastavená' WHERE order_id = ?;");
            $updateOrder->execute([$_GET['id']]);
            header('Location: history.php');
        }else if($_GET['action'] == "confirm" && !empty($_GET['id'])){
            $updateOrder = $db->prepare("UPDATE orders SET state = 'potvrzená' WHERE order_id = ?;");
            $updateOrder->execute([$_GET['id']]);
            header('Location: history.php');
        }
    }
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
            <h1 class="display-6">Historie objednávek</h1>
        </div>
    </div>
    <div class="container">
        <?php if ($orders) { ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <?php echo ($_SESSION['user_role'] == 'admin')? '<th scope="col">ID Uživatele</th>':''; ?>
                        <th scope="col">Číslo objednávky</th>
                        <th scope="col">Datum</th>
                        <th scope="col"></th>
                        <th scope="col">Stav</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order){ ?>
                        <tr>
                            <?php echo ($_SESSION['user_role'] == 'admin')? '<td>'.$order['user_id'].'</td>':''; ?>
                            <td><?php echo $order['code']?></td>
                            <td><?php echo $order['date']?></td>
                            <td>
                                <?php
                                    if (file_exists("invoices/".$order['code'].".pdf")){ ?>
                                        <a href="invoices/<?php echo $order['code']?>.pdf"><?php echo $order['code']?></a>
                                    <?php }else{
                                        echo 'Odkaz není k nalezení';
                                    }
                                ?>
                            </td>
                            <td>
                                <?php echo $order['state'].'</td>';
                                   if ($_SESSION['user_role'] == 'admin'){
                                       if ($order['state'] == 'pozastavená'){
                                           echo '<td><a href="history.php?action=confirm&id='.$order['order_id'].'" style="color: green;">Potvrdit</a></td>';
                                       }else{
                                           echo '<td><a href="history.php?action=stop&id='.$order['order_id'].'" style="color: red;">Pozastavit</a></td>';
                                       }
                                   }
                                ?>
                        </tr>
                   <?php }
                ?>
            </table>
        <?php }else{
                echo '<div class="alert alert-danger" role="alert" style="text-align: center; margin-top: 20px;">V historii nemáte žádnou objednávku.</div>';
            } ?>
    </div>