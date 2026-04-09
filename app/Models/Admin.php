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
    public function getAllProduct(){
        $sql = "SELECT * FROM products where is_deleted = 0";
        return $this->query($sql);
    }
}
?>