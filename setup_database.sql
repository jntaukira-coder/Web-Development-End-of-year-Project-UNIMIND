-- UNIMIND Database Setup Script
-- Run this script in MySQL to create the database and tables

-- Create the database
CREATE DATABASE IF NOT EXISTS unimind;
USE unimind;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    regNumber VARCHAR(50) UNIQUE NOT NULL,
    year_of_study INT NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create login_attempts table for security
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    success BOOLEAN DEFAULT FALSE
);

-- Create accommodations table
CREATE TABLE IF NOT EXISTS accommodations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    location VARCHAR(255) NOT NULL,
    contact_info VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create companies table
CREATE TABLE IF NOT EXISTS companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    description TEXT,
    industry VARCHAR(100),
    contact_email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create opportunities table
CREATE TABLE IF NOT EXISTS opportunities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    requirements TEXT,
    application_deadline DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

-- Create campus_services table
CREATE TABLE IF NOT EXISTS campus_services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    contact_info VARCHAR(255),
    rating DECIMAL(3,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create events table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    event_time TIME,
    location VARCHAR(255),
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data for testing
INSERT INTO campus_services (service_name, category, description, location, contact_info) VALUES
('University Hospital', 'Healthcare', '24/7 medical services for students', 'Main Campus', '+265 123 456 789'),
('Library Services', 'Education', 'Study resources and quiet spaces', 'Library Building', 'library@university.edu'),
('Student Cafeteria', 'Food', 'Affordable meals and snacks', 'Student Center', '+265 987 654 321'),
('Sports Complex', 'Recreation', 'Gym, pool, and sports facilities', 'Sports Ground', 'sports@university.edu');

INSERT INTO events (title, description, event_date, event_time, location, category) VALUES
('Welcome Week', 'Orientation activities for new students', '2024-09-01', '09:00:00', 'Main Campus', 'Academic'),
('Career Fair', 'Meet potential employers', '2024-09-15', '10:00:00', 'Student Center', 'Career'),
('Sports Tournament', 'Inter-department sports competition', '2024-09-20', '14:00:00', 'Sports Ground', 'Sports');

-- Create indexes for better performance
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_regNumber ON users(regNumber);
CREATE INDEX idx_login_attempts_username ON login_attempts(username);
CREATE INDEX idx_accommodations_owner_id ON accommodations(owner_id);
CREATE INDEX idx_companies_user_id ON companies(user_id);
CREATE INDEX idx_opportunities_company_id ON opportunities(company_id);

-- Show the created tables
SHOW TABLES;
