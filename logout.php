<?php
include __DIR__ .'/include/header.php';

if (!is_auth()) {
    header('Location: index.php');
    return;
}

unset($_SESSION['user_id']);
alert_success('Vous etes maintenant deconnecter');
header('Location: login.php');

include __DIR__ .'/include/footer.php';
