<nav>
    <div class="container-fluid">
        <div class="title">Amazon 2</div>
        <ul>
            <li class="categories">
                <a href="/index.php">Catégories</a>
                <ul>
                    <?php
                    $stmt = mysqli_prepare($database, 'SELECT id, name FROM categories');
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $id, $name);
                    while (mysqli_stmt_fetch($stmt)):
                        ?>
                        <li><a href="/index.php?category=<?= $id ?>"><?= $name ?></a></li>
                    <?php
                    endwhile;
                    mysqli_stmt_close($stmt);
                    ?>
                </ul>
            </li>
            <li><a href="/basket.php">Panier</a></li>
            <?php if (is_auth()): ?>
                <li><a href="/account.php">Mon compte</a></li>
                <?php if (get_user()['admin']): ?>
                    <li><a href="/admin/index.php">Administration</a></li>
                <?php endif ?>
                <li><a href="/logout.php">Se deconnecter</a></li>
            <?php else: ?>
                <li><a href="/login.php">Connexion</a></li>
                <li><a href="/register.php">Inscription</a></li>
            <?php endif ?>
        </ul>
    </div>
</nav>
