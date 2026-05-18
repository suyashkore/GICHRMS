<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HrmLeaveType;
use App\Models\HrmEmployeeLeaveBalance;
use App\Models\Staff;
use Carbon\Carbon;

class HrmEmployeeLeaveBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $year = Carbon::now()->year;

        $leaveTypes = HrmLeaveType::where('is_active', true)->get();

        $employees = Staff::all(); // change model if your employee model is different

        foreach ($employees as $employee) {
            foreach ($leaveTypes as $leaveType) {
                $allocated = $leaveType->yearly_limit;

                // For LOP keep large/unlimited style balance if needed
                if ($leaveType->code === 'LOP') {
                    $allocated = 365;
                }

                HrmEmployeeLeaveBalance::updateOrCreate(
                    [
                        'staff_id' => $employee->staffid,
                        'leave_type_id' => $leaveType->id,
                        'leave_year' => $year,
                    ],
                    [
                        'allocated' => $allocated,
                        'used' => 0,
                        'remaining' => $allocated,
                    ]
                );
            }
        }
    }
}