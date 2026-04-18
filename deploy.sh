#!/bin/bash
#
# deploy.sh — SystemPanic deploy / update workflow
#
# Run from the repo root on the server (as root, since it needs chown + systemctl):
#
#     ./deploy.sh
#
# Order of operations:
#   1. Commit any backend CMS edits under themes/panic-purple/content/
#   2. Discard unrelated local modifications (chmod flips etc.)
#   3. Pull latest from origin/claude/codebase-review-2yXTt (rebase)
#   4. Push the content commit back up
#   5. composer install + winter:up migrations (idempotent, quick if nothing changed)
#   6. Clear WinterCMS caches (twig, combiner, cms cache, framework views, app cache)
#   7. Restore file permissions
#   8. Restart php-fpm to blow away opcache
#

BRANCH="claude/codebase-review-2yXTt"
CONTENT_PATH="themes/panic-purple/content/"

cd "$(dirname "$0")" || { echo "! Could not cd to repo root"; exit 1; }

step() { echo; echo "▶ $*"; }
ok()   { echo "  ✓ $*"; }
warn() { echo "  ! $*"; }

# 1. Capture backend edits
step "Capturing backend edits in $CONTENT_PATH"
if [ -n "$(git status --porcelain "$CONTENT_PATH")" ]; then
    git add "$CONTENT_PATH"
    if git -c user.name="CMS Autosync" -c user.email="cms@systempanic.ca" \
           commit -m "Auto-sync CMS content from backend" >/dev/null; then
        ok "committed"
    else
        warn "commit failed"
    fi
else
    ok "nothing new"
fi

# 2. Discard unrelated local mods (mode flips etc.)
step "Discarding unrelated local modifications"
git checkout -- . 2>/dev/null && ok "clean working tree"

# 3. Pull latest
step "Pulling latest from origin/$BRANCH"
if ! git pull --rebase origin "$BRANCH"; then
    warn "pull failed — resolve conflicts manually, then re-run"
    exit 1
fi
ok "pulled"

# 4. Push any new content commit (never prompt — skip silently if no creds)
step "Pushing content commit to origin/$BRANCH"
if GIT_TERMINAL_PROMPT=0 git push origin "HEAD:$BRANCH" 2>/dev/null; then
    ok "pushed"
else
    warn "push skipped (no credentials yet — content is committed locally, safe until next pull)"
fi

# 5. Composer + migrations
step "Installing composer deps"
composer install --no-dev --optimize-autoloader --no-interaction --quiet && ok "done" || warn "composer failed"

step "Running winter:up migrations"
php artisan winter:up 2>/dev/null && ok "migrations ok" || warn "migrations skipped"

# 6. Clear caches
step "Writing BUILD file (short commit hash)"
git rev-parse --short HEAD > BUILD 2>/dev/null && ok "$(cat BUILD)" || warn "could not write BUILD"

step "Clearing WinterCMS + framework caches"
rm -rf storage/cms/twig/* storage/cms/combiner/* storage/cms/cache/* storage/framework/views/* 2>/dev/null
php artisan cache:clear >/dev/null
php artisan config:clear >/dev/null 2>&1 || true
php artisan view:clear   >/dev/null 2>&1 || true
ok "cleared"

# 7. Restore permissions
step "Restoring file permissions"
SITE_USER=$(stat -c '%U' .)
SITE_GROUP=$(stat -c '%G' .)
if chown -R "${SITE_USER}:${SITE_GROUP}" storage bootstrap/cache themes plugins 2>/dev/null; then
    chmod -R 775 storage bootstrap/cache themes plugins 2>/dev/null
    ok "chown + chmod done (${SITE_USER}:${SITE_GROUP})"
else
    warn "chown failed (need root?)"
fi

# 8. Restart php-fpm (opcache)
step "Restarting php-fpm (clears opcache)"
if systemctl restart php8.2-fpm 2>/dev/null \
|| systemctl restart php8.1-fpm 2>/dev/null \
|| systemctl restart php8.0-fpm 2>/dev/null \
|| systemctl restart php-fpm   2>/dev/null; then
    ok "php-fpm restarted"
else
    warn "could not restart php-fpm — try manually: systemctl restart <unit>"
fi

echo
echo "✓ Deploy complete."
