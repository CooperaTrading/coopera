#!/bin/bash
set -e

echo "🚀 Preparing Coopera Trading for deployment on https://cooperatrading.com/ (Namecheap cPanel) ..."

# 1. Compile frontend assets for production
echo "📦 Building production assets via Vite..."
npm run build

# 2. Clear old cached configs and views
echo "🧹 Clearing stale Laravel caches..."
php artisan optimize:clear || true

# 3. Create storage symlink if not present
echo "🔗 Ensuring public/storage link..."
php artisan storage:link || true

# 4. Optimize Laravel for production
echo "⚡ Optimizing routes, configs, and views..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "✅ Ready for production deployment on https://cooperatrading.com/!"
