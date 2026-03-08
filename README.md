# UNIMIND - Student Arrival Toolkit

A comprehensive web platform designed to help first-year university students in Malawi navigate campus life safely and confidently.

## 🎯 Project Overview

UNIMIND (University Mind) is a student-focused platform that addresses the challenges faced by first-year students when arriving at university. Founded by Jubeda Orleen Ntaukira after her own near-scam experience, the platform provides verified accommodations, campus services, mentorship connections, and personalized planning tools.

## 🚀 Features

### Core Functionality
- **🏠 Verified Accommodations** - Safe, scam-free hostel listings with reviews
- **📍 Campus Services Directory** - Healthcare, shopping, banking, and transport services
- **👥 Mentorship Program** - Connect with experienced students for guidance
- **📋 Planning Tools** - Personalized checklists for pre/post-arrival
- **🔍 Smart Search** - Find accommodations, services, and mentors easily
- **💳 Booking System** - Secure accommodation booking with payment integration

### Security & Performance
- **🔒 Advanced Security** - CSRF protection, rate limiting, secure sessions
- **📱 Mobile Responsive** - Optimized for all devices and screen sizes
- **⚡ Performance Optimized** - Fast loading with caching and compression
- **🎨 Modern UI/UX** - Consistent design system with accessibility focus

## 🛠️ Technology Stack

### Backend
- **PHP 7.4+** - Core application logic
- **MySQL/MariaDB** - Database management
- **Session Management** - Secure user authentication
- **File Upload System** - Profile pictures and documents

### Frontend
- **HTML5 & CSS3** - Semantic markup and modern styling
- **JavaScript (Vanilla)** - Interactive features
- **Responsive Design** - Mobile-first approach
- **CSS Grid & Flexbox** - Modern layout techniques

### Security Features
- **CSRF Protection** - Prevent cross-site request forgery
- **Rate Limiting** - Brute force protection
- **Input Validation** - XSS and SQL injection prevention
- **Secure Sessions** - HttpOnly, secure cookies
- **Password Hashing** - Bcrypt encryption

## 📁 Project Structure

```
unimind/
├── components/           # Reusable UI components
│   ├── header.php      # Page header with navigation
│   ├── footer.php      # Page footer
│   └── navigation.php  # Navigation menu
├── assets/
│   └── css/
│       └── unimind.css # Main stylesheet with design system
├── uploads/            # User uploaded files
│   └── profiles/       # Profile pictures
├── config.php          # Security and app configuration
├── functions.php        # Utility functions and security helpers
├── db_connect.php      # Database connection (secure)
├── database_setup.sql  # Complete database schema
├── .htaccess          # Performance and security configuration
├── README.md          # This file
└── [application files]
```

## 🚀 Quick Start

### Prerequisites
- PHP 7.4 or higher
- MySQL/MariaDB database
- Apache web server (with mod_rewrite, mod_headers, mod_expires)
- File upload permissions

### Installation

1. **Clone/Download the Project**
   ```bash
   git clone [repository-url]
   cd unimind
   ```

2. **Database Setup**
   ```sql
   CREATE DATABASE unimind;
   USE unimind;
   SOURCE database_setup.sql;
   ```

3. **Configure Database**
   - Update `config.php` with your database credentials
   - For production, use environment variables instead of hardcoded values

4. **Set Permissions**
   ```bash
   chmod 755 uploads/
   chmod 755 uploads/profiles/
   ```

5. **Configure Web Server**
   - Ensure Apache modules are enabled: rewrite, headers, expires, deflate
   - Point document root to the project directory

6. **Access the Application**
   - Open your browser and navigate to `http://localhost/unimind`
   - Register as a new user or login with existing credentials

## 🔧 Configuration

### Environment Variables (Production)
Set these in your server environment or `.env` file:
```bash
DB_HOST=localhost
DB_USER=your_username
DB_PASS=your_password
DB_NAME=unimind
```

### Security Settings
- All sensitive files are protected via `.htaccess`
- Sessions are configured with security flags
- Rate limiting prevents brute force attacks
- CSRF tokens protect form submissions

### Performance Optimization
- Gzip compression enabled
- Browser caching for static assets
- Minified CSS/JS (production)
- Database indexes for fast queries

## 📱 Mobile Optimization

The platform is fully responsive with:
- **Touch-friendly interface** - Minimum 44px touch targets
- **Adaptive layouts** - Works on all screen sizes
- **Fast loading** - Optimized for mobile networks
- **Progressive enhancement** - Core functionality works everywhere

## 🔐 Security Features

### Authentication & Authorization
- Secure password hashing with Bcrypt
- Session timeout and regeneration
- Role-based access control (student, mentor, admin)
- Email verification system

### Data Protection
- Input sanitization and validation
- SQL injection prevention with prepared statements
- XSS protection with output encoding
- CSRF token validation on all forms

### Rate Limiting
- Login attempt limiting (5 attempts, 15-minute lockout)
- Session timeout (1 hour inactivity)
- Request throttling for API endpoints

## 📊 Database Schema

### Core Tables
- **users** - User accounts and profiles
- **accommodations** - Verified accommodation listings
- **services** - Campus and nearby services
- **accommodation_bookings** - Booking management
- **mentorship_programs** - Mentor-mentee relationships
- **reviews** - User reviews and ratings
- **checklists** - Personal planning tools
- **notifications** - User notifications

### Relationships
- Foreign key constraints ensure data integrity
- Indexes optimize query performance
- JSON fields store flexible data (photos, amenities)

## 🎨 Design System

### Color Palette
- **Primary Blue**: #38bdf8
- **Primary Dark**: #021526
- **Secondary Dark**: #0f1724
- **Accent Dark**: #1a2236
- **Text Colors**: Various shades for hierarchy

### Typography
- **Font Family**: Poppins (Google Fonts)
- **Font Sizes**: Responsive scale from xs (0.75rem) to 4xl (2.25rem)
- **Font Weights**: 400, 500, 600, 700

### Components
- **Cards** - Flexible content containers
- **Buttons** - Multiple styles and sizes
- **Forms** - Consistent input styling
- **Navigation** - Mobile-responsive menu

## 🔄 Development Workflow

### Code Organization
- **MVC Pattern** - Separation of concerns
- **Reusable Components** - Header, footer, navigation
- **Utility Functions** - Security helpers, validation
- **Configuration Management** - Centralized settings

### Best Practices
- **Input Validation** - Always validate and sanitize
- **Error Handling** - Graceful error management
- **Security First** - Consider security in all decisions
- **Performance Awareness** - Optimize for speed and scalability

## 🚀 Deployment

### Production Setup
1. **Environment Configuration**
   - Set production environment variables
   - Configure HTTPS with SSL certificate
   - Set up proper file permissions

2. **Database Optimization**
   - Configure MySQL for production
   - Set up regular backups
   - Monitor performance

3. **Security Hardening**
   - Enable HTTPS only
   - Configure firewall rules
   - Set up monitoring and logging

### Performance Monitoring
- Monitor page load times
- Track database query performance
- Set up uptime monitoring
- Regular security audits

## 📈 Scaling Roadmap

### Phase 1: Foundation (Current)
- ✅ Core functionality complete
- ✅ Security and performance optimized
- ✅ Mobile responsive design

### Phase 2: Enhancement (3-6 months)
- Mobile app development
- Payment gateway integration
- Advanced matching algorithms
- Real-time notifications

### Phase 3: Expansion (6-12 months)
- Multi-university support
- Regional expansion
- Advanced analytics
- API development

## 🤝 Contributing

### Development Guidelines
1. Follow the existing code style
2. Implement security best practices
3. Test thoroughly before deployment
4. Document new features

### Git Workflow
- Create feature branches
- Use descriptive commit messages
- Test before merging
- Update documentation

## 📞 Support

### Contact Information
- **Email**: info@unimind.mw
- **Phone**: +265 123 456 789
- **Location**: MUBAS, Blantyre, Malawi

### Bug Reports
- Report issues via GitHub issues
- Include detailed reproduction steps
- Provide environment details

## 📄 License

This project is the intellectual property of Jubeda Orleen Ntaukira and MUBAS. All rights reserved.

## 🙏 Acknowledgments

- **MUBAS** - Malawi University of Business and Applied Sciences
- **Computer Science Department** - Academic support and guidance
- **Student Community** - Feedback and testing

---

**Built with ❤️ by Jubeda Orleen Ntaukira for Malawi's student community**
