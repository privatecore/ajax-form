<?php

if (!defined('INSCRIPT')) {
    exit;
}

class Form {

    protected $db;
    private $_mysqli;
    private $_mail;
    private $_data;

    public function __construct($input) {
        $this->_data = $input;

        $this->db = Database::getInstance();
        $this->_mysqli = $this->db->getConnection();

        $this->_mail = new Mail($this->_data);
    }

    private function validate() {
        foreach ($this->_data as $key => $value) {
            switch ($key) {
                case 'user-email': return $this->validateEmail($value);
                case 'user-name':  return $this->validateString($value, 3, 255);
            }
        }

        return true;
    }

    private function validateEmail($email) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    private function validateString($str, $min = 0, $max = 0, $optional = false) {
        if (empty($str) && $optional) {
            return false;
        }

        $str = filter_var($str, FILTER_SANITIZE_STRING);
        if ($min && strlen(utf8_decode($str)) < $min) {
            return false;
        }
        else if ($max && strlen(utf8_decode($str)) > $max) {
            return false;
        }

        return true;
    }

    private function addRecord() {
        $email = $this->_mysqli->real_escape_string($this->_data['user-email']);
        $query = "INSERT INTO `form` (`email`) value ('$email')";
        $this->_mysqli->query($query);
    }

    public function submit() {
        if ($this->validate()) {
            $this->_mail->send();
            $this->addRecord();

            return array('success' => true, 'message' => 'Message was sent!');
        }
        else {
            return array('success' => false, 'message' => 'Message was NOT sent!');
        }
    }
}