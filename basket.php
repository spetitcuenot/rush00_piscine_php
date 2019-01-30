<?php
include __DIR__ . '/include/header.php';
if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] == 'add') {
    $_SESSION['basket'][$_GET['id']] = isset($_SESSION['basket'][$_GET['id']]) ? $_SESSION['basket'][$_GET['id']] + 1 : 1;
}
if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] == 'delete') {
    unset($_SESSION['basket'][$_GET['id']]);
}
$items = [];
$total = 0;
foreach ($_SESSION['basket'] as $id => $quantity)
{
    $article = articles('WHERE id = ?', 's', $id);
    if (!isset($article[0])) {
        alert_error('Un article est introuvable.');
        continue;
    }
    $total += $_SESSION['basket'][$id] * $article[0]['price'];
    array_push($items, $article[0]);
}

if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] == 'delete') {
    unset($_SESSION['basket'][$_GET['id']]);
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= $title ?> | Panier</title>
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
            <h1>Panier</h1>

            <?php include __DIR__ . '/include/alert.php' ?>

            <div class="basket">
                <?php foreach ($items as $i => $item): ?>
                    <div class="item">
                        <div class="thumbnail">
                            <img src="/uploads/<?= $item['image'] ?>" alt="<?= $item['name'] ?>"
                                 title="<?= $item['name'] ?>">
                        </div>
                        <div class="title">
                            <a href="article.php?id=<?= $item['id'] ?>"><?= $item['name'] ?></a>
                        </div>
                        <div class="price">EUR <?= $item['price'] ?> x <?= $_SESSION['basket'][$item['id']] ?></div>
                        <div class="delivery">Livraison GRATUITE</div>
                        <div class="delete-basket">
                            <a href="basket.php?action=delete&id=<?= $item['id'] ?>" class="btn btn-default">
                                Supprimer du panier
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (count($items)): ?>
                <div class="basket-order">
                    <div class="total">
                        TOTAL EUR <?= $total ?>
                    </div>
                    <div class="order">
                        <a href="order.php" class="btn btn-primary">Commander !</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>
<?php include __DIR__ . '/include/footer.php' ?>
