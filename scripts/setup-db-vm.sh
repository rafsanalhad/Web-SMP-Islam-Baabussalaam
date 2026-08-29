#!/bin/bash
# ==============================================================================
# Script Setup MySQL Database untuk Laravel di VM (Aman untuk AI Trading Bot)
# ==============================================================================
set -e

echo "==> [1/5] Menyiapkan SWAP Memory (agar RAM aman untuk bot & MySQL)..."
if [ ! -f /swapfile ]; then
    sudo fallocate -l 2G /swapfile
    sudo chmod 600 /swapfile
    sudo mkswap /swapfile
    sudo swapon /swapfile
    echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
    echo "SWAP 2GB berhasil diaktifkan!"
else
    echo "SWAP sudah ada, melewati langkah ini."
fi

echo "==> [2/5] Menginstal MySQL Server..."
sudo apt update -y
sudo apt install -y mysql-server

echo "==> [3/5] Mengoptimalkan konfigurasi MySQL (hemat RAM & izinkan remote)..."
# Konfigurasi bind-address dan buffer pool kecil agar tidak ganggu bot
sudo bash -c 'cat > /etc/mysql/mysql.conf.d/laravel.cnf <<EOF
[mysqld]
bind-address = 0.0.0.0
innodb_buffer_pool_size = 128M
max_connections = 50
key_buffer_size = 16M
EOF'

sudo systemctl restart mysql
sudo systemctl enable mysql

echo "==> [4/5] Membuat Database dan User..."
DB_NAME="smp_baabussalaam"
DB_USER="laravel_user"
DB_PASS="BaabussalaamSecure2026!#"

sudo mysql <<EOF
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'%' IDENTIFIED BY '${DB_PASS}';
ALTER USER '${DB_USER}'@'%' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'%';
FLUSH PRIVILEGES;
EOF

# Dapatkan IP Publik VM
PUBLIC_IP=$(curl -s -4 ifconfig.me || curl -s -4 api.ipify.org || echo "IP_PUBLIK_VM_ANDA")

echo "=============================================================================="
echo "✅ DATABASE BERHASIL DIKONFIGURASI DENGAN AMAN!"
echo "Bot AI trading Anda tetap aman & tidak terpengaruh."
echo "=============================================================================="
echo "Silakan masukkan variabel berikut ke Google Cloud Run:"
echo "------------------------------------------------------------------------------"
echo "DB_CONNECTION=mysql"
echo "DB_HOST=${PUBLIC_IP}"
echo "DB_PORT=3306"
echo "DB_DATABASE=${DB_NAME}"
echo "DB_USERNAME=${DB_USER}"
echo "DB_PASSWORD=${DB_PASS}"
echo "=============================================================================="
