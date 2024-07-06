<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the appropriate role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'inventory_manager') {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if the unit_id is provided
if (isset($_GET['id'])) {
    $unit_id = $_GET['id'];

    // Verify that the blood unit exists and belongs to the inventory manager
    $sql_verify = "
        SELECT bu.unit_id
        FROM blood_units bu
        JOIN inventory i ON bu.unit_id = i.unit_id
        WHERE bu.unit_id = ? AND i.inventory_manager_id = ?";
    $stmt_verify = $conn->prepare($sql_verify);
    $stmt_verify->bind_param("ii", $unit_id, $user_id);
    $stmt_verify->execute();
    $stmt_verify->store_result();

    if ($stmt_verify->num_rows > 0) {
        // Delete the blood unit from the inventory table
        $sql_delete_inventory = "DELETE FROM inventory WHERE unit_id = ? AND inventory_manager_id = ?";
        $stmt_delete_inventory = $conn->prepare($sql_delete_inventory);
        $stmt_delete_inventory->bind_param("ii", $unit_id, $user_id);
        $stmt_delete_inventory->execute();
        $stmt_delete_inventory->close();

        // Delete the blood unit from the blood_units table
        $sql_delete_blood_unit = "DELETE FROM blood_units WHERE unit_id = ?";
        $stmt_delete_blood_unit = $conn->prepare($sql_delete_blood_unit);
        $stmt_delete_blood_unit->bind_param("i", $unit_id);
        $stmt_delete_blood_unit->execute();
        $stmt_delete_blood_unit->close();

        header("Location: dashboard.php?message=Blood unit deleted successfully");
        exit;
    } else {
        echo "Blood unit not found or you don't have permission to delete this unit.";
        exit;
    }

    $stmt_verify->close();
} else {
    echo "Invalid request.";
    exit;
}

$conn->close();
?>
