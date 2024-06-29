-- Insert sample users
-- Seed data for users table
INSERT INTO users (username, password_hash, role, email, phone_number, address, approved, created_at)
VALUES
    ('admin_user', 'hashed_password', 'admin', 'admin@example.com', '123456789', 'Admin Address', TRUE, NOW()),
    ('donor_user', 'hashed_password', 'donor', 'donor@example.com', '987654321', 'Donor Address', TRUE, NOW()),
    ('recipient_user', 'hashed_password', 'recipient', 'recipient@example.com', '654321987', 'Recipient Address', TRUE, NOW()),
    ('lab_user_pending', 'hashed_password', 'lab_technician', 'lab_pending@example.com', '456789123', 'Lab Technician Address', FALSE, NOW()),
    ('inventory_user_pending', 'hashed_password', 'inventory_manager', 'inventory_pending@example.com', '789123456', 'Inventory Manager Address', FALSE, NOW()),
    ('hospital_user_pending', 'hashed_password', 'hospital_rep', 'hospital_pending@example.com', '321654987', 'Hospital Rep Address', FALSE, NOW()),
    ('lab_user_approved', 'hashed_password', 'lab_technician', 'lab_approved@example.com', '456789123', 'Lab Technician Address', TRUE, NOW()),
    ('inventory_user_approved', 'hashed_password', 'inventory_manager', 'inventory_approved@example.com', '789123456', 'Inventory Manager Address', TRUE, NOW()),
    ('hospital_user_approved', 'hashed_password', 'hospital_rep', 'hospital_approved@example.com', '321654987', 'Hospital Rep Address', TRUE, NOW());

-- Insert sample blood units
INSERT INTO blood_units (donor_id, blood_type, volume, expiration_date, status)
VALUES
    (2, 'A+', 500, '2024-12-31', 'available'),
    (3, 'B-', 300, '2024-12-31', 'available'),
    (4, 'O+', 400, '2024-12-31', 'available'),
    (9, 'AB+', 200, '2024-12-31', 'available'),
    (10, 'O-', 350, '2024-12-31', 'available');

-- Insert sample donations
INSERT INTO donations (donor_id, unit_id, donation_date)
VALUES
    (2, 1, '2024-06-15'),
    (3, 2, '2024-06-16'),
    (2, 3, '2024-06-17'),
    (9, 4, '2024-06-18'),
    (10, 5, '2024-06-19');

-- Insert sample blood requests
INSERT INTO blood_requests (recipient_id, blood_type, volume, request_date, status)
VALUES
    (4, 'A+', 200, '2024-06-15', 'pending'),
    (4, 'B-', 300, '2024-06-16', 'pending'),
    (11, 'O+', 400, '2024-06-17', 'pending'),
    (12, 'AB+', 500, '2024-06-18', 'pending'),
    (13, 'A-', 250, '2024-06-19', 'pending');

-- Insert sample blood test results
INSERT INTO blood_test_results (unit_id, test_date, test_result)
VALUES
    (1, '2024-06-15', 'positive'),
    (2, '2024-06-16', 'negative'),
    (3, '2024-06-17', 'positive'),
    (4, '2024-06-18', 'negative'),
    (5, '2024-06-19', 'positive');

-- Insert sample inventory
INSERT INTO inventory (unit_id, inventory_manager_id, received_date, expiration_date, status)
VALUES
    (1, 6, '2024-06-15', '2024-12-31', 'available'),
    (2, 6, '2024-06-16', '2024-12-31', 'available'),
    (3, 6, '2024-06-17', '2024-12-31', 'available'),
    (4, 11, '2024-06-18', '2024-12-31', 'available'),
    (5, 11, '2024-06-19', '2024-12-31', 'available');
