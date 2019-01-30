<?php
include __DIR__ . '/../include/header.php';

if (!is_auth() || !get_user()['admin']) {
    header('Location: index.php');
    return;
}
?>
    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title><?= $title ?> | Administration</title>
            <link rel="stylesheet" href="/assets/style.css">
        </head>
        <body>
            <?php include __DIR__ . '/../include/nav.php' ?>
            <div class="container-fluid">
                <div class="panel">
                    <h1>Administration</h1>

                    <?php include __DIR__ . '/../include/alert.php' ?>

                    <div class="header">
                        <div class="title">
                            Commandes
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Commandé le</th>
                                <th>Commandé par</th>
                                <th>Adresse de livraison</th>
                                <th>Prix</th>
                                <th>Articles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (orders('ORDER BY orders.created_at DESC') as $i => $order): ?>
                                <tr>
                                    <td><?= date_format(date_create_from_format('Y-m-d H:i:s', $order['created_at']), 'd/m/Y') ?></td>
                                    <td><?= $order['user_name'] ?></td>
                                    <td><?= $order['address'] ?></td>
                                    <td><?= $order['total'] ?> EUR</td>
                                    <td>
                                        <ul style="margin-top: 10px;">
                                            <?php foreach ($order['articles'] as $i => $article): ?>
                                                <li><a href="/article.php?id=<?= $article['id'] ?>"><?= $article['name'] ?></a> x <?= $article['quantity'] ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="header" style="margin-top: 20px">
                        <div class="title">
                            Articles
                        </div>
                        <div class="actions">
                            <a href="article_create.php">Créer un article</a>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Prix</th>
                                <th>Stock</th>
                                <th style="width: 200px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (articles() as $i => $article): ?>
                                <tr>
                                    <td><img src="/uploads/<?= $article['image'] ?>" alt="<?= $article['name'] ?>"
                                             title="<?= $article['name'] ?>" height="40px"></td>
                                    <td><?= $article['name'] ?></td>
                                    <td><?= truncate($article['description'], 250) ?></td>
                                    <td><?= $article['price'] ?></td>
                                    <td><?= $article['stock'] ?></td>
                                    <td>
                                        <a href="article_update.php?id=<?= $article['id'] ?>" class="btn btn-default">Modifier</a>
                                        <a href="article_destroy.php?id=<?= $article['id'] ?>" class="btn btn-primary">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="header" style="margin-top: 20px">
                        <div class="title">
                            Utilisateurs
                        </div>
                        <div class="actions">
                            <a href="user_create.php">Créer un utilisateur</a>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Nom et prénom</th>
                                <th>Email</th>
                                <th>Adresse</th>
                                <th>Admin</th>
                                <th style="width: 200px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (users() as $i => $user): ?>
                                <tr style="height: 55px">
                                    <td><?= $user['name'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td><?= $user['address'] ?></td>
                                    <td><?= $user['admin'] ? "Oui" : "Non" ?></td>
                                    <td>
                                        <a href="user_update.php?id=<?= $user['id'] ?>" class="btn btn-default">Modifier</a>
                                        <a href="user_destroy.php?id=<?= $user['id'] ?>" class="btn btn-primary">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="header" style="margin-top: 20px">
                        <div class="title">
                            Catégories
                        </div>
                        <div class="actions">
                            <a href="category_create.php">Créer une catégorie</a>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Description</th>
                                <th style="width: 200px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (categories() as $i => $category): ?>
                                <tr style="height: 55px">
                                    <td><?= $category['name'] ?></td>
                                    <td><?= truncate($category['description'], 250) ?></td>
                                    <td>
                                        <a href="category_update.php?id=<?= $category['id'] ?>" class="btn btn-default">Modifier</a>
                                        <a href="category_destroy.php?id=<?= $category['id'] ?>"
                                           class="btn btn-primary">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </body>
    </html>
<?php
include __DIR__ . '/../include/footer.php';
