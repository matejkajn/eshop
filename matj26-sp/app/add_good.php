<?php
    /*
     * Přidání nového produktu společně s nahráním jeho obrázku
     * Pouze admin má oprávnění provádět tuto akci
     */
    require 'config.php';
    require 'admin_check.php';
    $currentPage = 'add_good';

    $addErrors = [];

    if (!empty($_POST)){
        if (empty($_POST['name'])){
            $addErrors['name']='Musíte zadat název produktu!';
        }

        if (empty($_POST['description'])){
            $addErrors['description']='Musíte zadat popis!';
        }

        if (empty($_POST['price'])){
            if ($_POST['price'] < 0){
                $addErrors['price']='Cena nemůže být záporná!';
            }
            $addErrors['price']='Vyplňte cenu produktu!';
        }

        if (!empty($_POST['category'])){
            $categoryQuery = $db->prepare("SELECT * FROM categories WHERE category_id=? LIMIT 1;");
            $categoryQuery->execute([$_POST['category']]);

            if ($categoryQuery->rowCount() == 0){
                $addErrors['category']='Vyberte kategorii!';
            }
        }else{
            $addErrors['category']='Kategorie neexistuje!';
        }

        //Přidání obrázku
        if (isset($_POST['submit'])){
            $file = $_FILES['file'];

            if ($file['error'] === 0) {
                $fileName = $file['name'];
                $fileSuffix = explode('.', $fileName);
                $allowedSuffixes = array('jpg', 'jpeg', 'png');
                if (in_array( $fileSuffix['1'], $allowedSuffixes)) {
                    if ($file['size'] < 300000){
                        move_uploaded_file($file['tmp_name'], 'uploads/'.$fileName);
                    }else{
                        $addErrors['file']='Soubor je příliš velký!';
                    }
                }else {
                    $addErrors['file']='Nelze nahrát tento typ souboru!';
                }
            }else {
                $addErrors['file']='Vyskytla se chyba při nahrávání vašeho souboru';
            }
        }

        if (empty($addErrors)){
            //Přidání produktu do databáze
            $addGood = $db->prepare("INSERT INTO goods (name, description, price, category_id, image) VALUES(?, ?, ?, ?, ?);");
            $addGood->execute([
               $_POST['name'],
               $_POST['description'],
               $_POST['price'],
               $_POST['category'],
               $_FILES['file']['name']
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
                <h1 class="display-6">Přidání nového produktu</h1>
            </div>
        </div>
        <div class="container" style="width: 400px; margin-top: 50px;">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Název produktu</label>
                    <input type="text" class="form-control <?php echo (!empty($addErrors['name'])?'is-invalid':'') ?>" name="name" id="name" value="<?php echo htmlspecialchars(@$_POST['name'])?>">
                    <?php
                        echo (!empty($addErrors['name'])? '<div class="invalid-feedback">'.$addErrors['name'].'</div>':'');
                    ?>
                </div>
                <div class="form-group">
                    <label for="description">Popisek produktu</label>
                    <textarea style="height: 150px;" class="form-control <?php echo (!empty($addErrors['description'])?'is-invalid':'') ?>" name="description" id="description"><?php echo htmlspecialchars(@$_POST['description']) ?></textarea>
                    <?php
                        echo (!empty($addErrors['description'])? '<div class="invalid-feedback">'.$addErrors['description'].'</div>':'');
                    ?>
                </div>
                <div class="form-group">
                    <label for="price">Cena</label>
                    <input type="number" class="form-control <?php echo (!empty($addErrors['price'])?'is-invalid':'') ?>" name="price" id="price" min="0" step="0.01" value="<?php echo htmlspecialchars(@$_POST['price']) ?>">
                    <?php
                        echo (!empty($addErrors['price'])? '<div class="invalid-feedback">'.$addErrors['price'].'</div>':'');
                    ?>
                </div>
                <div class="form-group">
                    <label for="category">Kategorie</label>
                    <select class="form-select <?php echo (!empty($addErrors['category'])?'is-invalid':'') ?>" name="category" id="category" aria-label="Default select example">
                        <option>Vyberte kategorii</option>
                        <?php
                            $categoriesQuery = $db->prepare("SELECT * FROM categories;");
                            $categoriesQuery->execute();
                            $categoriesList = $categoriesQuery->fetchAll(PDO::FETCH_ASSOC);

                            if (!empty($categoriesList)){
                                foreach ($categoriesList as $category){
                                    echo '<option value="'.$category['category_id'].'">'.$category['name'].'</option>';
                                }
                            }
                        ?>
                    </select>
                    <?php
                        echo (!empty($addErrors['category'])? '<div class="invalid-feedback">'.$addErrors['category'].'</div>':'');
                    ?>
                </div>
                <div class="form-group">
                    <label class="form-label" for="file">Vložte obrázek pro produkt</label>
                    <input type="file" name="file" id="file" class="form-control <?php echo(!empty($addErrors['file']) ? 'is-invalid' : '') ?>">
                    <?php
                        echo (!empty($addErrors['file'])? '<div class="invalid-feedback">'.$addErrors['file'].'</div>':'');
                    ?>
                </div>
                <div class="form-group" style="text-align: center; margin-top: 30px;">
                    <button type="submit" name="submit" class="btn btn-primary">Přidat</button>
                    <a href="index.php" class="btn btn-primary">Zpět</a>
                </div>
            </form>
        </div>
