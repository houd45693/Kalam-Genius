<?php
session_start();
include("db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['signup_submit'])) {
        // Handle registration logic here
        $user_name = $_POST['user_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (!empty($email) && !empty($password) && !is_numeric($email)) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Create a prepared statement for registration
            $query = "INSERT INTO user (user_name, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "sss", $user_name, $email, $hashed_password);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script type='text/javascript'>alert('Successfully Registered');</script>";
            } else {
                echo "<script type='text/javascript'>alert('Registration failed');</script>";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "<script type='text/javascript'>alert('Please Enter Some Valid Information');</script>";
        }
    }

    elseif (isset($_POST['login_submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (!empty($email) && !empty($password) && !is_numeric($email)) {
            // Retrieve user data for login
            $query = "SELECT * FROM user WHERE email = ? LIMIT 1";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);

                if (password_verify($password, $user_data['password'])) {
                    header("Location: staffIndex.php");
                    die();
                }
            }

            echo "<script type='text/javascript'>alert('Wrong username or password');</script>";
        } else {
            echo "<script type='text/javascript'>alert('Wrong username or password');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<link rel="preconnect" href="./assets/images/LOGO1.png">
<link rel="preconnect" href="./assets/images/LOGO1.png" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
<!-- Favicon-->
<link rel="icon" type="image/png" href="./assets/images/LOGO1.png" />
<title>Kalam Genius</title>

<!-- Bootstrap core CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- Additional CSS Files -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<link rel="stylesheet" href="assets/css/templatemo-chain-app-dev.css">
<link rel="stylesheet" href="assets/css/animated.css">
<link rel="stylesheet" href="assets/css/owl.css">
<link rel="stylesheet" href="assets/css/slide.css">
</head>

<body>

<!-- ***** Preloader Start ***** -->
<div id="js-preloader" class="js-preloader">
<div class="preloader-inner">
    <span class="dot"></span>
    <div class="dots">
    <span></span>
    <span></span>
    <span></span>
    </div>
</div>
</div>
<!-- ***** Preloader End ***** -->

<!-- ***** Header Area Start ***** -->
<header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
<div class="container">
    <div class="row">
    <div class="col-12">
        <nav class="main-nav">
        <!-- ***** Logo Start ***** -->
        <a href="index.php" class="logo">
            <img src="./assets/images/logo.png" alt="Kalam Genius">
        </a>
        <!-- ***** Logo End ***** -->
        <!-- ***** Menu Start ***** -->
        <ul class="nav">
            <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
            <li class="scroll-to-section"><a href="#services">Services</a></li>
            <li class="scroll-to-section"><a href="#about">About</a></li>
            <li class="scroll-to-section"><a href="#pricing">Testimonial</a></li>
            <li class="scroll-to-section"><a href="#newsletter">Teams</a></li>
            <div class="gradient-button"><a id="modal_trigger" href="#modal"><i class="fa fa-sign-in-alt"></i> Staff Sign In</a></div> 
        </ul>        
        <a class='menu-trigger'>
            <span>Menu</span>
        </a>
        <!-- ***** Menu End ***** -->
        </nav>
    </div>
    </div>
</div>
</header>
<!-- ***** Header Area End ***** -->

<div id="modal" class="popupContainer" style="display:none;">
<div class="popupHeader">
    <span class="header_title">Login</span>
    <span class="modal_close"><i class="fa fa-times"></i></span>
</div>

<section class="popupBody">
    <!-- Social Login -->
    <div class="social_login">

        <div class="action_btns">
            <div class="one_half"><a href="#" id="login_form" class="btn">Login</a></div>
            <div class="one_half last"><a href="#" id="register_form" class="btn">Sign up</a></div>
        </div>
    </div>

    <!-- Username & Password Login form -->
    <div class="user_login">
        <form id="LoginForm" method="POST">
            <label>Email Address</label>
            <input type="email"  id="mail" name="email" placeholder="E-mail"/>
            <br />

            <label>Password</label>
            <input type="password" id="pass" name="password" placeholder="Password"/>
            <br />

            <div class="action_btns">
                <div class="one_half"><a href="#" class="btn back_btn"><i class="fa fa-angle-double-left"></i> Back</a></div>
                <div class="one_half last"><a><button class="btn btn_red" type="submit" id="login" name="login_submit" value="Log In">Log In</button></a></div>
            </div>
        </form>

        <a href="#" class="forgot_password">Forgot password?</a>
    </div>

    <!-- Register Form -->
    <div class="user_register">
        <form id="RegisterForm" method="POST">
            <label>User Name</label>
            <input type="text"  id="user_name" name="user_name" placeholder="Username" required/>
            <br />

            <label>Email Address</label>
            <input type="email"  id="email" name="email" placeholder="E-mail" required/>
            <br />

            <label>Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required/>
            <br />

            <div class="action_btns">
                <div class="one_half"><a href="#" class="btn back_btn"><i class="fa fa-angle-double-left"></i> Back</a></div>
                <div class="one_half last"><a><button class="btn btn_red" type="submit" id="signUp" name="signup_submit" value="Sign Up">Sign Up</button></a></div>
            </div>
        </form>
    </div>
</section>
</div>

<div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
<div class="container">
    <div class="row">
    <div class="col-lg-12">
        <div class="row">
        <div class="col-lg-6 align-self-center">
            <div class="left-content show-up header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
            <div class="row">
                <div class="col-lg-12">
                <h2 style="text-align: center;">Kalam Genius<br>Tuition Centre</h2>
                <h4 style="text-align: center;">Passion Towards Quality Goals</h4>
                </div>
                <div style="text-align: center;" class="col-lg-12">
                <div class="white-button first-button scroll-to-section">
                    <a  href= "https://beacons.ai/qalamgenius">CONTACT US <i class="fa fa-phone"></i></a>
                </div>
                <div class="white-button first-button scroll-to-section">
                    <a  href= "https://my03.awfatech.com/qalamgenius/index.php">REGISTER NOW <i class="fa fa-angle-right"></i></a>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
            <img src="assets/images/sliders.png" alt="">
            </div>
        </div>
        
        </div>
    </div>
    </div>
</div>
</div>

<div  id="news" class="pricing-tables section" >
<div class="container">
    <div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="section-heading  wow fadeInDown" data-wow-duration="0.5s" data-wow-delay="0.5s">
        <h4>Kalam Genius<em> News</em></h4>
        <img src="assets/images/heading-line-dec.png" alt="">
        </div>
    </div>
    </div>
</div>
</div>



<!-- Slideshow container -->
<div class="slideshow-container" style="background-image: url(assets/images/newsBG.png);">

<?php
// Fetch news images from the database
$select = mysqli_query($con, "SELECT news_image FROM news ORDER BY id");
$counter = 1;

// Loop through the news images and generate slideshow elements
while ($row = mysqli_fetch_assoc($select)) {
    echo '<div class="mySlides fades">';
    echo '<img src="assets/uploaded_img/' . $row['news_image'] . '">';
    echo '</div>';
    // echo '<span class="dots" onclick="currentSlides(' . $counter . ')"></span>';
    
    $counter++;
}
?>

<!-- Next and previous buttons -->
<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
<a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>
<br>

<!-- The dots/circles -->
<div style="text-align:center">
<?php
// Generate dots for each slide
for ($i = 1; $i <= mysqli_num_rows($select); $i++) {
    echo '<span class="dots" onclick="currentSlides(' . $i . ')"></span>';
}
?>
</div>


<div id="services" class="services section">
<div class="container">
    <div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="section-heading  wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.5s">
        <h4><em>Programme</em> Offered for me</h4>
        <img src="assets/images/heading-line-dec.png" alt="">
        <p>At <em>Qalam Genius</em>, we take pride in providing a nurturing and empowering environment for students to thrive. We are excited to offer a wide range of subjects to cater to your unique learning needs. Our dedicated team of experienced educators is committed to helping you unlock your full potential.</p>
        </div>
    </div>
    </div>
</div>
<div class="container">
    <div class="row">
    <div class="col-lg-3">
        <div class="service-item first-service" style="height: 100%;">
        <div class="icon"></div>
        <h4>Sijil Pelajaran Malaysia</h4>
        <p>Bahasa Melayu<br>English<br>Mathematics Modern<br>Science<br>Sejarah<br>Additional Mathematics<br>Chemistry<br>Biology<br>Physics<br>Account </p>
        <div class="text-button">
        </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="service-item second-service" style="height: 100%;">
        <div class="icon"></div>
        <h4>UASA</h4>
        <p>Bahasa Melayu<br>English<br>Mathematics<br>Science<br>Sejarah<br>Geography</p>
        <div class="text-button">
        </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="service-item third-service" style="height: 100%;">
        <div class="icon"></div>
        <h4>KSSR</h4>
        <p>Bahasa Melayu<br>English<br>Mathematics<br>Science</p>
        <div class="text-button">
        </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="service-item fourth-service" style="height: 100%;">
        <div class="icon"></div>
        <h4>Help &amp; Support</h4>
        <p>Personal Teaching<br>Smart Reader<br>School Holiday Program<br>Free Counseling Sessions<br>Staged Assessment Twice a Year (UK & UPP)</p>
        <div class="text-button">
        </div>
        </div>
    </div>
    </div>
</div>
</div>

<div id="about" class="about-us section">
<div class="container">
    <div class="row">
    <div class="col-lg-6 align-self-center">
        <div class="section-heading">
        <h4><em>Excellence</em> in Education</h4>
        <img src="assets/images/heading-line-dec.png" alt="">
        <p>Crafting Educational Excellence for 16 Years. Experience top-notch teaching, a comfortable learning environment, and personalized student support. Join us for a journey to success!"</p>
        </div>
        <div class="row">
        <div class="col-lg-6">
            <div class="box-item">
            <h4>Experienced and skilled teachers</h4>
            <p>Our teaching staff includes excellent educators, book authors, curriculum developers, and exam paper graders (for UPSR, PT3, and SPM).</p><br>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="box-item">
            <h4>Comfortable and conducive learning environment</h4>
            <p>Our classrooms are equipped with air conditioning, projectors, and CCTV for a comfortable and conducive learning atmosphere.</p>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="box-item">
            <h4>Proven track record of achievement</h4>
            <p>Kalam Genius has produced many outstanding students throughout its 16 years of operation.</p>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="box-item">
            <h4>Student performance assessment</h4>
            <p>Complimentary counseling and annual performance assessments ensure continuous growth.</p>
            </div>
        </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="right-image">
        <img src="assets/images/bg2.png" alt="">
        </div>
    </div>
    </div>
</div>
</div>

<div id="pricing" class="the-clients">
<div class="container">
    <div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="section-heading">
        <h4>Explore<em> Parent Testimonials</em> for Insights and Reviews</h4>
        <img src="assets/images/heading-line-dec.png" alt="">
        <p>Real Stories, Real Success. Hear from our satisfied parents and their experiences with Kalam Genius.</p>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="naccs">
        <div class="grid">
            <div class="row">
            <div class="col-lg-7 align-self-center">
                <div class="menu">
                <div class="first-thumb active">
                    <div class="thumb">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-12">
                        <h4>Asyraf</h4>
                        <span class="date">UASA</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                        <span class="category">Tmn Bestari Indah</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-12">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <span class="rating">4.8</span>
                        </div>
                    </div>
                    </div>
                </div>
                <div>
                    <div class="thumb">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-12">
                        <h4>Aneesa</h4>
                        <span class="date">KSSR</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                        <span class="category"> Tmn Bukit Dahlia</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-12">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <span class="rating">4.5</span>
                        </div>
                    </div>
                    </div>
                </div>
                <div>
                    <div class="thumb">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-12">
                        <h4>Thaqif</h4>
                        <span class="date">UASA</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                        <span class="category">Tmn Sri Saujana</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-12">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <span class="rating">4.7</span>
                        </div>
                    </div>
                    </div>
                </div>
                <div>
                    <div class="thumb">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-12">
                        <h4>Arash</h4>
                        <span class="date">SPM</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                        <span class="category">Tmn Bestari Indah</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-12">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <span class="rating">3.9</span>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="last-thumb">
                    <div class="thumb">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-12">
                        <h4>Abd Hadi</h4>
                        <span class="date">SPM</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                        <span class="category">Tmn Bestari Indah</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-12">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <span class="rating">4.3</span>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div> 
            <div class="col-lg-5">
                <ul class="nacc">
                <li class="active">
                    <div>
                    <div class="thumb">
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="client-content">
                            <img src="assets/images/testimoni1.jpg" alt="" style="width: 100%; height: 00%; border-radius: 20px;">
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </li>
                <li>
                    <div>
                    <div class="thumb">
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="client-content">
                            <img src="assets/images/testimoni2.jpg" alt="" style="width: 100%; height: 100%; border-radius: 20px;">
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </li>
                <li>
                    <div>
                    <div class="thumb">
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="client-content">
                            <img src="assets/images/testimoni3.jpg" alt="" style="width: 100%; height: 100%; border-radius: 20px;">
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </li>
                <li>
                    <div>
                    <div class="thumb">
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="client-content">
                            <img src="assets/images/testimoni4.jpg" alt="" style="width: 100%; height: 100%; border-radius: 20px;">
                                ”</p>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </li>
                <li>
                    <div>
                    <div class="thumb">
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="client-content">
                            <img src="assets/images/testimoni5.jpg" alt="" style="width: 100%; height: 100%; border-radius: 20px;">
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </li>
                </ul>
            </div>          
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
</div>

<div id="newsletter" class="pricing-tables" style="background-image: url(assets/images/teamBG.png);">
<div class="container">
    <div class="row">
    <div class="container-fluid team py-6">
        <div class="container">
            <div class="section-heading">
            <h4>Our<em> Team</em></h4>
            <img src="assets/images/heading-line-dec.png" alt="">
            <p>Meet the Team: The driving force behind our success.</p>
            </div>
            <img src="assets/images/team.png" alt="" style="width: 60%; height: 50%; display: block; margin: 0 auto;">
        </div>
    </div>
    </div>
</div>
</div> 

<div id="services" class="services section">
<div class="container">
    <div class="row justify-content-center">
    <div class="col-lg-4">
        <div class="service-item first-service">
        <h4>Ulu Tiram</h4>
        <p>No. 20-01, 2, 69, Jalan Bestari, </p><p>81800 Ulu Tiram, Johor</p>
        <div class="text-button">
            <a href="https://www.wasap.my/60167773020">Contact Us <i class="fas fa-phone"></i></a>
        </div>
        <div class="text-button">
            <a href="https://maps.app.goo.gl/wDzpaVmMsxu5TsEK8">Locate Us <i class="fa fa-arrow-right"></i></a>
        </div>
        <div class="map" id="contact">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.2673529151216!2d103.79300844030463!3d1.5976309983942003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da6fc355f3d2f9%3A0xa49277edb209ced2!2sPusat%20Tuisyen%20Qalam%20Genius%20(Bestari%20Indah)!5e0!3m2!1sen!2smy!4v1696498623865!5m2!1sen!2smy" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
            <br />
            <small><a href="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.2673529151216!2d103.79300844030463!3d1.5976309983942003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da6fc355f3d2f9%3A0xa49277edb209ced2!2sPusat%20Tuisyen%20Qalam%20Genius%20(Bestari%20Indah)!5e0!3m2!1sen!2smy!4v1696498623865!5m2!1sen!2smy"></a></small>
        </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="service-item second-service">
        <h4>Kota Tinggi</h4>
        <p>45A, Jalan SS 2/6, Taman Sri Saujana 2, </p> <p>81900 Kota Tinggi, Johor</p>
        <div class="text-button">
            <a href="https://www.wasap.my/60177579301">Contact Us <i class="fas fa-phone"></i></a>
        </div>
        <div class="text-button">
            <a href="https://maps.app.goo.gl/wDzpaVmMsxu5TsEK8">Locate Us <i class="fa fa-arrow-right"></i></a>
        </div>
        <div class="map" id="contact">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63809.89611486874!2d103.77531124863283!3d1.672542199999994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da67b6e02e07a5%3A0x65dad666c9919bd9!2sPusat%20Tuisyen%20Qalam%20Genius%20(caw.%20Sri%20Saujana)!5e0!3m2!1en!2smy!4v1696509902455!5m2!1sen!2smy" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
            <br />
            <small><a href="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63809.89611486874!2d103.77531124863283!3d1.672542199999994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da67b6e02e07a5%3A0x65dad666c9919bd9!2sPusat%20Tuisyen%20Qalam%20Genius%20(caw.%20Sri%20Saujana)!5e0!3m2!1en!2smy!4v1696509902455!5m2!1sen!2smy"></a></small>
        </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="service-item third-service">
        <h4>Pasir Gudang</h4>
        <p>38A, Jalan Sejambak 14, Taman Bukit Dahlia, </p><p>81700 Pasir Gudang, Johor</p>
        <div class="text-button">
            <a href="https://www.wasap.my/60127206458">Contact Us <i class="fas fa-phone"></i></a>
        </div>
        <div class="text-button">
            <a href="https://maps.app.goo.gl/wDzpaVmMsxu5TsEK8">Locate Us <i class="fa fa-arrow-right"></i></a>
        </div>
        <div class="map" id="contact">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63815.82636862105!2d103.81798024863278!3d1.478985000000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da6b04a6e541c5%3A0x2ef9ca34b90f2319!2sPusat%20Tuisyen%20Qalam%20Genius%20(Bukit%20Dahlia)!5e0!3m2!1sen!2smy!4v1696509987765!5m2!1sen!2smy" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
            <br />
            <small><a href="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63815.82636862105!2d103.81798024863278!3d1.478985000000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da6b04a6e541c5%3A0x2ef9ca34b90f2319!2sPusat%20Tuisyen%20Qalam%20Genius%20(Bukit%20Dahlia)!5e0!3m2!1sen!2smy!4v1696509987765!5m2!1sen!2smy"></a></small>
        </div>
        </div>
    </div>
    </div>
</div>
</div>


<footer id="newsletter1">
<div class="container">
    <div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="section-heading">
        <h4>Join our successful students today!</h4>
        </div>
    </div>
    <div style="text-align: center;" class="col-lg-12">
        <div class="satu-button white-button first-button scroll-to-section">
        <a  href= "https://my03.awfatech.com/qalamgenius/index.php">REGISTER NOW <i class="fa fa-angle-right"></i></a>
        </div>
    </div>
    </div>
    <div class="row">
    <div class="col-lg-12">
        <div class="copyright-text">
        <p>Copyright © 2023 Kalam Genius Tuition Centre. All Rights Reserved. 
        <br>Design: Houd & Filzah</a></p>
        </div>
    </div>
    </div>
</div>
</footer>


<script>
    var slideIndex = 1;
var slideInterval;
var fadeDurationInSeconds = 4; // Set the duration for each slide in seconds

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dots");

    if (n > slides.length) {
        slideIndex = 1;
    }

    if (n < 1) {
        slideIndex = slides.length;
    }

    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" actives", "");
    }

    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " actives";
}

function plusSlides(n) {
    showSlides((slideIndex += n));
    resetTimer();
}

function currentSlides(n) {
    showSlides((slideIndex = n));
    resetTimer();
}

function resetTimer() {
    clearInterval(slideInterval);
    slideInterval = setInterval(function () {
        plusSlides(1);
    }, fadeDurationInSeconds * 1000); // Convert seconds to milliseconds
}

// Initial start of the slideshow
showSlides(slideIndex);
resetTimer();

</script>

<!-- Scripts -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/owl-carousel.js"></script>
<script src="assets/js/animation.js"></script>
<script src="assets/js/imagesloaded.js"></script>
<script src="assets/js/popup.js"></script>
<script src="assets/js/custom.js"></script>


</body>

</html>
