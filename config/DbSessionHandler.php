<?php
/**
 * config/DbSessionHandler.php
 *
 * Stores PHP sessions in MySQL instead of the local filesystem, since
 * Vercel's serverless functions don't share a persistent disk between
 * invocations — file-based sessions would randomly "forget" logged-in
 * users depending on which container handled the request.
 *
 * Requires the `sessions` table — see database/sessions_table.sql.
 */

class DbSessionHandler implements SessionHandlerInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function open($savePath, $sessionName): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read($id): string|false
    {
        $stmt = $this->db->prepare(
            'SELECT data FROM sessions WHERE id = ? AND expires_at > NOW()'
        );
        $stmt->execute([$id]);
        return $stmt->fetchColumn() ?: '';
    }

    public function write($id, $data): bool
    {
        $expires = date('Y-m-d H:i:s', time() + 1440);
        $stmt = $this->db->prepare(
            'INSERT INTO sessions (id, data, expires_at) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE data = VALUES(data), expires_at = VALUES(expires_at)'
        );
        return $stmt->execute([$id, $data, $expires]);
    }

    public function destroy($id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM sessions WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function gc($max_lifetime): int|false
    {
        $stmt = $this->db->prepare('DELETE FROM sessions WHERE expires_at < NOW()');
        $stmt->execute();
        return $stmt->rowCount();
    }
}