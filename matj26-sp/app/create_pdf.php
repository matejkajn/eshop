<?php
    /*
     * Soubor pro vytvoření pdf, uložení ho na server a poslání emailu
     * Email funguje jen na školní doméně
     */
    session_start();
    require "config.php";
    use Mpdf\Mpdf;
    use PHPMailer\PHPMailer\PHPMailer;
    require_once '../vendor/autoload.php';

    //Kontrola, zda byla vytvořena objednávka
    if (!isset($_GET['id'])){
        header('Location: confirm_order.php');
    }

    //Načtení záznamů z databáze
    $orderQuery = $db->prepare("SELECT * FROM orders WHERE order_id = ?;");
    $orderQuery->execute([$_GET['id']]);
    $order = $orderQuery->fetch();

    $goodsQuery = $db->prepare("SELECT * FROM single_order WHERE order_id = ?;");
    $goodsQuery->execute([$_GET['id']]);
    $goods = $goodsQuery->fetchAll();

    $totalPrice = 0;

    $html = '
        <h1>Objednávka '.$order['code'].'</h1>
        <h2>Přijata '.$order['date'].'</h2>
        <h3>Osobní informace</h3>
        <p>Jméno: '.$order['name'].'</p>
        <p>Příjmení: '.$order['surname'].'</p>
        
        <h3>Kontakt</h3>
        <p>Email: '.$order['email'].'</p>
        <p>Tel: '.$order['tel'].'</p>
        
        <h3>Dodací adresa</h3>
        <p>Ulice: '.$order['street'].'</p>
        <p>Město: '.$order['city'].'</p>
        <p>PSČ: '.$order['zip'].'</p>
        <h2>Objednané položky</h2>
    ';

    if ($_SESSION['cart']){
        $html .= '
                <table>
                <thead>
                    <tr>
                        <th>Název</th>
                        <th>Počet Ks</th>
                        <th>Jednotková cena</th>
                        <th>Celková cena</th>
                    </tr>
                </thead>
                <tbody>
            ';
        foreach ($_SESSION['cart'] as $good){

            $html .= '
                    <tr>
                        <td>'.$good['name'].'</td>
                        <td>'.$good['quantity'].'</td>
                        <td>'.$good['price'].' Kč</td>
                        <td>'.$good['price'] * $good['quantity'].' Kč</td>
                    </tr>
                ';
            $totalPrice += $good['price'] * $good['quantity'];
        }
        $html .= '
                </tbody></table>
                <h2 id="totalPrice">Celková cena činí: '.$totalPrice.' Kč</h2>';
    }

    $stylesheet = file_get_contents('css/pdf.css');

    //Vytvoření pdf a uložení na server
    $mpdf = new Mpdf(['tempDir' => '/tmp']);
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->WriteHTML($html);
    $mpdf->Output("invoices/".$order['code'].".pdf", "F");

    define('RECEIVER', 'email');
    $mailer = new PHPMailer(true);

    //Odeslání emailu
    try{
        $mailer->isSendmail();

        $mailer->addAddress(RECEIVER);
        $mailer->setFrom(RECEIVER);

        $mailer->CharSet = 'utf-8';
        $mailer->Subject = 'Objednávka č.'.$order['code'];

        $mailer->isHTML(true);
        $mailer->Body = $html;

        $mailer->addAttachment("invoices/".$order['code'].".pdf");

        $mailer->send();
    }catch (Exception $e){
        echo '<div class="alert alert-danger" role="alert">Omlouváme se, ale email se nepodařilo odeslat. Chyba: '.$mailer->ErrorInfo.'</div>';
    }

    unset($_SESSION['cart']);
    header('Location: index.php');