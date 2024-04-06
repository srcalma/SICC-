<?php
session_start();

// Destroy session
session_destroy();

// Redirect user to dashboard
header('Location: ../SITE1/LANDING-PAGE.html');
exit;