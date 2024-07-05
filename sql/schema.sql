-- Create User Table
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'donor', 'recipient', 'lab_technician', 'inventory_manager', 'hospital_rep') NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_number VARCHAR(20),
    address VARCHAR(255),
    approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Location Table (Lab/Hospital)
CREATE TABLE locations (
    location_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20),
    type ENUM('lab', 'hospital') NOT NULL
);

-- Create BloodUnit Table
CREATE TABLE blood_units (
    unit_id INT PRIMARY KEY AUTO_INCREMENT,
    donor_id INT,
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NOT NULL,
    volume INT(5) NOT NULL,
    expiration_date DATE NOT NULL,
    status ENUM('available', 'unavailable', 'donated') NOT NULL DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Create Donation Table
CREATE TABLE donations (
    donation_id INT PRIMARY KEY AUTO_INCREMENT,
    donor_id INT,
    unit_id INT,
    donation_date DATE NOT NULL,
    location_id INT,
    FOREIGN KEY (donor_id) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (unit_id) REFERENCES blood_units(unit_id) ON DELETE SET NULL,
    FOREIGN KEY (location_id) REFERENCES locations(location_id) ON DELETE SET NULL
);

-- Create BloodRequest Table
CREATE TABLE blood_requests (
    request_id INT PRIMARY KEY AUTO_INCREMENT,
    recipient_id INT,
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NOT NULL,
    volume INT(5) NOT NULL,
    request_date DATE NOT NULL,
    status ENUM('pending', 'fulfilled', 'cancelled') NOT NULL DEFAULT 'pending',
    FOREIGN KEY (recipient_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Create BloodTestResult Table
CREATE TABLE blood_test_results (
    result_id INT PRIMARY KEY AUTO_INCREMENT,
    unit_id INT,
    test_date DATE NOT NULL,
    test_result ENUM('positive', 'negative') NOT NULL,
    FOREIGN KEY (unit_id) REFERENCES blood_units(unit_id) ON DELETE CASCADE
);

-- Create Inventory Table
CREATE TABLE inventory (
    inventory_id INT PRIMARY KEY AUTO_INCREMENT,
    unit_id INT,
    inventory_manager_id INT,
    received_date DATE NOT NULL,
    expiration_date DATE NOT NULL,
    status ENUM('available', 'expired') NOT NULL DEFAULT 'available',
    FOREIGN KEY (unit_id) REFERENCES blood_units(unit_id) ON DELETE CASCADE,
    FOREIGN KEY (inventory_manager_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Create Hospital Representative Info Table
CREATE TABLE hospital_representative_info (
    hospital_rep_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    hospital_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (hospital_id) REFERENCES locations(location_id) ON DELETE CASCADE
);

-- Create Lab Technician Info Table
CREATE TABLE lab_technician_info (
    lab_technician_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    lab_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (lab_id) REFERENCES locations(location_id) ON DELETE CASCADE
);
