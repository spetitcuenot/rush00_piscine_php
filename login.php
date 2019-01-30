<?php
include __DIR__ . '/include/header.php';

if (is_auth()) {
    header('Location: index.php');
    return;
}

if (isset($_POST) && isset($_POST['email']) && isset($_POST['password'])) {
    if (empty($_POST['email'])) {
        alert_error('Adresse email invalide.');
    }
    if (strlen($_POST['password']) < 6 || strlen($_POST['password']) > 30) {
        alert_error('Mot de passe invalide.');
    }
    if (!has_alert_error()) {
        $stmt = mysqli_prepare($database, 'SELECT id FROM users WHERE email = ? AND password = ?');
        $email = $_POST['email'];
        $password = hash('sha512', $_POST['password']);
        mysqli_stmt_bind_param($stmt, 'ss', $email, $password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        if (isset($id)) {
            $_SESSION['user_id'] = $id;
            alert_success('Vous êtes maintenant connecté.');
            header('Location: index.php');
        } else {
            alert_error('Adresse email ou mot de passe incorrect.');
        }
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= $title ?> | Connexion</title>
        <link rel="stylesheet" href="/assets/style.css">
    </head>
    <body>
        <?php include __DIR__ . '/include/nav.php' ?>
        <div class="container">
            <div class="panel">
                <h1>Connexion</h1>
                <?php include __DIR__ . '/include/alert.php' ?>
                <form class="form login" action="login.php" method="post">
                    <div class="form-input">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Email" required>
                    </div>
                    <div class="form-input">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password" placeholder="Mot de passe" required min="6"
                               max="30">
                    </div>
                    <input type="submit" value="Connexion !" class="btn btn-primary">
                </form>
            </div>
        </div>
    </body>
</html>
<?php include __DIR__ . '/include/footer.php' ?>
