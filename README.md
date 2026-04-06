<p align="center">
  <img src=".github/assets/banner.svg" alt="SystemPanic Cauldron" width="100%" />
</p>

<p align="center">
  <img src=".github/assets/stack.svg" alt="Winter CMS 1.2 | Laravel 9 | PHP 8.1+ | Tailwind CSS" width="600" />
</p>

<p align="center">
  Website and CMS for <a href="https://systempanic.ca">SystemPanic</a>, built on <a href="https://wintercms.com">Winter CMS</a> and <a href="https://laravel.com">Laravel</a>.
</p>

---

## Setup

### Requirements

- PHP >= 8.1
- MySQL 8.0+
- Composer
- Apache with `mod_rewrite`

### Installation

```shell
git clone git@github.com:null-works/Cauldron.git
cd Cauldron

composer install

cp .env.example .env
# Edit .env with your database credentials and APP_KEY

php artisan winter:up
```

### Deploying

On the VPS, pull and run the deploy script:

```shell
./deploy.sh
```

This handles `git pull`, `composer install`, migrations, cache clearing, and permissions.

## Project Structure

```
Cauldron/
├── config/          # App, CMS, database, and session config
├── modules/         # Winter CMS core (system, backend, cms)
├── plugins/
│   └── winter/
│       ├── blog/    # Blog posts and categories
│       └── pages/   # Static pages and menus
├── themes/
│   ├── blocks/      # Parent theme (repeater-based sections)
│   └── panic-purple/# Custom dark purple theme
├── storage/         # Uploads, cache, logs, sessions
└── deploy.sh        # Production deploy script
```

## Theme

**Panic Purple** (v0.5.0) -- a dark purple theme inspired by Grabby Paws, extending the Blocks theme. Built by [StoryCraft.ink](https://storycraft.ink).

Color palette: `#2D1B69` deep purple, `#8B5CF6` purple, `#E879F9` pink, `#06B6D4` cyan.

## Built With

- [Winter CMS](https://wintercms.com) -- Laravel-based CMS (MIT)
- [Laravel 9](https://laravel.com)
- [Tailwind CSS](https://tailwindcss.com) (JIT CDN)

## License

[MIT](LICENSE)
