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
wdpai-app/
├── docker/
│   ├── db/Dockerfile           # postgres:16-alpine (skrypty initdb montowane z ./docker/db)
│   ├── nginx/
│   │   ├── Dockerfile          # nginx:stable-alpine
│   │   └── nginx.conf          # root /app/public, front-controller -> /index.php?$query_string
│   └── php/Dockerfile          # php:8.3-fpm-alpine (pdo_pgsql, pgsql, gd, zip, opcache, bcmath, composer)
├── public/
│   ├── index.php               # Front controller i definicja tras
│   ├── css/style.css           # Zmienne CSS dla Light i Dark mode
│   ├── js/theme.js             # Obsługa motywu (obecnie placeholder)
│   └── views/                  # Szablony widoków (*.html.php)
├── src/
│   ├── Routing.php             # Własny router regexowy (GET/POST, parametry {id})
│   └── controllers/
│       ├── AppController.php   # Bazowy kontroler z metodą render()
│       ├── DefaultController.php
│       └── ProjectController.php
├── composer.json               # PSR-4: App\ -> src/, App\Controller\ -> src/controllers/
├── docker-compose.yaml         # server (8080), php, db (5433), mailpit (8025), pgadmin (5050)
└── .env                        # Sekrety i porty (ignorowane przez git)
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

## 5. Architektura Backendu i Bazy Danych

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

## 6. Bezpieczeństwo (Security Bingo)

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

## 7. Standardy kodu i Git workflow

- **PHP:** Zawsze `declare(strict_types=1);`, ścisłe typowanie parametrów i typów zwracanych, zgodność z PSR-4 i PSR-12, brak duplikacji kodu (DRY).
- **Widoki:** Brak logiki biznesowej i zapytań SQL w plikach `public/views/*.html.php`.
- **Git:**
  - Praca na gałęziach typu `feature/<nazwa>` lub `fix/<nazwa>`, finalnie mergowanych do `main`.
  - Małe, atomowe i logiczne commity z czytelnymi opisami.
- **Dokumentacja (`README.md`):** Utrzymywać na bieżąco: opis projektu, instrukcję uruchomienia Dockerem, strukturę katalogów, diagram ERD (Mermaid), flow aplikacji, zrzuty ekranu oraz raport WCAG.

---

## 8. Komendy pomocnicze (Docker)

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
