<?php
namespace App\Models;

use App\Core\DatabaseHandler; 
class Auth extends DatabaseHandler {
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        return $this->query($sql, [$email])->fetch();
    }

    public function getAllRooms()
    {
    $sql = "SELECT id, room_number FROM rooms";
    return $this->query($sql)->fetchAll();
    }

    public function findRoomId($room_num){
        $sql = "SELECT id from rooms where room_number = ? limit 1";
        $result = $this->query($sql,[$room_num])->fetch();
        return $result ? $result['id'] : null;
    }
    public function create($name, $email, $password, $profile_path ,$room_num,$role = 'user') {
        $hashpassword = password_hash($password, PASSWORD_DEFAULT);
        $room_id = $this->findRoomId($room_num);
        $sql = "INSERT INTO users (name, email, password, role, room_id ,profile_path) VALUES (?, ?, ?, ?, ?,?)";
        return $this->query($sql, [$name, $email, $hashpassword, $role, $room_id ,$profile_path]);
    }
    public function forget($newpass , $user_email){
           $hashpassword = password_hash($newpass,PASSWORD_DEFAULT);
           $sql = "UPDATE users set password = ? where email = ?";
           return $this->query($sql,[$hashpassword,$user_email]);
    }
}