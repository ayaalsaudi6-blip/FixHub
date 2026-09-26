<?php

require_once "Notification.php";

class Booking
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($userId, $serviceId, $date, $time, $notes)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO bookings
            (user_id, service_id, booking_date, booking_time, notes)
            VALUES
            (:user_id, :service_id, :booking_date, :booking_time, :notes)"
        );

        $success = $stmt->execute([
            ":user_id" => $userId,
            ":service_id" => $serviceId,
            ":booking_date" => $date,
            ":booking_time" => $time,
            ":notes" => $notes
        ]);

        if ($success) {

            $notification = new Notification($this->pdo);

            $notification->create(
                $userId,
                "Your booking request has been received."
            );
        }

        return $success;
    }
}