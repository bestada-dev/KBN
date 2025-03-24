#!/bin/bash

# Hentikan dan hapus container lama jika ada
echo "🛑 Stopping and removing old containers..."
sudo docker stop production_kbn_container production_kbn_mysql_container 2>/dev/null
sudo docker rm production_kbn_container production_kbn_mysql_container 2>/dev/null

# Build MySQL image dari Dockerfile-mysql
echo "🐬 Building MySQL image..."
sudo docker build -t mysql:latest -f Dockerfile-mysql .

# Build Laravel image dari Dockerfile-for-production
echo "📦 Building Laravel image..."
sudo docker build -t production_kbn_image -f Dockerfile-for-production .

# Jalankan MySQL container
echo "🚀 Running MySQL container..."
sudo docker run -d \
  --name production_kbn_mysql_container \
  --restart unless-stopped \
  -e MYSQL_DATABASE=kbn_bestada_co_id \
  -e MYSQL_ROOT_PASSWORD=asdfgh \
  -p 3311:3306 \
  mysql:latest

# Jalankan Laravel container
echo "🚀 Running Laravel container..."
sudo docker run -d \
  --name production_kbn_container \
  --restart unless-stopped \
  --link production_kbn_mysql_container:db \
  -v $(pwd):/var/www/html \
  -p 2100:2100 \
  production_kbn_image

# Tampilkan container yang sedang berjalan
echo "✅ Containers are up:"
sudo docker ps