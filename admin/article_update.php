<?php
include __DIR__ . '/../include/header.php';

if (!is_auth() || !get_user()['admin']) {
    header('Location: index.php');
    return;
}

if (!isset($_GET) || !isset($_GET['id'])) {
    alert_error('Article introuvable.');
    header('Location: /admin/index.php');
    return;
}

$id = $_GET['id'];
$article = articles('WHERE id = ?', 's', $id);
if (!isset($article[0])) {
    alert_error('Article introuvable.');
    header('Location: /admin/index.php');
    return;
}
$article = $article[0];
$categories = categories();
$article_categories = array_map(function ($item) {
    return $item['category_id'];
}, article_categories('WHERE article_id = ?', 's', $id));

if (isset($_POST) && isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['stock'])) {
    $stmt = mysqli_prepare($database, 'UPDATE articles SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?');
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = htmlspecialchars($_POST['price']);
    $stock = htmlspecialchars($_POST['stock']);
    mysqli_stmt_bind_param($stmt, 'sssss', $name, $description, $price, $stock, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($database, 'DELETE FROM article_categories WHERE article_id = ?');
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (isset($_POST['categories']))
    {
        foreach ($_POST['categories'] as $category) {
            $stmt = mysqli_prepare($database, 'INSERT INTO article_categories (id, article_id, category_id) VALUES (uuid(), ?, ?)');
            mysqli_stmt_bind_param($stmt, 'ss', $id, $category);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    alert_success('Article mis à jour.');
    header('Location: /admin/index.php');
}
?>
    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title><?= $title ?> | Mettre a jour un article</title>
            <link rel="stylesheet" href="/assets/style.css">
        </head>
        <body>
            <?php include __DIR__ . '/../include/nav.php' ?>
            <div class="container">
                <div class="panel">
                    <h1>Mettre a jour un article</h1>
                    <form class="form" method="post">
                        <div class="form-input">
                            <label for="name">Nom</label>
                            <input type="text" name="name" id="name" placeholder="Nom" required min="2" max="30"
                                   value="<?= $article['name'] ?>">
                        </div>
                        <div class="form-input">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" placeholder="Description"
                                      required><?= $article['description'] ?></textarea>
                        </div>
                        <div class="form-input">
                            <label for="price">Prix</label>
                            <input type="number" name="price" id="price" placeholder="Prix" required step="0.01"
                                   value="<?= $article['price'] ?>">
                        </div>
                        <div class="form-input">
                            <label for="stock">Quantité</label>
                            <input type="number" name="stock" id="stock" placeholder="Quantité" required
                                   value="<?= $article['stock'] ?>">
                        </div>
                        <div class="form-input">
                            <label for="categories">Catégories</label>
                            <?php foreach ($categories as $i => $category): ?>
                                <div class="checkbox">
                                    <input type="checkbox" name="categories[]" id="categories"
                                           value="<?= $category['id'] ?>" <?= in_array($category['id'], $article_categories) ? 'checked' : '' ?>> <?= $category['name'] ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="submit" value="Modifier" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </body>
    </html>
<?php
include __DIR__ . '/../include/footer.php';
