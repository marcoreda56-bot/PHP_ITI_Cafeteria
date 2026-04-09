<?php
namespace App\Models;

use App\Core\DatabaseHandler; 
class User extends DatabaseHandler {
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        return $this->query($sql, [$email])->fetch();
    }
}