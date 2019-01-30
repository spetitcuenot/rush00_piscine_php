<?php
include __DIR__ . '/../include/header.php';

if (!is_auth() || !get_user()['admin']) {
    header('Location: index.php');
    return;
}

$categories = categories();
$article_categories = article_categories();

if (isset($_POST) && isset($_POST['name']) && isset($_POST['description']) &&
    isset($_POST['price']) && isset($_POST['stock']) && isset($_FILES['image'])) {
    $image = uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $image);
    $id = uuid();
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = htmlspecialchars($_POST['price']);
    $stock = htmlspecialchars($_POST['stock']);
    $stmt = mysqli_prepare($database, 'INSERT INTO articles (id, name, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?);');
    mysqli_stmt_bind_param($stmt, 'ssssss', $id, $name, $description, $price, $stock, $image);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if (isset($_POST['categories'])) {
        foreach ($_POST['categories'] as $category) {
            $stmt = mysqli_prepare($database, 'INSERT INTO article_categories (id, article_id, category_id) VALUES (uuid(), ?, ?)');
            mysqli_stmt_bind_param($stmt, 'ss', $id, $category);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    alert_success('Article créé.');
    header('Location: /admin/index.php');
}
?>
    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title><?= $title ?> | Creer un article</title>
            <link rel="stylesheet" href="/assets/style.css">
        </head>
        <body>
            <?php include __DIR__ . '/../include/nav.php' ?>
            <div class="container">
                <div class="panel">
                    <h1>Creer un article</h1>
                    <form class="form" method="post" enctype="multipart/form-data">
                        <div class="form-input">
                            <label for="name">Nom</label>
                            <input type="text" name="name" id="name" placeholder="Nom" required min="2" max="30">
                        </div>
                        <div class="form-input">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" placeholder="Description" required></textarea>
                        </div>
                        <div class="form-input">
                            <label for="price">Prix</label>
                            <input type="number" name="price" id="price" placeholder="Prix" required step="0.01">
                        </div>
                        <div class="form-input">
                            <label for="stock">Quantité</label>
                            <input type="number" name="stock" id="stock" placeholder="Quantité" required>
                        </div>
                        <div class="form-input">
                            <label for="image">Image</label>
                            <input type="file" name="image" id="image" required>
                        </div>
                        <div class="form-input">
                            <label for="categories">Catégories</label>
                            <?php foreach ($categories as $i => $category): ?>
                                <div class="checkbox">
                                    <input type="checkbox" name="categories[]" id="categories"
                                           value="<?= $category['id'] ?>"> <?= $category['name'] ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="submit" value="Creer" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </body>
    </html>
<?php
include __DIR__ . '/../include/footer.php';
