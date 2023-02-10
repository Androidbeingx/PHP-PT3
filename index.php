<!-- Author: Andrea Morales -->
<!-- Display all maincontroller data -->

<?php
    session_start();  //initialize session to access session variables.
    //Configuration for debugging (only developing mode). Change for production.
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);   
    ini_set('error_reporting', E_ALL);
    //
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Store manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fav icon -->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  </head>
  <body class="bg">
      <?php
        include "loadnavbar.php";  //navigation bar.
      ?>
      <?php
        //dynamic html content generated here by controller.
        require_once 'controllers/MainController.php';
        use proven\store\controllers\MainController;
        (new MainController())->processRequest();
      ?>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <style>
      body, html {
        height: 100%;
        margin: 0;
      }
      .jumbotron {
        height:100%;
        width:100%;
        margin:0 auto;
        }
      .container {
        background-color: white;
        border-radius: 5%;
        padding: 3%;
        margin-top: 2%;
      }
      .home{
        background-color: white;
        padding: 70px 0;
        text-align: center;
        border-radius: 2%;
        margin: auto;
        margin-top: 20%;
      }

      .my-custom-scrollbar {
      position: relative;
      height: 475px;
      overflow: auto;
      }
        .table-wrapper-scroll-y {
        display: block;
        }
      .bg {
        /* The image used */
        background-image: url("img/pixabay_back.jpg");

        /* Full height */
        height: 100%; 

        /* Center and scale the image nicely */
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
      }
    </style>
  </body>
</html>
