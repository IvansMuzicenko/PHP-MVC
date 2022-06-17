<?php
class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Insert user data to database
    // Returns boolean
    public function register($data)
    {
        $this->db->query('INSERT INTO users (username, email, password) VALUES(:username, :email, :password)');

        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Find an existence of an user by email
    // Returns boolean
    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        if ($this->db->rowCount > 0) {
            return true;
        } else {
            return false;
        }
    }
}
