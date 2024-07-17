<?php
// Include database connection
include '../../config/database.php';

// Fetch available blood units from inventory
$sql_inventory = "SELECT inventory_id, unit_id, inventory_manager_id, received_date, expiration_date, status 
                  FROM inventory 
                  WHERE status = 'available'";
$result_inventory = $conn->query($sql_inventory);

if ($result_inventory->num_rows > 0) {
    $inventory = $result_inventory->fetch_all(MYSQLI_ASSOC);
} else {
    $inventory = [];
}
?>
