<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="UNIMIND - Your Student Arrival Toolkit for Malawi Universities">
    <meta name="keywords" content="university, malawi, students, accommodation, campus, mentorship">
    <meta name="author" content="Jubeda Orleen Ntaukira">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="UNIMIND - Student Arrival Toolkit">
    <meta property="og:description" content="Helping first-year students navigate campus life safely and confidently">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- UNIMIND CSS -->
    <link rel="stylesheet" href="assets/css/unimind.css">
    
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>UNIMIND</title>
</head>
<body>
    <?php include 'components/navigation.php'; ?>
