# =====================================================================
# TERRAFORM CONFIGURATION FOR DIGITAL OCEAN - LIKIZOPRO
# =====================================================================
# This Terraform script creates:
# 1. Digital Ocean Droplet (Ubuntu 22.04)
# 2. Floating IP (optional)
# 3. Domain DNS Records
# 4. SSH Key
#
# Usage:
#   1. Install Terraform: https://www.terraform.io/downloads
#   2. Create terraform.tfvars with your values
#   3. Run: terraform init
#   4. Run: terraform plan
#   5. Run: terraform apply
# =====================================================================

terraform {
  required_version = ">= 1.0"
  
  required_providers {
    digitalocean = {
      source  = "digitalocean/digitalocean"
      version = "~> 2.0"
    }
  }
}

# =====================================================================
# CONFIGURATION (Fill in your values)
# =====================================================================

# API Token - Get from Digital Ocean Console
# Export as: export DO_TOKEN="your_token"

# Project Name
variable "project_name" {
  description = "Name of the project"
  type        = string
  default     = "likizopro"
}

# Droplet Configuration
variable "droplet_size" {
  description = "Droplet size (s-1vcpu-1gb = $6/mo)"
  type        = string
  default     = "s-1vcpu-1gb"
}

variable "droplet_region" {
  description = "Region (nyc1, lon1, sgp1, etc.)"
  type        = string
  default     = "nyc1"
}

variable "domain_name" {
  description = "Your domain name"
  type        = string
  default     = "likizopro.example.com"
}

# Database Configuration
variable "db_name" {
  description = "Database name"
type        = string
  default     = "likizopro_db"
}

variable "db_user" {
  description = "Database user"
  type        = string
  default     = "likizo_user"
}

variable "db_password" {
  description = "Database password"
  type        = string
  sensitive   = true
}

# =====================================================================
# RESOURCES
# =====================================================================

# SSH Key
resource "digitalocean_ssh_key" "likizopro_ssh" {
  name       = "${var.project_name}-ssh-key"
  public_key = file("~/.ssh/id_rsa.pub")
}

# Droplet
resource "digitalocean_droplet" "likizopro_server" {
  image    = "ubuntu-22-04-x64"
  name     = var.project_name
  region   = var.droplet_region
  size     = var.droplet_size
  ssh_keys = [digitalocean_ssh_key.likizopro_ssh.fingerprint]

  # User Data - Cloud-init script for automatic provisioning
  user_data = <<-EOF
    #cloud-config
    packages:
      - git
      - curl
      - wget
      - zip
      - unzip
      - nginx
      - mysql-server
      - software-properties-common
    
    runcmd:
      # Add PHP repository
      - add-apt-repository -y ppa:ondrej/php
      
      # Update and install PHP
      - apt-get update
      - apt-get install -y php8.2-fpm php8.2-mysql php8.2-curl php8.2-intl php8.2-mbstring php8.2-xml php8.2-gd php8.2-zip php8.2-bcmath
      
      # Install Composer
      - curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
      
      # Clone and setup application
      - cd /var/www
      - git clone https://github.com/your-org/likizopro.git
      - cd likizopro
      - composer install --no-dev --optimize-autoloader
      
      # Set permissions
      - chown -R www-data:www-data /var/www/likizopro
      - chmod -R 755 /var/www/likizopro
      - chmod -R 777 /var/www/likizopro/writable
      
      # Configure environment
      - cp .env.example .env
      - sed -i 's/database.default.database = .*/database.default.database = ${var.db_name}/' .env
      - sed -i 's/database.default.username = .*/database.default.username = ${var.db_user}/' .env
      - sed -i 's/database.default.password = .*/database.default.password = ${var.db_password}/' .env
      
      # Run migrations
      - php spark migrate
      - php spark db:seed DatabaseSeeder
      
      # Configure Nginx
      - cat > /etc/nginx/sites-available/likizopro << 'NGINX'
        server {
            listen 80;
            server_name ${var.domain_name};
            root /var/www/likizopro/public;
            index index.php;
            
            location / {
                try_files $uri $uri/ /index.php/$args;
            }
            
            location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/run/php/php8.2-fpm.sock;
            }
        }
      NGINX
      
      - ln -s /etc/nginx/sites-available/likizopro /etc/nginx/sites-enabled/
      - rm -f /etc/nginx/sites-enabled/default
      - nginx -t
      - systemctl restart nginx
      - systemctl restart php8.2-fpm
      
      # Setup firewall
      - ufw --force enable
      - ufw allow 'Nginx Full'
      - ufw allow OpenSSH
      
      # Install SSL
      - apt-get install -y certbot python3-certbot-nginx
      - certbot --nginx -d ${var.domain_name} --non-interactive --agree-tos -m admin@likizopro.co.tz --redirect
  EOF

  tags = [var.project_name, "production"]
}

# Domain Record (if using Digital Ocean DNS)
resource "digitalocean_record" "domain_a" {
  domain = var.domain_name
  type   = "A"
  name   = "@"
  value  = digitalocean_droplet.likizopro_server.ipv4_address
  ttl    = 300
}

resource "digitalocean_record" "domain_cname" {
  domain = var.domain_name
  type   = "CNAME"
  name   = "www"
  value  = "@"
  ttl    = 300
}

# =====================================================================
# OUTPUTS
# =====================================================================

output "droplet_ip" {
  description = "Droplet IPv4 Address"
  value       = digitalocean_droplet.likizopro_server.ipv4_address
}

output "droplet_name" {
  description = "Droplet Name"
  value       = digitalocean_droplet.likizopro_server.name
}

output "domain_name" {
  description = "Domain Name"
  value       = var.domain_name
}

output "login_command" {
  description = "SSH Login Command"
  value       = "ssh root@${digitalocean_droplet.likizopro_server.ipv4_address}"
}
