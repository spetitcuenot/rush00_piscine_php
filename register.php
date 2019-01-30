<?php
include __DIR__ . '/include/header.php';

if (is_auth()) {
    header('Location: index.php');
    return;
}

if (isset($_POST) && isset($_POST['email']) && isset($_POST['password']) &&
    isset($_POST['name']) && isset($_POST['address'])) {
    if (empty($_POST['email'])) {
        alert_error('Adresse email invalide.');
    }
    if (strlen($_POST['password']) < 6 || strlen($_POST['password']) > 30) {
        alert_error('Mot de passe invalide.');
    }
    if (strlen($_POST['name']) < 3 || strlen($_POST['name']) > 50) {
        $_SESSION['error'] = 'Nom et prenom invalide.';
    }
    if (strlen($_POST['address']) < 5 || strlen($_POST['address']) > 255) {
        alert_error('Adresse invalide.');
    }
    if (!has_alert_error()) {
        $stmt = mysqli_prepare($database, 'SELECT id FROM users WHERE email = ?');
        mysqli_stmt_bind_param($stmt, 's', $_POST['email']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        if (isset($id)) {
            alert_error('Adresse email deja prise.');
        } else {
            $stmt = mysqli_prepare($database, 'INSERT INTO users (id, name, email, password, address) VALUES (uuid(), ?, ?, ?, ?)');
            $password = hash('sha512', $_POST['password']);
            mysqli_stmt_bind_param($stmt, 'ssss', htmlspecialchars($_POST['name']), htmlspecialchars($_POST['email']), $password, htmlspecialchars($_POST['address']));
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            alert_success('Inscription reussie.');
            header('Location: login.php');
            return;
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
        <title><?= $title ?> | Inscription</title>
        <link rel="stylesheet" href="/assets/style.css">
    </head>
    <body>
        <?php include __DIR__ . '/include/nav.php' ?>
        <div class="container">
            <div class="panel">
                <h1>Inscription</h1>
                <?php include __DIR__ . '/include/alert.php' ?>
                <form class="form register" action="register.php" method="post">
                    <div class="form-input">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Email" required>
                    </div>
                    <div class="form-input">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password" placeholder="Mot de passe" required min="6"
                               max="30">
                    </div>
                    <div class="form-input">
                        <label for="name">Nom et Prénom</label>
                        <input type="text" name="name" id="name" placeholder="Nom et Prénom" required min="3" max="50">
                    </div>
                    <div class="form-input">
                        <label for="address">Adresse</label>
                        <input type="text" name="address" id="address" placeholder="Adresse" required min="5" max="255">
                    </div>
                    <input type="submit" value="Inscription !" class="btn btn-primary">
                </form>
            </div>
        </div>
    </body>
</html>
<?php include __DIR__ . '/include/footer.php' ?>
