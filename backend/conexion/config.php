<?php

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-type');
    header('Access-Control-Max-Age: 1728000');
    header('Content.Length: 0');
    header("Content-type: text/plain");

    class connection{
        private $conn;

        public function __construct(){
            // $this->conn = new mysqli("localhost", "root", "Seguro.22", "control_inversion");
            $this->conn = new mysqli("localhost", "root", "", "firmasecuador");
            // $this->conn = new mysqli("localhost", "ki547603_pruebas", "pruebas2023", "ki547603_pruebas");
        }

        public function getConnection(){
            return $this->conn;
        }
    }
?>