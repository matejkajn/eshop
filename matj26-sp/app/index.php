<?php
    /*
     * Hlavní stránka, která zobrazuje nabídku produktů a filtr kategorií
     */
    $currentPage = 'index';
    session_start();

    require "config.php";

    //Vypsání podle kategorie
    if (!empty($_GET['cat'])){
        $stmt = $db->prepare("SELECT * FROM goods WHERE category_id=? ORDER BY good_id;");
        $stmt->execute([$_GET['cat']]);
        $goods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }else{
        $stmt = $db->prepare("SELECT * FROM goods ORDER BY good_id;");
        $stmt->execute();
        $goods = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <h1 class="display-6">Nabídka produktů</h1>
        </div>
    </div>
    <div class="container">
        <div class="row" style="padding-top: 20px;">
            <?php if ($goods){ ?>
            <aside class="col-md-3" style="margin-bottom: 20px;">
                <div class="list-group">
                <?php
                    $categoriesQuery = $db->prepare("SELECT * FROM categories;");
                    $categoriesQuery->execute();
                    $categoriesList = $categoriesQuery->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($categoriesList)){
                        if (empty($_GET['cat'])){
                            echo'<a type="button" class="list-group-item list-group-item-action active" href="index.php?cat=">Vše</a>';
                        }else{
                            echo'<a type="button" class="list-group-item list-group-item-action" href="index.php?cat=">Vše</a>';
                        }
                        foreach ($categoriesList as $category){
                            if (!empty($_GET['cat']) && $category['category_id'] == $_GET['cat']) {
                                echo'<a type="button" class="list-group-item list-group-item-action active" href="index.php?cat='.$category['category_id'].'" id="cat">'.$category['name'].'</a>';
                            }else{
                                echo'<a type="button" class="list-group-item list-group-item-action" href="index.php?cat='.$category['category_id'].'" id="cat">'.$category['name'].'</a>';
                            }
                        }
                    }
                ?>

                </div>
            </aside>
            <main class="col-md-9">
                <?php foreach ($goods as $good){ ?>
                <article class="card card-product-list">
                    <div class="row">
                        <aside class="col-md-3" style=" padding: 15px 15px; text-align: center;">
                            <?php if (file_exists("uploads/".$good['image']) && $good['image'] != ''){ ?>
                                <img src="uploads/<?php echo $good['image']?>" width="220px" height="220px" alt="<?php echo $good['good_id']?>">
                            <?php }else{
                                echo 'Obrázek není k dispozici';
                            }?>
                        </aside>
                        <div class="col-md-6" style=" padding: 15px 15px;">
                            <h5><?php echo htmlspecialchars($good['name'])?></h5>
                            <p><?php echo htmlspecialchars($good['description'])?></p>
                        </div>
                        <aside class="col-sm-3" style=" padding: 15px 15px; border-left: 1px solid #e4e4e4; height: 100%;">
                            <h6><?php echo $good['price'] ?> Kč</h6>
                            <p>
                                <?php
                                if (!empty($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin'){
                                    echo '<a class="btn btn-warning btn-block" href="edit.php?action=edit&id='.$good['good_id'].'">Upravit</a>';
                                    echo '<a class="btn btn-danger btn-block" href="edit.php?action=delete&id='.$good['good_id'].'">Odebrat</a>';
                                }
                                ?>
                                <a class="btn btn-primary btn-block" href="add.php?action=add&id=<?php echo $good['good_id']?>">Přidat do košíku</a>
                            </p>
                        </aside>
                    </div>

                </article>
                <?php } ?>
            </main>
            <?php }else{
                echo '<div class="alert alert-danger" role="alert">Omlouváme se ale naše nabídka není momentálně k dispozici.</div>';
            } ?>
        </div>
    </div>
