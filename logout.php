<?php
/**
 * Logout Handler
 */

require_once 'core/auth.php';

logout();

header('Location: login.php');
exit;
