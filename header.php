<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .navbar {
            background-color: #f8f8f8;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 24px;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.1);
        }

        .navbar-nav .nav-link {
            color: #333;
            transition: color 0.3s ease;
            margin: 0 10px;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            color: #007bff;
        }

        .btn-custom {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-weight: bold;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .animated-icon {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            margin-top: auto;
            width: 100%;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .social-icons a {
            margin: 0 10px;
            color: #fff;
            transition: color 0.3s;
        }

        .social-icons a:hover {
            color: #007bff;
        }

        .copyright {
            margin-top: 15px;
            font-size: 0.9em;
        }

        @media (min-width: 768px) {
            .footer-content {
                flex-direction: row;
                justify-content: space-between;
                align-items: flex-start;
            }

            .copyright {
                margin-top: 0;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="../usuario/home.php">Nexus <i class="bi bi-diagram-3 animated-icon"></i></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../public/sobre.php"><i class="bi bi-info-circle"></i> Sobre</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 pt-5">
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>