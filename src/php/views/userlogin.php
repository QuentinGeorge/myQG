<header class="page__header">
    <h1 class="main-title login-title">Espace privé</h1>
</header>
<section class="page__content page-login">
    <h2 class="content__title">Connexion</h2>
    <form id="log-form" class="form" action="index.php" method="post">
        <label class="form__label" for="user">Utilisateur</label>
        <input id="user" class="form__input" type="text" name="user" autocomplete="off">
        <label class="form__label" for="password">Mot de passe</label>
        <input id="password" class="form__input" type="password" name="password">
        <input type="hidden" name="a" value="postLogin">
        <input type="hidden" name="r" value="user">
        <input id="connexion-submit" class="form__submit button" type="submit" value="Envoyer">
        <?php if ( $_SESSION[ 'logError' ] !== '' ): ?>
            <p class="form__error"><?= $_SESSION[ 'logError' ] ?></p>
        <?php endif; ?>
    </form>
</section>
