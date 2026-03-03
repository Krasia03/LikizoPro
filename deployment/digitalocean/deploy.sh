#!/bin/bash

# =====================================================================
# LIKIZOPRO - Digital Ocean Auto-Deployment Script
# =====================================================================
# This script automates the deployment of LIKIZOPRO (CodeIgniter 4)
# to a Digital Ocean Droplet with Ubuntu 22.04 LTS
#
# Usage: Run this script as User Data when creating a Droplet
#        OR run manually after droplet is created
# =====================================================================

set -e  # Exit on error

# =====================================================================
# CONFIGURATION - Update these variables before deployment
# =====================================================================

# Git Repository
REPO_URL="https://github.com/yourusername/likizopro.git"
BRANCH="main"

# Domain Configuration
DOMAIN_NAME="likizopro.yourdomain.com"
HTTPS="true"

# Database Configuration
DB_NAME="likizopro_db"
DB_USER="likizo_user"
DB_PASS="ChangeThisToSecurePassword123!"

# Application Configuration
APP_ENV="production"
APP_DEBUG="false"

# Admin Login (will be seeded)
ADMIN_EMAIL="admin@likizopro.co.tz"
ADMIN_PASSWORD="123456"

# Email Configuration (for notifications)
SMTP_HOST=""
SMTP_PORT="587"
SMTP_USER=""
SMTP_PASS=""
FROM_EMAIL="noreply@likizopro.co.tz"

# Timezone
APP_TIMEZONE="Africa/Dar_es_Salaam"

# =====================================================================
# SCRIPT START
# =====================================================================

echo "=========================================="
echo "LIKIZOPRO Auto-Deployment Script"
echo "=========================================="
echo "Starting at $(date)"
echo ""

# Function to check if command succeeded
check_status() {
    if [ $? -eq 0 ]; then
        echo "✓ $1"
    else
        echo "✗ $1 failed"
        exit 1
    fi
}

# =====================================================================
# 1. SYSTEM UPDATE AND DEPENDENCIES
# =====================================================================
echo ""
echo ">>> [1/10] Updating system and installing dependencies..."

export DEBIAN_FRONTEND=noninteractive
apt-get update -y
apt-get upgrade -y

# Install required packages
apt-get install -y \
    git \
    curl \
    wget \
    zip \
    unzip \
    software-properties-common \
    ufw \
    ca-certificates \
    gnupg \
    lsb-release

check_status "System update complete"

# =====================================================================
# 2. INSTALL NGINX
# =====================================================================
echo ""
echo ">>> [2/10] Installing Nginx..."

apt-get install -y nginx
systemctl enable nginx
systemctl start nginx

# Configure UFW for Nginx
ufw --force enable
ufw allow 'Nginx Full' > /dev/null 2>&1 || true
ufw allow OpenSSH > /dev/null 2>&1 || true

check_status "Nginx installed"

# =====================================================================
# 3. INSTALL MYSQL 8.0
# =====================================================================
echo ""
echo ">>> [3/10] Installing MySQL 8.0..."

apt-get install -y mysql-server

# Start MySQL
systemctl enable mysql
systemctl start mysql

# Configure MySQL
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'RootPass123!';"
mysql -e "FLUSH PRIVILEGES;"

# Create database and user
mysql -uroot -pRootPass123! -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -uroot -pRootPass123! -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -uroot -pRootPass123! -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';"
mysql -uroot -pRootPass123! -e "FLUSH PRIVILEGES;"

check_status "MySQL installed and configured"

# =====================================================================
# 4. INSTALL PHP 8.2
# =====================================================================
echo ""
echo ">>> [4/10] Installing PHP 8.2..."

# Add PHP repository
add-apt-repository -y ppa:ondrej/php > /dev/null 2>&1
apt-get update -y

# Install PHP and required extensions
apt-get install -y \
    php8.2-fpm \
    php8.2-mysql \
    php8.2-curl \
    php8.2-intl \
    php8.2-mbstring \
    php8.2-xml \
    php8.2-gd \
    php8.2-zip \
    php8.2-bcmath \
    php8.2-redis

# Configure PHP-FPM
sed -i 's/pm.max_children = 5/pm.max_children = 20/' /etc/php/8.2/fpm/pool.d/www.conf
sed -i 's/pm.start_servers = 2/pm.start_servers = 5/' /etc/php/8.2/fpm/pool.d/www.conf
sed -i 's/pm.min_spare_servers = 1/pm.min_spare_servers = 5/' /etc/php/8.2/fpm/pool.d/www.conf
sed -i 's/pm.max_spare_servers = 3/pm.max_spare_servers = 10/' /etc/php/8.2/fpm/pool.d/www.conf

systemctl enable php8.2-fpm
systemctl restart php8.2-fpm

check_status "PHP 8.2 installed"

# =====================================================================
# 5. INSTALL COMPOSER
# =====================================================================
echo ""
echo ">>> [5/10] Installing Composer..."

curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure Composer for faster installs
composer config --global process-timeout 600
composer config --global repo.packagist composer https://packagist.org

check_status "Composer installed"

# =====================================================================
# 6. DEPLOY APPLICATION
# =====================================================================
echo ""
echo ">>> [6/10] Deploying LIKIZOPRO application..."

# Create application directory
mkdir -p /var/www
cd /var/www

# Clone repository
if [ -d "likizopro" ]; then
    cd likizopro
    git pull origin $BRANCH
else
    git clone -b $BRANCH $REPO_URL likizopro
    cd likizopro
fi

# Install PHP dependencies
composer install --no-dev --optimize-autoloader --ignore-platform-reqs

check_status "Application code deployed"

# =====================================================================
# 7. CONFIGURE ENVIRONMENT
# =====================================================================
echo ""
echo ">>> [7/10] Configuring environment..."

cd /var/www/likizopro

# Copy and configure .env file
if [ -f ".env.example" ]; then
    cp .env.example .env
elif [ -f "env" ]; then
    cp env .env
fi

# Configure .env file
cat > .env <<EOF
# Application
CI_ENVIRONMENT = $APP_ENV
app.baseURL = https://$DOMAIN_NAME/
app.indexPage = index.php
app.timezone = $APP_TIMEZONE

# Security
app.CSRFProtection = cookie
app.tokenName = csrf_token_name
app.cookieHTTPOnly = true

# Session
session.driver = CodeIgniter\Session\Handlers\DatabaseHandler
session.savePath = ci_sessions

# Database
database.default.hostname = localhost
database.default.database = $DB_NAME
database.default.username = $DB_USER
database.default.password = $DB_PASS
database.default.DBDriver = MySQLi
database.default.DBDebug = false

# Email (SMTP)
email.protocol = smtp
email.SMTPHost = $SMTP_HOST
email.SMTPPort = $SMTP_PORT
email.SMTPUser = $SMTP_USER
email.SMTPPass = $SMTP_PASS
email.mailType = html
email.fromEmail = $FROM_EMAIL

# JWT Auth
auth.jwtKey = likizopro_jwt_secret_$(date +%s)_change_in_production
auth.jwtAlgorithm = HS256
auth.jwtExpiry = 86400
EOF

check_status "Environment configured"

# =====================================================================
# 8. RUN MIGRATIONS AND SEEDERS
# =====================================================================
echo ""
echo ">>> [8/10] Running database migrations..."

cd /var/www/likizopro

# Set permissions for spark
chmod +x spark

# Run migrations
php spark migrate

# Run seeders
php spark db:seed DatabaseSeeder

check_status "Database migrations complete"

# =====================================================================
# 9. CONFIGURE NGINX
# =====================================================================
echo ""
echo ">>> [9/10] Configuring Nginx..."

# Create Nginx configuration
cat > /etc/nginx/sites-available/likizopro <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name $DOMAIN_NAME www.$DOMAIN_NAME;

    # Redirect HTTP to HTTPS (if enabled)
    if (\$https != "on") {
        return 301 https://\$host\$request_uri;
    }
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name $DOMAIN_NAME www.$DOMAIN_NAME;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/$DOMAIN_NAME/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/$DOMAIN_NAME/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Root Directory
    root /var/www/likizopro/public;
    index index.php index.html index.htm;

    # Main Location
    location / {
        try_files \$uri \$uri/ /index.php/\$args;
    }

    # PHP-FPM Configuration
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
        
        # Increase timeout for long-running requests
        fastcgi_read_timeout 300;
        fastcgi_send_timeout 300;
    }

    # Block access to sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location ~ /writable {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml application/javascript application/json;
}
EOF

# Enable the site
ln -sf /etc/nginx/sites-available/likizopro /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
nginx -t

# Restart Nginx
systemctl restart nginx

check_status "Nginx configured"

# =====================================================================
# 10. SSL CERTIFICATE (Let's Encrypt)
# =====================================================================
echo ""
echo ">>> [10/10] Setting up SSL certificate..."

# Install Certbot
apt-get install -y certbot python3-certbot-nginx

# Request SSL certificate (non-interactive)
if [ "$HTTPS" = "true" ]; then
    certbot --nginx -d $DOMAIN_NAME -d www.$DOMAIN_NAME --non-interactive --agree-tos -m admin@likizopro.co.tz --redirect || {
        echo "Note: SSL certificate request may need manual verification"
    }
    
    # Set up auto-renewal
    echo "0 0 * * * certbot renew --quiet --post-hook 'systemctl reload nginx'" >> /etc/cron.d/certbot-renewal
    chmod 644 /etc/cron.d/certbot-renewal
    
    check_status "SSL certificate installed"
else
    echo "Skipping SSL configuration (HTTPS=false)"
fi

# =====================================================================
# 11. FINAL PERMISSIONS AND CLEANUP
# =====================================================================
echo ""
echo ">>> Setting final permissions..."

# Set ownership
chown -R www-data:www-data /var/www/likizopro

# Set directory permissions
find /var/www/likizopro -type d -exec chmod 755 {} \;

# Set file permissions
find /var/www/likizopro -type f -exec chmod 644 {} \;

# Make writable directories fully accessible
chmod -R 777 /var/www/likizopro/writable
chmod -R 777 /var/www/likizopro/public/uploads 2>/dev/null || true

# Create log symbolic link
ln -sf /var/www/likizopro/writable/logs /var/log/likizopro

# =====================================================================
# DEPLOYMENT COMPLETE
# =====================================================================
echo ""
echo "=========================================="
echo "🎉 LIKIZOPRO Deployment Complete!"
echo "=========================================="
echo ""
echo "Application URL: https://$DOMAIN_NAME"
echo "Database: $DB_NAME (user: $DB_USER)"
echo ""
echo "Default Login:"
echo "  Email: $ADMIN_EMAIL"
echo "  Password: $ADMIN_PASSWORD"
echo ""
echo "Important Commands:"
echo "  View logs: tail -f /var/www/likizopro/writable/logs/"
echo "  Restart PHP: systemctl restart php8.2-fpm"
echo "  Restart Nginx: systemctl restart nginx"
echo "  Run migrations: cd /var/www/likizopro && php spark migrate"
echo ""
echo "Deployment finished at $(date)"
