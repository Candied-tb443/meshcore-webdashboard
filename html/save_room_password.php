<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try
{
    $raw = file_get_contents('php://input');

    if ($raw === false)
    {
        throw new RuntimeException('Request konnte nicht gelesen werden.');
    }

    $data = json_decode($raw, true);

    if (!is_array($data))
    {
        throw new RuntimeException('Ungültiges JSON im Request.');
    }

    $roomNodeId = (int) ($data['room_node_id'] ?? 0);
    $roomName = trim((string) ($data['room_name'] ?? ''));
    $password = trim((string) ($data['password'] ?? ''));

    if ($roomNodeId <= 0)
    {
        throw new RuntimeException('Ungültige room_node_id.');
    }

    if ($roomName === '')
    {
        throw new RuntimeException('Room-Name fehlt.');
    }

    if ($password === '')
    {
        throw new RuntimeException('Passwort fehlt.');
    }

    if (mb_strlen($roomName, 'UTF-8') > 64)
    {
        throw new RuntimeException('Room-Name ist zu lang.');
    }

    if (mb_strlen($password, 'UTF-8') > 64)
    {
        throw new RuntimeException('Passwort ist zu lang.');
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

    $sql = "
        INSERT INTO room_credentials
        (
            room_node_id,
            room_name,
            password
        )
        VALUES
        (
            ?,
            ?,
            ?
        )
        ON DUPLICATE KEY UPDATE
            room_name = VALUES(room_name),
            password = VALUES(password),
            updated_at = CURRENT_TIMESTAMP
    ";

    $stmt = $db->prepare($sql);
    $stmt->bind_param('iss', $roomNodeId, $roomName, $password);
    $stmt->execute();

    echo json_encode(
        [
            'success' => true,
            'room_node_id' => $roomNodeId,
            'room_name' => $roomName,
        ],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
}
catch (Throwable $e)
{
    http_response_code(500);

    echo json_encode(
        [
            'success' => false,
            'error' => $e->getMessage(),
        ],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
}
