<?php

declare(strict_types=1);

/**
 * Reusable shopping list item tile (ListTile).
 *
 * Expected $item keys:
 * - id (int|string)
 * - name (string)
 * - quantity (string)
 * - price (string)
 * - old_price (?string)
 * - promo_label (?string)
 * - promo_variant (string: 'error'|'success')
 * - purchased (bool)
 * - is_discounted (bool)
 */
$itemId = 'item-' . htmlspecialchars((string) ($item['id'] ?? '0'), ENT_QUOTES, 'UTF-8');
$isPurchased = (bool) ($item['purchased'] ?? false);
$isDiscounted = (bool) ($item['is_discounted'] ?? false);
$promoLabel = $item['promo_label'] ?? null;
$promoVariant = ($item['promo_variant'] ?? 'error') === 'success' ? 'success' : 'error';
$oldPrice = $item['old_price'] ?? null;
?>
<article class="list-tile<?= $isPurchased ? ' list-tile--purchased' : '' ?>">
    <div class="list-tile__lead">
        <input
            class="list-tile__checkbox"
            type="checkbox"
            id="<?= $itemId ?>"
            <?= $isPurchased ? 'checked' : '' ?>
        >
        <div class="list-tile__content">
            <label class="list-tile__title" for="<?= $itemId ?>">
                <?= htmlspecialchars((string) ($item['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
            </label>
            <div class="list-tile__meta">
                <span class="list-tile__quantity">
                    <?= htmlspecialchars((string) ($item['quantity'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                </span>

                <?php if ($isPurchased): ?>
                    <span class="list-tile__dot" aria-hidden="true">&bull;</span>
                    <span class="list-tile__status">Kupiono &#10003;</span>
                <?php elseif ($promoLabel !== null && $promoLabel !== ''): ?>
                    <span class="list-tile__dot" aria-hidden="true">&bull;</span>
                    <span class="promo-badge promo-badge--<?= $promoVariant ?>">
                        <?= htmlspecialchars((string) $promoLabel, ENT_QUOTES, 'UTF-8') ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="list-tile__pricing">
        <span class="list-tile__price<?= $isDiscounted && !$isPurchased ? ' list-tile__price--promo' : '' ?>">
            <?= htmlspecialchars((string) ($item['price'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
        </span>
        <?php if ($oldPrice !== null && $oldPrice !== '' && !$isPurchased): ?>
            <span class="list-tile__old-price">
                <?= htmlspecialchars((string) $oldPrice, ENT_QUOTES, 'UTF-8') ?>
            </span>
        <?php endif; ?>
    </div>
</article>
