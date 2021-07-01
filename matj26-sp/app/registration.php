<?php
    /*
     * Stránka sloužící pro registraci uživatele
     * Stránka uživatele rovnou nepřihlašuje
     */
    $currentPage = 'login';
    require 'config.php';
    session_start();

    $errors=[];

    if (!empty($_POST)){
        //Kontrola splnění formátu pro email
        if (empty($_POST['email']) || !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
            $errors['email']='Musíte zadat platnou emailovou adresu!';
        }else{
            $emailQuery = $db->prepare("SELECT * FROM users WHERE email=? LIMIT 1;");
            $emailQuery->execute([
                $_POST['email']
            ]);
            if ($emailQuery->rowCount()>0){
                $errors['email']='Tento email je jíž registrovaný!';
            }
        }

        //Kontrola prvního zadaného hesla
        if (empty($_POST['password']) || (mb_strlen($_POST['password'],'utf-8')<5)){
            $errors['password']='Heslo musí mít minimálně 6 znaků!';
        }elseif($_POST['password'] != $_POST['password2']){
            //Kontrola druhého zadaného hesla
            $errors['password2']='Hesla se musí shodovat!';
        }

        if (empty($errors)){
            //Zapsání uživatele do databáze a přemístění na přihlášení
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $userQuery = $db->prepare("INSERT INTO users (email, password) VALUES(?, ?);");
            $userQuery->execute([
                $_POST['email'],
                $password
            ]);

            header('Location: login.php');
        }
    }
?>
<!Doctype html>
<html lang="cs">
    <?php include 'layouts/head.php'; ?>
    <body class="text-center">
    <?php
        include 'layouts/navbar.php';
    ?>
    <form method="post" class="form-signin">
        <h1 class="h3 mb-3 font-weight-normal">Registrace</h1>
        <label for="email" class="sr-only">Email</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars(@$_POST['email'])?>" class="form-control<?php echo (!empty($errors['email'])?' is-invalid':'');?>" placeholder="Email" autocomplete="off" required autofocus>
        <?php
        if (!empty($errors['email'])){
            echo '<div class="invalid-feedback">'.$errors['email'].'</div>';
        }
        ?>
        <label for="password" class="sr-only">Heslo</label>
        <input type="password" name="password" id="password" value="" class="form-control<?php echo (!empty($errors['password'])?' is-invalid':'');?>" placeholder="Heslo" autocomplete="off" required>
        <?php
        if (!empty($errors['password'])){
            echo '<div class="invalid-feedback">'.$errors['password'].'</div>';
        }
        ?>
        <label for="password2" class="sr-only">Potvrzení hesla</label>
        <input type="password" name="password2" id="password2" value="" class="form-control<?php echo (!empty($errors['password2'])?' is-invalid':'');?>" placeholder="Heslo znovu" autocomplete="off" required>
        <?php
        if (!empty($errors['password2'])){
            echo '<div class="invalid-feedback">'.$errors['password2'].'</div>';
        }
        ?>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Registrovat se</button>
    </form>
    <a href="login.php">Účet už mám a chci se přihlásit.</a>
    <?php
        include 'layouts/footer.php';
    ?>
