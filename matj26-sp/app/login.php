<?php
    /*
     * Přihlášení uživatele
     */
    $currentPage = 'login';
    require 'config.php';
    session_start();

    //Přesměruje přihlášeného uživatele
    if (!empty($_SESSION['user_id'])){
        header('Location: index.php');
        exit();
    }

    $login = true;
    if (!empty($_POST)){
        //Kontrola zda je uživatel v databázi
        $userQuery = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $userQuery->execute([$_POST['email']]);

        //Pokud je zaregistrovaný, tak ho přihlásím
        if ($user = $userQuery->fetch()){
            if (password_verify($_POST['password'], $user['password'])){
                unset($_SESSION['cart']);
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                header('Location: index.php');
                exit();
            }else{
                $login = false;
            }
        }else{
            $login = false;
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
            <h1 class="h3 mb-3 font-weight-normal">Přihlášení</h1>
            <label for="email" class="sr-only">Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars(@$_POST['email'])?>" class="form-control <?php echo (!$login?'is-invalid':'')?>" placeholder="Email" autocomplete="off" required autofocus/>
            <label for="password" class="sr-only">Heslo</label>
            <input type="password" name="password" id="password" value="" class="form-control" placeholder="Heslo" autocomplete="off" required/>
            <?php
                echo (!$login? '<div class="invalid-feedback">Nesprávný email nebo heslo!</div>':'');
            ?>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Přihlásit se</button>
        </form>
        <a href="registration.php">Nemám účet a chci se zaregistrovat.</a>
        <?php
        include 'layouts/footer.php';
        ?>
