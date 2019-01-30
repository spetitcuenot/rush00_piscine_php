<?php
include __DIR__ . '/include/header.php';

if (!is_auth()) {
    header('Location: index.php');
    return;
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case "update-account":
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
                    if (isset($id) && get_user()['email'] != $_POST['email']) {
                        alert_error('Adresse email deja prise.');
                    } else {
                        $stmt = mysqli_prepare($database, 'UPDATE users SET name = ?, email = ?, address = ? WHERE id = ?');
                        $name = htmlspecialchars($_POST['name']);
                        $email = htmlspecialchars($_POST['email']);
                        $address = htmlspecialchars($_POST['address']);
                        mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $address, get_user()['id']);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                        alert_success('Informations mis a jour.');
                        header('Location: account.php');
                        return;
                    }
                }
            }
            break;
        case "update-password":
            if (isset($_POST) && isset($_POST['oldpassword']) && isset($_POST['newpassword'])) {
                if (strlen($_POST['newpassword']) < 6 || strlen($_POST['newpassword']) > 30) {
                    alert_error('Nouveau mot de passe invalide.');
                }
                if (get_user()['password'] != hash('sha512', $_POST['oldpassword'])) {
                    alert_error('Ancien mot de passe incorrect.');
                }
                if (!has_alert_error()) {
                    $stmt = mysqli_prepare($database, 'UPDATE users SET password = ? WHERE id = ?');
                    mysqli_stmt_bind_param($stmt, 'ss', hash('sha512', $_POST['newpassword']), get_user()['id']);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    alert_success('Mot de passe mis a jour');
                    header('Location: account.php');
                }
            }
            break;
        case "delete-account":
            $id = get_user()['id'];
            $stmt = mysqli_prepare($database, 'DELETE FROM orders WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 's', $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $stmt = mysqli_prepare($database, 'DELETE FROM users WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 's', $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            unset($_SESSION['user_id']);
            alert_success('Votre compte a ete supprimer.');
            header('Location: /index.php');
            break;
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= $title ?> | Mon compte</title>
        <link rel="stylesheet" href="/assets/style.css">
    </head>
    <body>
        <?php include __DIR__ . '/include/nav.php' ?>
        <div class="container">
            <div class="panel" style="padding-bottom: 60px">
                <h1>Mon compte</h1>

                <?php include __DIR__ . '/include/alert.php' ?>

                <div class="header" style="margin-top: 20px">
                    <div class="title">
                        Mes commandes
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Commandé le</th>
                            <th>Prix</th>
                            <th>Articles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (orders('WHERE orders.user_id = ?', 's', get_user()['id']) as $i => $order): ?>
                            <tr>
                                <td><?= date_format(date_create_from_format('Y-m-d H:i:s', $order['created_at']), 'd/m/Y') ?></td>
                                <td><?= $order['total'] ?> EUR</td>
                                <td>
                                    <ul style="margin-top: 10px;">
                                        <?php foreach ($order['articles'] as $i => $article): ?>
                                            <li><a href="article.php?id=<?= $article['id'] ?>"><?= $article['name'] ?></a> x <?= $article['quantity'] ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="header" style="margin-top: 20px">
                    <div class="title">
                        Mes informations
                    </div>
                </div>
                <form class="form register" action="account.php?action=update-account" method="post">
                    <div class="form-input">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Email"
                               value="<?= get_user()['email'] ?>" required>
                    </div>
                    <div class="form-input">
                        <label for="name">Nom et Prénom</label>
                        <input type="text" name="name" id="name" placeholder="Nom et Prénom"
                               value="<?= get_user()['name'] ?>" required min="3" max="50">
                    </div>
                    <div class="form-input">
                        <label for="address">Adresse</label>
                        <input type="text" name="address" id="address" placeholder="Adresse"
                               value="<?= get_user()['address'] ?>" required min="5" max="255">
                    </div>
                    <input type="submit" value="Mettre à jour" class="btn btn-primary"
                           style="width: 20%; float: left; margin-right: 10px">
                    <a class="btn btn-default" style="float: left;width: 30%;" href="account.php?action=delete-account">Supprimer
                        mon compte</a>
                </form>

                <div class="header" style="margin-top: 20px">
                    <div class="title">
                        Mettre à jour mon mot de passe
                    </div>
                </div>
                <form class="form register" action="account.php?action=update-password" method="post">
                    <div class="form-input">
                        <label for="oldpassword">Ancien mot de passe</label>
                        <input type="password" name="oldpassword" id="oldpassword" placeholder="Ancien mot de passe"
                               required min="6" max="30">
                    </div>
                    <div class="form-input">
                        <label for="newpassword">Nouveau mot de passe</label>
                        <input type="password" name="newpassword" id="newpassword" placeholder="Nouveau mot de passe"
                               required min="6" max="30">
                    </div>
                    <input type="submit" value="Mettre à jour" class="btn btn-primary" style="width: 20%; float: left;">
                </form>
            </div>
        </div>
    </body>
</html>
<?php include __DIR__ . '/include/footer.php' ?>
