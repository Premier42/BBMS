blood-bank-management-system/
│
├── index.html                    # Main homepage
│
├── login.php                     # Login page
├── register.php                  # Registration page
│
├── dashboard.php                 # Main dashboard page (role-based content)
│
├── profile.php                   # Profile management page
│
├── donation.php                  # Donation management page
│
├── blood_request.php             # Blood request management page
│
├── inventory.php                 # Inventory management page
│
├── blood_tests.php               # Blood testing management page
│
├── reports.php                   # Reporting and analytics page
│
├── css/
│   └── styles.css                # CSS styles
│
├── js/
│   └── scripts.js                # JavaScript scripts
│
├── img/                          # Images and icons
│   └── logo.png                  # Example: Blood bank logo
│
├── includes/
│   ├── db_config.php             # Database configuration
│   ├── db_connection.php         # Database connection script
│   ├── session.php               # Session management script
│   └── functions.php             # Common PHP functions
│
├── db_scripts/
│   ├── create_tables.sql         # SQL script to create database tables
│   └── sample_data.sql           # Optional: SQL script to populate sample data
│
├── admin/
│   ├── manage_users.php          # Admin dashboard - manage users
│   ├── manage_inventory.php      # Admin dashboard - manage inventory
│   └── manage_reports.php        # Admin dashboard - generate reports
│
├── donor/
│   ├── schedule_donation.php     # Donor dashboard - schedule donations
│   └── donation_history.php      # Donor dashboard - view donation history
│
├── recipient/
│   ├── submit_request.php        # Recipient dashboard - submit blood requests
│   └── track_requests.php        # Recipient dashboard - track request status
│
├── lab_tech/
│   ├── record_tests.php          # Lab technician dashboard - record blood tests
│   └── manage_tests.php          # Lab technician dashboard - manage test results
│
└── inventory_manager/
    ├── manage_inventory.php      # Inventory manager dashboard - manage inventory
    └── expiration_alerts.php     # Inventory manager dashboard - manage expiration alerts
