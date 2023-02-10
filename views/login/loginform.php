<div class="container">
<h2>Login</h2>
<?php if (isset($_SESSION['name'])): ?>
    <p style='font-weight: bold;font-style: oblique;'><?php echo "User logged:  ".$_SESSION['name']." ".$_SESSION['surname'] ?></p>
<?php endif ?>
<br>

<?php if (isset($params['message'])): ?>
<div class='alert alert-warning'>
<strong><?php echo $params['message']; ?></strong>
</div>
<?php endif ?>
<?php 
if (isset($_SESSION['name'])){
  echo <<<EOT
  <div class="jumbotron">
  <form method="post">
  <span class="col-auto">
    <label for="user" class="col-form-label">User</label>
  </span>
  <span class="col-auto">
    <input type="text" id="user" name="user" class="form-control" aria-describedby="searchHelpInline">
  </span>
  <span class="col-auto">
    <label for="pass" class="col-form-label">Password</label>
  </span>
  <span class="col-auto">
    <input type="password" id="pass" name="pass" class="form-control" aria-describedby="searchHelpInline">
  </span>
  <br>
  <span class="col-auto">
    <button class="btn btn-primary" type="submit" name="action" value="user/login" disabled>Enter</button>
  </span>
  </div>
  </form>
  </div>
  EOT;
}else{
  echo <<<EOT
  <div class="jumbotron">
  <form method="post">
  <span class="col-auto">
    <label for="user" class="col-form-label">User</label>
  </span>
  <span class="col-auto">
    <input type="text" id="user" name="user" class="form-control" aria-describedby="searchHelpInline">
  </span>
  <span class="col-auto">
    <label for="pass" class="col-form-label">Password</label>
  </span>
  <span class="col-auto">
    <input type="password" id="pass" name="pass" class="form-control" aria-describedby="searchHelpInline">
  </span>
  <br>
  <span class="col-auto">
    <button class="btn btn-primary" type="submit" name="action" value="user/login">Enter</button>
  </span>
  </div>
  </form>
  </div>
  EOT;

}
 

