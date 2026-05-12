<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hr_payroll_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
                
                [
                    'name'      => 'Pay period',
                    'key'       => '{month_created}',
                    'available' => [
                        'hr_payroll',
                    ],
                ],
                [
                    'name'      => 'Staff Firstname',
                    'key'       => '{staff_firstname}',
                    'available' => [
                        'hr_payroll',
                    ],
                ],
                [
                    'name'      => 'Staff Lastname',
                    'key'       => '{staff_lastname}',
                    'available' => [
                        'hr_payroll',
                    ],
                ],
                
            ];
    }

    /**
     * Merge fields for payslip_detail
     * @param  mixed $payslip_detail payslip_detail id
     * @return array
     */
    public function format($payslip_detail)
    {
        $fields = [];
        
        if (!$payslip_detail) {
            return $fields;
        }

        $fields['{month_created}']   = $payslip_detail->month_created;
        $fields['{staff_firstname}']        = $payslip_detail->firstname;
        $fields['{staff_lastname}']     = $payslip_detail->lastname;

        return hooks()->apply_filters('hr_payroll_merge_fields', $fields, [
        'id'       => $payslip_detail->id,
        'payslip_detail' => $payslip_detail,
     ]);
    }
}
