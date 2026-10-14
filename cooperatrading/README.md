# Coopera Trading Website

A modern Laravel 11 website for Coopera Trading — a B2B trading company supplying construction, industrial chemicals, water, packaging, technology, and export products across Ethiopia and beyond.

Includes a public catalog, a customer-facing quote request form, and a minimal CMS admin panel that generates VAT-compliant proforma invoices and emails them to customers as PDFs.

## Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Blade templates, Tailwind CSS, Alpine.js
- **Database:** SQLite (dev) / MySQL (production)
- **PDF:** barryvdh/laravel-dompdf
- **Email:** Laravel Mail (SMTP via cPanel in production)
- **Auth:** Laravel Breeze (Blade)

## Getting Started

This project is set up to run via Docker using the official Composer image. The host does not need PHP installed.

### Prerequisites

- Docker
- Node.js 18+ and npm (for asset building)

### First-time setup

```bash
# 1. Install PHP dependencies (via Docker)
docker run --rm --user $(id -u):$(id -g) \
  -v $(pwd):/app -w /app \
  composer:latest composer install

# 2. Set up .env
cp .env.example .env
docker run --rm --user $(id -u):$(id -g) \
  -v $(pwd):/app -w /app -e HOME=/tmp \
  composer:latest php artisan key:generate

# 3. Migrate and seed
docker run --rm --user $(id -u):$(id -g) \
  -v $(pwd):/app -w /app -e HOME=/tmp \
  composer:latest php artisan migrate --seed

# 4. Build assets
npm install
npm run build

# 5. Create storage symlink
docker run --rm --user $(id -u):$(id -g) \
  -v $(pwd):/app -w /app -e HOME=/tmp \
  composer:latest php artisan storage:link
```

### Daily development

```bash
# Start the dev server (binds to port 8000)
docker run --rm --user $(id -u):$(id -g) \
  -v $(pwd):/app -w /app -e HOME=/tmp -p 8000:8000 \
  composer:latest php artisan serve --host=0.0.0.0 --port=8000

# Or use the helper script
./artisan-container.sh php artisan <command>
```

The helper script `artisan-container.sh` wraps all commands in the Docker container.

## Default Admin Account

- **Email:** `info@cooperatrading.com`
- **Password:** `password`
- **Admin URL:** `/admin`

Change the password immediately after first login.

## Project Structure

```
cooperatrading/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/           # Admin panel controllers
│   │   │   ├── CategoryController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ProductController.php
│   │   │   ├── ProformaController.php
│   │   │   └── QuoteRequestController.php
│   │   ├── HomeController.php
│   │   ├── ProductCatalogController.php
│   │   ├── QuoteRequestController.php
│   │   └── ContactController.php
│   ├── Http/Middleware/EnsureUserIsAdmin.php
│   ├── Mail/
│   │   ├── ContactFormSubmission.php
│   │   └── ProformaSent.php
│   └── Models/
│       ├── Category.php
│       ├── Product.php
│       ├── QuoteRequest.php
│       ├── QuoteRequestItem.php
│       ├── Proforma.php
│       └── ProformaItem.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── AdminUserSeeder.php
│       └── CategoryProductSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/        # admin.blade.php, app.blade.php
│   │   ├── admin/          # admin views
│   │   ├── products/       # catalog views
│   │   ├── quote/          # quote request views
│   │   ├── emails/         # markdown email templates
│   │   └── pdf/            # dompdf proforma template
│   └── css/, js/           # tailwind + alpine
├── routes/web.php
└── public/                 # document root
```

## Key URLs

| URL | Description |
|---|---|
| `/` | Home page |
| `/about` | About Us |
| `/products` | Catalog overview (all categories) |
| `/products/{category-slug}` | Products in a category |
| `/products/{category-slug}/{product-slug}` | Product detail |
| `/contact` | Contact form |
| `/quote` | Request a Quote (multi-select) |
| `/login` | Admin login |
| `/admin` | Admin dashboard |
| `/admin/categories` | Manage categories |
| `/admin/products` | Manage products |
| `/admin/quote-requests` | View quote requests |
| `/admin/quote-requests/{id}` | Quote request detail |
| `/admin/proformas/create/{quoteId}` | Generate proforma |
| `/admin/proformas/{id}` | View proforma |
| `/admin/proformas/{id}/download` | Download PDF |

## Database Schema

- **categories** — id, name, slug, description
- **products** — id, category_id, name, slug, image, description, unit_of_measure
- **quote_requests** — id, customer_name, company_name, email, phone, message, status (pending/processed)
- **quote_request_items** — id, quote_request_id, product_id, product_name, unit_of_measure, quantity
- **proformas** — id, quote_request_id, proforma_number, issue_date, validity_date, payment_terms, delivery_time, bank_details, notes, subtotal, vat, total
- **proforma_items** — id, proforma_id, product_id, product_name, unit_of_measure, quantity, unit_price, total_price

## Configuration

### Currency

Set in `.env`:
```
APP_CURRENCY=ETB
APP_CURRENCY_SYMBOL=Br
APP_VAT_RATE=15
```

### Mail (Production)

Set in `.env` for cPanel SMTP:
```
MAIL_MAILER=smtp
MAIL_HOST=mail.cooperatrading.com
MAIL_PORT=587
MAIL_USERNAME=info@cooperatrading.com
MAIL_PASSWORD=your-cpanel-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="info@cooperatrading.com"
MAIL_FROM_NAME="Coopera Trading"
ADMIN_EMAIL=info@cooperatrading.com
```

For development, `MAIL_MAILER=log` writes emails to `storage/logs/laravel.log`.

### Database (Production)

For MySQL on cPanel:
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_cpanel_db
DB_USERNAME=your_cpanel_user
DB_PASSWORD=your_cpanel_password
```

## Deployment to cPanel

1. Push the project to a Git repository (GitHub, GitLab, etc.)
2. In cPanel, use **Git Version Control** to clone the repo into a domain or subdomain
3. SSH into the cPanel account and run:
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm install && npm run build
   ```
4. Point the document root to the `public/` directory
5. Add the `public/storage` symlink (or use `php artisan storage:link`)
6. Set the cron job for the Laravel scheduler (optional):
   ```
   * * * * * cd /path/to/cooperatrading && php artisan schedule:run >> /dev/null 2>&1
   ```

## License

Proprietary Software. Copyright © 2026 [RCC](https://www.razielcc.com). All rights reserved.

- **Developer:** [Abrham Taye](https://github.com/Abrhamtaye7)
- **Page Owner:** [Coopera Trading](https://cooperatrading.com)

