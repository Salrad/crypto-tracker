# 🪙 Crypto Tracker — Топ 50 криптовалют

Простое PHP-приложение, отображающее данные о ТОП-50 криптовалютах по рыночной капитализации. Данные берутся из [CoinGecko API](https://www.coingecko.com/).

---

## ✅ Требования

- PHP 7.4+ с поддержкой SQLite и cURL
- Расширения PHP:
  - `pdo_sqlite`
  - `curl`
- Любой современный браузер (Chrome, Firefox и др.)

---

## 🚀 Установка и запуск

1. **Клонируй репозиторий:**

```bash
git clone https://github.com/yourusername/crypto-tracker.git
cd crypto-tracker
Запусти сборщик данных (в отдельном терминале):

```bash
php fetch.php
Скрипт будет обновлять информацию каждые 5 секунд и сохранять её в локальную SQLite-базу crypto_data.sqlite.

Запусти встроенный PHP-сервер:

```bash

php -S localhost:8000
Открой приложение в браузере:

http://localhost:8000/index.php