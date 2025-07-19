# 🪙 Crypto Tracker — Топ 50 криптовалют

Простое PHP-приложение, отображающее данные о ТОП-50 криптовалютах по рыночной капитализации. Данные берутся из CoinGecko API и сохраняются в SQLite.

## ✅ Требования

- PHP 7.4+ с поддержкой SQLite и cURL
- Расширения PHP: `pdo_sqlite`, `curl`
- Любой современный браузер (Chrome, Firefox и др.)

## 🚀 Установка и запуск

1. Клонируйте репозиторий:

git clone https://github.com/Salrad/crypto-tracker.git
cd crypto-tracker


Запустите сбор данных (в отдельном терминале):

php fetch.php


Скрипт обновляет информацию каждые 12 секунд и сохраняет в базу crypto_data.sqlite.

Запустите встроенный PHP-сервер:

php -S localhost:8000


Перейдите в браузере:

http://localhost:8000/index.php


