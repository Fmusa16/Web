<?php
/**
 * Return a PDO connection to the app database.
 *
 * @return PDO
 */
function get_db(): PDO
{

    static $pdo = null;

    
    if ($pdo === null) {
        $host = "localhost";
        $user = "root";
        $pass = ""; 
        $db   = "db_f1xcoffe";
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Tampilkan error sebagai exception
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
            PDO::ATTR_EMULATE_PREPARES   => false,                 
        ];


        $pdo = new PDO($dsn, $user, $pass, $options);
    }

    return $pdo;
}
?>