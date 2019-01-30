<?php
include __DIR__ .'/../include/header.php';

if (!is_auth() || !get_user()['admin']) {
    header('Location: index.php');
    return;
}

if (isset($_GET) && isset($_GET['id'])) {
    $stmt = mysqli_prepare($database, 'DELETE FROM article_categories WHERE article_id = ?');
    mysqli_stmt_bind_param($stmt, 's', $_GET['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $stmt = mysqli_prepare($database, 'DELETE FROM order_articles WHERE article_id = ?');
    mysqli_stmt_bind_param($stmt, 's', $_GET['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $stmt = mysqli_prepare($database, 'DELETE FROM articles WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 's', $_GET['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    alert_success('Article supprimé.');
}

header('Location: /admin/index.php');

include __DIR__ .'/../include/footer.php';
