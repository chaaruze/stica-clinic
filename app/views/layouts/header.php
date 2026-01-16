<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo SITENAME; ?>
    </title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <link href="<?= URLROOT ?>/assets/css/CSS.css" rel="stylesheet">
    <link href="<?= URLROOT ?>/assets/css/main.min.css" rel="stylesheet">
    <style>
        .navbar-container {
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom {
            background-color: #2c3e50;
            color: #fff;
        }

        .navbar-custom .nav-link {
            display: flex;
            align-items: center;
            color: #fff;
        }

        .navbar-custom .nav-link .fa {
            margin-right: 8px;
        }

        .navbar-nav .nav-item {
            margin-right: 25px;
        }

        .offcanvas {
            background-color: #2c3e50;
            color: #ecf0f1;
            border-radius: 0 10px 10px 0;
            padding: 15px;
            width: 300px !important;
        }

        .offcanvas .card {
            background-color: #34495e;
        }

        .custom-nav-link {
            color: #ecf0f1 !important;
            background-color: #34495e;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            text-align: start;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            text-decoration: none;
        }

        .custom-nav-link:hover {
            background-color: #f8f8ff;
            color: #2c3e50 !important;
        }

        .custom-nav-link i {
            margin-right: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body style="background-color: #F0F2F2;">