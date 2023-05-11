<!DOCTYPE html>
<html lang="en">
	<style>
		
	</style>
<head>

<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">

<link rel="stylesheet" type="text/css" href="../css/style.css">


<link rel="icon" href="../images/fevicon.png" type="image/gif" />

<title>HomePage</title>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">

</head>
<body>
    <?php session_start(); ?>
	<div class="header_section">
		
			<nav class="snip1143">
				<ul> <?php if (isset($_SESSION['email_or_username'])): ?>
				  <li><a href="logged.php" data-hover="Home">Home</a></li>
				  <li><a href="schedule.PHP" data-hover="Schedule">Schedule</a></li>
				  <li><a href="Booking.html" data-hover="Booking">Book A Flight</a></li>
				  <li><a href="Loyalty.html" data-hover="Loyalty Program">Loyalty Program</a></li>
				  <li>
                     
                          <?php
                              $display_name = $_SESSION['email_or_username'];
                              if (strpos($display_name, '@') !== false) {
                                  $display_name = explode('@', $display_name)[0];
                              }
                          ?>
                          <a href="#"><?php echo $display_name; ?></a>
                      <?php else: ?>
                        <li><a href="index.php" data-hover="Home">Home</a></li>
				  <li><a href="Login.php" data-hover="Schedule">Schedule</a></li>
				  <li><a href="login.php" data-hover="Booking">Book A Flight</a></li>
				  <li><a href="login.php" data-hover="Loyalty Program">Loyalty Program</a></li>
                          <a href="login.php" data-hover="LOGIN/REGISTER">LOGIN/REGISTER</a>
                      <?php endif; ?>
                  </li>
				</ul>
			  </nav>
		</div>
	</div>
	
	<div class="banner_section layout_padding">
	  <div class="container">
		<h1 class="banner_taital">SkyTrack</h1>
		<h2 class="free_text">Your high-flying travel companion. Safe, comfortable, and efficient air travel, from takeoff to landing </h2>
			  <div class="read_bt">
				<div class="read_text"><a href="ReadMore.html">Read More</a></div>
			  </div>
	  </div>
    
	</div>
</body>

</html>
