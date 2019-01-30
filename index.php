<?php
include __DIR__ . '/include/header.php';
if (isset($_GET['category']))
{
    $id = $_GET['category'];
    $category = categories('WHERE id = ?', 's', $id);
    if (!isset($category[0])) {
        alert_error('Categorie introuvable.');
        header('Location: /index.php');
        return;
    }
    $category = $category[0];
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= $title ?></title>
        <link rel="stylesheet" href="/assets/style.css">
        <style>
            body {
                background: #fff;
            }
        </style>
    </head>
    <body>
        <?php include __DIR__ . '/include/nav.php' ?>
        <div class="container">
            <?php if (isset($category)): ?>
                <h1 style="margin-bottom: 5px;"><?= $category['name'] ?></h1>
                <h6 style="color: #444; font-size: 14px; margin: 0; font-weight: 300;"><?= $category['description'] ?></h6>
            <?php else: ?>
                <h1>Bienvenue sur Amazon 2 !</h1>
            <?php endif; ?>
            <?php include __DIR__ . '/include/alert.php' ?>

            <div class="articles">
                <?php foreach ((isset($category) ? articles_from_category($category['id']) : articles('ORDER BY created_at DESC LIMIT 20')) as $i => $article): ?>
                    <div class="article">
                        <div class="thumbnail">
                            <img src="/uploads/<?= $article['image'] ?>" alt="<?= $article['name'] ?>"
                                 title="<?= $article['name'] ?>" height="100px">
                        </div>
                        <div class="title">
                            <a href="article.php?id=<?= $article['id'] ?>"><?= $article['name'] ?></a>
                        </div>
                        <div class="price">EUR <?= $article['price'] ?></div>
                        <div class="delivery">Livraison GRATUITE</div>
                        <?php if ($article['stock'] < 20): ?>
                            <div class="stock">Plus que <?= $article['stock'] ?> ex. Commandez vite !</div>
                        <?php endif; ?>
                        <div class="add-basket">
                            <a href="basket.php?action=add&id=<?= $article['id'] ?>" class="btn btn-primary">
                                Ajouter au panier
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </body>
</html>
<?php include __DIR__ . '/include/footer.php' ?>
