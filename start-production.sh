#!/bin/bash

# Hentikan dan hapus container Laravel lama jika ada
echo "🛑 Stopping and removing old Laravel container..."
sudo docker stop production_kbn_container 2>/dev/null
sudo docker rm production_kbn_container 2>/dev/null

# Build Laravel image dari Dockerfile-for-production
echo "📦 Building Laravel image..."
sudo docker build -t production_kbn_image -f Dockerfile-for-production .

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
echo "✅ Laravel container is up:"
sudo docker ps | grep production_kbn_container