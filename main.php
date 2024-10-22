<?php
// server.php

$host = '0.0.0.0';
$port = 5000;

echo "Запускаю сервер на http://$host:$port...\n";
chdir(__DIR__); // Зміна робочої директорії

// Обробка запитів
if (php_sapi_name() === 'cli-server') {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Включення файлів на основі URI
    if ($uri === '/ok.php') {
        include 'ok.php'; // Включення файлу ok.php
    } else {
        include 'index.html'; // Включення файлу index.html
    }
} else {
    // Запуск вбудованого сервера
    exec("php -S $host:$port");
}
?>
