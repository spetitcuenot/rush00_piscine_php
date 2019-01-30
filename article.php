<?php
include __DIR__ . '/include/header.php';
if (!isset($_GET['id'])) {
    alert_error('Article introuvable.');
    header('Location: /index.php');
    return;
}
$id = $_GET['id'];
$article = articles('WHERE id = ?', 's', $id);
if (!isset($article[0])) {
    alert_error('Article introuvable.');
    header('Location: /index.php');
    return;
}
$article = $article[0];
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
            <?php include __DIR__ . '/include/alert.php' ?>

            <div class="large-article">
                <div class="thumbnail">
                    <img src="/uploads/<?= $article['image'] ?>" alt="<?= $article['name'] ?>"
                         title="<?= $article['name'] ?>">
                </div>
                <div class="title">
                    <h1><?= $article['name'] ?></h1>
                </div>
                <div class="price">EUR <?= $article['price'] ?></div>
                <div class="delivery">Livraison GRATUITE</div>
                <?php if ($article['stock'] < 20): ?>
                    <div class="stock">Plus que <?= $article['stock'] ?> ex. Commandez vite !</div>
                <?php endif; ?>
                <div class="add-basket">
                    <a href="basket.php?action=add&id=<?= $article['id'] ?>" class="btn btn-primary">Ajouter au panier</a>
                </div>
                <div class="description">
                    <?= $article['description'] ?>
                </div>
            </div>

            <div class="recommendations">
                <div class="title">Produits fréquemment achetés ensemble</div>
                <div class="recommendeds">
                    <?php
                    $stmt = mysqli_prepare($database, 'SELECT articles.id, articles.name, articles.price, articles.stock, articles.image
                        FROM order_articles
                        INNER JOIN articles ON order_articles.article_id = articles.id
                        WHERE order_id IN (SELECT order_id
                                           FROM order_articles
                                           WHERE article_id = ?)
                          AND article_id != ?
                        GROUP BY article_id
                        ORDER BY SUM(quantity) DESC
                        LIMIT 5');
                    mysqli_stmt_bind_param($stmt, 'ss', $article['id'], $article['id']);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $id, $name, $price, $stock, $image);
                    while (mysqli_stmt_fetch($stmt)):
                        ?>
                        <div class="recommended">
                            <div class="thumbnail"><img src="/uploads/<?= $image ?>" alt="<?= $name ?>" title="<?= $name ?>"></div>
                            <div class="description">
                                <div class="title">
                                    <a href="article.php?id=<?= $id ?>"><?= $name ?></a>
                                </div>
                                <div class="price">EUR <?= $price ?></div>
                                <div class="delivery">Livraison GRATUITE</div>
                            </div>
                        </div>
                    <?php
                    endwhile;
                    mysqli_stmt_close($stmt);
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
<?php include __DIR__ . '/include/footer.php' ?>
