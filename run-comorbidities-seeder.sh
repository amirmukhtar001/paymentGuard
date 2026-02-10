#!/bin/bash

# Run Comorbidities Seeder Only
# This script runs only the comorbidities seeder for the ReferSystem module

echo "Running Comorbidities Seeder..."

php artisan db:seed --class="Modules\\ReferSystem\\Database\\Seeders\\ComorbiditiesSeeder"

if [ $? -eq 0 ]; then
    echo "✅ Comorbidities seeder completed successfully!"
else
    echo "❌ Comorbidities seeder failed!"
    exit 1
fi