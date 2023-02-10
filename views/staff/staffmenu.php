<?php


  echo <<<EOT
  <nav class="navbar navbar-default navbar-expand-sm navbar-light bg-dark " data-bs-theme="dark">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">Game Store</a>
    </div>
    <div>
    <ul class="nav navbar-nav">
      <li class="active"><a class="nav-link" href="index.php?action=home">Home</a></li>
      <li><a class="nav-link" href="index.php?action=category">Categories</a></li>
      <li><a class="nav-link" href="index.php?action=product">Products</a></li>
      <li><a class="nav-link" href="index.php?action=warehouse">Warehouses</a></li>
    </ul>
    </div>
    <a class="btn btn-danger navbar-btn" href="index.php?action=user/logout">logout</a>
  </div>
  </nav>
EOT;

