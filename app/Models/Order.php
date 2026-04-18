<?php
namespace App\Models;

use App\Core\DatabaseHandler;

class Order extends DatabaseHandler {
    public function placeOrder($userId, $roomId, array $cartItems, $notes, $paymentMethod) {
        if (empty($cartItems)) {
            throw new \RuntimeException('Cart is empty.');
        }

        $paymentMethod = strtolower(trim((string) $paymentMethod));
        if (!in_array($paymentMethod, ['cash', 'card'], true)) {
            throw new \InvalidArgumentException('Invalid payment method.');
        }

        $this->connection->beginTransaction();

        try {
            $orderTotal = 0;
            $resolvedItems = [];

            foreach ($cartItems as $cartItem) {
                $productId = (int) ($cartItem['product_id'] ?? 0);
                $quantity = max(1, (int) ($cartItem['quantity'] ?? 0));

                $product = $this->getProductById($productId);
                if (!$product || strtolower($product['status'] ?? '') !== 'available') {
                    throw new \RuntimeException('One of the selected products is no longer available.');
                }

                $unitPrice = (float) $product['price'];
                $lineTotal = $unitPrice * $quantity;
                $orderTotal += $lineTotal;

                $resolvedItems[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $unitPrice,
                ];
            }

            $sql = "INSERT INTO orders (user_id, room_id, status, total_price, notes)
                    VALUES (?, ?, 'processing', ?, ?)";
            $this->query($sql, [$userId, $roomId, $orderTotal, $notes]);
            $orderId = (int) $this->connection->lastInsertId();

            foreach ($resolvedItems as $item) {
                $sql = "INSERT INTO order_items (order_id, product_id, quantity, price)
                        VALUES (?, ?, ?, ?)";
                $this->query($sql, [
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price'],
                ]);
            }

            $sql = "INSERT INTO checks (order_id, payment_method) VALUES (?, ?)";
            $this->query($sql, [$orderId, $paymentMethod]);

            $this->connection->commit();

            return $orderId;
        } catch (\Throwable $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }

            throw $e;
        }
    }

    public function getOrdersByUser($userId) {
        $sql = "SELECT o.id, o.status, o.total_price, o.notes, o.created_at,
                       o.room_id, r.room_number, r.ext,
                       (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) AS items_count
                FROM orders o
                LEFT JOIN rooms r ON r.id = o.room_id
                WHERE o.user_id = ?
                ORDER BY o.created_at DESC, o.id DESC";

        return $this->query($sql, [$userId])->fetchAll();
    }

    public function getOrderByUser($userId, $orderId) {
        $sql = "SELECT o.id, o.status, o.total_price, o.notes, o.created_at,
                       o.room_id, r.room_number, r.ext,
                       c.payment_method, c.paid_at
                FROM orders o
                LEFT JOIN rooms r ON r.id = o.room_id
                LEFT JOIN checks c ON c.order_id = o.id
                WHERE o.user_id = ? AND o.id = ?
                LIMIT 1";

        return $this->query($sql, [$userId, $orderId])->fetch();
    }

    public function cancelOrderForUser($userId, $orderId) {
        $sql = "UPDATE orders
                SET status = 'cancelled'
                WHERE id = ?
                  AND user_id = ?
                  AND status = 'processing'
                  AND created_at >= (NOW() - INTERVAL 15 MINUTE)";

        return $this->query($sql, [$orderId, $userId]);
    }

    public function getOrderItems($orderId) {
        $sql = "SELECT oi.quantity, oi.price, p.product_name, p.product_img
                FROM order_items oi
                INNER JOIN products p ON p.id = oi.product_id
                WHERE oi.order_id = ?
                ORDER BY oi.id ASC";

        return $this->query($sql, [$orderId])->fetchAll();
    }

    public function countOrdersByUser($userId) {
        $sql = "SELECT COUNT(id) FROM orders WHERE user_id = ?";
        return (int) $this->query($sql, [$userId])->fetchColumn();
    }

    public function getProductById($id) {
        $sql = "SELECT id, product_name, price, product_img, status
                FROM products
                WHERE id = ? AND is_deleted = 0
                LIMIT 1";

        return $this->query($sql, [$id])->fetch();
    }

    public function getRooms() {
        $sql = "SELECT id, room_number, ext FROM rooms ORDER BY room_number ASC";
        return $this->query($sql)->fetchAll();
    }

    public function getAllOrders() {
        $sql = "SELECT o.id, o.user_id, u.name, u.email, o.room_id, r.room_number, o.status, 
                       o.total_price, o.notes, o.created_at,
                       (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) AS items_count,
                       c.payment_method, c.paid_at
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN rooms r ON o.room_id = r.id
                LEFT JOIN checks c ON c.order_id = o.id
                ORDER BY o.created_at DESC, o.id DESC";

        return $this->query($sql)->fetchAll();
    }
}