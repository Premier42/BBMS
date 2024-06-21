-- Insert sample users
INSERT INTO users (username, password_hash, role, email, phone_number, address)
VALUES
    ('admin', 'hashed_admin_password', 'admin', 'admin@example.com', '1234567890', 'Admin Address'),
    ('donor1', 'hashed_donor1_password', 'donor', 'donor1@example.com', '1234567891', 'Donor 1 Address'),
    ('donor2', 'hashed_donor2_password', 'donor', 'donor2@example.com', '1234567892', 'Donor 2 Address'),
    ('recipient1', 'hashed_recipient1_password', 'recipient', 'recipient1@example.com', '1234567893', 'Recipient 1 Address'),
    ('labtech1', 'hashed_labtech1_password', 'lab_technician', 'labtech1@example.com', '1234567894', 'Lab Technician 1 Address'),
    ('inventory1', 'hashed_inventory1_password', 'inventory_manager', 'inventory1@example.com', '1234567895', 'Inventory Manager 1 Address'),
    ('hospital1', 'hashed_hospital1_password', 'hospital_rep', 'hospital1@example.com', '1234567896', 'Hospital 1 Address'),
    ('donor3', 'hashed_donor3_password', 'donor', 'donor3@example.com', '1234567897', 'Donor 3 Address'),
    ('donor4', 'hashed_donor4_password', 'donor', 'donor4@example.com', '1234567898', 'Donor 4 Address'),
    ('recipient2', 'hashed_recipient2_password', 'recipient', 'recipient2@example.com', '1234567899', 'Recipient 2 Address'),
    ('labtech2', 'hashed_labtech2_password', 'lab_technician', 'labtech2@example.com', '1234567800', 'Lab Technician 2 Address'),
    ('inventory2', 'hashed_inventory2_password', 'inventory_manager', 'inventory2@example.com', '1234567801', 'Inventory Manager 2 Address'),
    ('hospital2', 'hashed_hospital2_password', 'hospital_rep', 'hospital2@example.com', '1234567802', 'Hospital 2 Address');

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
