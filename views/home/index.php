<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Bank System</title>
    <link rel="stylesheet" href="/bloodbank/assets/css/style.css">
    <style>
        body {
            margin: 0;
            background: linear-gradient(135deg, #7f1d1d, #111827);
            font-family: Poppins, sans-serif;
            color: white;
        }
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 60px;
            gap: 40px;
        }
        .hero-text {
            max-width: 500px;
        }
        .hero-text h1 {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        .hero-text span {
            color: #ef4444;
        }
        .hero-text p {
            color: #d1d5db;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .hero-buttons a {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            margin-right: 10px;
            font-weight: 600;
        }
        .btn-primary {
            background: #ef4444;
            color: white;
        }
        .btn-outline {
            border: 2px solid #ef4444;
            color: #ef4444;
        }
        .hero-card {
            background: rgba(255,255,255,0.08);
            padding: 30px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            max-width: 350px;
        }
    </style>
</head>
<body>

<div class="hero">
    <div class="hero-text">
        <h1>Donate <span>Your Blood</span><br>Save a Life</h1>
        <p>
            Blood Bank System helps hospitals and donors connect easily.
            One donation can save up to three lives.
        </p>
        <div class="hero-buttons">
            <a href="/bloodbank/login" class="btn-primary">Login</a>
            <a href="/bloodbank/register" class="btn-outline">Become a Donor</a>
        </div>
    </div>

    <div class="hero-card">
        <h3>Why Donate Blood?</h3>
        <ul style="color:#e5e7eb; line-height:1.8;">
            <li>✔ Saves lives</li>
            <li>✔ Emergency ready</li>
            <li>✔ Free & safe</li>
            <li>✔ Social responsibility</li>
        </ul>
    </div>
</div>

</body>
</html>
