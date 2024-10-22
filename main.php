<?php
// server.php

$host = '0.0.0.0';
$port = 5000;

echo "Запускаю сервер на http://$host:$port...\n";
chdir(__DIR__); // Зміна робочої директорії

// Запуск вбудованого сервера
if (php_sapi_name() === 'cli-server') {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Обробка запитів
    if ($uri === '/ok.php') {
        include 'public/ok.php'; // Включення файлу ok.php
    } elseif ($uri === '/' || $uri === '/index.html') {
        include 'public/index.html'; // Включення файлу index.html з папки public
    } else {
        // Спробуйте обслуговувати статичні файли
        $filePath = __DIR__ . '/public' . $uri;
        if (file_exists($filePath)) {
            return false; // Дозволяє серверу обробляти файл
        } else {
            // Обробка помилки 404
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
        }
    }
} else {
    // Запуск вбудованого сервера
    exec("php -S $host:$port server.php");
}
?>
