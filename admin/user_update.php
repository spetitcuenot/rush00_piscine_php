<?php
include __DIR__ . '/../include/header.php';

if (!is_auth() || !get_user()['admin']) {
    header('Location: index.php');
    return;
}

if (!isset($_GET) || !isset($_GET['id'])) {
    alert_error('Utilisateur introuvable.');
    header('Location: /admin/index.php');
    return;
}

$id = $_GET['id'];
$user = users('WHERE id = ?', 's', $id);
if (!isset($user[0])) {
    alert_error('Utilisateur introuvable.');
    header('Location: /admin/index.php');
    return;
}
$user = $user[0];

if (isset($_POST) && isset($_POST['email']) && isset($_POST['name']) && isset($_POST['address'])) {
    if (empty($_POST['email'])) {
        alert_error('Adresse email invalide.');
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
        if (isset($id) && $user['email'] != $_POST['email']) {
            alert_error('Adresse email deja prise.');
        } else {
            $stmt = mysqli_prepare($database, 'UPDATE users SET email = ?, name = ?, address = ?, admin = ? WHERE id = ?');
            $email = htmlspecialchars($_POST['email']);
            $name = htmlspecialchars($_POST['name']);
            $address = htmlspecialchars($_POST['address']);
            $admin = isset($_POST['admin']) && $_POST['admin'] == 'on';
            mysqli_stmt_bind_param($stmt, 'sssis', $email, $name, $address, $admin, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            alert_success('Utilisateur mis a jour.');
            header('Location: /admin/index.php');
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
            <title><?= $title ?> | Mettre a jour un utilisateur</title>
            <link rel="stylesheet" href="/assets/style.css">
        </head>
        <body>
            <?php include __DIR__ . '/../include/nav.php' ?>
            <div class="container">
                <div class="panel">
                    <h1>Mettre a jour un utilisateur</h1>
                    <form class="form" method="post">
                        <div class="form-input">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="Email"
                                   value="<?= $user['email'] ?>" required>
                        </div>
                        <div class="form-input">
                            <label for="name">Nom et Prénom</label>
                            <input type="text" name="name" id="name" placeholder="Nom et Prénom"
                                   value="<?= $user['name'] ?>" required min="3" max="50">
                        </div>
                        <div class="form-input">
                            <label for="address">Adresse</label>
                            <input type="text" name="address" id="address" placeholder="Adresse"
                                   value="<?= $user['address'] ?>" required min="5" max="255">
                        </div>
                        <div class="form-input">
                            <div class="checkbox">
                                <input type="checkbox" name="admin" id="admin" <?= $user['admin'] ? 'checked' : '' ?>>
                                Admin
                            </div>
                        </div>
                        <input type="submit" value="Modifier" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </body>
    </html>
<?php
include __DIR__ . '/../include/footer.php';
