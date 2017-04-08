<?php

// Define script executed in site
define('INSCRIPT', true);

// Prevent direct access without header
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
    exit;
}

require_once './includes/Database.class.php';
require_once './includes/Mail.class.php';
require_once './includes/Form.class.php';

if (!empty($_POST)) {
    $fm = new Form($_POST);
    echo json_encode($fm->submit());
}
