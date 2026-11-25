<?php

declare(strict_types=1);

/**
 * Reusable login form component.
 *
 * Expected variables:
 * - $csrfToken (string)
 * - $email (string)
 * - $errors (array<string, string>)
 * - $action (string, optional, defaults to '/login')
 */
$formAction = (string) ($action ?? '/login');
$emailValue = (string) ($email ?? '');
$formErrors = is_array($errors ?? null) ? $errors : [];
$tokenValue = (string) ($csrfToken ?? '');

$generalError = $formErrors['general'] ?? null;
$emailError = $formErrors['email'] ?? null;
$passwordError = $formErrors['password'] ?? null;
$successText = isset($successMessage) && is_string($successMessage) ? $successMessage : null;
?>
<form class="auth-form" method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8') ?>" novalidate>
    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($tokenValue, ENT_QUOTES, 'UTF-8') ?>"
    >

    <?php if ($successText !== null && $successText !== ''): ?>
        <div class="auth-alert auth-alert--success" role="status">
            <svg class="auth-alert__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="m9 12 2 2 4-4"></path>
            </svg>
            <span><?= htmlspecialchars($successText, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
    <?php endif; ?>

    <?php if ($generalError !== null && $generalError !== ''): ?>
        <div class="auth-alert" role="alert">
            <svg class="auth-alert__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span><?= htmlspecialchars((string) $generalError, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
    <?php endif; ?>

    <div class="auth-field">
        <label class="auth-field__label" for="login-email">Adres e-mail</label>
        <input
            id="login-email"
            class="auth-field__input<?= $emailError !== null ? ' auth-field__input--error' : '' ?>"
            type="email"
            name="email"
            value="<?= htmlspecialchars($emailValue, ENT_QUOTES, 'UTF-8') ?>"
            placeholder="np. jan@panmaselko.pl"
            autocomplete="email"
            required
            <?= $emailError !== null ? 'aria-invalid="true" aria-describedby="login-email-error"' : '' ?>
        >
        <?php if ($emailError !== null): ?>
            <p id="login-email-error" class="auth-field__error" role="alert">
                <?= htmlspecialchars((string) $emailError, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="auth-field">
        <label class="auth-field__label" for="login-password">Hasło</label>
        <input
            id="login-password"
            class="auth-field__input<?= $passwordError !== null ? ' auth-field__input--error' : '' ?>"
            type="password"
            name="password"
            placeholder="Wpisz hasło"
            autocomplete="current-password"
            required
            <?= $passwordError !== null ? 'aria-invalid="true" aria-describedby="login-password-error"' : '' ?>
        >
        <?php if ($passwordError !== null): ?>
            <p id="login-password-error" class="auth-field__error" role="alert">
                <?= htmlspecialchars((string) $passwordError, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>
    </div>

    <button class="auth-form__submit" type="submit">Zaloguj się</button>
</form>
