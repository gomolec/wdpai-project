<?php

declare(strict_types=1);

$activeNavigation = $activeNavigation ?? 'lists';
$navItems = [
    [
        'id' => 'lists',
        'label' => 'Lista zakupów',
        'href' => '/',
        'icon' => 'cart',
    ],
    [
        'id' => 'promotions',
        'label' => 'Promocje',
        'href' => '/projects',
        'icon' => 'tag',
    ],
    [
        'id' => 'saved',
        'label' => 'Zapisane',
        'href' => '/projects',
        'icon' => 'bookmark',
    ],
];
?>
<header class="site-header">
    <div class="site-header__inner">
        <div class="site-header__left">
            <button
                class="icon-btn site-header__menu-btn"
                type="button"
                aria-label="Otwórz menu nawigacji"
                aria-expanded="false"
                aria-controls="mobile-nav-drawer"
                data-menu-toggle
            >
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 7h16"></path>
                    <path d="M4 12h16"></path>
                    <path d="M4 17h16"></path>
                </svg>
            </button>

            <a class="brand" href="/">
                <span class="brand__avatar" aria-hidden="true">
                    <img
                        src="/assets/images/pan-maselko-maskotka-wita.png"
                        alt=""
                        width="36"
                        height="36"
                    >
                </span>
                <span class="brand__name">Pan Masełko</span>
            </a>
        </div>

        <nav class="site-nav" aria-label="Nawigacja główna">
            <ul class="site-nav__list">
                <?php foreach ($navItems as $navItem): ?>
                    <?php $isActive = $activeNavigation === $navItem['id']; ?>
                    <li class="site-nav__item">
                        <a
                            class="site-nav__link<?= $isActive ? ' site-nav__link--active' : '' ?>"
                            href="<?= htmlspecialchars($navItem['href'], ENT_QUOTES, 'UTF-8') ?>"
                            <?= $isActive ? 'aria-current="page"' : '' ?>
                        >
                            <?php if ($navItem['icon'] === 'cart'): ?>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="8" cy="21" r="1"></circle>
                                    <circle cx="19" cy="21" r="1"></circle>
                                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                                </svg>
                            <?php elseif ($navItem['icon'] === 'tag'): ?>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"></path>
                                    <path d="M7 7h.01"></path>
                                </svg>
                            <?php else: ?>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"></path>
                                </svg>
                            <?php endif; ?>
                            <span><?= htmlspecialchars($navItem['label'], ENT_QUOTES, 'UTF-8') ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="site-header__actions">
            <button class="icon-btn" type="button" aria-label="Szukaj" title="Szukaj">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
            </button>

            <button class="icon-btn icon-btn--badge" type="button" aria-label="Powiadomienia" title="Powiadomienia">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                </svg>
                <span class="icon-btn__dot" aria-hidden="true"></span>
            </button>

            <button
                class="icon-btn theme-toggle"
                type="button"
                aria-label="Włącz ciemny motyw"
                aria-pressed="false"
                title="Zmień motyw"
            >
                <svg
                    class="theme-toggle__icon theme-toggle__icon--moon"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
                <svg
                    class="theme-toggle__icon theme-toggle__icon--sun"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <circle cx="12" cy="12" r="4"></circle>
                    <path d="M12 2v2"></path>
                    <path d="M12 20v2"></path>
                    <path d="m4.93 4.93 1.41 1.41"></path>
                    <path d="m17.66 17.66 1.41 1.41"></path>
                    <path d="M2 12h2"></path>
                    <path d="M20 12h2"></path>
                    <path d="m6.34 17.66-1.41 1.41"></path>
                    <path d="m19.07 4.93-1.41 1.41"></path>
                </svg>
            </button>

            <a class="profile-btn" href="/login" aria-label="Zaloguj się" title="Zaloguj się">
                <span class="profile-btn__circle" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M20 21a8 8 0 0 0-16 0"></path>
                    </svg>
                </span>
            </a>
        </div>
    </div>

    <nav
        id="mobile-nav-drawer"
        class="mobile-nav"
        aria-label="Nawigacja mobilna"
        hidden
    >
        <ul class="mobile-nav__list">
            <?php foreach ($navItems as $navItem): ?>
                <?php $isActive = $activeNavigation === $navItem['id']; ?>
                <li>
                    <a
                        class="mobile-nav__link<?= $isActive ? ' mobile-nav__link--active' : '' ?>"
                        href="<?= htmlspecialchars($navItem['href'], ENT_QUOTES, 'UTF-8') ?>"
                        <?= $isActive ? 'aria-current="page"' : '' ?>
                    >
                        <?= htmlspecialchars($navItem['label'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <li>
                <a
                    class="mobile-nav__link<?= $activeNavigation === 'login' ? ' mobile-nav__link--active' : '' ?>"
                    href="/login"
                    <?= $activeNavigation === 'login' ? 'aria-current="page"' : '' ?>
                >
                    Zaloguj się
                </a>
            </li>
        </ul>
    </nav>
</header>
