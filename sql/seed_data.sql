-- Seed Data for Users
INSERT INTO users (username, password_hash, role, email, phone_number, address, approved) VALUES
('admin', '$2y$10$qOoEXwVGQu2eaVUoF9xOTOGehS13HlSZ0mN4aMZT63NTOyM4xQ6jO', 'admin', 'admin1@example.com', '1234567890', '123 Admin St, Admin City', TRUE),
('donor', '$2y$10$/ZN44TAqLL.5JLFfH3G52uizAtQjEo80dZiU95vQTL6JqFZ.V6PDy', 'donor', 'donor1@example.com', '2345678901', '456 Donor St, Donor City', TRUE),
('recipient', '$2y$10$N8Cw5Jcub6.R2CAVTLxBNu.77OY7Kg0QksukApIObs93CiSXhGoRm', 'recipient', 'recipient1@example.com', '3456789012', '789 Recipient St, Recipient City', TRUE),
('lab', '$2y$10$ZAyCiKEckHW42Mfu1WYNReCo1O4SzaLYzTkG2UBQWJr83m9jmQa0m', 'lab_technician', 'labtech1@example.com', '4567890123', '321 Lab St, Lab City', TRUE),
('inventory', '$2y$10$fHPt1aS.SQtxLChFHCkh.eQ1KK5jx7UNRORcyLaFbVjQFMbqqZPfi', 'inventory_manager', 'invmanager1@example.com', '5678901234', '654 Inventory St, Inventory City', TRUE),
('hospital', '$2y$10$6nM9cVG0d8HUMedHCkn9o.zg73Y./g19MSakMsxUxRtN7tKJINfBW', 'hospital_rep', 'hosprep1@example.com', '6789012345', '987 Hospital St, Hospital City', TRUE);

-- Seed Data for Locations
INSERT INTO locations (name, address, phone_number, type) VALUES
('Central Lab', '123 Lab St, Lab City', '1111111111', 'lab'),
('North Hospital', '456 Hospital St, Hospital City', '2222222222', 'hospital'),
('South Hospital', '789 Hospital St, Hospital City', '3333333333', 'hospital');

-- Seed Data for Blood Units
INSERT INTO blood_units (donor_id, blood_type, volume, expiration_date, status) VALUES
(2, 'A+', 500, '2024-12-31', 'available'),
(2, 'O-', 450, '2024-11-30', 'available');

-- Seed Data for Donations
INSERT INTO donations (donor_id, unit_id, donation_date, location_id) VALUES
(2, 1, '2024-06-01', 1),
(2, 2, '2024-06-15', 1);

-- Seed Data for Blood Requests
INSERT INTO blood_requests (recipient_id, blood_type, volume, request_date, status) VALUES
(3, 'A+', 500, '2024-06-20', 'pending'),
(3, 'O-', 450, '2024-07-01', 'pending');

-- Seed Data for Blood Test Results
INSERT INTO blood_test_results (unit_id, test_date, test_result) VALUES
(1, '2024-06-02', 'negative'),
(2, '2024-06-16', 'negative');

-- Seed Data for Inventory
INSERT INTO inventory (unit_id, inventory_manager_id, received_date, expiration_date, status) VALUES
(1, 5, '2024-06-03', '2024-12-31', 'available'),
(2, 5, '2024-06-17', '2024-11-30', 'available');

-- Seed Data for Hospital Representative Info
INSERT INTO hospital_representative_info (user_id, hospital_id) VALUES
(6, 2);

-- Seed Data for Lab Technician Info
INSERT INTO lab_technician_info (user_id, lab_id) VALUES
(4, 1);
