# AGENTS.md — Pan Masełko (WDPAI)

**Pan Masełko** — responsywna aplikacją webową do zarządzania współdzielonymi listami zakupów oraz śledzenia promocji dyskontowych.

---

## 1. Kontekst projektu i ograniczenia

- **Zakaz frameworków:** Cały backend i frontend muszą powstać w **czystym PHP 8.3 (OOP), czystym HTML5, czystym CSS3 oraz czystym JavaScript (ES6+)**.
  - **NIE instaluj** Laravela, Symfony, Reacta, Vue, Angulara, Tailwinda ani Bootstrapa.
  - Composer służy wyłącznie do autoloadingu PSR-4 oraz dopuszczalnych lekkich bibliotek narzędziowych (np. `phpmailer/phpmailer`, `phpunit/phpunit`, `vlucas/phpdotenv`).
- **Zgodność ze strukturą prowadzącego:** Należy bezwzględnie zachować istniejący szkielet repozytorium (`public/`, `src/`, `docker/`, `composer.json`, `docker-compose.yaml`) i rozwijać go w tym samym stylu.

---

## 2. Aktualna struktura repozytorium i wykryte uwagi techniczne

```text
.
├── docker/
│   ├── db/
│   │   ├── Dockerfile              # postgres:16-alpine
│   │   └── init/                   # numerowane skrypty SQL (001_..., 002_..., 003_...)
│   ├── nginx/
│   │   ├── Dockerfile              # nginx:stable-alpine
│   │   └── nginx.conf              # root /app/public, front-controller -> /index.php?$query_string
│   └── php/
│       └── Dockerfile              # php:8.3-fpm-alpine (pdo_pgsql, pgsql, gd, zip, opcache, bcmath, composer)
├── public/                         # JEDYNY publicznie dostępny katalog (document root Nginx)
│   ├── index.php                   # Front controller i definicje wszystkich tras
│   ├── assets/images/              # Statyczne grafiki (w tym maskotka Pana Masełka)
│   ├── uploads/                    # Pliki wrzucane na serwer (w bazie tylko względny URL)
│   ├── css/
│   │   ├── style.css               # Globalne tokeny kolorów (Light/Dark) i style bazowe
│   │   └── <widok>.css             # Style dedykowane dla konkretnych podstron (np. nav.css, login.css)
│   ├── js/
│   │   └── theme.js                # Obsługa Dark/Light mode + skrypty Fetch API (AJAX)
│   └── views/
│       ├── layout.html.php         # Wspólny szkielet HTML (head, anty-FOUC, theme-toggle, <?= $content ?>)
│       ├── components/             # Powtarzalne fragmenty UI (np. nav.html.php, sidebar.html.php)
│       └── <widok>.html.php        # Szablony poszczególnych podstron
├── src/
│   ├── Routing.php                 # Rejestracja i obsługa tras (regex)
│   ├── Database.php                # Singleton połączenia PDO z PostgreSQL
│   ├── controllers/
│   │   ├── AppController.php       # Bazowy kontroler (isGet, isPost, render z layoutem)
│   │   └── *Controller.php         # Kontrolery domenowe (App\Controller\*)
│   ├── Repository/
│   │   ├── Repository.php          # Bazowa klasa repozytorium z wstrzykiwanym ?PDO
│   │   └── *Repository.php         # Zapytania SQL do PostgreSQL (App\Repository\*)
│   ├── Model/                      # Encje / DTO (App\Model\*)
│   └── Service/                    # Logika biznesowa (kategoryzacja, upload, mailer, i18n)
├── tests/                          # Testy jednostkowe (PHPUnit)
├── bin/                            # Skrypty CLI (np. CRON do alertów promocyjnych)
├── composer.json                   # PSR-4: App\ -> src/, App\Controller\ -> src/controllers/
├── docker-compose.yaml             # server (8080), php, db (5433), mailpit (8025), pgadmin (5050)
└── .env                            # Sekrety i konfiguracja (ignorowane przez git)
```

### Znane błędy w starterze do naprawienia w pierwszej kolejności

1. **Sieci w `docker-compose.yaml`:** Kontener `php` jest podpięty tylko do `app-network`, natomiast `db` i `mailpit` są w `pg-network`. Aby PHP mogło łączyć się z PostgreSQL i Mailpitem, należy dodać `pg-network` do serwisu `php`.
2. **Składnia CSS w `public/css/style.css`:** Zmienne kolorów są błędnie zapisane jako `var(#f2d258)` zamiast `#f2d258`. Należy usunąć błędne `var(...)` z definicji tokenów w `:root` oraz `[data-theme="dark"]`.
3. **Placeholder w `public/js/theme.js`:** Usunąć `alert("Witaj na PK");` i wdrożyć właściwą obsługę motywu.
4. **Kontroler testowy:** `ProjectController` oraz widoki `projects.html.php` / `project.html.php` pochodzą ze szkieletu zajęciowego — docelowo zastąpić je domeną aplikacji **Pan Masełko**.

---

## 3. Domena i główne funkcjonalności — „Pan Masełko”

### Role użytkowników (RBAC)

1. **`user` (Użytkownik):**
   - Rejestracja, logowanie, wylogowywanie, zarządzanie profilem.
   - Tworzenie i współdzielenie list zakupowych z innymi użytkownikami (relacja wiele-do-wielu, uprawnienia właściciela vs współtwórcy).
   - Dodawanie, edycja, oznaczanie jako kupione i usuwanie produktów na liście.
   - Automatyczna kategoryzacja produktów (np. Nabiał, Owoce i warzywa, Pieczywo, Chemia, Inne) — poprzez lekkie zapytanie do API AI z fallbackiem słownikowym po stronie backendu.
   - Automatyczne wykrywanie i oznaczanie (badge/ikona), czy dla produktu na liście istnieje aktywna promocja w bazie.
   - Przeglądanie dopasowanych promocji (kafelki ze zdjęciem, logo/nazwą sklepu, ceną i datą ważności) oraz zapisywanie ulubionych okazji.
2. **`admin` (Administrator):**
   - Dostęp do dashboardu ze statystykami (liczba aktywnych użytkowników, liczba promocji w bazie, liczba utworzonych list zakupowych).
   - Zarządzanie ofertami promocyjnymi (widok listy/tabeli z wyszukiwaniem i filtrowaniem).
   - Formularz dodawania/edycji oferty promocyjnej: upload zdjęcia produktu (zapis na serwerze, w bazie tylko URL/ścieżka), wybór sklepu, tytuł, cena, data obowiązywania, opis.

### Główne ekrany (każdy responsywny: Desktop + Mobile)

1. **Ekran główny (Lista zakupów):** Podział produktów na kategorie, przycisk `+`, ikony aktywnych promocji przy produktach, przełącznik współdzielonych list.
2. **Wyszukiwarka / lista promocji:** Siatka kafelków ofert (zdjęcie, sklep, nazwa, cena, data końca).
3. **Zapisane promocje:** Siatka ulubionych/zapisanych ofert użytkownika.
4. **Dashboard admina:** Karty ze statystykami systemowymi.
5. **Panel admina — lista promocji:** Kompaktowy widok listy (`ListTile`/tabela) z filtrowaniem i przyciskiem `+`.
6. **Formularz dodawania/edycji promocji:** Upload zdjęcia, sklep, tytuł, cena, data ważności, opis.
7. **Ekrany autoryzacji:** Logowanie i rejestracja.

---

## 4. Design System i Frontend

- **Styl:** Czysty, prosty w implementacji w czystym HTML/CSS, przyjazny charakter inspirowany Duolingo z maskotką Pana Masełka.
- **Paleta kolorów (oparta o tokeny z `public/css/style.css`):**
  - **Light Mode:** Primary `#f2d258` (maślany pastel), Secondary/Accent `#2050cd` (niebieski/błękitny akcent CTA), Background `#faf8f3`, Surface `#ffffff`, Text `#181c22`.
  - **Dark Mode:** Primary `#e1b53e`, Secondary/Accent `#3662e3`, Background `#0d1325`, Surface `#1a2233`, Text `#ffffff`.
- **Tryb Dark/Light (wymaganie obowiązkowe):**
  - Atrybut `data-theme="light|dark"` na elemencie `<html>`.
  - Zapis wyboru w `localStorage`.
  - Obsługa preferencji systemowych (`prefers-color-scheme`).
  - **Skrypt anty-FOUC** umieszczony synchronicznie w `<head>` przed załadowaniem CSS.
- **AJAX / Fetch API:** Asynchroniczne akcje (np. dodawanie/odznaczanie produktu na liście, zapisywanie promocji, filtrowanie) realizowane przez `fetch()` bez przeładowania strony, z endpointami zwracającymi JSON.
- **Dostępność (WCAG 2.1 AA):** Semantyczny HTML (`<header>`, `<nav>`, `<main>`, `<section>`, `<button>`), poprawne etykiety `<label>`, atrybuty `aria-*`, nawigacja klawiaturą, odpowiedni kontrast kolorów (weryfikacja pod raport WCAG do `README.md`).
- **Wielojęzyczność (i18n):** Co najmniej **PL** i **EN** (np. pliki słownikowe PHP/JSON + przełącznik języka w sesji/cookie).

---

## 5. Architektura MVC, Routing i Renderowanie Podstron

Przepływ żądania:

```text
przeglądarka -> Nginx -> public/index.php -> Routing -> Controller -> Repository/Service -> View (w layout.html.php) -> HTML/JSON
```

### Front Controller i Routing (`public/index.php`, `src/Routing.php`)

- Wszystkie trasy rejestruj wyłącznie w `public/index.php`:
  ```php
  $routing->get('/lists', ListController::class, 'index');$routing->get('/lists/{id}', ListController::class, 'show');
  $routing->get('/login', SecurityController::class, 'login');$routing->post('/login', SecurityController::class, 'login');
  ```
- Router dopasowuje parametry `{name}` jako cyfry (`(?P<name>\d+)`), konwertuje je na `int` i przekazuje do akcji kontrolera jako nazwane argumenty (`$controller->{$action}(...$parameters)`).
- Query params obsługuj w warstwie wejścia (kontrolerze) z walidacją.
- Dla nieznanych tras `public/index.php` ustawia `http_response_code(404)` i wywołuje `(new DefaultController())->notFound()`.
- **Nie dodawaj logiki biznesowej do `Routing.php`.**

### Kontrolery (`src/controllers/AppController.php`)

- Kontrolery w `src/controllers/` używają namespace `App\Controller` i dziedziczą po `AppController`.
- `AppController` udostępnia:
  - `$this->isGet()` / `$this->isPost()` — rozróżnianie metod HTTP w jednej akcji (np. `SecurityController::login()`).
  - `$this->render(string $template, array $variables = [])` — renderuje `public/views/<template>.html.php` do bufora (`ob_start()` / `ob_get_clean()`) jako `$content` i wstrzykuje go do `public/views/layout.html.php`.
- Kontroler musi być cienki: walidacja wejścia, wywołanie serwisu/repozytorium, przekazanie danych do `render()` lub zwrot JSON dla Fetch API.

### Osobne strony, Layout i Komponenty (`public/views/`)

- **`layout.html.php`** zawiera cały szkielet HTML (`<!doctype html>`, `<head>`, globalny `/css/style.css`, synchroniczny skrypt anty-FOUC, przycisk `.theme-toggle`, `/js/theme.js` oraz `<?= $content ?>`).
- **Dedykowane arkusze CSS per strona:** Widok może na górze pliku zadeklarować tablicę `$stylesheets`, którą `layout.html.php` automatycznie podepnie w `<head>`:
  ```php
  <?php
  $stylesheets = ['/css/nav.css', '/css/lists.css'];$activeNavigation = 'lists';
  ?>
  <?php require __DIR__ . '/components/nav.html.php'; ?>
  <main>...</main>
  ```
- **Komponenty:** Powtarzalne elementy (nawigacja, sidebar admina, karty promocji) wydzielaj do `public/views/components/*.html.php` i dołączaj przez `require __DIR__ . '/components/...'`.
- **Escaping (XSS):** Każda wartość z bazy lub od użytkownika MUSI być escapowana:
  ```php
  <?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') ?>
  ```

---

## 6. Baza danych (PostgreSQL) i Warstwa Repository

- **`src/Database.php`:** Singleton połączenia `PDO` z PostgreSQL pobierający konfigurację z `getenv()` (`DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`), z opcjami `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`, `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC`, `PDO::ATTR_EMULATE_PREPARES => false`.
- **`src/Repository/Repository.php`:** Abstrakcyjna klasa bazowa w `App\Repository`, przyjmująca `?PDO $connection = null` w konstruktorze (pod testy jednostkowe) i domyślnie korzystająca z `Database::getInstance()->getConnection()`.
- **Wymagania bazodanowe:**
  - Znormalizowany schemat (tabele m.in. `users`, `roles`, `shopping_lists`, `list_members`, `categories`, `list_items`, `stores`, `promotions`, `saved_promotions`) + diagram **ERD w Mermaid** w `README.md`.
  - **Widoki (`VIEW`)**, **funkcje i wyzwalacze (`FUNCTION` + `TRIGGER`)**, **transakcje (`BEGIN`/`COMMIT`/`ROLLBACK`)**, jawne **akcje na referencjach** (`ON DELETE CASCADE` / `ON DELETE SET NULL`).
  - Numerowane skrypty w `docker/db/init/` oraz pełny eksport bazy do pliku `.sql`.

### Zalecana rozbudowa katalogu `src/` (MVC + Repository)

```text
src/
├── Routing.php                 # Rozszerzyć o obsługę metod (GET, POST, PUT/DELETE lub _method), query params, regexy
├── Core/                       # Database (PDO singleton/factory), Session, Csrf, Translator, Env, Mailer
├── controllers/                # Kontrolery dziedziczące po AppController (Auth, List, Promotion, Admin, Api)
├── models/                     # Encje / DTO (User, ShoppingList, ListItem, Category, Store, Promotion)
├── repositories/               # Warstwa dostępu do PostgreSQL (wyłącznie PDO + prepared statements)
└── services/                   # Logika biznesowa (kategoryzacja, upload plików, wysyłka maili, raporty)
```

### Baza danych PostgreSQL (wymagania obowiązkowe)

- **Złożoność:** Znormalizowany schemat relacyjny (m.in. `users`, `roles`, `shopping_lists`, `list_members`, `categories`, `list_items`, `stores`, `promotions`, `saved_promotions`).
- **Obiekty bazodanowe:**
  - **Widoki (`VIEW`):** np. widok aktywnych promocji ze sklepami, widok statystyk dla dashboardu admina.
  - **Funkcje i wyzwalacze (`FUNCTION` + `TRIGGER`):** np. automatyczna aktualizacja `updated_at`, automatyczne oznaczanie/przeliczanie dopasowanych promocji lub szacowanego kosztu listy.
  - **Transakcje (`BEGIN` / `COMMIT` / `ROLLBACK`):** przy operacjach wieloetapowych (np. tworzenie listy z przypisaniem właściciela, rejestracja użytkownika z rolą).
  - **Akcje na referencjach:** jawne `ON DELETE CASCADE` / `ON DELETE SET NULL` / `ON UPDATE CASCADE`.
- **Eksport:** Aktualny zrzut schematu i danych początkowych w pliku `.sql` (np. `docker/db/init.sql` lub `database/dump.sql`) oraz diagram **ERD w Mermaid** w `README.md`.

### Usługi dodatkowe

- **Upload plików:** Zdjęcia promocji zapisywane w `public/uploads/promotions/` (z walidacją typu MIME, rozmiaru i losową nazwą pliku); w bazie przechowywana jest wyłącznie względna ścieżka/URL.
- **PHP Mailer + Mailpit:** Wysyłka maili (np. powitalny, alert promocyjny) konfigurowana przez zmienne `.env` i testowana lokalnie przez `mailpit:1025` (podgląd na `http://localhost:8025`).
- **CRON:** Skrypt CLI w PHP (np. `bin/cron_promotions.php`) sprawdzający wygasłe promocje lub wysyłający powiadomienia mailowe o nowych promocjach pasujących do produktów na listach użytkowników, uruchamiany cyklicznie w Dockerze.
- **Unit testy:** Testy jednostkowe (PHPUnit) w katalogu `tests/` pokrywające kluczową logikę (np. `Routing`, serwisy kategoryzacji/dopasowania promocji, walidatory).

---

## 7. Bezpieczeństwo (Security Bingo)

Każdy nowy kod musi przestrzegać poniższych zasad:

- **SQL Injection:** Wyłącznie PDO z _prepared statements_ (`prepare` + `execute`), zero konkatenacji zmiennych w SQL.
- **XSS:** Każde wyjście w widokach `.html.php` escapowane przez `htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8')`.
- **CSRF:** Token CSRF w sesji, weryfikowany dla wszystkich żądań modyfikujących stan (`POST`/`PUT`/`DELETE`).
- **Hasła:** Wyłącznie `password_hash(..., PASSWORD_DEFAULT)` oraz `password_verify()`.
- **Sesje:** `session_regenerate_id(true)` po logowaniu, flagi ciasteczek `HttpOnly`, `SameSite=Lax`, `Secure` (na HTTPS).
- **Autoryzacja (RBAC):** Sprawdzanie roli i własności zasobu (ochrona przed IDOR — użytkownik może modyfikować tylko listy, do których należy; panel admina tylko dla roli `admin`).
- **Upload plików:** Walidacja przez `finfo_file()` (nie ufać rozszerzeniu ani `$_FILES['type']`), limit rozmiaru, blokada wykonywania PHP w katalogu `uploads/`.
- **Sekrety:** Wyłącznie w `.env` (nigdy nie commitować `.env`, utrzymywać `.env.example`).

---

## 8. Standardy kodu i Git workflow

- **PHP:** Zawsze `declare(strict_types=1);`, ścisłe typowanie parametrów i typów zwracanych, zgodność z PSR-4 i PSR-12, brak duplikacji kodu (DRY).
- **Widoki:** Brak logiki biznesowej i zapytań SQL w plikach `public/views/*.html.php`.
- **Git:**
  - Praca na gałęziach typu `feature/<nazwa>` lub `fix/<nazwa>`, finalnie mergowanych do `main`.
  - Małe, atomowe i logiczne commity z czytelnymi opisami.
- **Dokumentacja (`README.md`):** Utrzymywać na bieżąco: opis projektu, instrukcję uruchomienia Dockerem, strukturę katalogów, diagram ERD (Mermaid), flow aplikacji, zrzuty ekranu oraz raport WCAG.

---

## 9. Komendy pomocnicze (Docker)

```bash
# Uruchomienie środowiska
docker compose up --build -d

# Logi PHP / Nginx
docker compose logs -f php server

# Wejście do kontenera PHP (np. composer, testy, cron)
docker compose exec php sh

# Adresy lokalne
# Aplikacja:  http://localhost:8080
# Mailpit:    http://localhost:8025
# pgAdmin:    http://localhost:5050
```
