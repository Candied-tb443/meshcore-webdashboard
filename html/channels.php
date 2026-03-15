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

function derivePublicKeyHex(string $name): string
{
    return strtoupper(hash('sha256', 'meshcore-public:' . $name));
}

function findChannelByIdx(mysqli $db, int $channelIdx): ?array
{
    $stmt = $db->prepare('
        SELECT
            channel_idx,
            name,
            join_mode,
            key_hex,
            enabled,
            is_default,
            is_observed,
            UNIX_TIMESTAMP(last_seen_at) AS last_seen_epoch
        FROM channels
        WHERE channel_idx = ?
        LIMIT 1
    ');
    $stmt->bind_param('i', $channelIdx);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row ?: null;
}

function upsertChannel(
    mysqli $db,
    int $channelIdx,
    string $name,
    int $joinMode,
    string $keyHex,
    bool $enabled,
    bool $isDefault,
    bool $isObserved
): void
{
    $existing = findChannelByIdx($db, $channelIdx);

    if ($existing !== null)
    {
        $sql = '
            UPDATE channels
            SET
                name = ?,
                join_mode = ?,
                key_hex = ?,
                enabled = ?,
                is_default = ?,
                is_observed = ?,
                last_seen_at = NOW()
            WHERE channel_idx = ?
            LIMIT 1
        ';

        $stmt = $db->prepare($sql);

        $enabledInt = $enabled ? 1 : 0;
        $isDefaultInt = $isDefault ? 1 : 0;
        $isObservedInt = $isObserved ? 1 : 0;

        $stmt->bind_param(
            'sisiiii',
            $name,
            $joinMode,
            $keyHex,
            $enabledInt,
            $isDefaultInt,
            $isObservedInt,
            $channelIdx
        );
        $stmt->execute();
        $stmt->close();
        return;
    }

    $sql = '
        INSERT INTO channels
        (
            channel_idx,
            name,
            join_mode,
            passphrase,
            key_hex,
            enabled,
            is_default,
            is_observed,
            last_seen_at
        )
        VALUES
        (
            ?, ?, ?, NULL, ?, ?, ?, ?, NOW()
        )
    ';

    $stmt = $db->prepare($sql);

    $enabledInt = $enabled ? 1 : 0;
    $isDefaultInt = $isDefault ? 1 : 0;
    $isObservedInt = $isObserved ? 1 : 0;

    $stmt->bind_param(
        'isisiii',
        $channelIdx,
        $name,
        $joinMode,
        $keyHex,
        $enabledInt,
        $isDefaultInt,
        $isObservedInt
    );
    $stmt->execute();
    $stmt->close();
}

function ensureDefaultPublicChannel(mysqli $db): void
{
    $channelIdx = 0;
    $name = 'Public';
    $joinMode = 3;
    $keyHex = derivePublicKeyHex($name);

    upsertChannel(
        $db,
        $channelIdx,
        $name,
        $joinMode,
        $keyHex,
        true,
        true,
        false
    );
}

try
{
    $db = new mysqli(
        'localhost',
        'meshcore',
        '',
        'meshcore',
        3306,
        '/run/mysqld/mysqld.sock'
    );

    $db->set_charset('utf8mb4');

    ensureDefaultPublicChannel($db);

    $sql = '
        SELECT
            channel_idx,
            name,
            enabled,
            is_default,
            is_observed,
            UNIX_TIMESTAMP(last_seen_at) AS last_seen_epoch
        FROM channels
        ORDER BY
            is_default DESC,
            enabled DESC,
            is_observed ASC,
            channel_idx ASC
    ';

    $result = $db->query($sql);

    $channels = [];

    while ($row = $result->fetch_assoc())
    {
        $channels[] =
        [
            'type' => 'channel',
            'channel_idx' => (int) $row['channel_idx'],
            'name' => (string) $row['name'],
            'enabled' => (int) $row['enabled'] === 1,
            'is_default' => (int) $row['is_default'] === 1,
            'is_observed' => (int) $row['is_observed'] === 1,
            'last_seen_epoch' => $row['last_seen_epoch'] !== null ? (int) $row['last_seen_epoch'] : null,
        ];
    }

    $db->close();

    jsonResponse(
        [
            'success' => true,
            'channels' => $channels
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