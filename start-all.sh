#!/bin/bash

echo "========================================"
echo "PDF Generator for WordPress - Information Portal"
echo "========================================"
echo ""
echo "Starting web server on port 5000..."
echo "Serving plugin information and documentation"
echo ""

# Start PHP server on port 5000, serving from root directory
cd /home/runner/${REPL_SLUG}
exec php -S 0.0.0.0:5000 -t .
