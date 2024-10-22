<?php

// Перевіряємо, чи виконується скрипт через веб-сервер
if (php_sapi_name() != 'cli') {
    // Перевіряємо наявність змінної REQUEST_URI
    if (isset($_SERVER['REQUEST_URI'])) {
        $requestUri = $_SERVER['REQUEST_URI'];
    } else {
        $requestUri = '/';
    }

    // Шлях до папки 'public'
    $publicDir = __DIR__ . '/public';

    // Перевіряємо URI запиту
    if ($requestUri == '/' || $requestUri == '/index.html') {
        include($publicDir . '/index.html');
    } elseif ($requestUri == '/ok.php') {
        include($publicDir . '/ok.php');
    } else {
        http_response_code(404);
        echo "404 - Page Not Found";
    }
} else {
    echo "Цей скрипт повинен виконуватися через веб-сервер.";
}

