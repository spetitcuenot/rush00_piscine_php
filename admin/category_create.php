<?php
include __DIR__ .'/../include/header.php';

if (!is_auth() || !get_user()['admin']) {
    header('Location: index.php');
    return;
}

if (isset($_POST) && isset($_POST['name']) && isset($_POST['description'])) {
    $stmt = mysqli_prepare($database, 'INSERT INTO categories (id, name, description) VALUES (uuid(), ?, ?)');
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    mysqli_stmt_bind_param($stmt, 'ss', $name, $description);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    alert_success('Categorie créé.');
    header('Location: /admin/index.php');
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= $title ?> | Creer une categorie</title>
        <link rel="stylesheet" href="/assets/style.css">
    </head>
    <body>
        <?php include __DIR__ .'/../include/nav.php' ?>
        <div class="container">
            <div class="panel">
                <h1>Creer une categorie</h1>
                <form class="form" method="post" enctype="multipart/form-data">
                    <div class="form-input">
                        <label for="name">Nom</label>
                        <input type="text" name="name" id="name" placeholder="Nom" required min="2" max="30">
                    </div>
                    <div class="form-input">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" placeholder="Description" required></textarea>
                    </div>
                    <input type="submit" value="Creer" class="btn btn-primary">
                </form>
            </div>
        </div>
    </body>
</html>
<?php
include __DIR__ .'/../include/footer.php';
