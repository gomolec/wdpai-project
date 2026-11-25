<?php

declare(strict_types=1);

$stylesheets = ['/css/login.css'];
?>
<main class="auth-page">
    <section class="auth-card" aria-labelledby="register-heading">
        <div class="auth-card__header">
            <div class="auth-card__mascot" aria-hidden="true">
                <img
                    src="/assets/images/pan-maselko-maskotka-wita.png"
                    alt=""
                    width="56"
                    height="56"
                >
            </div>
            <h1 id="register-heading" class="auth-card__title">Załóż konto w Panu Masełku</h1>
            <p class="auth-card__subtitle">
                Twórz współdzielone listy zakupów i łap najlepsze promocje w dyskontach.
            </p>
        </div>

        <?php require __DIR__ . '/components/register-form.html.php'; ?>

        <p class="auth-card__footer">
            Masz już konto? <a href="/login">Zaloguj się</a>
        </p>
    </section>
</main>
