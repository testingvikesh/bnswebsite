#!/usr/bin/env bash
# Run on the Hostinger/cPanel box as user bns.
# GitHub Actions calls this after git reset --hard origin/main.
set -euo pipefail

APP_DIR="/home/bns/bnswebsite"
WEB_DIR="/home/bns/public_html"

echo "=== BNS deploy $(date -Is) ==="
echo "APP_DIR=$APP_DIR"
echo "WEB_DIR=$WEB_DIR"

if [ ! -d "$APP_DIR/.git" ]; then
  echo "ERROR: $APP_DIR is not a git checkout"
  exit 1
fi

cd "$APP_DIR"
echo "HEAD: $(git log -1 --oneline)"
echo "Title in git checkout:"
grep "<title>" resources/views/layouts/front.blade.php || true

# Clear Laravel caches in every folder that looks like this app
clear_artisan() {
  local dir="$1"
  if [ -f "$dir/artisan" ]; then
    echo "php artisan optimize:clear in $dir"
    (cd "$dir" && php artisan optimize:clear) || echo "WARN: artisan failed in $dir"
  fi
}

clear_artisan "$APP_DIR"
clear_artisan "/home/bns"
clear_artisan "$WEB_DIR"

if [ ! -e "$WEB_DIR" ]; then
  echo "No $WEB_DIR — document root is probably $APP_DIR/public"
  echo "=== Deploy done ==="
  exit 0
fi

echo "public_html -> $(readlink -f "$WEB_DIR" 2>/dev/null || echo "$WEB_DIR")"
ls -ld "$WEB_DIR"

# Symlink into this repo: git pull already updated live files
if [ -L "$WEB_DIR" ]; then
  echo "public_html is a symlink; no file copy needed"
  echo "=== Deploy done ==="
  exit 0
fi

# Separate git checkout used as the live tree
if [ -d "$WEB_DIR/.git" ]; then
  echo "public_html has its own git repo — pulling main"
  cd "$WEB_DIR"
  git fetch origin main
  git reset --hard origin/main
  clear_artisan "$WEB_DIR"
  echo "=== Deploy done ==="
  exit 0
fi

# Full Laravel copy sitting in public_html (artisan at web root is unusual but happens)
if [ -f "$WEB_DIR/artisan" ]; then
  echo "Syncing Laravel app into public_html (keeping .env and storage)"
  rsync -a \
    --exclude='.git/' \
    --exclude='.env' \
    --exclude='.env.*' \
    --exclude='node_modules/' \
    --exclude='storage/logs/' \
    --exclude='storage/framework/cache/' \
    --exclude='storage/framework/sessions/' \
    --exclude='storage/framework/views/' \
    "$APP_DIR/" "$WEB_DIR/"
  clear_artisan "$WEB_DIR"
  echo "=== Deploy done ==="
  exit 0
fi

# Typical cPanel layout: public_html is a copy of Laravel public/
if [ -f "$WEB_DIR/index.php" ]; then
  echo "Syncing public/ into public_html"
  echo "Live index.php bootstrap:"
  grep -E "autoload|bootstrap" "$WEB_DIR/index.php" || true
  rsync -a \
    --exclude='.user.ini' \
    --exclude='cgi-bin/' \
    --exclude='.well-known/' \
    "$APP_DIR/public/" "$WEB_DIR/"
fi

echo "=== Deploy done ==="
