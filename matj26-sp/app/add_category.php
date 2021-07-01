<?php
    /*
    * Přidání nové kategorie
    * Pouze admin má oprávnění provádět tuto akci
    */
    require 'config.php';
    require 'admin_check.php';
    $currentPage = 'add_category';

    $addErrors = [];
    if (!empty($_POST)){

        if (empty($_POST['category'])){
            $addErrors['category']='Musíte zadat název kategorie!';
        }
        //Kontrola, zda tato kategorie již neexistuje
        $checkQuery = $db->prepare("SELECT * FROM categories WHERE name=? LIMIT 1;");
        $checkQuery->execute([$_POST['category']]);

        if ($checkQuery->rowCount() > 0){
            $addErrors['category']='Tato kategorie již existuje!';
        }

        if (empty($addErrors)){
            //Přidání nové kategorie do databáze
            $addQuery = $db->prepare("INSERT INTO categories (name) VALUES(?);");
            $addQuery->execute([
                $_POST['category']
            ]);
            header('Location: index.php');
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
                <h1 class="display-6">Přidání nové kategorie</h1>
            </div>
        </div>
        <div class="container" style="width: 400px; margin-top: 50px;">
            <form method="post">
                <div class="form-group">
                    <label for="category">Zadejte název kategorie</label>
                    <input type="text" class="form-control <?php echo (!empty($addErrors['category'])?'is-invalid':'') ?>" name="category" id="category" value="<?php echo htmlspecialchars(@$_POST['category'])?>">
                    <?php
                        echo (!empty($addErrors['category'])? '<div class="invalid-feedback">'.$addErrors['category'].'</div>':'');
                    ?>
                </div>
                <div class="form-group" style="text-align: center; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">Přidat</button>
                    <a href="index.php" class="btn btn-primary">Zpět</a>
                </div>
            </form>
        </div>
