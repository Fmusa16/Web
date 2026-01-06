<?php
require __DIR__ . '/config/config.php';
try{
    $pdo = get_db();
    $stmt = $pdo->query("SHOW TABLES LIKE 'contacts'");
    $exists = $stmt->fetch();
    echo $exists ? "contacts table EXISTS\n" : "contacts table DOES NOT exist\n";
} catch (Throwable $e){
    echo "DB error: " . $e->getMessage() . "\n";
}
