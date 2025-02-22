CREATE DATABASE feedback_db;

CREATE TABLE feedback (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    rating INT(1) NOT NULL,
    comments TEXT,
    file VARCHAR(255),
    submission_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
