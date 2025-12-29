<?php
session_start();
require __DIR__ . '/config/database.php';

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
        (new DonorController($conn))->store();
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
        (new AuthController($conn))->login();
        break;

    case '/auth/register':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController($conn))->register();
        break;

    case '/logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController($conn))->logout();
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
        (new DonorController($conn))->index();
        break;

    case '/donors/update':
        require_once __DIR__ . '/controllers/DonorController.php';
        (new DonorController($conn))->update();
        break;

    /* ---------- INVENTORY ---------- */
    case '/inventory':
        require_once __DIR__ . '/controllers/InventoryController.php';
        (new InventoryController($conn))->index();
        break;

    case '/inventory/update':
        require_once __DIR__ . '/controllers/InventoryController.php';
        (new InventoryController($conn))->update();
        break;

    /* ---------- REQUESTS ---------- */
    case '/requests':
        require_once __DIR__ . '/controllers/RequestController.php';
        (new RequestController($conn))->index();
        break;

    case '/request-blood':
        require_once __DIR__ . '/controllers/RequestController.php';
        (new RequestController($conn))->create();
        break;

    case '/requests/store':
        require_once __DIR__ . '/controllers/RequestController.php';
        (new RequestController($conn))->store();
        break;

    case '/requests/update-status':
        require_once __DIR__ . '/controllers/RequestController.php';
        (new RequestController($conn))->updateStatus();
        break;

    /* ---------- APPOINTMENTS ---------- */
    case '/appointments':
        require_once __DIR__ . '/controllers/AppointmentController.php';
        (new AppointmentController($conn))->index();
        break;

    case '/appointments/store':
        require_once __DIR__ . '/controllers/AppointmentController.php';
        (new AppointmentController($conn))->store();
        break;

    case '/appointments/update-status':
        require_once __DIR__ . '/controllers/AppointmentController.php';
        (new AppointmentController($conn))->updateStatus();
        break;

    /* ---------- PROFILE ---------- */
    case '/profile':
        require __DIR__ . '/views/profile/index.php';
        break;

    case '/profile/update':
        require_once __DIR__ . '/controllers/ProfileController.php';
        (new ProfileController($conn))->update();
        break;

    /* ---------- 404 ---------- */
    default:
        http_response_code(404);
        echo "<h1 style='text-align:center;padding:100px'>404 - Page Not Found</h1>";
}
