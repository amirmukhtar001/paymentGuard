#!/bin/bash

echo "🔄 Running ReferSystem Database Seeders..."
echo "This will add 100 sample patients and 100 sample referrals"
echo ""

# Run the seeder
php artisan db:seed --class="Modules\ReferSystem\Database\Seeders\ReferSystemDatabaseSeeder"

echo ""
echo "✅ Seeding completed!"
echo ""
echo "📋 You can now:"
echo "   - View patients at: /refer-system/patients"
echo "   - View referrals at: /refer-system/referrals"
echo "   - Use API endpoints for mobile app"
echo ""