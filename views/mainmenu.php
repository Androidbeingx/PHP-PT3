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
    </ul>
    </div>
    <div>
    <a class="btn btn-info navbar-btn" href="index.php?action=loginform">login</a>
    </div>
  </div>
  </nav>
EOT;

