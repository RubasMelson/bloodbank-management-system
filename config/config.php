<?php
define('BASE_URL', '/bloodbank');

// Error Reporting
error_reporting(E_ALL);
ini_set('log_errors', 1);
// Hide errors in production (or generally for cleaner UI), show in dev if needed.
// For now, we will hide display to user and rely on logs, or you can toggle this.
ini_set('display_errors', 0);
