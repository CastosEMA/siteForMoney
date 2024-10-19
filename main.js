const express = require('express');
const bodyParser = require('body-parser'); // Якщо не використовуєте, можна пропустити
const app = express();

// Налаштування статичних файлів
app.use(express.static('public'));

// Налаштування парсера для JSON
app.use(express.json());

// Маршрут для домашньої сторінки
app.get('/', (req, res) => {
  res.sendFile(__dirname + '/public/index.html');
});

// Маршрут для обробки подій
app.post('/api/endpoint', (req, res) => {
  const { test_event_code } = req.body;

  // Логіка обробки тестових подій
  if (test_event_code === 'TEST31786') {
    console.log('Тестова подія отримана!');
    res.json({ message: 'Тестова подія оброблена успішно!' });
  } else {
    res.status(400).json({ message: 'Невірний код події.' });
  }
});

const PORT = process.env.PORT || 80;
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
