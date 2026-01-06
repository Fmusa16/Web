<?php

// ensure we include the local config file correctly
require_once __DIR__ . '/config/config.php';

// show errors during development
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

function safe_post(string $k): string {
    return trim($_POST[$k] ?? '');
}

$name = safe_post('name');
$email = safe_post('email');
$phone = safe_post('phone');
$message = safe_post('message');

$errors = [];
if ($name === '') $errors[] = 'Nama wajib diisi.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
if ($message === '') $errors[] = 'Pesan wajib diisi.';

if ($errors) {

    echo implode("<br>", array_map('htmlspecialchars', $errors));
    exit;
}


try {
    $pdo = get_db();
} catch (Throwable $e) {
    $pdo = null;
}

if ($pdo instanceof PDO) {
    try {
        $stmt = $pdo->prepare('INSERT INTO contacts (name,email,phone,message) VALUES (?,?,?,?)');
        $stmt->execute([$name, $email, $phone, $message]);

        header('Location: contact.html?sent=1');
        exit;
    } catch (Throwable $e) {

        echo "Database error: " . htmlspecialchars($e->getMessage());
        exit;
    }
} else {

    $file = __DIR__ . '/config/contacts.json';
    $record = ['name'=>$name,'email'=>$email,'phone'=>$phone,'message'=>$message,'created_at'=>date('c')];
    $data = [];
    if (file_exists($file)) {
        $old = file_get_contents($file);
        $data = json_decode($old, true) ?: [];
    }
    $data[] = $record;
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    header('Location: contact.html?sent=1');
    exit;
}
