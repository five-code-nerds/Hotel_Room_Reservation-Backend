<?php

namespace Src\Models;

use Src\Core\Database;
use PDO;

class Payment
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function createPayment($data)
    {
        try {
        $stmt = $this->db->prepare("
            INSERT INTO payments
            (reservation_id, amount, payment_method, transaction_ref , payment_status)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data["reservation_id"],
            $data["amount"],
            $data["payment_method"],
            $data["transaction_ref"],
            $data["payment_status"]
        ]);

       return $this->db->lastInsertId();

        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function findPaymentByTransactionRef($transaction_ref)
    {
        try {
        $stmt = $this->db->prepare("
            SELECT * FROM payments WHERE transaction_ref = ?
        ");

        $stmt->execute([$transaction_ref]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePaymentStatus($transaction_ref, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE payments SET payment_status = ? WHERE transaction_ref = ?
        ");

        $stmt->execute([$status, $transaction_ref]);
    }
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
}

?>
