<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HrmRequestType;

class HrmRequestTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requestTypes = [
            [
                'code'       => 'LEAVE',
                'name'       => 'Leave',
                'icon'       => 'calendar',
                'color_code' => '#3B82F6',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'code'       => 'REGULARISATION',
                'name'       => 'Regularisation',
                'icon'       => 'clock',
                'color_code' => '#10B981',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'code'       => 'WFH',
                'name'       => 'Work From Home',
                'icon'       => 'home',
                'color_code' => '#8B5CF6',
                'sort_order' => 3,
                'is_active'  => true,
            ],
            [
                'code'       => 'ON_DUTY',
                'name'       => 'On Duty',
                'icon'       => 'briefcase',
                'color_code' => '#F59E0B',
                'sort_order' => 4,
                'is_active'  => true,
            ],
            [
                'code'       => 'COMP_OFF',
                'name'       => 'Comp Off',
                'icon'       => 'refresh-cw',
                'color_code' => '#EF4444',
                'sort_order' => 5,
                'is_active'  => true,
            ],
            [
                'code'       => 'EXPENSE',
                'name'       => 'Expense',
                'icon'       => 'receipt',
                'color_code' => '#06B6D4',
                'sort_order' => 6,
                'is_active'  => true,
            ],
            [
                'code'       => 'RESTRICTED_HOLIDAY',
                'name'       => 'Restricted Holiday',
                'icon'       => 'umbrella',
                'color_code' => '#EC4899',
                'sort_order' => 7,
                'is_active'  => true,
            ],
            [
                'code'       => 'SHORT_LEAVE',
                'name'       => 'Short Leave',
                'icon'       => 'timer',
                'color_code' => '#6366F1',
                'sort_order' => 8,
                'is_active'  => true,
            ],
        ];

        foreach ($requestTypes as $type) {
            HrmRequestType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
