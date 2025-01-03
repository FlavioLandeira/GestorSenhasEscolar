<?php
require_once "../models/User.php";
require_once __DIR__ . '/../config/database.php';

session_start();

class AuthController {
    public $userModel;

    public function __construct() {

        $this->userModel = new User();
    }
    public function register() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            if ($this->userModel->register($nome, $email, $senha)) {
                header("Location: ../view/login.php");
                exit;
            } else {
                echo "Erro ao registrar usuário.";
            }
        }
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $senha = $_POST['senha'];
    
            $user = $this->userModel->login($email, $senha);
            if ($user) {
                $_SESSION['user'] = $user;
    
                // Redireciona conforme o tipo de utilizador
                switch ($user['tipo_utilizador']) {
                    case 'administrador':
                        header("Location: ../view/admin/dashboard.php");
                        break;
                    case 'funcionario':
                        header("Location: ../view/funcionario/dashboard.php");
                        break;
                    case 'cliente':
                        header("Location: ../view/cliente/dashboard.php");
                        break;
                    default:
                        echo "Tipo de utilizador inválido.";
                        exit;
                }
                exit;
            } else {
                echo "Email ou senha inválidos.";
            }
        }
    }
    
}

$authController = new AuthController();
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $authController->register();
} elseif (isset($_POST['action']) && $_POST['action'] === 'login') {
    $authController->login();
}
?>
