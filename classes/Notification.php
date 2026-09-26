<?php

class Notification
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($userId, $message)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO notifications
            (user_id, message)
            VALUES
            (:user_id, :message)"
        );

        return $stmt->execute([
            ":user_id" => $userId,
            ":message" => $message
        ]);
    }

    public function getByUser($userId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM notifications
             WHERE user_id = :user_id
             ORDER BY created_at DESC"
        );

        $stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUnreadCount($userId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM notifications
             WHERE user_id = :user_id
             AND is_read = 0"
        );

        $stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function markAsRead($id)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE notifications
             SET is_read = 1
             WHERE id = :id"
        );

        return $stmt->execute([
            ":id" => $id
        ]);
    }

    public function markAllAsRead($userId)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE notifications
             SET is_read = 1
             WHERE user_id = :user_id"
        );

        return $stmt->execute([
            ":user_id" => $userId
        ]);
    }
}