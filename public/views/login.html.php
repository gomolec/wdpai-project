<?php

declare(strict_types=1);

$stylesheets = ['/css/login.css'];
?>
<main class="auth-page">
    <section class="auth-card" aria-labelledby="login-heading">
        <div class="auth-card__header">
            <div class="auth-card__mascot" aria-hidden="true">
                <img
                    src="/assets/images/pan-maselko-maskotka-wita.png"
                    alt=""
                    width="56"
                    height="56"
                >
            </div>
            <h1 id="login-heading" class="auth-card__title">Zaloguj się do Pana Masełka</h1>
            <p class="auth-card__subtitle">
                Zarządzaj współdzielonymi listami zakupów i śledź najlepsze promocje.
            </p>
        </div>

        <?php require __DIR__ . '/components/login-form.html.php'; ?>

        <p class="auth-card__footer">
            Nie masz jeszcze konta? <a href="/register">Zarejestruj się</a>
        </p>
    </section>
</main>
