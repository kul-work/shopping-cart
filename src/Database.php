<?php

class Database {
    private $conn;

    public function __construct() {
        require_once __DIR__ . '/../config/config.php';

        $this->conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public static function sanitize($string, $nostriptags = false) {
        // This is a simplified sanitize function. For a real application, consider using a more robust solution.
        $string = str_replace("'", "", $string);
        $string = str_replace("\"", "", $string);
        if (!$nostriptags) {
            $string = strip_tags($string);
        }
        $string = trim($string);

        // Note: mysqli_real_escape_string requires an active connection.
        // A better approach would be to use prepared statements.
        // For the scope of this refactoring, we will keep it simple.
        // A connection is needed to use mysqli_real_escape_string.
        // We will create a temporary connection for this static method.
        // This is not ideal, but it's a step towards better structure.
        $temp_conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($temp_conn->connect_error) {
            die("Connection failed for sanitization: " . $temp_conn->connect_error);
        }
        $sanitized_string = mysqli_real_escape_string($temp_conn, $string);
        $temp_conn->close();

        return $sanitized_string;
    }
}
