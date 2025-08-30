CREATE DATABASE smart_city;

USE smart_city;

CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255) NOT NULL,
    complaint_title VARCHAR(255) NOT NULL,
    complaint_description TEXT NOT NULL,
    complaint_status VARCHAR(100) DEFAULT 'Pending',
    complaint_progress INT DEFAULT 0,  -- Progress in percentage (0 to 100)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
