<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../../helpers/SessionHelper.php';
require_once __DIR__ . '/../../config/database.php';

class LoginController {
    private $db;
    private $model;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->model = new UserModel($this->db);
    }

    public function login() {
        SessionHelper::startSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->model->login($email, $password);

            if ($user) {
                SessionHelper::set('user_id', $user['id']);
                SessionHelper::set('user_name', $user['name']);
                header("Location: /dashboard");
            } else {
                $error = "Invalid credentials!";
                include __DIR__ . '/../views/login.php';
            }
        } else {
            include __DIR__ . '/../views/login.php';
        }
    }

    public function logout() {
        SessionHelper::destroy();
        header("Location: /login");
    }
}
?>
