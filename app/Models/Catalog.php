<?php
namespace App\Models;

use App\Core\DatabaseHandler;

class Catalog extends DatabaseHandler {
    public function getCategories() {
        $sql = "SELECT id, cat_name FROM category WHERE is_deleted = 0 ORDER BY cat_name ASC";
        return $this->query($sql)->fetchAll();
    }

    public function countAvailableProducts() {
        $sql = "SELECT COUNT(id) FROM products WHERE is_deleted = 0 AND status = 'available'";
        return (int) $this->query($sql)->fetchColumn();
    }

    public function getAvailableProducts($limit, $offset) {
        $limit = (int) $limit;
        $offset = (int) $offset;

        $sql = "SELECT p.id, p.product_name, p.price, p.product_img, p.status, p.cat_id, c.cat_name
                FROM products p
                LEFT JOIN category c ON c.id = p.cat_id
                WHERE p.is_deleted = 0 AND p.status = 'available'
                ORDER BY p.id DESC
                LIMIT $limit OFFSET $offset";

        return $this->query($sql)->fetchAll();
    }

    public function countAvailableProductsFiltered($search = '', $categoryId = null) {
        $conditions = ["p.is_deleted = 0", "p.status = 'available'"];
        $params = [];

        $search = trim((string) $search);
        if ($search !== '') {
            $conditions[] = "p.product_name LIKE ?";
            $params[] = '%' . $search . '%';
        }

        if (!empty($categoryId)) {
            $conditions[] = "p.cat_id = ?";
            $params[] = (int) $categoryId;
        }

        $sql = "SELECT COUNT(p.id)
                FROM products p
                WHERE " . implode(' AND ', $conditions);

        return (int) $this->query($sql, $params)->fetchColumn();
    }

    public function getAvailableProductsFiltered($limit, $offset, $search = '', $categoryId = null, $sort = 'newest') {
        $limit = (int) $limit;
        $offset = (int) $offset;

        $conditions = ["p.is_deleted = 0", "p.status = 'available'"];
        $params = [];

        $search = trim((string) $search);
        if ($search !== '') {
            $conditions[] = "p.product_name LIKE ?";
            $params[] = '%' . $search . '%';
        }

        if (!empty($categoryId)) {
            $conditions[] = "p.cat_id = ?";
            $params[] = (int) $categoryId;
        }

        $orderBy = match ($sort) {
            'price_low' => 'p.price ASC, p.id DESC',
            'price_high' => 'p.price DESC, p.id DESC',
            'name_az' => 'p.product_name ASC, p.id DESC',
            default => 'p.id DESC',
        };

        $sql = "SELECT p.id, p.product_name, p.price, p.product_img, p.status, p.cat_id, c.cat_name
                FROM products p
                LEFT JOIN category c ON c.id = p.cat_id
                WHERE " . implode(' AND ', $conditions) . "
                ORDER BY {$orderBy}
                LIMIT $limit OFFSET $offset";

        return $this->query($sql, $params)->fetchAll();
    }

    public function getProductById($id) {
        $sql = "SELECT p.id, p.product_name, p.price, p.product_img, p.status, p.cat_id, c.cat_name
                FROM products p
                LEFT JOIN category c ON c.id = p.cat_id
                WHERE p.id = ? AND p.is_deleted = 0 LIMIT 1";

        return $this->query($sql, [$id])->fetch();
    }
}