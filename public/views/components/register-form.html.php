<?php

declare(strict_types=1);

/**
 * Reusable registration form component.
 *
 * Expected variables:
 * - $csrfToken (string)
 * - $name (string)
 * - $email (string)
 * - $errors (array<string, string>)
 * - $action (string, optional, defaults to '/register')
 */
$formAction = (string) ($action ?? '/register');
$nameValue = (string) ($name ?? '');
$emailValue = (string) ($email ?? '');
$formErrors = is_array($errors ?? null) ? $errors : [];
$tokenValue = (string) ($csrfToken ?? '');

$generalError = $formErrors['general'] ?? null;
$nameError = $formErrors['name'] ?? null;
$emailError = $formErrors['email'] ?? null;
$passwordError = $formErrors['password'] ?? null;
$passwordConfirmError = $formErrors['password_confirm'] ?? null;
?>
<form class="auth-form" method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8') ?>" novalidate>
    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($tokenValue, ENT_QUOTES, 'UTF-8') ?>"
    >

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
        <label class="auth-field__label" for="register-name">Imię lub nazwa użytkownika</label>
        <input
            id="register-name"
            class="auth-field__input<?= $nameError !== null ? ' auth-field__input--error' : '' ?>"
            type="text"
            name="name"
            value="<?= htmlspecialchars($nameValue, ENT_QUOTES, 'UTF-8') ?>"
            placeholder="np. Jan Kowalski"
            autocomplete="name"
            required
            <?= $nameError !== null ? 'aria-invalid="true" aria-describedby="register-name-error"' : '' ?>
        >
        <?php if ($nameError !== null): ?>
            <p id="register-name-error" class="auth-field__error" role="alert">
                <?= htmlspecialchars((string) $nameError, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="auth-field">
        <label class="auth-field__label" for="register-email">Adres e-mail</label>
        <input
            id="register-email"
            class="auth-field__input<?= $emailError !== null ? ' auth-field__input--error' : '' ?>"
            type="email"
            name="email"
            value="<?= htmlspecialchars($emailValue, ENT_QUOTES, 'UTF-8') ?>"
            placeholder="np. jan@panmaselko.pl"
            autocomplete="email"
            required
            <?= $emailError !== null ? 'aria-invalid="true" aria-describedby="register-email-error"' : '' ?>
        >
        <?php if ($emailError !== null): ?>
            <p id="register-email-error" class="auth-field__error" role="alert">
                <?= htmlspecialchars((string) $emailError, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="auth-field">
        <label class="auth-field__label" for="register-password">Hasło</label>
        <input
            id="register-password"
            class="auth-field__input<?= $passwordError !== null ? ' auth-field__input--error' : '' ?>"
            type="password"
            name="password"
            placeholder="Minimum 8 znaków"
            autocomplete="new-password"
            required
            <?= $passwordError !== null ? 'aria-invalid="true" aria-describedby="register-password-error register-password-hint"' : 'aria-describedby="register-password-hint"' ?>
        >
        <p id="register-password-hint" class="auth-field__hint">
            Użyj co najmniej 8 znaków.
        </p>
        <?php if ($passwordError !== null): ?>
            <p id="register-password-error" class="auth-field__error" role="alert">
                <?= htmlspecialchars((string) $passwordError, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="auth-field">
        <label class="auth-field__label" for="register-password-confirm">Powtórz hasło</label>
        <input
            id="register-password-confirm"
            class="auth-field__input<?= $passwordConfirmError !== null ? ' auth-field__input--error' : '' ?>"
            type="password"
            name="password_confirm"
            placeholder="Wpisz hasło ponownie"
            autocomplete="new-password"
            required
            <?= $passwordConfirmError !== null ? 'aria-invalid="true" aria-describedby="register-password-confirm-error"' : '' ?>
        >
        <?php if ($passwordConfirmError !== null): ?>
            <p id="register-password-confirm-error" class="auth-field__error" role="alert">
                <?= htmlspecialchars((string) $passwordConfirmError, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>
    </div>

    <button class="auth-form__submit" type="submit">Załóż konto</button>
</form>
