#!/usr/bin/env bash
# Executed on the live server by .github/workflows/deploy.yml (appleboy/ssh-action).
# Requires GH_TOKEN in the environment. Pulls main into the live checkout and
# clears Laravel caches. Recovers from a checkout whose .git metadata has been
# made unreadable to the app user (e.g. after git was run there as root).
set -e

APP_USER="businessnavachar"
APP_DIR="/home/${APP_USER}/bnswebsite"
REPO_URL="https://x-access-token:${GH_TOKEN}@github.com/testingvikesh/bnswebsite.git"

echo "SSH user=$(whoami)"

if [ -z "${GH_TOKEN:-}" ]; then
  echo "ERROR: GH_TOKEN is not set"
  exit 1
fi

# Ownership must be fixed before any git command runs as APP_USER, otherwise
# `set -e` aborts on the first permission error and the chown never happens.
if [ "$(id -u)" = "0" ]; then
  chown -R "${APP_USER}:${APP_USER}" "$APP_DIR" || true
elif command -v sudo >/dev/null 2>&1 && sudo -n true 2>/dev/null; then
  sudo -n chown -R "${APP_USER}:${APP_USER}" "$APP_DIR" || true
fi

PULL_SCRIPT=$(cat <<'EOF'
set -e
cd "$APP_DIR"
git config --global --add safe.directory "$APP_DIR" >/dev/null 2>&1 || true

BROKEN=""
if [ ! -r .git/config ] || ! git rev-parse --git-dir >/dev/null 2>&1; then
  echo "WARN: .git is not usable by $(whoami); rebuilding repository metadata"
  ls -ld .git .git/config 2>&1 || true
  BROKEN=".git.broken.$(date +%Y%m%d%H%M%S)"
  mv .git "$BROKEN"
  git init -q .
  git config --global --add safe.directory "$APP_DIR" >/dev/null 2>&1 || true
fi

git fetch --depth=1 "$REPO_URL" main
git reset --hard FETCH_HEAD

if [ -n "$BROKEN" ]; then
  rm -rf "$BROKEN" || echo "WARN: could not remove $BROKEN; delete it manually"
fi

php artisan optimize:clear
git log -1 --oneline
EOF
)

if [ "$(id -u)" = "0" ]; then
  sudo -u "$APP_USER" -H \
    env GH_TOKEN="$GH_TOKEN" APP_DIR="$APP_DIR" REPO_URL="$REPO_URL" \
    bash -lc "$PULL_SCRIPT"
  chown -R "${APP_USER}:${APP_USER}" "$APP_DIR" || true
else
  APP_DIR="$APP_DIR" REPO_URL="$REPO_URL" bash -lc "$PULL_SCRIPT"
fi

echo "Deploy finished"
