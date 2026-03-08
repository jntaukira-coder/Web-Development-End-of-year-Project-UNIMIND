<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load database and functions
require_once 'db.php';
require_once 'functions.php';

// Get user and subscription info
$user = null;
$subscription = ['status' => 'none', 'remaining_seconds' => 0];

if(isset($_SESSION['user_id'])) {
    $user = get_user($_SESSION['user_id'], $pdo);
    if($user) {
        $subscription = get_subscription_status($user, $pdo);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UNIMIND Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://in.paychangu.com/js/popup.js"></script>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: #0f0f20;
    color: #ffffff;
    line-height: 1.6;
    scroll-behavior: smooth;
}

.app-container {
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.sidebar {
    width: 280px;
    background: linear-gradient(180deg, #1a0a2e 0%, #2a0a4e 100%);
    color: white;
    padding: 2rem 0;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
    z-index: 1000;
}

.sidebar-header {
    padding: 0 2rem 2rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 2rem;
}

.logo {
    font-size: 1.5rem;
    font-weight: 700;
    color: #00d4ff;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 1.5rem;
}

.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #00d4ff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1.125rem;
}

.user-info {
    flex: 1;
}

.user-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.user-status {
    font-size: 0.875rem;
    opacity: 0.8;
}

.sidebar-nav {
    padding: 0 1rem;
}

.nav-item {
    display: block;
    padding: 0.875rem 1rem;
    margin-bottom: 0.5rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.nav-item:hover {
    background: rgba(0, 212, 255, 0.1);
    color: white;
}

.nav-item.active {
    background: #00d4ff;
    color: white;
}

.nav-item i {
    width: 20px;
    text-align: center;
}

/* Main Content */
.main-content {
    flex: 1;
    margin-left: 280px;
    padding: 2rem;
}

.content-header {
    background: linear-gradient(135deg, #1a0a2e, #2a0a4e);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 212, 255, 0.3);
    border: 1px solid #4a00ff;
}

.header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn {
    padding: 0.625rem 1.25rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    cursor: pointer;
}

.btn-primary {
    background: #00d4ff;
    color: white;
}

.btn-primary:hover {
    background: #00a8cc;
    transform: translateY(-1px);
}

.btn-secondary {
    background: rgba(0, 212, 255, 0.1);
    color: #e0e0ff;
    border: 1px solid #4a00ff;
}

.btn-secondary:hover {
    background: rgba(0, 212, 255, 0.2);
}

.subscription-banner {
    background: linear-gradient(135deg, #00d4ff, #4a00ff);
    border: 1px solid #4a00ff;
    border-radius: 8px;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 20px rgba(74, 0, 255, 0.3);
}

.banner-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.banner-icon {
    font-size: 1.25rem;
    color: #00d4ff;
}

.banner-text {
    color: #ffffff;
    font-weight: 500;
}

.banner-timer {
    color: #ffffff;
    font-weight: 600;
}

/* Stats Overview */
.stats-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, #1a0a2e, #2a0a4e);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 212, 255, 0.3);
    border-left: 4px solid #00d4ff;
    border: 1px solid #4a00ff;
    transition: all 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 212, 255, 0.4);
}

.stat-card.success {
    border-left-color: #4a00ff;
}

.stat-card.warning {
    border-left-color: #ff8800;
}

.stat-card.info {
    border-left-color: #00ffff;
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.stat-title {
    font-size: 0.875rem;
    color: #e0e0ff;
    font-weight: 500;
}

.stat-icon {
    font-size: 1.25rem;
    color: #00d4ff;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.5rem;
}

.stat-description {
    font-size: 0.875rem;
    color: #e0e0ff;
}

/* Tools Section */
.tools-section {
    background: linear-gradient(135deg, #1a0a2e, #2a0a4e);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 1px 3px rgba(0, 212, 255, 0.3);
    border: 1px solid #4a00ff;
    margin-bottom: 2rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f1f5f9;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
}

.tools-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.tool-card {
    background: linear-gradient(135deg, #2a0a4e, #1a0a2e);
    border: 1px solid #4a00ff;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.2s ease;
    position: relative;
}

.tool-card:hover {
    border-color: #00d4ff;
    box-shadow: 0 4px 12px rgba(0, 212, 255, 0.4);
    transform: translateY(-2px);
}

.tool-card.locked {
    opacity: 0.7;
    background: linear-gradient(135deg, #1a0a2e, #0a0a1a);
}

.tool-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.tool-icon {
    width: 48px;
    height: 48px;
    background: #00d4ff;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.tool-badge {
    background: #4a00ff;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.tool-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 0.5rem;
}

.tool-description {
    color: #e0e0ff;
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

.tool-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.tool-link {
    color: #00d4ff;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
}

.tool-link:hover {
    text-decoration: underline;
}

/* Activity Feed */
.activity-section {
    background: linear-gradient(135deg, #1a0a2e, #2a0a4e);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 1px 3px rgba(0, 212, 255, 0.3);
    border: 1px solid #4a00ff;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border-radius: 8px;
    transition: background 0.2s ease;
}

.activity-item:hover {
    background: rgba(0, 212, 255, 0.1);
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: rgba(0, 212, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #00d4ff;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 500;
    color: #ffffff;
    margin-bottom: 0.25rem;
}

.activity-time {
    font-size: 0.875rem;
    color: #e0e0ff;
}

/* Upgrade Section */
.upgrade-section {
    background: linear-gradient(135deg, #00d4ff, #4a00ff);
    border-radius: 12px;
    padding: 2rem;
    color: white;
    text-align: center;
    margin-bottom: 2rem;
    border: 1px solid #4a00ff;
    box-shadow: 0 8px 32px rgba(74, 0, 255, 0.3);
}

.upgrade-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.upgrade-description {
    margin-bottom: 2rem;
    opacity: 0.9;
}

.plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.plan-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
}

.plan-card.featured {
    background: rgba(255, 255, 255, 0.2);
    border-color: #00d4ff;
    transform: scale(1.05);
}

.plan-name {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.plan-price {
    font-size: 2rem;
    font-weight: 700;
    color: #00d4ff;
    margin-bottom: 0.5rem;
}

.plan-period {
    opacity: 0.8;
    margin-bottom: 1.5rem;
}

.plan-features {
    list-style: none;
    margin-bottom: 2rem;
    text-align: left;
}

.plan-features li {
    padding: 0.5rem 0;
    opacity: 0.9;
}

.plan-features li:before {
    content: "✓ ";
    color: #00ffff;
    font-weight: bold;
}

.plan-button {
    background: #00d4ff;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    width: 100%;
    transition: all 0.2s ease;
    cursor: pointer;
    position: relative;
}

.plan-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 212, 255, 0.4);
}

/* PayChangu Payment Styles */
.payment-wrapper {
    margin-top: 1rem;
}

#start-payment-button {
    cursor: pointer;
    position: relative;
    background: linear-gradient(135deg, #00d4ff, #4a00ff);
    color: #ffffff;
    width: 100%;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    font-size: 14px;
    border-radius: 8px;
    border: none;
    transition: all 0.3s ease;
    vertical-align: middle;
    font-family: 'Inter', sans-serif;
}

#start-payment-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 212, 255, 0.4);
    background: linear-gradient(135deg, #00a8cc, #3a00cc);
}

#start-payment-button:active {
    transform: translateY(0);
}

.plan-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 212, 255, 0.4);
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .header-top {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .stats-overview {
        grid-template-columns: 1fr;
    }
    
    .tools-grid {
        grid-template-columns: 1fr;
    }
    
    .plans-grid {
        grid-template-columns: 1fr;
    }
    
    .plan-card.featured {
        transform: none;
    }
}
</style>
</head>
<body>

<div class="app-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                UNIMIND
            </div>
            <div class="user-profile">
                <div class="user-avatar">
                    <?php echo isset($user['fullname']) ? substr($user['fullname'], 0, 1) : 'U'; ?>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo isset($user['fullname']) ? htmlspecialchars($user['fullname']) : 'Student'; ?></div>
                    <div class="user-status">
                        <?php 
                        if($subscription['status'] == 'premium') {
                            echo 'Premium';
                        } else {
                            echo 'Free Mode';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <a href="Home.php" class="nav-item active">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="focus.php" class="nav-item">
                <i class="fas fa-bullseye"></i>
                Focus Zone
            </a>
            <a href="aboutme.php" class="nav-item">
                <i class="fas fa-user"></i>
                Discover Yourself
            </a>
            <a href="campus life.php" class="nav-item">
                <i class="fas fa-calendar"></i>
                Campus Life
            </a>
            <?php if(check_subscription($user, $pdo)): ?>
            <a href="Accomodation.php" class="nav-item">
                <i class="fas fa-home"></i>
                Accommodation
            </a>
            <a href="services.php" class="nav-item">
                <i class="fas fa-store"></i>
                Campus Services
            </a>
            <a href="opportunities.php" class="nav-item">
                <i class="fas fa-briefcase"></i>
                Opportunities
            </a>
            <a href="register.php" class="nav-item">
                <i class="fas fa-user-graduate"></i>
                AI Mentor
            </a>
            <a href="yaza_corner.php" class="nav-item">
                <i class="fas fa-star"></i>
                Yaza Corner
            </a>
            <?php else: ?>
            <a href="#upgrade-section" class="nav-item">
                <i class="fas fa-home"></i>
                Accommodation
                <span style="background: #64748b; color: white; padding: 0.125rem 0.5rem; border-radius: 4px; font-size: 0.625rem; margin-left: auto;">PRO</span>
            </a>
            <a href="#upgrade-section" class="nav-item">
                <i class="fas fa-store"></i>
                Campus Services
                <span style="background: #64748b; color: white; padding: 0.125rem 0.5rem; border-radius: 4px; font-size: 0.625rem; margin-left: auto;">PRO</span>
            </a>
            <a href="#upgrade-section" class="nav-item">
                <i class="fas fa-briefcase"></i>
                Opportunities
                <span style="background: #64748b; color: white; padding: 0.125rem 0.5rem; border-radius: 4px; font-size: 0.625rem; margin-left: auto;">PRO</span>
            </a>
            <a href="#upgrade-section" class="nav-item">
                <i class="fas fa-user-graduate"></i>
                AI Mentor
                <span style="background: #64748b; color: white; padding: 0.125rem 0.5rem; border-radius: 4px; font-size: 0.625rem; margin-left: auto;">PRO</span>
            </a>
            <a href="#upgrade-section" class="nav-item">
                <i class="fas fa-star"></i>
                Yaza Corner
                <span style="background: #64748b; color: white; padding: 0.125rem 0.5rem; border-radius: 4px; font-size: 0.625rem; margin-left: auto;">PRO</span>
            </a>
            <?php endif; ?>
            <a href="#upgrade-section" class="nav-item">
                <i class="fas fa-crown"></i>
                Upgrade Plan
            </a>
            <a href="login.php" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Content Header -->
        <div class="content-header">
            <div class="header-top">
                <h1 class="page-title">Dashboard</h1>
                <div class="header-actions">
                    <a href="#upgrade-section" class="btn btn-secondary">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                    <a href="#upgrade-section" class="btn btn-primary">
                        <i class="fas fa-crown"></i>
                        Upgrade
                    </a>
                </div>
            </div>
            
            <div class="subscription-banner">
                <div class="banner-content">
                    <div class="banner-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <div class="banner-text">
                            <?php 
                            if(isset($_GET['payment_success'])) {
                                echo 'Payment Successful! Premium Activated';
                            } elseif($subscription['status'] == 'premium') {
                                echo 'Premium Plan Active';
                            } else {
                                echo 'Free Mode - Upgrade for Premium Features';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php if($subscription['status'] != 'premium' && !isset($_GET['payment_success'])): ?>
                <a href="#upgrade-section" class="btn btn-primary">
                    <i class="fas fa-crown"></i>
                    Upgrade Now
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Account Status</div>
                    <div class="stat-icon">
                        <i class="fas fa-user-circle"></i>
                    </div>
                </div>
                <div class="stat-value"><?php echo $subscription['status'] == 'premium' ? 'PREMIUM' : 'FREE'; ?></div>
                <div class="stat-description">
                    <?php 
                    if($subscription['status'] == 'premium') {
                        echo 'Full premium access';
                    } else {
                        echo 'Basic features available';
                    }
                    ?>
                </div>
            </div>
            
            <div class="stat-card success">
                <div class="stat-header">
                    <div class="stat-title">Available Tools</div>
                    <div class="stat-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
                <div class="stat-value">
                    <?php 
                    if($subscription['status'] == 'premium') {
                        echo '8';
                    } else {
                        echo '3';
                    }
                    ?>
                </div>
                <div class="stat-description">
                    <?php 
                    if($subscription['status'] == 'premium') {
                        echo 'All tools unlocked';
                    } else {
                        echo '3 free tools available';
                    }
                    ?>
                </div>
            </div>
            
            <div class="stat-card <?php echo check_subscription($user, $pdo) ? 'success' : 'warning'; ?>">
                <div class="stat-header">
                    <div class="stat-title">Premium Features</div>
                    <div class="stat-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                </div>
                <div class="stat-value">
                    <?php echo check_subscription($user, $pdo) ? 'ACTIVE' : 'LOCKED'; ?>
                </div>
                <div class="stat-description">
                    <?php echo check_subscription($user, $pdo) ? 'All premium features available' : 'Upgrade to unlock premium tools'; ?>
                </div>
            </div>
            
            <div class="stat-card info">
                <div class="stat-header">
                    <div class="stat-title">Study Progress</div>
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="stat-value">--</div>
                <div class="stat-description">Track your learning journey</div>
            </div>
        </div>

        <?php if($subscription['status'] != 'premium'): ?>
        <!-- Upgrade Section -->
        <div class="upgrade-section">
            <h2 class="upgrade-title">Unlock Your Full Potential</h2>
            <p class="upgrade-description">Get unlimited access to all premium features and accelerate your success</p>
            
            <div class="plans-grid">
                <div class="plan-card">
                    <div class="plan-name">Student Plan</div>
                    <div class="plan-price">MWK 500</div>
                    <div class="plan-period">per month</div>
                    <ul class="plan-features">
                        <li>All basic tools</li>
                        <li>Campus Services access</li>
                        <li>Opportunities database</li>
                        <li>AI Mentor matching</li>
                    </ul>
                    <div class="payment-wrapper">
                        <div id="wrapper"></div>
                        <button type="button" id="start-payment-button-student" onClick="makePaymentStudent()">Pay MWK 500</button>
                    </div>
                </div>
                
                <div class="plan-card featured">
                    <div class="plan-name">Premium Plan</div>
                    <div class="plan-price">MWK 1,000</div>
                    <div class="plan-period">per month</div>
                    <ul class="plan-features">
                        <li>Everything in Student</li>
                        <li>Safe Accommodation</li>
                        <li>Yaza Corner exclusive</li>
                        <li>Priority support</li>
                    </ul>
                    <div class="payment-wrapper">
                        <div id="wrapper"></div>
                        <button type="button" id="start-payment-button-premium" onClick="makePaymentPremium()">Pay MWK 1,000</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tools Section -->
        <div class="tools-section">
            <div class="section-header">
                <h2 class="section-title">Available Tools</h2>
            </div>
            <div class="tools-grid">
                <div class="tool-card">
                    <div class="tool-header">
                        <div class="tool-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                    </div>
                    <h3 class="tool-title">Focus Zone</h3>
                    <p class="tool-description">Productivity and study enhancement tools with distraction blocking and time tracking.</p>
                    <div class="tool-footer">
                        <a href="focus.php" class="tool-link">Open Tool →</a>
                    </div>
                </div>
                
                <div class="tool-card">
                    <div class="tool-header">
                        <div class="tool-icon">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <h3 class="tool-title">Discover Yourself</h3>
                    <p class="tool-description">Personal growth assessments and AI-powered insights for self-improvement.</p>
                    <div class="tool-footer">
                        <a href="aboutme.php" class="tool-link">Open Tool →</a>
                    </div>
                </div>
                
                <div class="tool-card">
                    <div class="tool-header">
                        <div class="tool-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                    <h3 class="tool-title">Campus Life</h3>
                    <p class="tool-description">Discover events, activities, and connect with fellow students.</p>
                    <div class="tool-footer">
                        <a href="campus life.php" class="tool-link">Open Tool →</a>
                    </div>
                </div>
                
                <div class="tool-card <?php echo !check_subscription($user, $pdo) ? 'locked' : ''; ?>">
                    <div class="tool-header">
                        <div class="tool-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <?php if(!check_subscription($user, $pdo)): ?>
                        <div class="tool-badge">PRO</div>
                        <?php endif; ?>
                    </div>
                    <h3 class="tool-title">Safe Accommodation</h3>
                    <p class="tool-description">Verified hostel listings, AI-powered matching, and virtual tours.</p>
                    <div class="tool-footer">
                        <?php if(check_subscription($user, $pdo)): ?>
                        <a href="Accomodation.php" class="tool-link">Open Tool →</a>
                        <?php else: ?>
                        <a href="#upgrade-section" class="tool-link">Upgrade →</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="tool-card <?php echo !check_subscription($user, $pdo) ? 'locked' : ''; ?>">
                    <div class="tool-header">
                        <div class="tool-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <?php if(!check_subscription($user, $pdo)): ?>
                        <div class="tool-badge">PRO</div>
                        <?php endif; ?>
                    </div>
                    <h3 class="tool-title">Campus Services</h3>
                    <p class="tool-description">Directory of nearby hospitals, shops, banks, and facilities.</p>
                    <div class="tool-footer">
                        <?php if(check_subscription($user, $pdo)): ?>
                        <a href="services.php" class="tool-link">Open Tool →</a>
                        <?php else: ?>
                        <a href="#upgrade-section" class="tool-link">Upgrade →</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="tool-card <?php echo !check_subscription($user, $pdo) ? 'locked' : ''; ?>">
                    <div class="tool-header">
                        <div class="tool-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <?php if(!check_subscription($user, $pdo)): ?>
                        <div class="tool-badge">PRO</div>
                        <?php endif; ?>
                    </div>
                    <h3 class="tool-title">Opportunities</h3>
                    <p class="tool-description">Internships, part-time jobs, scholarships, and career development.</p>
                    <div class="tool-footer">
                        <?php if(check_subscription($user, $pdo)): ?>
                        <a href="opportunities.php" class="tool-link">Open Tool →</a>
                        <?php else: ?>
                        <a href="#upgrade-section" class="tool-link">Upgrade →</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="tool-card <?php echo !check_subscription($user, $pdo) ? 'locked' : ''; ?>">
                    <div class="tool-header">
                        <div class="tool-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <?php if(!check_subscription($user, $pdo)): ?>
                        <div class="tool-badge">PRO</div>
                        <?php endif; ?>
                    </div>
                    <h3 class="tool-title">AI Mentor Match</h3>
                    <p class="tool-description">Connect with experienced students and alumni for guidance.</p>
                    <div class="tool-footer">
                        <?php if(check_subscription($user, $pdo)): ?>
                        <a href="register.php" class="tool-link">Open Tool →</a>
                        <?php else: ?>
                        <a href="#upgrade-section" class="tool-link">Upgrade →</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="tool-card <?php echo !check_subscription($user, $pdo) ? 'locked' : ''; ?>">
                    <div class="tool-header">
                        <div class="tool-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <?php if(!check_subscription($user, $pdo)): ?>
                        <div class="tool-badge">PRO</div>
                        <?php endif; ?>
                    </div>
                    <h3 class="tool-title">Yaza Corner</h3>
                    <p class="tool-description">Exclusive content, resources, and community features.</p>
                    <div class="tool-footer">
                        <?php if(check_subscription($user, $pdo)): ?>
                        <a href="yaza_corner.php" class="tool-link">Open Tool →</a>
                        <?php else: ?>
                        <a href="#upgrade-section" class="tool-link">Upgrade →</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="tool-card">
                    <div class="tool-header">
                        <div class="tool-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                    </div>
                    <h3 class="tool-title">Focus Zone</h3>
                    <p class="tool-description">Productivity timer, study music, and focus techniques for better concentration.</p>
                    <div class="tool-footer">
                        <a href="focus.php" class="tool-link">Open Tool →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Section -->
        <div class="activity-section">
            <div class="section-header">
                <h2 class="section-title">Recent Activity</h2>
            </div>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Account Created</div>
                        <div class="activity-time"><?php echo isset($user['created_at']) ? date('M j, Y', strtotime($user['created_at'])) : 'Recently'; ?></div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Last Login</div>
                        <div class="activity-time">Today</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Profile Status</div>
                        <div class="activity-time">Complete</div>
                    </div>
                </div>
                
                <?php if($subscription['status'] == 'trial'): ?>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Trial Started</div>
                        <div class="activity-time">Active</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<script>
// Handle smooth scrolling to upgrade section
document.addEventListener('DOMContentLoaded', function() {
    // Get only upgrade links, not all links
    const upgradeLinks = document.querySelectorAll('a[href="#upgrade-section"]');
    
    upgradeLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Find the upgrade section
            const upgradeSection = document.querySelector('.upgrade-section');
            
            if (upgradeSection) {
                // Calculate the position to scroll to
                const headerOffset = 80; // Account for fixed header
                const elementPosition = upgradeSection.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                // Smooth scroll to the section
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
});

// PayChangu Payment Functions
function makePaymentStudent() {
    PaychanguCheckout({
        "public_key": "PUB-VrbnEstk9h5RtHLgKkVDGLsaO3yazgcH",
        "tx_ref": '' + Math.floor((Math.random() * 1000000000) + 1),
        "amount": 500,
        "currency": "MWK",
        "callback_url": "https://yourdomain.com/payment_callback.php",
        "return_url": window.location.href,
        "customer": {
            "email": "<?php echo isset($user['email']) ? $user['email'] : 'student@unimind.com'; ?>",
            "first_name": "<?php echo isset($user['fullname']) ? explode(' ', $user['fullname'])[0] : 'Student'; ?>",
            "last_name": "<?php echo isset($user['fullname']) ? explode(' ', $user['fullname'])[1] ?? 'User' : 'User'; ?>",
        },
        "customization": {
            "title": "UNIMIND Student Plan",
            "description": "Monthly subscription for Student Plan",
        },
        "meta": {
            "plan": "student",
            "user_id": "<?php echo isset($user['id']) ? $user['id'] : ''; ?>",
            "response": "Payment processed"
        }
    });
}

function makePaymentPremium() {
    PaychanguCheckout({
        "public_key": "PUB-VrbnEstk9h5RtHLgKkVDGLsaO3yazgcH",
        "tx_ref": '' + Math.floor((Math.random() * 1000000000) + 1),
        "amount": 1000,
        "currency": "MWK",
        "callback_url": "https://yourdomain.com/payment_callback.php",
        "return_url": window.location.href,
        "customer": {
            "email": "<?php echo isset($user['email']) ? $user['email'] : 'student@unimind.com'; ?>",
            "first_name": "<?php echo isset($user['fullname']) ? explode(' ', $user['fullname'])[0] : 'Student'; ?>",
            "last_name": "<?php echo isset($user['fullname']) ? explode(' ', $user['fullname'])[1] ?? 'User' : 'User'; ?>",
        },
        "customization": {
            "title": "UNIMIND Premium Plan",
            "description": "Monthly subscription for Premium Plan",
        },
        "meta": {
            "plan": "premium",
            "user_id": "<?php echo isset($user['id']) ? $user['id'] : ''; ?>",
            "response": "Payment processed"
        }
    });
}
</script>

</body>
</html>
