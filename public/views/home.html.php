<?php

declare(strict_types=1);

$stylesheets = ['/css/home.css'];
?>
<main class="home-main">
    <section class="welcome-banner" aria-labelledby="welcome-heading">
        <div class="welcome-banner__lead">
            <div class="welcome-banner__mascot" aria-hidden="true">
                <img
                    src="/assets/images/pan-maselko-maskotka-wita.png"
                    alt=""
                    width="56"
                    height="56"
                >
            </div>
            <div class="welcome-banner__copy">
                <p class="welcome-banner__eyebrow">Asystent spożywczy w akcji</p>
                <h1 id="welcome-heading" class="welcome-banner__title">
                    Witaj z powrotem! Oto twoja lista zakupów na ten tydzień
                </h1>
                <p class="welcome-banner__subtitle">
                    Twoje artykuły są posegregowane według alejek supermarketu.
                </p>
            </div>
        </div>

        <div class="welcome-banner__promo-box">
            <span class="welcome-banner__promo-label">Promocje</span>
            <span class="welcome-banner__promo-value">
                <?= htmlspecialchars((string) ($activePromotionsCount ?? 0), ENT_QUOTES, 'UTF-8') ?> aktywne
            </span>
        </div>
    </section>

    <div class="home-grid">
        <div class="home-primary">
            <section class="controls-panel" aria-label="Dodawanie produktu i filtry kategorii">
                <form class="add-item-form" action="/" method="post">
                    <span class="add-item-form__icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                    </span>
                    <label class="sr-only" for="new-product-name">Dodaj produkt do listy</label>
                    <input
                        id="new-product-name"
                        class="add-item-form__input"
                        type="text"
                        name="product_name"
                        placeholder="Dodaj produkt do listy... (np. Mleko, Masło, Jajka)"
                        autocomplete="off"
                    >
                    <button class="add-item-form__submit" type="submit">+ Dodaj</button>
                </form>

                <div class="filter-pills" role="group" aria-label="Filtruj kategorie">
                    <?php foreach (($filters ?? []) as $filter): ?>
                        <?php $isActiveFilter = (bool) ($filter['active'] ?? false); ?>
                        <button
                            class="filter-pill<?= $isActiveFilter ? ' filter-pill--active' : '' ?>"
                            type="button"
                            aria-pressed="<?= $isActiveFilter ? 'true' : 'false' ?>"
                        >
                            <?= htmlspecialchars((string) ($filter['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </section>

            <div class="category-list">
                <?php foreach (($categories ?? []) as $category): ?>
                    <section class="category-section" aria-label="<?= htmlspecialchars((string) ($category['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        <div class="category-section__header">
                            <div class="category-section__title-wrap">
                                <span class="category-section__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22c4.5 0 8-3.5 8-8 0-5.5-5.5-11-8-12-2.5 1-8 6.5-8 12 0 4.5 3.5 8 8 8Z"></path>
                                        <circle cx="12" cy="14" r="3"></circle>
                                    </svg>
                                </span>
                                <h2 class="category-section__title">
                                    <?= htmlspecialchars((string) ($category['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                </h2>
                            </div>

                            <span class="category-section__progress">
                                <?= htmlspecialchars((string) ($category['progress'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>

                        <div class="category-section__items">
                            <?php foreach (($category['items'] ?? []) as $item): ?>
                                <?php require __DIR__ . '/components/list-item-tile.html.php'; ?>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="home-sidebar">
            <?php require __DIR__ . '/components/tip-card.html.php'; ?>

            <aside class="savings-card" aria-labelledby="savings-heading">
                <div class="savings-card__header">
                    <div>
                        <h2 id="savings-heading" class="savings-card__title">Inteligentne Oszczędności</h2>
                        <p class="savings-card__subtitle">Podsumowanie koszyka</p>
                    </div>
                    <span class="savings-card__icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"></path>
                            <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
                        </svg>
                    </span>
                </div>

                <div class="savings-card__rows">
                    <div class="savings-card__row">
                        <span>Wartość regularna:</span>
                        <span class="savings-card__row-value">
                            <?= htmlspecialchars((string) ($summary['regular_total'] ?? '0,00 zł'), ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>
                    <div class="savings-card__row savings-card__row--discount">
                        <span>Wykryte rabaty:</span>
                        <span class="savings-card__row-value">
                            <?= htmlspecialchars((string) ($summary['discount_total'] ?? '0,00 zł'), ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>
                </div>

                <div class="savings-card__total">
                    <span class="savings-card__total-label">Do zapłaty:</span>
                    <span class="savings-card__total-value">
                        <?= htmlspecialchars((string) ($summary['final_total'] ?? '0,00 zł'), ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>
            </aside>
        </div>
    </div>
</main>
