<?php
require_once '../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register($name, $email, $password, $type, $localId) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        return $this->userModel->createUser($name, $email, $hashedPassword, $type, $localId);
    }

    public function login($email, $password) {
        $user = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['senha'])) {
            session_start();
            $_SESSION['user_id'] = $user['id_utilizador'];
            $_SESSION['user_type'] = $user['tipo_utilizador'];
            return true;
        }
        return false;
    }
}