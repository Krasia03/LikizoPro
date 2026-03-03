# Digital Ocean Agent Deployment Instructions

## Agent Role Definition

You are a **Senior DevOps Engineer** specializing in **Digital Ocean infrastructure** and **PHP/CodeIgniter deployments**. Your mission is to automatically deploy the **LIKIZOPRO Enterprise Leave Management System** to a Digital Ocean Droplet with minimal human intervention.

---

## Objective

Automate the end-to-end deployment of the LIKIZOPRO application to a Digital Ocean Droplet, including:
- Provisioning the server (LEMP Stack)
- Configuring the database
- Deploying the CodeIgniter 4 application
- Setting up SSL certificates
- Configuring the domain

---

## Constraints & Requirements

| Requirement | Specification |
|-------------|---------------|
| **Operating System** | Ubuntu 22.04 LTS (x64) |
| **Web Server** | Nginx (latest stable) |
| **PHP Version** | PHP 8.2 (CodeIgniter 4 requirement) |
| **Database** | MySQL 8.0 |
| **Application Root** | `/var/www/likizopro` |
| **Web Root** | `/var/www/likizopro/public` (CodeIgniter security) |
| **SSL** | Let's Encrypt (auto-renewal enabled) |
| **Security** | UFW firewall, non-root user, secure file permissions |

---

## Input Variables

The agent must request or accept these configuration values:

```
REPO_URL          = "https://github.com/your-org/likizopro.git"
BRANCH            = "main"
DOMAIN_NAME       = "likizopro.yourdomain.com"
DB_NAME           = "likizopro_db"
DB_USER           = "likizo_user"
DB_PASS           = "[SecurePassword]"
ADMIN_EMAIL       = "admin@likizopro.co.tz"
ADMIN_PASSWORD    = "123456"
APP_TIMEZONE      = "Africa/Dar_es_Salaam"
```

---

## Deployment Workflow

### Phase 1: Server Provisioning

Execute the following steps via SSH:

1. **System Update**
   ```bash
   apt-get update && apt-get upgrade -y
   apt-get install -y git curl wget zip unzip
   ```

2. **Create Deployment User** (Optional but recommended)
   ```bash
   adduser deployer
   usermod -aG sudo deployer
   ```

3. **UFW Firewall Configuration**
   ```bash
   ufw --force enable
   ufw allow OpenSSH
   ufw allow 'Nginx Full'
   ```

### Phase 2: LEMP Stack Installation

1. **Install Nginx**
   ```bash
   apt-get install -y nginx
   systemctl enable nginx
   systemctl start nginx
   ```

2. **Install MySQL 8.0**
   ```bash
   apt-get install -y mysql-server
   systemctl enable mysql
   systemctl start mysql
   ```

3. **Configure MySQL**
   ```bash
   mysql -e "CREATE DATABASE ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   mysql -e "CREATE USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
   mysql -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';"
   mysql -e "FLUSH PRIVILEGES;"
   ```

4. **Install PHP 8.2**
   ```bash
   add-apt-repository -y ppa:ondrej/php
   apt-get update
   apt-get install -y php8.2-fpm php8.2-mysql php8.2-curl php8.2-intl php8.2-mbstring php8.2-xml php8.2-gd php8.2-zip php8.2-bcmath
   ```

5. **Install Composer**
   ```bash
   curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
   ```

### Phase 3: Application Deployment

1. **Clone Repository**
   ```bash
   mkdir -p /var/www
   cd /var/www
   git clone ${REPO_URL} likizopro
   cd likizopro
   git checkout ${BRANCH}
   ```

2. **Install Dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Configure Environment**
   ```bash
   cp .env.example .env
   
   # Update .env with database credentials
   sed -i "s/database.default.database = .*/database.default.database = ${DB_NAME}/" .env
   sed -i "s/database.default.username = .*/database.default.username = ${DB_USER}/" .env
   sed -i "s/database.default.password = .*/database.default.password = ${DB_PASS}/" .env
   sed -i "s|app.baseURL = .*|app.baseURL = 'https://${DOMAIN_NAME}/'|" .env
   ```

4. **Run Migrations**
   ```bash
   php spark migrate
   php spark db:seed DatabaseSeeder
   ```

5. **Set Permissions**
   ```bash
   chown -R www-data:www-data /var/www/likizopro
   chmod -R 755 /var/www/likizopro
   chmod -R 777 /var/www/likizopro/writable
   ```

### Phase 4: Nginx Configuration

Create `/etc/nginx/sites-available/likizopro`:

```nginx
server {
    listen 80;
    server_name DOMAIN_NAME www.DOMAIN_NAME;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name DOMAIN_NAME www.DOMAIN_NAME;
    root /var/www/likizopro/public;
    index index.php index.html;

    # SSL Certificates
    ssl_certificate /etc/letsencrypt/live/DOMAIN_NAME/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/DOMAIN_NAME/privkey.pem;

    # PHP Processing
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Main Routing
    location / {
        try_files $uri $uri/ /index.php/$args;
    }

    # Security
    location ~ /\. { deny all; }
    location ~ /\.env { deny all; }
}
```

Enable the site:
```bash
ln -s /etc/nginx/sites-available/likizopro /etc/nginx/sites-enabled/
rm /etc/nginx/sites-enabled/default
nginx -t
systemctl restart nginx
```

### Phase 5: SSL Certificate

```bash
apt-get install -y certbot python3-certbot-nginx

certbot --nginx -d DOMAIN_NAME -d www.DOMAIN_NAME \
    --non-interactive --agree-tos \
    -m admin@likizopro.co.tz --redirect
```

---

## Agent Execution Checklist

Mark each step as complete after execution:

- [ ] **1. Server Creation**
  - Droplet created with Ubuntu 22.04 LTS
  - SSH key added
  - Droplet IP noted: `______________`

- [ ] **2. DNS Configuration**
  - A record pointing to droplet IP
  - CNAME for www
  - Propagation verified

- [ ] **3. LEMP Stack**
  - [ ] Nginx installed and running
  - [ ] MySQL installed and configured
  - [ ] PHP 8.2 installed with all extensions
  - [ ] Composer installed

- [ ] **4. Application**
  - [ ] Repository cloned
  - [ ] Dependencies installed
  - [ ] .env configured
  - [ ] Migrations run
  - [ ] Seeders run

- [ ] **5. Web Server**
  - [ ] Nginx configured
  - [ ] Site enabled
  - [ ] Permissions set correctly

- [ ] **6. SSL**
  - [ ] Let's Encrypt certificate issued
  - [ ] Auto-renewal configured
  - [ ] HTTPS working

- [ ] **7. Testing**
  - [ ] Login page loads: `https://DOMAIN_NAME`
  - [ ] Default credentials work: `admin@likizopro.co.tz` / `123456`
  - [ ] Database connection successful
  - [ ] No errors in logs

---

## Verification Commands

```bash
# Check services
systemctl status nginx
systemctl status mysql
systemctl status php8.2-fpm

# Check application logs
tail -f /var/www/likizopro/writable/logs/

# Test database connection
mysql -u likizo_user -p -e "SHOW DATABASES;"

# Test API endpoint
curl -I https://likizopro.yourdomain.com/api/auth/login
```

---

## Troubleshooting Guide

| Issue | Solution |
|-------|----------|
| **502 Bad Gateway** | Check PHP-FPM: `systemctl status php8.2-fpm` |
| **Database Connection Failed** | Verify .env credentials and MySQL user privileges |
| **SSL Certificate Failed** | Ensure domain DNS is pointing to droplet |
| **Permission Denied** | Run: `chown -R www-data:www-data /var/www/likizopro` |
| **White Screen** | Check PHP error logs: `/var/www/likizopro/writable/logs/` |

---

## Post-Deployment Steps

1. **Change Admin Password** - Immediately change the default admin password
2. **Update JWT Key** - Generate a new secure key in `.env`
3. **Configure Backups** - Set up automated MySQL backups
4. **Monitor Logs** - Set up log monitoring (e.g., Linode Longview)
5. **Setup Redis** (Optional) - For session/caching optimization

---

## Rollback Procedure

If deployment fails catastrophically:

1. **Destroy Droplet**
2. **Create New Droplet** with the deployment script
3. **Import Database Backup** if available:
   ```bash
   mysql -u likizo_user -p likizopro_db < backup.sql
   ```
4. **Restore Application Code** from Git

---

**End of Agent Instructions**
