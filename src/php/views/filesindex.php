<h2>Bienvenue utilisateur <?= ucfirst( $_SESSION[ 'user' ][ 'name' ] ) ?></h2>

<a href="index.php?r=user&a=getLogout">Déconnexion</a>

<?php include 'views/upload.php'; ?>

<h3>Liste des fichiers</h3>
