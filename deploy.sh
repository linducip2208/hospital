#!/bin/bash
# =============================================================================
# SIMRS Hospital - Production Deploy Script v1.0
# =============================================================================
# Target: Ubuntu 22.04 / 24.04, PHP 8.3, MySQL 8.x, Nginx
# Usage:  chmod +x deploy.sh && sudo bash deploy.sh
# =============================================================================
set -euo pipefail

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m'

APP_NAME="SIMRS Hospital"
PROJECT_DIR="/var/www/hospital"
PHP_VERSION="8.3"
DB_NAME="hospital_prod"
DB_USER="hospital_user"
DB_PASS=""
DOMAIN=""
DEPLOY_MODE="full"  # full | code-only | db-only

log()  { echo -e "${GREEN}[✓]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }
err()  { echo -e "${RED}[✗]${NC} $1"; exit 1; }
info() { echo -e "${CYAN}[i]${NC} $1"; }

# ─── Parse Args ───────────────────────────────────────────────────────────
while [[ $# -gt 0 ]]; do
    case $1 in
        --domain)    DOMAIN="$2"; shift 2 ;;
        --db-pass)   DB_PASS="$2"; shift 2 ;;
        --db-name)   DB_NAME="$2"; shift 2 ;;
        --db-user)   DB_USER="$2"; shift 2 ;;
        --mode)      DEPLOY_MODE="$2"; shift 2 ;;
        --dir)       PROJECT_DIR="$2"; shift 2 ;;
        -h|--help)
            echo "Usage: sudo bash deploy.sh [options]"
            echo ""
            echo "Options:"
            echo "  --domain <domain>    Domain name (required for production)"
            echo "  --db-pass <pass>     MySQL password for hospital_user"
            echo "  --db-name <name>     Database name (default: hospital_prod)"
            echo "  --db-user <user>     Database user (default: hospital_user)"
            echo "  --mode <mode>        full | code-only | db-only (default: full)"
            echo "  --dir <path>         Project directory (default: /var/www/hospital)"
            echo ""
            exit 0
            ;;
        *) err "Unknown option: $1" ;;
    esac
done

# ─── Prerequisites Check ───────────────────────────────────────────────────
if [[ $EUID -ne 0 ]]; then
    err "Script must run as root (sudo bash deploy.sh)"
fi

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SQL_FILE="$SCRIPT_DIR/hospital-db-dump.sql"

if [[ ! -f "$SQL_FILE" && "$DEPLOY_MODE" != "code-only" ]]; then
    warn "hospital-db-dump.sql not found in $SCRIPT_DIR"
    warn "Will skip database import. Use --mode code-only to suppress this warning."
    read -p "Continue? [y/N] " -n 1 -r; echo
    [[ $REPLY =~ ^[Yy]$ ]] || exit 0
fi

# ─── 1. Install System Packages ────────────────────────────────────────────
step_install_packages() {
    if [[ "$DEPLOY_MODE" == "db-only" ]]; then
        log "Skipping package install (db-only mode)"
        return
    fi

    info "Installing system packages..."
    apt update -qq

    # PHP 8.3 + extensions
    apt install -y -qq \
        nginx \
        mysql-server \
        software-properties-common \
        ca-certificates \
        lsb-release \
        apt-transport-https \
        git unzip curl wget

    # Add ondrej/php PPA for PHP 8.3
    add-apt-repository -y ppa:ondrej/php 2>/dev/null || true
    apt update -qq

    apt install -y -qq \
        "php${PHP_VERSION}" \
        "php${PHP_VERSION}-fpm" \
        "php${PHP_VERSION}-mysql" \
        "php${PHP_VERSION}-mbstring" \
        "php${PHP_VERSION}-xml" \
        "php${PHP_VERSION}-bcmath" \
        "php${PHP_VERSION}-gd" \
        "php${PHP_VERSION}-zip" \
        "php${PHP_VERSION}-intl" \
        "php${PHP_VERSION}-curl" \
        "php${PHP_VERSION}-redis" \
        "php${PHP_VERSION}-imagick"

    # Node.js LTS (for Vite build)
    if ! command -v node &>/dev/null; then
        curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
        apt install -y -qq nodejs
    fi

    # Composer
    if ! command -v composer &>/dev/null; then
        curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    fi

    log "System packages installed"
}

# ─── 2. Database Setup ─────────────────────────────────────────────────────
step_database() {
    if [[ "$DEPLOY_MODE" == "code-only" ]]; then
        log "Skipping database setup (code-only mode)"
        return
    fi

    info "Setting up database..."

    # Generate password if not provided
    if [[ -z "$DB_PASS" ]]; then
        DB_PASS=$(openssl rand -base64 24 | tr -d '+/=' | cut -c1-24)
        warn "No DB password provided. Generated: ${DB_PASS}"
        warn "SAVE THIS PASSWORD! It will not be shown again."
    fi

    # MySQL root auth
    MYSQL_AUTH=""
    if mysql -u root -e "SELECT 1" &>/dev/null; then
        MYSQL_AUTH="mysql -u root"
    elif [[ -f /root/.my.cnf ]]; then
        MYSQL_AUTH="mysql --defaults-file=/root/.my.cnf"
    else
        read -sp "MySQL root password: " MYSQL_ROOT_PASS; echo
        MYSQL_AUTH="mysql -u root -p${MYSQL_ROOT_PASS}"
    fi

    # Create database & user
    $MYSQL_AUTH -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" || true
    $MYSQL_AUTH -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';" || true
    $MYSQL_AUTH -e "GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';" || true
    $MYSQL_AUTH -e "FLUSH PRIVILEGES;"

    # Import SQL dump
    if [[ -f "$SQL_FILE" ]]; then
        info "Importing database from hospital-db-dump.sql..."
        $MYSQL_AUTH "$DB_NAME" < "$SQL_FILE"
        log "Database imported successfully"
    else
        warn "No SQL file found. Run php artisan migrate --force + db:seed --force after deploy."
    fi

    log "Database setup complete"
}

# ─── 3. Project Setup ──────────────────────────────────────────────────────
step_project() {
    if [[ "$DEPLOY_MODE" == "db-only" ]]; then
        log "Skipping project setup (db-only mode)"
        return
    fi

    info "Setting up project in $PROJECT_DIR..."

    # Create directory
    mkdir -p "$PROJECT_DIR"

    # Copy project files from script directory (excludes vendor, node_modules, .git)
    if [[ "$SCRIPT_DIR" != "$PROJECT_DIR" ]]; then
        info "Copying project files..."
        rsync -av --exclude='vendor/' --exclude='node_modules/' --exclude='.git/' \
            --exclude='storage/logs/*' --exclude='storage/framework/cache/*' \
            --exclude='storage/framework/views/*' --exclude='storage/framework/sessions/*' \
            --exclude='.env' --exclude='*.zip' --exclude='-p/' \
            "$SCRIPT_DIR/" "$PROJECT_DIR/"
    fi

    cd "$PROJECT_DIR"

    # Setup .env
    if [[ ! -f .env ]]; then
        if [[ -f .env.production.example ]]; then
            cp .env.production.example .env
        else
            cp .env.example .env
        fi
    fi

    # Configure .env
    APP_KEY=$(php artisan key:generate --show 2>/dev/null || echo "")
    if [[ -z "$APP_KEY" ]]; then
        APP_KEY="base64:$(openssl rand -base64 32)"
    fi

    sed -i "s|APP_ENV=.*|APP_ENV=production|" .env
    sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|" .env
    sed -i "s|APP_URL=.*|APP_URL=https://${DOMAIN}|" .env
    sed -i "s|APP_KEY=.*|APP_KEY=${APP_KEY}|" .env
    sed -i "s|DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|" .env
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=${DB_USER}|" .env
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASS}|" .env
    sed -i "s|SESSION_SECURE_COOKIE=.*|SESSION_SECURE_COOKIE=true|" .env
    sed -i "s|LICENSE_DEV_BYPASS=.*|LICENSE_DEV_BYPASS=false|" .env
    sed -i "s|LOG_LEVEL=.*|LOG_LEVEL=warning|" .env
    sed -i "s|LOG_CHANNEL=.*|LOG_CHANNEL=daily|" .env
    sed -i "s|FORCE_HTTPS=.*|FORCE_HTTPS=true|" .env

    # Composer install
    info "Installing composer dependencies..."
    if [[ -f composer.lock ]]; then
        composer install --no-dev --optimize-autoloader --no-interaction
    else
        composer install --no-dev --optimize-autoloader --no-interaction
    fi

    # NPM install & build
    if [[ -f package.json ]]; then
        info "Building frontend assets..."
        npm ci --no-audit --no-fund 2>/dev/null || npm install --no-audit --no-fund
        npm run build
    fi

    # Laravel post-install
    php artisan storage:link 2>/dev/null || true

    # Migrate (only if SQL was NOT imported)
    if [[ ! -f "$SQL_FILE" ]] || [[ "$DEPLOY_MODE" == "code-only" ]]; then
        info "Running migrations..."
        php artisan migrate --force --no-interaction
    fi

    # Cache optimization
    info "Optimizing caches..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache

    # Permissions
    chown -R www-data:www-data "$PROJECT_DIR"
    chmod -R 775 "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache"

    log "Project setup complete"
}

# ─── 4. Nginx Configuration ─────────────────────────────────────────────────
step_nginx() {
    if [[ "$DEPLOY_MODE" == "db-only" ]]; then
        log "Skipping Nginx setup (db-only mode)"
        return
    fi

    if [[ -z "$DOMAIN" ]]; then
        warn "No --domain provided, skipping Nginx configuration"
        warn "Manually configure Nginx later, then run: certbot --nginx"
        return
    fi

    info "Configuring Nginx for $DOMAIN..."

    cat > "/etc/nginx/sites-available/hospital" <<NGINX
# HTTP → HTTPS redirect
server {
    listen 80;
    server_name ${DOMAIN} www.${DOMAIN};
    return 301 https://\$host\$request_uri;
}

server {
    listen 443 ssl http2;
    server_name ${DOMAIN} www.${DOMAIN};
    root ${PROJECT_DIR}/public;

    ssl_certificate     /etc/letsencrypt/live/${DOMAIN}/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/${DOMAIN}/privkey.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;
    ssl_ciphers         HIGH:!aNULL:!MD5;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    index index.php;
    charset utf-8;

    # Service Worker
    location /sw.js {
        add_header Cache-Control "no-cache, no-store, must-revalidate";
        try_files \$uri =404;
    }

    # Static assets — cache 1 year
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|webp)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files \$uri =404;
    }

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php${PHP_VERSION}-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }
    client_max_body_size 10M;
}
NGINX

    ln -sf "/etc/nginx/sites-available/hospital" "/etc/nginx/sites-enabled/hospital"
    rm -f "/etc/nginx/sites-enabled/default"

    nginx -t && systemctl reload nginx
    log "Nginx configured"
}

# ─── 5. SSL via Certbot ────────────────────────────────────────────────────
step_ssl() {
    if [[ "$DEPLOY_MODE" == "db-only" ]]; then
        return
    fi

    if [[ -z "$DOMAIN" ]]; then
        return
    fi

    info "Obtaining SSL certificate..."
    if ! command -v certbot &>/dev/null; then
        apt install -y -qq certbot python3-certbot-nginx
    fi

    certbot --nginx -d "$DOMAIN" -d "www.${DOMAIN}" --non-interactive --agree-tos --email "admin@${DOMAIN}" || true
    systemctl enable certbot.timer

    log "SSL configured"
}

# ─── 6. Cron & Queue Worker ────────────────────────────────────────────────
step_cron_and_queue() {
    if [[ "$DEPLOY_MODE" == "db-only" ]]; then
        return
    fi

    info "Setting up cron and queue worker..."

    # Laravel scheduler cron
    CRON_JOB="* * * * * cd ${PROJECT_DIR} && php artisan schedule:run >> /dev/null 2>&1"
    (crontab -l 2>/dev/null | grep -v "schedule:run" || true; echo "$CRON_JOB") | crontab -

    # DB backup cron (2 AM daily)
    BACKUP_DIR="/backup"
    mkdir -p "$BACKUP_DIR"
    BACKUP_CRON="0 2 * * * mysqldump -u ${DB_USER} -p${DB_PASS} ${DB_NAME} | gzip > ${BACKUP_DIR}/db-\$(date +\%Y\%m\%d).sql.gz"
    CLEANUP_CRON="0 3 * * * find ${BACKUP_DIR} -name 'db-*.sql.gz' -mtime +30 -delete"
    (crontab -l 2>/dev/null | grep -v "mysqldump.*${DB_NAME}" | grep -v "find.*db-"; echo "$BACKUP_CRON"; echo "$CLEANUP_CRON") | crontab -

    log "Cron jobs configured"
}

# ─── 7. Firewall ───────────────────────────────────────────────────────────
step_firewall() {
    if command -v ufw &>/dev/null; then
        ufw allow 80/tcp 2>/dev/null || true
        ufw allow 443/tcp 2>/dev/null || true
        ufw allow 22/tcp 2>/dev/null || true
    fi
    log "Firewall rules set"
}

# ─── Run All Steps ─────────────────────────────────────────────────────────
echo ""
echo "╔══════════════════════════════════════════════════════════╗"
echo "║   ${APP_NAME} — Production Deploy v1.0                  ║"
echo "╠══════════════════════════════════════════════════════════╣"
echo "║  Mode:   ${DEPLOY_MODE}"
echo "║  Domain: ${DOMAIN:-'(not set)'}"
echo "║  DB:     ${DB_NAME}@localhost (user: ${DB_USER})"
echo "║  Dir:    ${PROJECT_DIR}"
echo "╚══════════════════════════════════════════════════════════╝"
echo ""

read -p "Proceed with deployment? [y/N] " -n 1 -r; echo
[[ $REPLY =~ ^[Yy]$ ]] || exit 0

step_install_packages
step_database
step_project
step_nginx
step_ssl
step_cron_and_queue
step_firewall

echo ""
echo "╔══════════════════════════════════════════════════════════╗"
echo "║   🎉 Deployment Complete!                               ║"
echo "╠══════════════════════════════════════════════════════════╣"
if [[ -n "$DOMAIN" ]]; then
echo "║   URL: https://${DOMAIN}                                ║"
fi
echo "║   DB:  ${DB_NAME} (user: ${DB_USER})                    ║"
echo "║   Dir: ${PROJECT_DIR}                                   ║"
echo "╠══════════════════════════════════════════════════════════╣"
echo "║   Post-deploy checklist:                                ║"
echo "║   1. Visit https://${DOMAIN:-YOUR-DOMAIN}/__pair        ║"
echo "║   2. Enter activation key from whitelabel.co.id          ║"
echo "║   3. Login with seeded admin credentials                ║"
echo "║   4. Check all cetak/print views render correctly        ║"
echo "╚══════════════════════════════════════════════════════════╝"
echo ""
