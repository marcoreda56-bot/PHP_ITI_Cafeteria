<?php
namespace App\Models;
use App\Core\DatabaseHandler; 
class Admin extends DatabaseHandler{

    public function getAllRooms() {
    $sql = "SELECT id, room_number FROM rooms";
    return $this->query($sql)->fetchAll();
    }
    public function findRoomId($room_num){
        $sql = "SELECT id from rooms where room_number = ? limit 1";
        $result = $this->query($sql,[$room_num])->fetch();
        return $result ? $result['id'] : null;
    }
    // add new user
    public function create($name, $email, $password, $profile_path ,$room_num,$role = 'user') {
        $hashpassword = password_hash($password, PASSWORD_DEFAULT);
        $room_id = $this->findRoomId($room_num);
        $sql = "INSERT INTO users (name, email, password, role, room_id ,profile_path) VALUES (?, ?, ?, ?, ?,?)";
        return $this->query($sql, [$name, $email, $hashpassword, $role, $room_id ,$profile_path]);
    }
    //edit user
    public function updateUser($id, $name, $email, $password, $profile_path ,$room_num,$role) {
        $hashpassword = password_hash($password, PASSWORD_DEFAULT);
        $room_id = $this->findRoomId($room_num);
        $sql = "UPDATE users SET name = ?, email = ?, password = ?, role = ?, room_id = ?, profile_path = ? WHERE id = ?";
        return $this->query($sql, [$name, $email, $hashpassword, $role, $room_id ,$profile_path, $id]);
    }
    public function deleteUser($id) {
    $sql = "UPDATE users SET is_deleted = 1 WHERE id = ?";
    return $this->query($sql, [$id]);
    }
    public function getTrashedUsers() {
        $sql = "SELECT users.*, rooms.room_number 
                FROM users 
                LEFT JOIN rooms ON users.room_id = rooms.id 
                WHERE users.is_deleted = 1";
        return $this->query($sql)->fetchAll();
    }

    public function restoreUser($id) {
        $sql = "UPDATE users SET is_deleted = 0 WHERE id = ?";
        return $this->query($sql, [$id]);
    }
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        return $this->query($sql, [$id])->fetch();
    }
    public function getAllProduct($limit, $offset){
        $sql = "SELECT id,product_name,price,product_img,status FROM products where is_deleted = 0 LIMIT $limit OFFSET $offset";
        return $this->query($sql)->fetchAll();
    }
    public function countProducts() {
    $sql = "SELECT COUNT(id) FROM products WHERE is_deleted = 0";
    return $this->query($sql)->fetchColumn();
}

    public function getAllUsers(){
        $sql = "SELECT * FROM users where is_deleted = 0";
        return $this->query($sql)->fetchAll();
    }
    public function countUsers(){
        $sql ="SELECT COUNT(id) FROM users WHERE is_deleted = 0";
        return $this->query($sql)->fetchColumn();
    }
    public function getAllCategory(){
        $sql = "SELECT c.id, c.cat_name, COUNT(p.id) as products_count
                FROM category c
                LEFT JOIN products p ON c.id = p.cat_id AND p.is_deleted = 0
                WHERE c.is_deleted = 0
                GROUP BY c.id, c.cat_name
                ORDER BY c.cat_name";
        return $this->query($sql)->fetchAll();
    }

    public function createProduct($name, $price, $img, $cat_id) {
    $sql = "INSERT INTO products (product_name, price, product_img, cat_id, status) VALUES (?, ?, ?, ?, 'available')";
    return $this->query($sql, [$name, $price, $img, $cat_id]);
    }

    public function deleteProduct($id) {
        $sql = "UPDATE products SET is_deleted = 1 WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    public function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        return $this->query($sql, [$id])->fetch();
    }

    public function updateProduct($id, $name, $price, $cat_id, $img = null) {
        if ($img) {
            $sql = "UPDATE products SET product_name = ?, price = ?, cat_id = ?, product_img = ? WHERE id = ?";
            return $this->query($sql, [$name, $price, $cat_id, $img, $id]);
        } else {
            $sql = "UPDATE products SET product_name = ?, price = ?, cat_id = ? WHERE id = ?";
            return $this->query($sql, [$name, $price, $cat_id, $id]);
        }
    }
    public function deletedProduct(){
        $sql= "SELECT * FROM products where is_deleted = 1";
        return $this->query($sql)->fetchAll();
    }
    public function restoreProduct($id){
        $sql= "UPDATE products set is_deleted=0 where id =?";
        return $this->query($sql,[$id]);
    }
    public function createCategory($category_name ){
        $sql = "INSERT INTO category(cat_name) values(?)";
        return $this->query($sql,[trim($category_name)]);
    }
    
    public function getCategoryById($id) {
        $sql = "SELECT id, cat_name FROM category WHERE id = ? AND is_deleted = 0";
        return $this->query($sql, [$id])->fetch();
    }
    
    public function updateCategory($id, $cat_name) {
        $sql = "UPDATE category SET cat_name = ? WHERE id = ?";
        return $this->query($sql, [trim($cat_name), $id]);
    }
    
    public function deleteCategory($id) {
        $sql = "UPDATE category SET is_deleted = 1 WHERE id = ?";
        return $this->query($sql, [$id]);
    }
    }
?>