<?php

// Отримуємо URI запиту
$requestUri = $_SERVER['REQUEST_URI'];

// Шлях до папки 'public'
$publicDir = __DIR__ . '/public';

// Перевіряємо URI запиту
if ($requestUri == '/' || $requestUri == '/index.html') {
    // Якщо запит на корінь або index.html — віддаємо index.html
    include($publicDir . '/index.html');
} elseif ($requestUri == '/ok.php') {
    // Якщо запит на /ok.php — віддаємо ok.php
    include($publicDir . '/ok.php');
} else {
    // Якщо запит невідомий — можна віддати 404 або перенаправити на головну
    http_response_code(404);
    echo "404 - Page Not Found";
}
