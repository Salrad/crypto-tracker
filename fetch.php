<?php
// === Конфигурация ===
$dbFile = __DIR__ . '/crypto_data.sqlite';
$apiUrl = 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=50&page=1&sparkline=false';

// === Подключение к SQLite и создание базы при необходимости ===
try {
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Создаём таблицу, если не существует
    $db->exec("
        CREATE TABLE IF NOT EXISTS cryptocurrencies (
            id TEXT PRIMARY KEY,
            ticker TEXT,
            price REAL,
            price_change_24h REAL,
            market_cap REAL,
            volume REAL,
            updated_at TEXT
        )
    ");
} catch (PDOException $e) {
    exit("Ошибка подключения к базе данных: " . $e->getMessage());
}

// === Получение данных с CoinGecko через cURL ===
function fetchCryptoData($url) {
    // Список популярных user agents
    $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/115.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 13_5) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Safari/605.1.15',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 Chrome/117.0.0.0 Safari/537.36',
        'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:115.0) Gecko/20100101 Firefox/115.0',
        'MyCryptoApp/' . rand(1, 100), // Собственный агент с рандомной версией
    ];

    $randomUA = $userAgents[array_rand($userAgents)];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $randomUA);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception('Ошибка cURL: ' . curl_error($ch));
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        throw new Exception("Ошибка HTTP: $http_code");
    }

    return json_decode($response, true);
}


// === Обновление базы данных ===
function updateDatabase($db, $cryptoList) {
    // Удаляем старые данные
    $db->exec("DELETE FROM cryptocurrencies");

    // Вставляем новые
    $stmt = $db->prepare("
        INSERT INTO cryptocurrencies 
        (id, ticker, price, price_change_24h, market_cap, volume, updated_at)
        VALUES (:id, :ticker, :price, :price_change_24h, :market_cap, :volume, :updated_at)
    ");

    foreach ($cryptoList as $coin) {
        $stmt->execute([
            ':id' => $coin['id'],
            ':ticker' => strtoupper($coin['symbol']),
            ':price' => $coin['current_price'],
            ':price_change_24h' => $coin['price_change_percentage_24h'],
            ':market_cap' => $coin['market_cap'],
            ':volume' => $coin['total_volume'],
            ':updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}


// === Главный цикл обновления ===
echo "Обновление данных каждые 5 секунд. Нажмите Ctrl+C для выхода.\n";

while (true) {
    try {
        $data = fetchCryptoData($apiUrl);
        updateDatabase($db, $data);
        echo "[" . date('H:i:s') . "] Успешно обновлено " . count($data) . " записей\n";
    } catch (Exception $e) {
        echo "[" . date('H:i:s') . "] Ошибка: " . $e->getMessage() . "\n";
    }

    sleep(12);
}
