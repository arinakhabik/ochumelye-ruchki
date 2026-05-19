# CI/CD пайплайн Laravel-приложения «Очумелые ручки»

## Назначение

Пайплайн проверяет Laravel-приложение перед попаданием изменений в долгоживущие ветки `develop`, `uat`, `main` и `master`.

## Ветки

- `develop` — среда разработки, используется файл `.env.dev`.
- `uat` — тестовая/приёмочная среда, используется файл `.env.uat`.
- `main` / `master` — production-среда, используется файл `.env.prod`.

## Файлы окружений

В репозиторий добавлены шаблоны окружений:

- `.env.dev`
- `.env.uat`
- `.env.prod`
- `.env.ci`

Основной локальный файл `.env` не должен попадать в репозиторий. Он указан в `.gitignore`.
Если `.env` уже был добавлен в Git, нужно выполнить:

```bash
git rm --cached .env
```

## Этапы пайплайна

### 1. Тесты

Команда:

```bash
php artisan test --coverage --min=50
```

Пайплайн завершается с ошибкой, если:

- хотя бы один тест упал;
- покрытие кода меньше 50%.

### 2. Статический анализ

Команда:

```bash
vendor/bin/phpstan analyse --memory-limit=1G
```

Используется Larastan/PHPStan. При любой найденной ошибке пайплайн завершается с ошибкой.

### 3. Linting

Команда:

```bash
vendor/bin/pint --test
```

Используется Laravel Pint с preset `laravel`. На долгоживущих ветках автоисправление не выполняется. Если стиль кода нарушен, пайплайн падает.

Для короткоживущих веток при `push` запускается отдельный job `pint-auto-fix`, который выполняет:

```bash
vendor/bin/pint
```

Если Pint внёс изменения, они автоматически коммитятся обратно в эту же ветку.

### 4. Симуляция деплоя

Симуляция деплоя выполняется только после успешного прохождения тестов, статического анализа и линтера.

- `develop` → копируется `.env.dev` как `.env`, выводится сообщение `Deploying to DEV with .env.dev`.
- `uat` → копируется `.env.uat` как `.env`, выводится сообщение `Deploying to UAT with .env.uat`.
- `main` / `master` → копируется `.env.prod` как `.env`, выводится сообщение `Deploying to PROD with .env.prod`.

Для production используется GitHub Environment `production`. В настройках репозитория нужно включить Required reviewers, чтобы production-deploy требовал ручного подтверждения.

## Уведомления

Job `notify` выводит результат пайплайна в лог.

Дополнительно можно отправлять результат в Telegram. Для этого в GitHub нужно добавить repository secrets:

- `TELEGRAM_BOT_TOKEN`
- `TELEGRAM_CHAT_ID`

Если secrets не заданы, отправка в Telegram пропускается, а результат выводится в лог пайплайна.

## Локальные команды перед push

```bash
composer install
composer require --dev "larastan/larastan:^3.0"
copy .env.dev .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan test --coverage --min=50
vendor/bin/phpstan analyse --memory-limit=1G
vendor/bin/pint --test
```

На Windows вместо `copy .env.dev .env` можно использовать:

```bash
copy .env.dev .env
```

На macOS/Linux:

```bash
cp .env.dev .env
```
