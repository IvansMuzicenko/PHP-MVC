<?php
class Users extends Controller
{
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function register()
    {
        $data = [
            "username" => '',
            "email" => '',
            "password" => '',
            "confrimPassword" => '',
            "usernameError" => '',
            "emailError" => '',
            "passwordError" => '',
            "confirmPasswordError" => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Array with trimmed data
            $data = [
                "username" => trim($_POST['username']),
                "email" => trim($_POST['email']),
                "password" => trim($_POST['password']),
                "confrimPassword" => trim($_POST['confirmPassword']),
                "usernameError" => '',
                "emailError" => '',
                "passwordError" => '',
                "confirmPasswordError" => '',
            ];

            $nameValidation = "/^[a-zA-Z0-9]*$/";
            $passwordValidation = "/^(.{0,7}|[^a-z]*|[^\d]*)$/i";

            // Username validation by existence and containing only letters and numbers
            if (empty($data['username'])) {
                $data['usernameError'] = 'Please enter username.';
            } elseif (!preg_match($nameValidation, $data['username'])) {
                $data['usernameError'] = 'Name can only contain letters and numbers.';
            }

            // Email validation by existence, email format, email taken
            if (empty($data['email'])) {
                $data['emailError'] = 'Please enter email.';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['emailError'] = 'Please enter the correct email.';
            } elseif ($this->userModel->findUserByEmail($data['email'])) {
                $data['emailError'] = 'Email is already taken.';
            }

            // Password validation by existence, length, containg numeric obligatory
            if (empty($data['password'])) {
                $data['passwordError'] = 'Please enter password.';
            } elseif (strlen($data['password']) < 6) {
                $data['passwordError'] = 'Password must be at least 6 characters';
            } elseif (preg_match($passwordValidation, $data['password'])) {
                $data['passwordError'] = 'Password must have at least one numeric value';
            }

            // Confirm password validation by existence and passwords match
            if (empty($data['confirmPassword'])) {
                $data['confirmPasswordError'] = 'Please enter password.';
            } elseif ($data['password'] != $data['confirmPassword']) {
                $data['confirmPasswordError'] = 'Passwords do not match.';
            }

            // Check for all validation errors emptyness
            if (empty($data['usernameError']) && empty($data['emailError']) && empty($data['passwordError']) && empty($data['confrimPasswordError'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if ($this->userModel->register($data)) {
                    header('location: ' . URLROOT . '/users/login');
                } else {
                    die("Registration failed, please try again.");
                }
            }
        }
        $this->view('users/register', $data);
    }
}
