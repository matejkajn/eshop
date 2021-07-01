<?php
    /*
     * Kód pro editaci určitého produktu
     * Změna produktu - cena, popis, obrázek atd.
     * Smazání produktu
     * Pouze admin má oprávnění provádět tuto akci
     */
    require 'config.php';
    require 'admin_check.php';
    $currentPage = 'edit_good';

    //Smazání nebo načtení produktu
    if (isset($_GET['action'])){
        if ($_GET['action'] == "edit" && !empty($_GET['id'])){
            $editQuery = $db->prepare("SELECT * FROM goods WHERE good_id=? LIMIT 1;");
            $editQuery->execute([$_GET['id']]);
            $good = $editQuery->fetch();
        }

        if ($_GET['action'] == "delete" && !empty($_GET['id'])){
            $deleteQuery = $db->prepare("DELETE FROM goods WHERE good_id = ?;");
            $deleteQuery->execute([$_GET['id']]);
            header('Location: index.php');
        }
    }else{
        header('Location: index.php');
    }

    $editErrors=[];
    if (!empty($_POST)){
        if (empty($_POST['name'])){
            $editErrors['name']='Musíte zadat název produktu';
        }

        if (empty($_POST['description'])){
            $editErrors['description']='Musíte zadat popis!';
        }

        if (empty($_POST['price'])){
            $editErrors['price']='Vyplňte cenu produktu!';
        }

        if (empty($_POST['category'])){
            $editErrors['category']='Musíte vybrat nějakou kategorii!';
        }

        //Upload obrázku
        if (isset($_POST['submit'])){
            $file = $_FILES['file'];

            if ($file['error'] === 0) {
                $fileName = $file['name'];
                $fileSuffix = explode('.', $fileName);
                $allowedSuffixes = array('jpg', 'jpeg', 'png');
                if (in_array( $fileSuffix['1'], $allowedSuffixes)) {
                    if ($file['size'] < 300000){
                        if (file_exists("uploads/".$good['image'])){
                            unlink("uploads/".$good['image']);
                        }
                        move_uploaded_file($file['tmp_name'], 'uploads/'.$fileName);
                    }else{
                        $editErrors['file']='Soubor je příliš velký!';
                    }
                }else {
                    $editErrors['file']='Nelze nahrát tento typ souboru!';
                }
            }else {
                $editErrors['file']='Vyskytla se chyba při nahrávání vašeho souboru';
            }
        }

        if (empty($editErrors)){
            //Update produktu
            $editGood = $db->prepare("UPDATE goods SET name = ?, description = ?, price = ?, category_id = ?, image = ? WHERE good_id = ?;");
            $editGood->execute([
                $_POST['name'],
                $_POST['description'],
                $_POST['price'],
                $_POST['category'],
                $_FILES['file']['name'],
                $good['good_id']
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
            <h1 class="display-6">Úprava produktu s ID: <?php echo $good['good_id']?></h1>
        </div>
    </div>
    <div class="container" style="width: 400px; margin-top: 50px;">
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Název produktu</label>
                <input type="text" class="form-control  <?php echo (!empty($editErrors['name'])?'is-invalid':'') ?>" name="name" id="name" value="<?php echo htmlspecialchars(@$good['name']) ?>">
                <?php
                    echo (!empty($editErrors['name'])? '<div class="invalid-feedback">'.$editErrors['name'].'</div>':'');
                ?>
            </div>
            <div class="form-group">
                <label for="description">Popisek produktu</label>
                <textarea style="height: 150px;" class="form-control  <?php echo (!empty($editErrors['description'])?'is-invalid':'') ?>" name="description" id="description"><?php echo htmlspecialchars(@$good['description']) ?></textarea>
                <?php
                    echo (!empty($editErrors['description'])? '<div class="invalid-feedback">'.$editErrors['description'].'</div>':'');
                ?>
            </div>
            <div class="form-group">
                <label for="price">Cena</label>
                <input type="number" class="form-control  <?php echo (!empty($editErrors['price'])?'is-invalid':'') ?>" name="price" id="price" min="0" step="0.01" value="<?php echo htmlspecialchars(@$good['price']) ?>">
                <?php
                    echo (!empty($editErrors['price'])? '<div class="invalid-feedback">'.$editErrors['price'].'</div>':'');
                ?>
            </div>
            <div class="form-group">
                <label for="category">Kategorie</label>
                <select class="form-select <?php echo (!empty($editErrors['category'])?'is-invalid':'') ?>" name="category" id="category" aria-label="Default select example">
                    <?php
                        $categoriesQuery = $db->prepare("SELECT * FROM categories;");
                        $categoriesQuery->execute();
                        $categoriesList = $categoriesQuery->fetchAll(PDO::FETCH_ASSOC);
                        if (!empty($categoriesList)){
                            foreach ($categoriesList as $category){
                                echo '<option value="'.$category['category_id'].'" '.($category['category_id']==$good['category_id']?'selected="selected"':'').'>'.$category['name'].'</option>';
                            }
                        }
                    ?>
                </select>
                <?php
                    echo (!empty($editErrors['category'])? '<div class="invalid-feedback">'.$editErrors['category'].'</div>':'');
                ?>
            </div>
            <div class="form-group">
                <label class="form-label" for="file">Vložte obrázek pro produkt</label>
                <input type="file" name="file" id="file" class="form-control <?php echo(!empty($editErrors['file']) ? 'is-invalid' : '') ?>">
                <?php
                    echo (!empty($editErrors['file'])? '<div class="invalid-feedback">'.$editErrors['file'].'</div>':'');
                ?>
            </div>
            <div class="form-group" style="text-align: center; margin-top: 30px;">
                <button type="submit" name="submit" class="btn btn-primary">Uložit</button>
                <a href="index.php" class="btn btn-primary">Zpět</a>
            </div>
        </form>
    </div>