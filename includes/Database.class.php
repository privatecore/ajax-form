<?php

if (!defined('INSCRIPT')) {
    exit;
}

class Database {

    private $_host = "<host>";
    private $_username = "<username>";
    private $_password = "<password>";
    private $_database = "<database>";
    private $_mysqli;
    private static $_instance;

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private function __construct() {
        $this->_mysqli = @new mysqli($this->_host, $this->_username, $this->_password, $this->_database);

        // Error handling
        if ($this->_mysqli->connect_error) {
            trigger_error("Failed to conencto to MySQL: " . $this->_mysqli->connect_error(), E_USER_ERROR);
        }
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone() { }

    public function getConnection() {
        return $this->_mysqli;
    }
}
