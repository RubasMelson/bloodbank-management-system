<?php
session_start();
require_once __DIR__ . '/config/database.php';

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);
$base = '/bloodbank';

if (strpos($path, $base) === 0) {
    $path = substr($path, strlen($base));
}
if ($path === '') $path = '/';

/* ================= ROUTES ================= */
switch ($path) {

    /* ---------- HOME ---------- */
    case '/':
        require __DIR__ . '/views/home/index.php';
        break;

    case '/become-donor':
        require __DIR__ . '/views/home/donor.php';
        break;

    case '/become-donor/store':
        require_once __DIR__ . '/controllers/DonorController.php';
        (new DonorController($pdo))->store();
        break;

    /* ---------- AUTH ---------- */
    case '/login':
        require __DIR__ . '/views/auth/login.php';
        break;

    case '/register':
        require __DIR__ . '/views/auth/register.php';
        break;

    case '/auth/login':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController($pdo))->login();
        break;

    case '/auth/register':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController($pdo))->register();
        break;

    case '/logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController($pdo))->logout();
        break;

    /* ---------- DASHBOARD ---------- */
    case '/dashboard':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /bloodbank/login');
            exit;
        }
        require __DIR__ . '/views/dashboard/index.php';
        break;

    /* ---------- DONORS (ADMIN) ---------- */
    case '/donors':
        require_once __DIR__ . '/controllers/DonorController.php';
        (new DonorController($pdo))->index();
        break;

    case '/donors/update':
        require_once __DIR__ . '/controllers/DonorController.php';
        (new DonorController($pdo))->update();
        break;

    /* ---------- INVENTORY ---------- */
    case '/inventory':
        require_once __DIR__ . '/controllers/InventoryController.php';
        (new InventoryController($pdo))->index();
        break;

    case '/inventory/update':
        require_once __DIR__ . '/controllers/InventoryController.php';
        (new InventoryController($pdo))->update();
        break;

    /* ---------- REQUESTS ---------- */
    case '/requests':
        require_once __DIR__ . '/controllers/RequestController.php';
        (new RequestController($pdo))->index();
        break;

    case '/request-blood':
        require_once __DIR__ . '/controllers/RequestController.php';
        (new RequestController($pdo))->create();
        break;

    case '/requests/store':
        require_once __DIR__ . '/controllers/RequestController.php';
        (new RequestController($pdo))->store();
        break;

    case '/requests/update-status':
        require_once __DIR__ . '/controllers/RequestController.php';
        (new RequestController($pdo))->updateStatus();
        break;

    /* ---------- APPOINTMENTS ---------- */
    case '/appointments':
        require_once __DIR__ . '/controllers/AppointmentController.php';
        (new AppointmentController($pdo))->index();
        break;

    case '/appointments/store':
        require_once __DIR__ . '/controllers/AppointmentController.php';
        (new AppointmentController($pdo))->store();
        break;

    case '/appointments/update-status':
        require_once __DIR__ . '/controllers/AppointmentController.php';
        (new AppointmentController($pdo))->updateStatus();
        break;

    /* ---------- PROFILE ---------- */
    case '/profile':
        require __DIR__ . '/views/profile/index.php';
        break;

    case '/profile/update':
        require_once __DIR__ . '/controllers/ProfileController.php';
        (new ProfileController($pdo))->update();
        break;

    /* ---------- 404 ---------- */
    default:
        http_response_code(404);
        echo "<h1 style='text-align:center;padding:100px'>404 - Page Not Found</h1>";
}
