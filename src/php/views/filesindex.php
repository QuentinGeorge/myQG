<h2>Bienvenue utilisateur <?= ucfirst( $_SESSION[ 'user' ][ 'name' ] ) ?></h2>

<a href="index.php?r=user&a=getLogout">Déconnexion</a>

<?php foreach ( $_SESSION[ 'user' ][ 'groups' ] as $key => $value ): ?>
    <h3>Liste des fichiers du groupe <?= $value ?></h3>
    <form action="index.php" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>Envoyer un fichier dans ce groupe</legend>
            <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
            <input type="file" name="file">
            <input type="hidden" name="a" value="upload">
            <input type="hidden" name="r" value="file">
            <input type="hidden" name="group" value="<?= $value ?>">
            <button type="submit">Envoyer le fichier</button>
            <?php if ( !empty( $_SESSION[ 'uploadfeedback' ][ $value ] ) ): ?>
                <p><?= $_SESSION[ 'uploadfeedback' ][ $value ] ?></p>
            <?php endif; ?>
        </fieldset>
    </form>
    <?php var_dump(scandir( './data/' . $value . '/' )); ?>
<?php endforeach; ?>
