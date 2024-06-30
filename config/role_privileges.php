<?php

// Define role privileges
$rolePrivileges = [
    'donor' => ['view_dashboard', 'add_donation'],
    'recipient' => ['view_dashboard', 'request_blood'],
    'lab_technician' => ['view_dashboard', 'add_test_result'],
    'inventory_manager' => ['view_dashboard', 'manage_inventory'],
    'hospital_rep' => ['view_dashboard', 'manage_requests'],
    'admin' => ['view_dashboard', 'manage_users', 'manage_all']
];

?>
