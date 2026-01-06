<?php
session_start();
header('Content-Type: application/json');
echo json_encode(['user' => isset($_SESSION['logged_in_user']) ? $_SESSION['logged_in_user'] : null]);
