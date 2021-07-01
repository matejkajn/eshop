<?php
    /*
     * Stránka s formulářem na vytvoření objednávky
     * Objednávka se uloží spolu s osobními údaji, dodací adresou a
     *
     */
    session_start();
    require "config.php";
    $currentPage = 'edit_order';
    $confirmErrors = [];
    if (!empty($_POST)){

        if (empty($_POST['name'])) {
            $confirmErrors['name'] = 'Musíte zadat jméno!';
        }

        if (empty($_POST['surname'])) {
            $confirmErrors['surname'] = 'Musíte zadat příjmení!';
        }

        if (empty($_POST['email']) || !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
            $confirmErrors['email'] = 'Musíte zadat platnou emailovou adresu!';
        }

        if (!empty($_POST['tel'])){
            $_POST['tel'] = str_replace([' ','-','/'],['','',''], $_POST['tel']);
            if (!preg_match('/^(\+420)?\d{9}$/', $_POST['tel'])){
                $confirmErrors['tel'] = 'Musíte zadat platné české telefonní číslo!';
            }else{
                $_POST['tel'] = str_replace(['+420'],[''], $_POST['tel']);
            }
        }else{
            $confirmErrors['tel'] = 'Zadejte platné české telefonní číslo!';
        }

        if (empty($_POST['street'])) {
            $confirmErrors['street'] = 'Musíte zadat ulici!';
        }

        if (empty($_POST['city'])) {
            $confirmErrors['city'] = 'Musíte zadat město!';
        }

        if (!empty($_POST['zip'])){
            $_POST['zip'] = str_replace([' ','-','/'],['','',''], $_POST['zip']);
            if (!preg_match('/^\d{5}$/',$_POST['zip'])) {
                $confirmErrors['zip'] = 'Musíte zadat platné poštovní směrovací číslo!';
            }
        }else{
            $confirmErrors['zip'] = 'Zadejte poštovní směrovací číslo!';
        }

        if (empty($confirmErrors)){
            $user=(isset($_SESSION['user_id']))?$_SESSION['user_id']:NULL;
            $currentDate = date("Y-m-d h:i:s");

            $codeCheck = $db->prepare("SELECT code FROM orders;");
            $codeCheck->execute([$orderCode]);
            $codeCheck->fetchAll();

            do{
                $orderCode = str_pad(mt_rand(1,999999999),9,'0',STR_PAD_LEFT);
            } while(in_array($orderCode, $codeCheck));

            //Uložení objednávky
            $createOrder = $db->prepare("INSERT INTO orders (user_id, code, name, surname, email, date, street, city, zip, tel) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
            $createOrder->execute([
                $user,
                $orderCode,
                $_POST['name'],
                $_POST['surname'],
                $_POST['email'],
                $currentDate,
                $_POST['street'],
                $_POST['city'],
                $_POST['zip'],
                $_POST['tel']
            ]);

            $last_id = $db->lastInsertId();

            //Uložení vybraných produktů do objednávky
            foreach ($_SESSION['cart'] as $good){
                $addGood = $db->prepare("INSERT INTO single_order (order_id, good_id, price, quantity) VALUES(?, ?, ?, ?)");
                $addGood->execute([
                    $last_id,
                    $good['id'],
                    $good['price'],
                    $good['quantity']
                ]);
            }

            header('Location: create_pdf.php?id='.$last_id.'');
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
            <h1 class="display-6">Vytvoření objednávky</h1>
        </div>
    </div>
    <div class="container" style="padding-top: 20px;">
        <form method="post">
            <div class="row">
                <h4>Osobní údaje</h4>
                <div class="form-group col-md-2">
                    <label for="name">Jméno</label>
                    <input type="text" class="form-control <?php echo (!empty($confirmErrors['name'])?'is-invalid':'') ?>" name="name" id="name" value="<?php echo htmlspecialchars(@$_POST['name'])?>" placeholder="Jméno">
                    <?php
                        echo (!empty($confirmErrors['name'])? '<div class="invalid-feedback">'.$confirmErrors['name'].'</div>':'');
                    ?>
                </div>
                <div class="form-group col-md-2">
                    <label for="surname">Příjmení</label>
                    <input type="text" class="form-control <?php echo (!empty($confirmErrors['surname'])?'is-invalid':'') ?>" name="surname" id="surname" value="<?php echo htmlspecialchars(@$_POST['surname'])?>" placeholder="Příjmení">
                    <?php
                        echo (!empty($confirmErrors['surname'])? '<div class="invalid-feedback">'.$confirmErrors['surname'].'</div>':'');
                    ?>
                </div>
            </div>
            <div class="row">
                <h4>Kontakt</h4>
                <div class="form-group col-md-2">
                    <label for="email">Email</label>
                    <input type="text" class="form-control <?php echo (!empty($confirmErrors['email'])?'is-invalid':'') ?>" name="email" id="email" value="<?php echo ((!empty($_SESSION['user_email']))? htmlspecialchars($_SESSION['user_email']):'') ?>" placeholder="Email">
                    <?php
                        echo (!empty($confirmErrors['email'])? '<div class="invalid-feedback">'.$confirmErrors['email'].'</div>':'');
                    ?>
                </div>
                <div class="form-group col-md-2">
                    <label for="tel">Telefon</label>
                    <input type="text" class="form-control <?php echo (!empty($confirmErrors['tel'])?'is-invalid':'') ?>" name="tel" id="tel" value="<?php echo htmlspecialchars(@$_POST['tel']) ?>" placeholder="Tel.">
                    <?php
                        echo (!empty($confirmErrors['tel'])? '<div class="invalid-feedback">'.$confirmErrors['tel'].'</div>':'');
                    ?>
                </div>
            </div>
            <h4>Dodací adresa</h4>
            <div class="row">
                <div class="form-group col-md-2">
                    <label for="street">Ulice</label>
                    <input type="text" class="form-control <?php echo (!empty($confirmErrors['street'])?'is-invalid':'') ?>" name="street" id="street" value="<?php echo htmlspecialchars(@$_POST['street']) ?>" placeholder="Ulice">
                    <?php
                        echo (!empty($confirmErrors['street'])? '<div class="invalid-feedback">'.$confirmErrors['street'].'</div>':'');
                    ?>
                </div>
                <div class="form-group col-md-2">
                    <label for="city">Město</label>
                    <input type="text" class="form-control <?php echo (!empty($confirmErrors['city'])?'is-invalid':'') ?>" name="city" id="city" value="<?php echo htmlspecialchars(@$_POST['city']) ?>" placeholder="Město">
                    <?php
                        echo (!empty($confirmErrors['city'])? '<div class="invalid-feedback">'.$confirmErrors['city'].'</div>':'');
                    ?>
                </div>
                <div class="form-group col-md-2">
                    <label for="zip">PSČ</label>
                    <input type="text" class="form-control <?php echo (!empty($confirmErrors['zip'])?'is-invalid':'') ?>" name="zip" id="zip" value="<?php echo htmlspecialchars(@$_POST['zip']) ?>" placeholder="Psč">
                    <?php
                        echo (!empty($confirmErrors['zip'])? '<div class="invalid-feedback">'.$confirmErrors['zip'].'</div>':'');
                    ?>
                </div>
            </div>
            <div>
                <?php if (!empty($_SESSION['cart'])){ ?>
                    <h4>Košík</h4>
                    <hr>
                    <table class="table table-borderless">
                        <thead class="text-muted">
                        <tr class="small text-uppercase">
                            <th scope="col">Obrázek</th>
                            <th scope="col">Název</th>
                            <th scope="col">Počet ks</th>
                            <th scope="col">Celková Cena</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($_SESSION['cart'] as $item) { ?>
                            <tr>
                                <th>Obrázek</th>
                                <td><?php echo $item['name'] ?></td>
                                <td><?php echo $item['quantity'] ?></td>
                                <td><?php echo ($item['price'] * $item['quantity'])?> Kč</td>
                            </tr>
                            <?php
                            $totalPrice+=($item['price'] * $item['quantity']);
                        } ?>
                        </tbody>
                    </table>
                    <hr>
                    <?php echo '<strong>Celková cena: '.$totalPrice.' Kč</strong>'; ?>
                    <hr>
                <?php }else{
                    header('Location: cart.php');
                } ?>
            </div>
            <div class="form-group" style="text-align: center; margin-top: 30px;">
                <button type="submit" class="btn btn-primary">Potvrdit objednávku</button>
                <a href="cart.php" class="btn btn-primary">Zpět</a>
            </div>
        </form>
    </div>
