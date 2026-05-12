<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Contract_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
                [
                    'name'      => 'Contract PAN',
                    'key'       => '{contracts_pan_number}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Contract ID',
                    'key'       => '{contract_id}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Contract Subject',
                    'key'       => '{contract_subject}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Contract Description',
                    'key'       => '{contract_description}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Contract Date Start',
                    'key'       => '{contract_datestart}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Contract Date End',
                    'key'       => '{contract_dateend}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Contract Value',
                    'key'       => '{contract_contract_value}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Contract Link',
                    'key'       => '{contract_link}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Contract Type',
                    'key'       => '{contract_type}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Project name',
                    'key'       => '{project_name}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Created At',
                    'key'       => '{contract_created_at}',
                    'available' => [
                        'contract',
                    ],
                ],
                // Details contract
                [
                    'name'      => 'Project Duration',
                    'key'       => '{contracts_project_duration}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Facebook',
                    'key'       => '{contracts_facebook}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Linkedin',
                    'key'       => '{contracts_linkedin}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Instagram',
                    'key'       => '{contracts_instagram}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'GMB',
                    'key'       => '{contracts_google_my_business}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'YouTube',
                    'key'       => '{contracts_youtube}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'VideoCreation',
                    'key'       => '{contracts_VideoCreation}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Keyboard',
                    'key'       => '{contracts_keywords}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Blog',
                    'key'       => '{contracts_blogs}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Aditional Services',
                    'key'       => '{contracts_remark_for_a_smo}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Monthly Payable',
                    'key'       => '{contracts_monthly_payable}',
                    'available' => [
                        'contract',
                    ],
                ],
                [
                    'name'      => 'Monthly Due Date',
                    'key'       => '{contracts_monthly_due_date}',
                    'available' => [
                        'contract',
                    ],
                ],
            ];
    }

    /**
     * Merge field for contracts
     * @param  mixed $contract_id contract id
     * @return array
     */
    public function format($contract_id)
    {
        $fields = [];
        $this->ci->db->select(db_prefix() . 'contracts.id as id, subject, description, datestart, dateend, contract_value, hash, project_id,tblclients.company_pan, ' . db_prefix() . 'contracts_types.name as type_name,'.db_prefix().'contracts.dateadded as created_at');
        $this->ci->db->where('contracts.id', $contract_id);
        $this->ci->db->join(db_prefix() . 'contracts_types', '' . db_prefix() . 'contracts_types.id = ' . db_prefix() . 'contracts.contract_type', 'left');
        $this->ci->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.userid = ' . db_prefix() . 'contracts.client', 'left');
        $contract = $this->ci->db->get(db_prefix() . 'contracts')->row();
        // Contract details 
        $this->ci->db->select(db_prefix() . 'contractsDetails.*');
        $this->ci->db->where('tblcontractsDetails.ContractID', $contract_id);
        $contractDetails = $this->ci->db->get(db_prefix() . 'contractsDetails')->row();

        if (!$contract) {
            return $fields;
        }

        $currency = get_base_currency();

        $fields['{contracts_pan_number}']             = $contract->company_pan;
        $fields['{contract_id}']             = e($contract->id);
        $fields['{contract_subject}']        = e($contract->subject);
        $fields['{contract_type}']           = e($contract->type_name);
        $fields['{contract_description}']    = nl2br($contract->description);
        $fields['{contract_datestart}']      = e(_d($contract->datestart));
        $fields['{contract_dateend}']        = e(_d($contract->dateend));
        $fields['{contract_contract_value}'] = e(app_format_money($contract->contract_value, $currency));

        $fields['{contract_link}']       = site_url('contract/' . $contract->id . '/' . $contract->hash);
        $fields['{project_name}']        = e(get_project_name_by_id($contract->project_id));
        $fields['{contract_short_url}']  = get_contract_shortlink($contract);
        $fields['{contract_created_at}'] = e(_dt($contract->created_at));
        
        $fields['{contracts_project_duration}'] = $contractDetails->ProjectDuration;
        $fields['{contracts_facebook}'] = $contractDetails->Facebook;
        $fields['{contracts_linkedin}'] = $contractDetails->LinkedIn;
        $fields['{contracts_instagram}'] = $contractDetails->Instagram;
        $fields['{contracts_google_my_business}'] = $contractDetails->GMB;
        $fields['{contracts_youtube}'] = $contractDetails->YouTube;
        $fields['{contracts_VideoCreation}'] = $contractDetails->VideoCreation;
        $fields['{contracts_keywords}'] = $contractDetails->Keywords;
        $fields['{contracts_blogs}'] = $contractDetails->Blogs;
        $fields['{contracts_remark_for_a_smo}'] = $contractDetails->remark_a_smo;
        $fields['{contracts_monthly_payable}'] = $contractDetails->MonthlyPayment;
        $fields['{contracts_monthly_due_date}'] = $contractDetails->MonthlyDueDate;

        $custom_fields = get_custom_fields('contracts');
        foreach ($custom_fields as $field) {
            //$fields['{' . $field['slug'] . '}'] = get_custom_field_value($contract_id, $field['id'], 'contracts');
        }

        return hooks()->apply_filters('contract_merge_fields', $fields, [
        'id'       => $contract_id,
        'contract' => $contract,
     ]);
    }
}
