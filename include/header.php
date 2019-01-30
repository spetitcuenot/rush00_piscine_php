<?php
$database = mysqli_connect('mysql', 'user', 'password', 'database');
session_start();
$title = 'Amazon 2';

if (mysqli_connect_errno()) {
    echo 'Echec de la connexion: ' . mysqli_connect_error();
    exit();
}

if (!isset($_SESSION['alert_success']))
    $_SESSION['alert_success'] = [];
if (!isset($_SESSION['alert_error']))
    $_SESSION['alert_error'] = [];
if (!isset($_SESSION['basket']))
    $_SESSION['basket'] = [];

function uuid()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

function is_auth()
{
    return isset($_SESSION['user_id']) && isset(get_user()['id']);
}

function get_user()
{
    global $database;
    $user = [];
    $stmt = mysqli_prepare($database, 'SELECT id, name, password, email, address, admin, created_at FROM users WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user['id'], $user['name'], $user['password'], $user['email'], $user['address'], $user['admin'], $user['created_at']);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $user;
}

function articles($query = '', $types = '', ...$params)
{
    global $database;
    $articles = [];
    $stmt = mysqli_prepare($database, 'SELECT id, name, description, price, stock, image, created_at FROM articles ' . $query);
    if (count($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $name, $description, $price, $stock, $image, $created_at);
    while (mysqli_stmt_fetch($stmt))
        array_push($articles, [
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
            'image' => $image,
            'created_at' => $created_at
        ]);
    mysqli_stmt_close($stmt);
    return $articles;
}

function article_categories($query = '', $types = '', ...$params) {
    global $database;
    $article_categories = [];
    $stmt = mysqli_prepare($database, 'SELECT id, article_id, category_id FROM article_categories ' . $query);
    if (count($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $article_id, $category_id);
    while (mysqli_stmt_fetch($stmt))
        array_push($article_categories, [
            'id' => $id,
            'article_id' => $article_id,
            'category_id' => $category_id
        ]);
    mysqli_stmt_close($stmt);
    return $article_categories;
}

function orders($query = '', $types = '', ...$params)
{
    global $database;
    $orders = [];
    $stmt = mysqli_prepare($database, 'SELECT orders.id, orders.total, orders.created_at,  articles.id, order_articles.quantity, articles.name, users.name, users.address
        FROM order_articles
          INNER JOIN orders ON order_articles.order_id = orders.id
          INNER JOIN users ON orders.user_id = users.id
          INNER JOIN articles ON order_articles.article_id = articles.id '. $query);
    if (count($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_bind_result($stmt, $id, $total, $created_at, $article_id, $quantity, $name, $user_name, $user_address);
    mysqli_stmt_execute($stmt);
    while (mysqli_stmt_fetch($stmt))
    {
        if (!isset($orders[$id]))
            $orders[$id] = [
                'id' => $id,
                'user_name' => $user_name,
                'address' => $user_address,
                'total' => $total,
                'created_at' => $created_at,
                'articles' => []
            ];
        array_push($orders[$id]['articles'], [
            'id' => $article_id,
            'name' => $name,
            'quantity' => $quantity
        ]);
    }
    mysqli_stmt_close($stmt);
    return $orders;
}

function truncate($text, $len)
{
    if (strlen($text) > $len)
    {
        return substr($text, 0, $len) .'...';
    }
    return $text;
}

function articles_from_category($category)
{
    global $database;
    $articles = [];
    $stmt = mysqli_prepare($database, 'SELECT articles.id, articles.name, articles.description, articles.price, articles.stock, articles.image, articles.created_at
                  FROM articles INNER JOIN article_categories ON articles.id = article_categories.article_id WHERE article_categories.category_id = ?');
    mysqli_stmt_bind_param($stmt, 's', $category);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $name, $description, $price, $stock, $image, $created_at);
    while (mysqli_stmt_fetch($stmt))
        array_push($articles, [
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
            'image' => $image,
            'created_at' => $created_at
        ]);
    mysqli_stmt_close($stmt);
    return $articles;
}

function users($query = '', $types = '', ...$params)
{
    global $database;
    $users = [];
    $stmt = mysqli_prepare($database, 'SELECT id, name, email, password, address, admin, created_at FROM users ' . $query);
    if (count($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $name, $email, $password, $address, $admin, $created_at);
    while (mysqli_stmt_fetch($stmt))
        array_push($users, [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'address' => $address,
            'admin' => $admin,
            'created_at' => $created_at
        ]);
    mysqli_stmt_close($stmt);
    return $users;
}

function categories($query = '', $types = '', ...$params)
{
    global $database;
    $categories = [];
    $stmt = mysqli_prepare($database, 'SELECT id, name, description, created_at FROM categories ' . $query);
    if (count($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $name, $description, $created_at);
    while (mysqli_stmt_fetch($stmt))
        array_push($categories, [
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'created_at' => $created_at
        ]);
    mysqli_stmt_close($stmt);
    return $categories;
}

function alert_success($text)
{
    array_push($_SESSION['alert_success'], $text);
}

function alert_error($text)
{
    array_push($_SESSION['alert_error'], $text);
}

function has_alert_success()
{
    return count($_SESSION['alert_success']) > 0;
}

function has_alert_error()
{
    return count($_SESSION['alert_error']) > 0;
}
