<?php
$dbFile = __DIR__ . '/crypto_data.sqlite';

try {
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->query("SELECT * FROM cryptocurrencies ORDER BY market_cap DESC LIMIT 50");
    $cryptos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Топ 50 криптовалют</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            margin-top: 20px;
        }

        th, td {
            padding: 10px 12px;
            text-align: center;
            border-bottom: 1px solid #eaeaea;
        }

        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #111827;
        }

        tr:hover {
            background-color: #f1f5f9;
        }

        .negative {
            color: #dc2626;
        }

        .positive {
            color: #16a34a;
        }

        .small {
            font-size: 0.9em;
            color: #6b7280;
        }
    </style>
</head>
<body>

<h1>ТОП 50 Криптовалют по капитализации</h1>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Тикер</th>
            <th>Цена (USD)</th>
            <th>Изм. 24ч (%)</th>
            <th>Капитализация</th>
            <th>Объём торгов</th>
            <th class="small">Обновлено</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cryptos as $index => $coin): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><strong><?= htmlspecialchars($coin['ticker']) ?></strong></td>
                <td>$<?= number_format($coin['price'], 2) ?></td>
                <td class="<?= $coin['price_change_24h'] < 0 ? 'negative' : 'positive' ?>">
                    <?= number_format($coin['price_change_24h'], 2) ?>%
                </td>
                <td>$<?= number_format($coin['market_cap'], 0, '.', ' ') ?></td>
                <td>$<?= number_format($coin['volume'], 0, '.', ' ') ?></td>
                <td class="small"><?= $coin['updated_at'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
