<?php
namespace App\Models;
use App\Core\DatabaseHandler;
use App\Models\Order;
use PDO;

class Check extends DatabaseHandler {
    public function getUsersPayments($start_date = null, $end_date = null, $limit = 10, $offset = 0) {
        $sql = "SELECT u.id, u.name, SUM(o.total_price) AS total_paid FROM users u
                JOIN orders o ON u.id = o.user_id
                WHERE o.status = 'completed'";
        $params = [];

        if ($start_date) {
            $sql .= " AND o.created_at >= ?";
            $params[] = $start_date;
        }

        if ($end_date) {
            $sql .= " AND o.created_at <= ?";
            $params[] = $end_date;
        }

        $sql .= " GROUP BY u.id, u.name LIMIT $limit OFFSET $offset"; //
        #pass limit and offset as integers to prevent SQL injection

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getOneUserPayments($userId, $start_date = null, $end_date = null) {
         $sql = "SELECT u.id, u.name, SUM(o.total_price) AS total_paid FROM users u
                JOIN orders o ON u.id = o.user_id
                WHERE o.user_id = ? AND o.status = 'completed'";
        $params = [$userId];
        if ($start_date) {
            $sql .= " AND o.created_at >= ?";
            $params[] = $start_date;
        }
        if ($end_date) {
            $sql .= " AND o.created_at <= ?";
            $params[] = $end_date;
        }
        $sql .= " GROUP BY u.id, u.name";
        return $this->query($sql, $params)->fetchAll();
    }

    public function getUserOrders($userId) {
        $sql = "SELECT o.id, o.total_price, o.status, o.created_at FROM orders o
                WHERE o.user_id = ? AND o.status = 'completed'
                ORDER BY o.created_at DESC;";
        return $this->query($sql, [$userId])->fetchAll();
    }

    public function getOrderDetails($orderId) {
        $items = (new Order())->getOrderItems($orderId);
        return $items;
    }

}