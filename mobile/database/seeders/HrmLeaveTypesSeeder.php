<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HrmLeaveType;

class HrmLeaveTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            [
                'code' => 'CSL',
                'name' => 'Casual or Sick Leave',
                'yearly_limit' => 12,
                'is_paid' => true,
                'carry_forward' => false,
                'is_active' => true,
            ],
            [
                'code' => 'MEL',
                'name' => 'Maternity Leave',
                'yearly_limit' => 24,
                'is_paid' => true,
                'carry_forward' => false,
                'is_active' => true,
            ],
            [
                'code' => 'CPL',
                'name' => 'Compensatory Leave',
                'yearly_limit' => 2,
                'is_paid' => true,
                'carry_forward' => false,
                'is_active' => true,
            ],
            [
                'code' => 'LOP',
                'name' => 'Loss Of Pay',
                'yearly_limit' => 0,
                'is_paid' => false,
                'carry_forward' => false,
                'is_active' => true,
            ],
            [
                'code' => 'ADL',
                'name' => 'Admin Leave',
                'yearly_limit' => 30,
                'is_paid' => true,
                'carry_forward' => false,
                'is_active' => true,
            ],
        ];

        foreach ($leaveTypes as $type) {
            HrmLeaveType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
