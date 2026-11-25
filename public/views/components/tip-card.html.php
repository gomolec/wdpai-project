<?php

declare(strict_types=1);

/**
 * Reusable mascot tip card ("Wskazówka dnia" / "Wskazówka od Pana Masełka!").
 *
 * Expected $tip keys:
 * - eyebrow (string)
 * - title_desktop (string)
 * - title_mobile (string)
 * - body (string)
 */
$eyebrow = (string) ($tip['eyebrow'] ?? 'Wskazówka dnia');
$titleDesktop = (string) ($tip['title_desktop'] ?? 'Kup masło w 3-paku!');
$titleMobile = (string) ($tip['title_mobile'] ?? 'Wskazówka od Pana Masełka!');
$body = (string) ($tip['body'] ?? '');
?>
<aside class="tip-card" aria-label="Wskazówka od Pana Masełka">
    <div class="tip-card__avatar" aria-hidden="true">
        <img
            src="/assets/images/pan-maselko-maskotka-wpada-na-pomysl.png"
            alt=""
            width="48"
            height="48"
        >
    </div>
    <div class="tip-card__body">
        <p class="tip-card__eyebrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"></path>
                <path d="M9 18h6"></path>
                <path d="M10 22h4"></path>
            </svg>
            <span><?= htmlspecialchars($eyebrow, ENT_QUOTES, 'UTF-8') ?></span>
        </p>
        <h2 class="tip-card__title tip-card__title--desktop">
            <?= htmlspecialchars($titleDesktop, ENT_QUOTES, 'UTF-8') ?>
        </h2>
        <h2 class="tip-card__title tip-card__title--mobile">
            <?= htmlspecialchars($titleMobile, ENT_QUOTES, 'UTF-8') ?>
        </h2>
        <p class="tip-card__text">
            <?= htmlspecialchars($body, ENT_QUOTES, 'UTF-8') ?>
        </p>
    </div>
</aside>
