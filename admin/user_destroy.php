<?php
include __DIR__ .'/../include/header.php';

if (!is_auth() || !get_user()['admin']) {
    header('Location: /admin/index.php');
    return;
}

if (isset($_GET) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = mysqli_prepare($database, 'DELETE FROM orders WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $stmt = mysqli_prepare($database, 'DELETE FROM users WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    alert_success('Utilisateur supprimé.');
}

header('Location: /admin/index.php');

include __DIR__ .'/../include/footer.php';
