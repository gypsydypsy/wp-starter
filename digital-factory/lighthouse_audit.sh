#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

. ~/.profile
DIR="$( cd "$( dirname "$0" )" && pwd )"
cd $DIR/../htdocs/wp-content/themes/havas-starter-pack/front/

# Install dependencies
echo "📦 Installing npm dependencies..."
npm ci

# Run Lighthouse CI
echo "🚀 Running Lighthouse CI audit..."
npm run lhci || echo "⚠️ Lighthouse audit failed, but continuing..."

echo "✅ Lighthouse audit completed."
