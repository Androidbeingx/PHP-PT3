<?php 
?>
<div class= "container home">
<h1 >Welcome to Games Store</h1>
<hr>
<?php if (isset($_SESSION['name']) ): ?>
<p><?php echo "Welcome ".$_SESSION['name']." ".$_SESSION['surname'] ?></p>
</div>
<?php endif; ?>
   

