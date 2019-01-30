<?php
include __DIR__ . '/include/header.php';

if (!is_auth()) {
    alert_error('Vous devez etre connecté.');
    header('Location: login.php');
    return;
}

if (count($_SESSION['basket']) == 0) {
    alert_error('Votre panier est vide.');
    header('Location: index.php');
    return;
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

$id = uuid();
$stmt = mysqli_prepare($database, 'INSERT INTO orders (id, user_id, total) VALUES (?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'ssd', $id, get_user()['id'], $total);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

foreach ($items as $i => $item)
{
    $stmt = mysqli_prepare($database, 'INSERT INTO order_articles (id, order_id, article_id, quantity) VALUES (uuid(), ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssi', $id, $item['id'], $_SESSION['basket'][$item['id']]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
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
            <h1>Recapitulatif de votre commande</h1>

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
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="basket-order">
                <div class="total">
                    TOTAL EUR <?= $total ?>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
include __DIR__ . '/include/footer.php';
$_SESSION['basket'] = [];
?>
