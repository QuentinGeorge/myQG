<h2>Bienvenue utilisateur <?= ucfirst( $_SESSION[ 'user' ][ 'name' ] ) ?></h2>

<a href="index.php?r=user&a=getLogout">Déconnexion</a>

<?php foreach ( $_SESSION[ 'user' ][ 'groups' ] as $sGroup ): ?>
    <h3>Liste des fichiers du groupe <?= ucfirst( $sGroup ) ?></h3>
    <form action="index.php" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>Envoyer un fichier dans ce groupe</legend>
            <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_UPLOAD_SIZE ?>">
            <input type="file" name="file">
            <input type="hidden" name="a" value="upload">
            <input type="hidden" name="r" value="file">
            <input type="hidden" name="group" value="<?= $sGroup ?>">
            <button type="submit">Envoyer le fichier</button>
            <?php if ( !empty( $_SESSION[ 'uploadfeedback' ][ $sGroup ] ) ): ?>
                <p><?= $_SESSION[ 'uploadfeedback' ][ $sGroup ] ?></p>
            <?php endif; ?>
        </fieldset>
    </form>
    <?php if ( !empty( $_SESSION[ 'fileslist' ][ $sGroup ] ) ): ?>
    <ul>
        <?php foreach ( $_SESSION[ 'fileslist' ][ $sGroup ] as $sFileName ): ?>
            <li>
                <p><?= $sFileName[ 'originalname' ] ?></p>
                <?php if ( file_exists( FILES_DIRECTORY . $sGroup . '/' . $sFileName[ 'thumb' ] ) ): ?>
                    <img src="<?= FILES_DIRECTORY . $sGroup . '/' . $sFileName[ 'thumb' ] ?>" alt="<?= $sFileName[ 'originalname' ] ?>">
                <?php elseif ( $sFileName[ 'type' ] === 'audio' ): ?>
                    <img src="<?= IMG_DIRECTORY . '/thumb-audio.svg' ?>" alt="Casque audio avec une vague sonore au milieu" width="<?= THUMB_WIDTH ?>">
                <?php elseif ( $sFileName[ 'type' ] === 'video' ): ?>
                    <img src="<?= IMG_DIRECTORY . '/thumb-video.svg' ?>" alt="Pelliculle de film" width="<?= THUMB_WIDTH ?>">
                <?php else: ?>
                    <img src="<?= IMG_DIRECTORY . '/thumb-file.svg' ?>" alt="Icone de fichier représentée par une feuille avec une flèche vers le bas" width="<?= THUMB_WIDTH ?>">
                <?php endif; ?>
                <?php if ( $sFileName[ 'type' ] !== 'other' ): ?>
                    <a href="<?= FILES_DIRECTORY . $sGroup . '/' . $sFileName[ 'servername' ] ?>">Afficher/Lire</a>
                <?php endif; ?>
                <a href="<?= FILES_DIRECTORY . $sGroup . '/' . $sFileName[ 'servername' ] ?>" download="<?= $sFileName[ 'originalname' ] ?>">Télécharger</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
        <p>Ce groupe ne contient aucuns fichiers</p>
    <?php endif; ?>
<?php endforeach; ?>
