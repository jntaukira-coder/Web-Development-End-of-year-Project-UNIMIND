-- UNIMIND Database Schema
-- Run this script to set up the complete database structure

-- Users table (enhanced)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    registration_number VARCHAR(20) UNIQUE NOT NULL,
    user_type ENUM('student', 'mentor', 'admin', 'landlord') DEFAULT 'student',
    profile_picture VARCHAR(255),
    phone VARCHAR(20),
    bio TEXT,
    year_of_study INT DEFAULT 1,
    program VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE
);

-- Accommodations table (enhanced with owner_id)
CREATE TABLE IF NOT EXISTS accommodations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    address VARCHAR(255) NOT NULL,
    price_per_month DECIMAL(10,2) NOT NULL,
    contact_person VARCHAR(100),
    contact_phone VARCHAR(20),
    contact_email VARCHAR(100),
    capacity INT NOT NULL,
    available_spaces INT DEFAULT 0,
    amenities TEXT,
    photos JSON,
    verified BOOLEAN DEFAULT FALSE,
    rating DECIMAL(3,2) DEFAULT 0.00,
    reviews_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Companies table (NEW)
CREATE TABLE IF NOT EXISTS companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    industry VARCHAR(100),
    location VARCHAR(255),
    website VARCHAR(255),
    contact_email VARCHAR(100),
    contact_phone VARCHAR(20),
    logo VARCHAR(255),
    verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Opportunities table (NEW)
CREATE TABLE IF NOT EXISTS opportunities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    opportunity_type ENUM('internship', 'part_time', 'full_time', 'volunteer', 'scholarship', 'training', 'competition', 'other') NOT NULL,
    department VARCHAR(100),
    requirements TEXT,
    application_deadline DATE NOT NULL,
    salary_range VARCHAR(100),
    location VARCHAR(255),
    is_remote BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'closed', 'draft') DEFAULT 'active',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Applications table (NEW)
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    opportunity_id INT NOT NULL,
    student_id INT NOT NULL,
    cover_letter TEXT,
    resume_file VARCHAR(255),
    status ENUM('pending', 'under_review', 'accepted', 'rejected') DEFAULT 'pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (opportunity_id) REFERENCES opportunities(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Announcements table (NEW)
CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    accommodation_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    priority ENUM('normal', 'important', 'urgent') DEFAULT 'normal',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (accommodation_id) REFERENCES accommodations(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);
-- Accommodation bookings
CREATE TABLE IF NOT EXISTS accommodation_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    accommodation_id INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    total_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (accommodation_id) REFERENCES accommodations(id) ON DELETE CASCADE
);

-- Services table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category ENUM('healthcare', 'shopping', 'banking', 'transport', 'food', 'other') NOT NULL,
    description TEXT,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    website VARCHAR(255),
    operating_hours VARCHAR(100),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    photos JSON,
    verified BOOLEAN DEFAULT FALSE,
    rating DECIMAL(3,2) DEFAULT 0.00,
    reviews_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Mentorship program
CREATE TABLE IF NOT EXISTS mentorship_programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mentor_id INT NOT NULL,
    mentee_id INT,
    program_name VARCHAR(100) NOT NULL,
    description TEXT,
    status ENUM('active', 'completed', 'paused') DEFAULT 'active',
    start_date DATE,
    end_date DATE,
    meeting_frequency ENUM('weekly', 'biweekly', 'monthly') DEFAULT 'weekly',
    goals TEXT,
    FOREIGN KEY (mentor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (mentee_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User reviews
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    target_id INT NOT NULL,
    target_type ENUM('accommodation', 'service', 'user') NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Checklists
CREATE TABLE IF NOT EXISTS checklists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    category ENUM('pre_arrival', 'post_arrival', 'academic', 'personal') NOT NULL,
    is_public BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Checklist items
CREATE TABLE IF NOT EXISTS checklist_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    checklist_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    is_completed BOOLEAN DEFAULT FALSE,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (checklist_id) REFERENCES checklists(id) ON DELETE CASCADE
);

-- Notifications
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'warning', 'success', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Campus Buildings table (NEW)
CREATE TABLE IF NOT EXISTS campus_buildings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    block VARCHAR(10) NOT NULL,
    floors INT NOT NULL,
    description TEXT,
    departments TEXT,
    facilities JSON,
    has_wifi BOOLEAN DEFAULT TRUE,
    has_library BOOLEAN DEFAULT FALSE,
    has_cafe BOOLEAN DEFAULT FALSE,
    has_lab BOOLEAN DEFAULT FALSE,
    has_lecture_halls BOOLEAN DEFAULT FALSE,
    access_instructions TEXT,
    emergency_exits TEXT,
    operating_hours VARCHAR(100),
    coordinates_lat DECIMAL(10,8),
    coordinates_lng DECIMAL(11,8),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- Search logs (for analytics)
CREATE TABLE IF NOT EXISTS search_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    search_query VARCHAR(255) NOT NULL,
    search_type ENUM('accommodation', 'service', 'general') NOT NULL,
    results_count INT DEFAULT 0,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert sample data
INSERT INTO users (username, email, password_hash, full_name, registration_number, user_type, bio, program) VALUES
('admin', 'admin@unimind.mw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'ADMIN/25/SS/001', 'admin', 'System administrator for UNIMIND platform', 'Computer Science'),
('jmentor', 'john.mentor@unimind.mw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Mentor', 'BECE/24/SS/015', 'mentor', '3rd year student passionate about helping first-years', 'Computer Engineering'),
('mlandlord', 'mary.landlord@unimind.mw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mary Landlord', 'LAND/23/SS/001', 'landlord', 'Experienced accommodation provider with 5+ years', 'Property Management'),
('ccompany', 'techcorp@unimind.mw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'TechCorp Malawi', 'COMP/23/SS/001', 'company', 'Leading technology company in Malawi', 'Information Technology');

INSERT INTO accommodations (owner_id, name, description, address, price_per_month, contact_person, contact_phone, capacity, available_spaces, amenities, verified) VALUES
(3, 'MUBAS Hostel Block A', 'Modern hostel with 24/7 security, WiFi, and study areas', 'MUBAS Campus, Blantyre', 45000.00, 'Mary Banda', '+265991234567', 50, 12, 'WiFi, Security, Study Room, Kitchen, Laundry', TRUE),
(3, 'Sunshine Student Residence', 'Off-campus residence with modern amenities', 'Chichiri, Blantyre', 55000.00, 'Mary Phiri', '+265998765432', 30, 8, 'WiFi, Gym, Swimming Pool, Laundry, Parking', TRUE);

INSERT INTO companies (user_id, name, description, industry, location, website, contact_email, contact_phone, verified) VALUES
(4, 'TechCorp Malawi', 'Leading technology company providing innovative solutions for businesses and students', 'Information Technology', 'Blantyre, Malawi', 'https://techcorp.mw', 'careers@techcorp.mw', '+265991111111', TRUE),
(4, 'FinanceHub Ltd', 'Financial services and banking solutions for Malawian students', 'Banking & Finance', 'Lilongwe, Malawi', 'https://financehub.mw', 'jobs@financehub.mw', '+265992222222', TRUE);

INSERT INTO opportunities (company_id, title, description, opportunity_type, department, requirements, application_deadline, salary_range, location, is_remote, created_by) VALUES
(1, 'Software Engineering Internship', 'Join our team to work on cutting-edge web and mobile applications. Gain hands-on experience in modern development frameworks.', 'internship', 'Software Development', 'Currently studying Computer Science or related field. Basic knowledge of programming languages required.', '2025-12-31', 'MWK 80,000 - 120,000', 'Blantyre', FALSE, 4),
(1, 'Data Science Research Assistant', 'Assist our data science team in analyzing student data and building predictive models for educational outcomes.', 'part_time', 'Data Analytics', 'Strong analytical skills, experience with Python/R, knowledge of statistics.', '2025-11-30', 'MWK 100,000 - 150,000', 'Lilongwe', TRUE, 4),
(2, 'Financial Analyst Trainee', 'Learn financial analysis and investment strategies while working with real client portfolios.', 'full_time', 'Finance', 'Business or Finance degree required. Strong Excel skills and attention to detail.', '2025-12-15', 'MWK 150,000 - 200,000', 'Blantyre', FALSE, 4);

INSERT INTO campus_buildings (name, category, block, floors, description, departments, has_wifi, has_library, has_cafe, has_lab, has_lecture_halls, access_instructions, emergency_exits, operating_hours) VALUES
('Main Administration Block', 'Administrative', 'A', 4, 'Central administrative building housing the Vice-Chancellor office, registrar, bursar, and main administrative departments.', 'Administration, Registry, Bursar, HR', TRUE, FALSE, TRUE, FALSE, FALSE, 'Enter through main entrance, proceed to reception for visitor registration.', 'Ground Floor: North and South exits. Upper floors: Emergency stairwells on both ends.', '8:00 AM - 5:00 PM Weekdays'),

('Faculty of Business Block', 'Academic', 'B', 5, 'Main academic building for business studies, featuring modern lecture halls, seminar rooms, and faculty offices.', 'Business Administration, Accounting, Finance, Marketing, Management', TRUE, FALSE, TRUE, FALSE, TRUE, 'Student ID required for entry. Use side entrance for lecture halls.', 'Emergency exits on each floor. Assembly point: Main campus field.', '7:00 AM - 9:00 PM Mon-Fri, 8:00 AM - 6:00 PM Sat'),

('Faculty of Applied Sciences Block', 'Academic', 'C', 6, 'State-of-the-art science building with laboratories, workshops, and specialized facilities for applied sciences.', 'Computer Science, Engineering, Information Technology, Applied Mathematics', TRUE, FALSE, TRUE, TRUE, TRUE, 'Lab coats required for laboratory access. Check lab schedule before entry.', 'Multiple emergency exits. Lab-specific emergency procedures posted.', '7:00 AM - 10:00 PM Mon-Fri, 9:00 AM - 6:00 PM Sat'),

('University Library', 'Library', 'D', 3, 'Main library building with extensive collection of books, journals, digital resources, and study spaces.', 'Library Services, Research Support, Digital Resources', TRUE, TRUE, TRUE, FALSE, FALSE, 'Student ID card required for entry. Silent study areas on upper floors.', 'Emergency exits on each floor. Fire assembly point: Library garden.', '8:00 AM - 10:00 PM Mon-Fri, 9:00 AM - 8:00 PM Sat, 1:00 PM - 6:00 PM Sun'),

('Student Center Complex', 'Student Services', 'E', 4, 'Comprehensive student services building including cafeteria, student union, counseling services, and recreational facilities.', 'Student Affairs, Counseling, Cafeteria, Student Union, Recreation', TRUE, FALSE, TRUE, FALSE, FALSE, 'Open to all registered students. Some services require appointments.', 'Multiple emergency exits. First aid station at reception.', '7:00 AM - 11:00 PM Daily'),

('Engineering Workshop Block', 'Academic', 'F', 2, 'Specialized workshop facility for engineering students with equipment for practical training and projects.', 'Mechanical Engineering, Electrical Engineering, Workshop Training', TRUE, FALSE, TRUE, TRUE, FALSE, 'Safety equipment mandatory. Supervised access only. Book workshop slots in advance.', 'Large emergency doors for equipment evacuation. Assembly point: Workshop yard.', '8:00 AM - 6:00 PM Mon-Fri'),

('Health Sciences Block', 'Academic', 'G', 4, 'Modern health sciences facility with simulation labs, clinical skills rooms, and medical equipment.', 'Public Health, Health Sciences, Medical Laboratory Technology', TRUE, FALSE, TRUE, TRUE, FALSE, 'Clinical uniform required for lab access. Follow hygiene protocols.', 'Emergency exits with medical emergency equipment. Assembly point: Health sciences garden.', '7:00 AM - 8:00 PM Mon-Fri'),

('Sports Complex', 'Sports', 'H', 2, 'Comprehensive sports facility including gymnasium, indoor courts, and changing rooms.', 'Physical Education, Sports Teams, Recreation', TRUE, FALSE, TRUE, FALSE, FALSE, 'Sports attire required. Book facilities in advance during peak hours.', 'Multiple emergency exits. First aid station at reception.', '6:00 AM - 10:00 PM Daily'),

('Conference Center', 'Events', 'I', 3, 'Modern conference and events facility with auditorium, meeting rooms, and exhibition spaces.', 'Events Management, Conferences, Seminars, Workshops', TRUE, FALSE, TRUE, FALSE, FALSE, 'Check event schedule for room availability. Registration required for some events.', 'Multiple emergency exits with clear signage. Assembly point: Main parking area.', '8:00 AM - 10:00 PM (Event-dependent)'),

('Research & Innovation Hub', 'Research', 'J', 3, 'Dedicated research facility supporting innovation projects, startup incubation, and collaborative research.', 'Research Office, Innovation Lab, Startup Incubator, Technology Transfer', TRUE, FALSE, TRUE, TRUE, FALSE, 'Research pass required. Book lab space in advance. Follow research protocols.', 'Emergency exits with research safety equipment. Assembly point: Research garden.', '8:00 AM - 8:00 PM Mon-Fri'),

('Hostel Block A', 'Accommodation', 'K', 5, 'Modern student accommodation with furnished rooms, common areas, and security services.', 'Student Housing, Accommodation Services', TRUE, FALSE, TRUE, FALSE, FALSE, 'Student accommodation card required for entry. Visitors must register at security.', 'Emergency exits on each floor. Assembly point: Hostel courtyard.', '24/7 for residents, 7:00 AM - 10:00 PM for visitors'),

('Hostel Block B', 'Accommodation', 'L', 5, 'Second major student accommodation facility with similar amenities to Block A.', 'Student Housing, Accommodation Services', TRUE, FALSE, TRUE, FALSE, FALSE, 'Student accommodation card required. Same security protocols as Block A.', 'Emergency exits on each floor. Assembly point: Hostel B garden.', '24/7 for residents, 7:00 AM - 10:00 PM for visitors');
INSERT INTO services (name, category, description, address, phone, operating_hours, verified) VALUES
('MUBAS Health Centre', 'healthcare', 'Primary healthcare for students and staff', 'MUBAS Campus, Blantyre', '+265991112233', '24/7 Emergency, 8AM-5PM Weekdays', TRUE),
('Shoprite Chichiri', 'shopping', 'Supermarket with groceries and household items', 'Chichiri Shopping Mall, Blantyre', '+265991122334', '8AM-9PM Daily', TRUE),
('National Bank MUBAS Branch', 'banking', 'Full banking services for students', 'MUBAS Campus, Blantyre', '+265991133445', '8AM-4PM Weekdays', TRUE);

CREATE INDEX idx_campus_buildings_category ON campus_buildings(category);
CREATE INDEX idx_campus_buildings_block ON campus_buildings(block);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_reg_number ON users(registration_number);
CREATE INDEX idx_accommodations_owner ON accommodations(owner_id);
CREATE INDEX idx_accommodations_verified ON accommodations(verified);
CREATE INDEX idx_accommodations_price ON accommodations(price_per_month);
CREATE INDEX idx_announcements_accommodation ON announcements(accommodation_id);
CREATE INDEX idx_announcements_created ON announcements(created_at);
CREATE INDEX idx_companies_user ON companies(user_id);
CREATE INDEX idx_companies_verified ON companies(verified);
CREATE INDEX idx_opportunities_company ON opportunities(company_id);
CREATE INDEX idx_opportunities_type ON opportunities(opportunity_type);
CREATE INDEX idx_opportunities_deadline ON opportunities(application_deadline);
CREATE INDEX idx_applications_opportunity ON applications(opportunity_id);
CREATE INDEX idx_applications_student ON applications(student_id);
CREATE INDEX idx_services_category ON services(category);
CREATE INDEX idx_services_verified ON services(verified);
CREATE INDEX idx_notifications_user_id ON notifications(user_id);
CREATE INDEX idx_notifications_unread ON notifications(user_id, is_read);
