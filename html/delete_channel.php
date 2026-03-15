<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function jsonResponse(array $data, int $statusCode = 200): never
{
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

try
{
    $raw = file_get_contents('php://input');

    if ($raw === false || $raw === '')
    {
        throw new RuntimeException('Keine Eingabedaten erhalten.');
    }

    $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    $channelIdx = (int) ($data['channel_idx'] ?? 0);

    if ($channelIdx < 0 || $channelIdx > 255)
    {
        throw new RuntimeException('Ungültige channel_idx.');
    }

    if ($channelIdx === 0)
    {
        throw new RuntimeException('Der Default Channel kann nicht gelöscht werden.');
    }

    $db = new mysqli(
        'localhost',
        'meshcore',
        '',
        'meshcore',
        3306,
        '/run/mysqld/mysqld.sock'
    );

    $db->set_charset('utf8mb4');

    $stmt = $db->prepare('SELECT is_default FROM channels WHERE channel_idx = ? LIMIT 1');
    $stmt->bind_param('i', $channelIdx);
    $stmt->execute();
    $stmt->bind_result($isDefault);

    $found = $stmt->fetch();
    $stmt->close();

    if (!$found)
    {
        throw new RuntimeException('Channel nicht gefunden.');
    }

    if ((int) $isDefault === 1)
    {
        throw new RuntimeException('Der Default Channel kann nicht gelöscht werden.');
    }

    $stmt = $db->prepare('DELETE FROM channels WHERE channel_idx = ? LIMIT 1');
    $stmt->bind_param('i', $channelIdx);
    $stmt->execute();

    $deletedRows = $stmt->affected_rows;
    $stmt->close();

    jsonResponse(
        [
            'success' => true,
            'channel_idx' => $channelIdx,
            'deleted' => $deletedRows > 0
        ]
    );
}
catch (Throwable $e)
{
    jsonResponse(
        [
            'success' => false,
            'error' => $e->getMessage()
        ],
        500
    );
}