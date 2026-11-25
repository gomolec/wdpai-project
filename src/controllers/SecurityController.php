<?php

declare(strict_types=1);

namespace App\Controller;

final class SecurityController extends AppController
{
    private const DEMO_EMAIL = 'demo@panmaselko.pl';
    // Tymczasowy hash hasła "haslo123" do czasu podpięcia UserRepository i PostgreSQL
    private const DEMO_PASSWORD_HASH = '$2y$10$f4pQ4Y0B6hX7M4o8C5o5xO3p7kK1xJ9z2V8w4L6mN2pQ5rS8tU1vW';

    public function login(): void
    {
        $this->ensureSessionStarted();

        $email = '';
        $errors = [];
        $successMessage = null;

        if (!empty($_SESSION['flash_success']) && is_string($_SESSION['flash_success'])) {
            $successMessage = $_SESSION['flash_success'];
            unset($_SESSION['flash_success']);
        }

        if ($this->isPost()) {
            if (!$this->isValidCsrfToken()) {
                http_response_code(403);
                $errors['general'] = 'Sesja formularza wygasła. Odśwież stronę i spróbuj ponownie.';
            } else {
                $email = trim((string) ($_POST['email'] ?? ''));
                $password = (string) ($_POST['password'] ?? '');

                if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    $errors['email'] = 'Podaj poprawny adres e-mail.';
                }

                if ($password === '') {
                    $errors['password'] = 'Podaj hasło.';
                }

                if ($errors === []) {
                    if ($this->verifyCredentials($email, $password)) {
                        session_regenerate_id(true);
                        $_SESSION['user'] = [
                            'email' => $email,
                            'role' => 'user',
                        ];
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                        header('Location: /', true, 303);
                        exit;
                    }

                    http_response_code(401);
                    $errors['general'] = 'Nieprawidłowy adres e-mail lub hasło.';
                } else {
                    http_response_code(422);
                }
            }
        }

        $this->render('login', [
            'pageTitle' => 'Pan Masełko — Logowanie',
            'activeNavigation' => 'login',
            'csrfToken' => $this->getCsrfToken(),
            'email' => $email,
            'errors' => $errors,
            'successMessage' => $successMessage,
        ]);
    }

    public function register(): void
    {
        $this->ensureSessionStarted();

        $name = '';
        $email = '';
        $errors = [];

        if ($this->isPost()) {
            if (!$this->isValidCsrfToken()) {
                http_response_code(403);
                $errors['general'] = 'Sesja formularza wygasła. Odśwież stronę i spróbuj ponownie.';
            } else {
                $name = trim((string) ($_POST['name'] ?? ''));
                $email = trim((string) ($_POST['email'] ?? ''));
                $password = (string) ($_POST['password'] ?? '');
                $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

                if ($name === '' || mb_strlen($name) < 2) {
                    $errors['name'] = 'Imię lub nazwa użytkownika musi mieć co najmniej 2 znaki.';
                } elseif (mb_strlen($name) > 80) {
                    $errors['name'] = 'Imię lub nazwa użytkownika może mieć maksymalnie 80 znaków.';
                }

                if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    $errors['email'] = 'Podaj poprawny adres e-mail.';
                } elseif ($this->emailExists($email)) {
                    $errors['email'] = 'Konto z tym adresem e-mail już istnieje.';
                }

                if (strlen($password) < 8) {
                    $errors['password'] = 'Hasło musi mieć co najmniej 8 znaków.';
                }

                if ($passwordConfirm === '') {
                    $errors['password_confirm'] = 'Potwierdź hasło.';
                } elseif ($password !== $passwordConfirm) {
                    $errors['password_confirm'] = 'Hasła nie są takie same.';
                }

                if ($errors === []) {
                    // TODO: Zastąpić zapisem w UserRepository (PostgreSQL) w transakcji z rolą 'user'.
                    $normalizedEmail = strtolower($email);
                    $_SESSION['demo_users'][$normalizedEmail] = [
                        'name' => $name,
                        'email' => $email,
                        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                        'role' => 'user',
                    ];
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    $_SESSION['flash_success'] = 'Konto zostało utworzone. Możesz się teraz zalogować.';

                    header('Location: /login', true, 303);
                    exit;
                }

                http_response_code(422);
            }
        }

        $this->render('register', [
            'pageTitle' => 'Pan Masełko — Rejestracja',
            'activeNavigation' => 'register',
            'csrfToken' => $this->getCsrfToken(),
            'name' => $name,
            'email' => $email,
            'errors' => $errors,
        ]);
    }

    private function emailExists(string $email): bool
    {
        $normalizedEmail = strtolower($email);

        if (hash_equals(strtolower(self::DEMO_EMAIL), $normalizedEmail)) {
            return true;
        }

        $demoUsers = $_SESSION['demo_users'] ?? [];

        return is_array($demoUsers) && isset($demoUsers[$normalizedEmail]);
    }

    private function verifyCredentials(string $email, string $password): bool
    {
        $normalizedEmail = strtolower($email);
        $demoUsers = $_SESSION['demo_users'] ?? [];

        if (is_array($demoUsers) && isset($demoUsers[$normalizedEmail]['password_hash'])) {
            return password_verify($password, (string) $demoUsers[$normalizedEmail]['password_hash']);
        }

        // TODO: Zastąpić zapytaniem do UserRepository (PostgreSQL) po utworzeniu schematu bazy.
        $demoHash = self::DEMO_PASSWORD_HASH;
        if (!password_verify('haslo123', $demoHash)) {
            $demoHash = password_hash('haslo123', PASSWORD_DEFAULT);
        }

        $isEmailMatch = hash_equals(strtolower(self::DEMO_EMAIL), $normalizedEmail);
        $isPasswordMatch = password_verify($password, $demoHash);

        return $isEmailMatch && $isPasswordMatch;
    }

    private function isValidCsrfToken(): bool
    {
        $submittedToken = (string) ($_POST['csrf_token'] ?? '');
        $sessionToken = (string) ($_SESSION['csrf_token'] ?? '');

        return $sessionToken !== '' && hash_equals($sessionToken, $submittedToken);
    }

    private function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();
    }

    private function getCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}
