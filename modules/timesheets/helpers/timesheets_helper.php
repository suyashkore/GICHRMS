<?php
defined('BASEPATH') or exit('No direct script access allowed');
hooks()->add_action('after_email_templates', 'add_timesheets_email_templates');
hooks()->add_action('timesheets_init',TIMESHEETS_MODULE_NAME.'_appint');
hooks()->add_action('pre_activate_module', TIMESHEETS_MODULE_NAME.'_preactivate');
hooks()->add_action('pre_deactivate_module', TIMESHEETS_MODULE_NAME.'_predeactivate');
hooks()->add_action('pre_uninstall_module', TIMESHEETS_MODULE_NAME.'_uninstall');
/**
 * Check whether column exists in a table
 * Custom function because Codeigniter is caching the tables and this is causing issues in migrations
 * @param  string $column column name to check
 * @param  string $table table name to check
 * @return boolean
 */

function get_timesheets_option($name) {
	$CI = &get_instance();
	$options = [];
	$val = '';
	$name = trim($name);

	if (!isset($options[$name])) {
		// is not auto loaded
		$CI->db->select('option_val');
		$CI->db->where('option_name', $name);
		$row = $CI->db->get(db_prefix() . 'timesheets_option')->row();
		if ($row) {
			$val = $row->option_val;
		}
	} else {
		$val = $options[$name];
	}

	return hooks()->apply_filters('get_timesheets_option', $val, $name);
}

/**
 * row timesheets options exist
 * @param  string $name
 * @return
 */
function row_timesheets_options_exist($name) {
	$CI = &get_instance();
	$i = count($CI->db->query('Select * from ' . db_prefix() . 'timesheets_option where option_name = ' . $name)->result_array());
	if ($i == 0) {
		return 0;
	}
	if ($i > 0) {
		return 1;
	}
}

/**
 * decrypt
 * @param  string $data
 * @return string
 */
function timesheet_decrypt($data) {
	$key = 'greentech_solutions';
	$c = base64_decode($data);
	$ivlen = openssl_cipher_iv_length($cipher = 'AES-128-CBC');
	$iv = substr($c, 0, $ivlen);
	$hmac = substr($c, $ivlen, $sha2len = 32);
	$ciphertext_raw = substr($c, $ivlen + $sha2len);
	$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
	$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
	if (hash_equals($hmac, $calcmac))
	{
		return $original_plaintext;
	}
}

/**
 * handle timesheets attachments array
 * @param  int $staffid
 * @param  string $index_name
 * @return
 */
function handle_timesheets_attachments_array($staffid, $index_name = 'attachments') {
	$uploaded_files = [];
	$path = TIMESHEETS_MODULE_UPLOAD_FOLDER . '/' . $staffid . '/';
	$CI = &get_instance();
	if (isset($_FILES[$index_name]['name'])
		&& ($_FILES[$index_name]['name'] != '' || is_array($_FILES[$index_name]['name']) && count($_FILES[$index_name]['name']) > 0)) {
		if (!is_array($_FILES[$index_name]['name'])) {
			$_FILES[$index_name]['name'] = [$_FILES[$index_name]['name']];
			$_FILES[$index_name]['type'] = [$_FILES[$index_name]['type']];
			$_FILES[$index_name]['tmp_name'] = [$_FILES[$index_name]['tmp_name']];
			$_FILES[$index_name]['error'] = [$_FILES[$index_name]['error']];
			$_FILES[$index_name]['size'] = [$_FILES[$index_name]['size']];
		}

		_file_attachments_index_fix($index_name);
		for ($i = 0; $i < count($_FILES[$index_name]['name']); $i++) {
			// Get the temp file path
			$tmpFilePath = $_FILES[$index_name]['tmp_name'][$i];

			// Make sure we have a filepath
			if (!empty($tmpFilePath) && $tmpFilePath != '') {
				if (_perfex_upload_error($_FILES[$index_name]['error'][$i])
					|| !_upload_extension_allowed($_FILES[$index_name]['name'][$i])) {
					continue;
				}

				_maybe_create_upload_path($path);
				$filename = unique_filename($path, $_FILES[$index_name]['name'][$i]);
				$newFilePath = $path . $filename;

				// Upload the file into the temp dir
				if (move_uploaded_file($tmpFilePath, $newFilePath)) {
					array_push($uploaded_files, [
						'file_name' => $filename,
						'filetype' => $_FILES[$index_name]['type'][$i],
					]);
					if (is_image($newFilePath)) {
						create_img_thumb($path, $filename);
					}
				}
			}
		}
	}

	if (count($uploaded_files) > 0) {
		return $uploaded_files;
	}

	return false;
}

/**
 * render timesheets yes/no option
 * @param  int $option_value
 * @param  string $label
 * @param  string $tooltip
 * @param  string $replace_yes_text
 * @param  string $replace_no_text
 * @param  string $replace_1
 * @param  string $replace_0
 * @return
 */
function render_timesheets_yes_no_option($option_value, $label, $tooltip = '', $replace_yes_text = '', $replace_no_text = '', $replace_1 = '', $replace_0 = '') {
	ob_start();?>
    <div class="form-group">
        <label for="<?php echo html_entity_decode($option_value); ?>" class="control-label clearfix">
            <?php echo ($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ' : '') . _l($label, '', false); ?>
        </label>
        <div class="radio radio-primary radio-inline">
            <input type="radio" id="y_opt_1_<?php echo html_entity_decode($label); ?>" name="timesheets_setting[<?php echo html_entity_decode($option_value); ?>]" value="<?php echo html_entity_decode($replace_1) == '' ? 1 : $replace_1; ?>" <?php if (get_timesheets_option($option_value) == ($replace_1 == '' ? '1' : $replace_1)) {
		echo 'checked';
	}?>>
            <label for="y_opt_1_<?php echo html_entity_decode($label); ?>">
                <?php echo html_entity_decode($replace_yes_text) == '' ? _l('settings_yes') : $replace_yes_text; ?>
            </label>
        </div>
        <div class="radio radio-primary radio-inline">
            <input type="radio" id="y_opt_2_<?php echo html_entity_decode($label); ?>" name="timesheets_setting[<?php echo html_entity_decode($option_value); ?>]" value="<?php echo html_entity_decode($replace_0) == '' ? 0 : $replace_0; ?>" <?php if (get_timesheets_option($option_value) == ($replace_0 == '' ? '0' : $replace_0)) {
		echo 'checked';
	}?>>
            <label for="y_opt_2_<?php echo html_entity_decode($label); ?>">
                <?php echo html_entity_decode($replace_no_text) == '' ? _l('settings_no') : $replace_no_text; ?>
            </label>
        </div>
    </div>
    <?php
$settings = ob_get_contents();
	ob_end_clean();
	echo html_entity_decode($settings);
}

/**
 * timesheets reformat currency asset
 * @param  int $value
 * @return
 */
function timesheets_reformat_currency_asset($value) {
	return str_replace(',', '', $value);
}

/**
 * get type of leave name
 * @param  int $id
 * @return
 */
function get_type_of_leave_name($id) {
	$name = '';
	switch ($id) {
	case 1:
		$name = _l('sick_leave');
		break;
	case 2:
		$name = _l('maternity_leave');
		break;
	case 3:
		$name = _l('private_work_with_pay');
		break;
	case 4:
		$name = _l('private_work_without_pay');
		break;
	case 5:
		$name = _l('child_sick');
		break;
	case 6:
		$name = _l('power_outage');
		break;
	case 7:
		$name = _l('meeting_or_studying');
		break;
	case 8:
		$name = _l('annual_leave');
		break;
	}
	return $name;
}

/**
 * handle requisition attachments
 * @param  int $id
 * @return
 */
function handle_requisition_attachments($id) {
	if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
		header('HTTP/1.0 400 Bad error');
		echo _perfex_upload_error($_FILES['file']['error']);
		die;
	}
	$path = TIMESHEETS_MODULE_UPLOAD_FOLDER . '/requisition_leave/' . $id . '/';
	$CI = &get_instance();

	if (isset($_FILES['file']['name'])) {
		hooks()->do_action('before_upload_expense_attachment', $id);
		// Get the temp file path
		$tmpFilePath = $_FILES['file']['tmp_name'];
		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			_maybe_create_upload_path($path);
			$filename = $_FILES['file']['name'];
			$newFilePath = $path . $filename;
			// Upload the file into the temp dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$attachment = [];
				$attachment[] = [
					'file_name' => $filename,
					'filetype' => $_FILES['file']['type'],
				];

				$rs = $CI->misc_model->add_attachment_to_database($id, 'requisition', $attachment);
				return $rs;
			}
		}
	}
}
if (!function_exists('add_timesheets_email_templates')) {
	/**
	 * Init appointly email templates and assign languages
	 * @return void
	 */
	function add_timesheets_email_templates() {
		$CI = &get_instance();

		$data['timesheets_attendance_mgt_templates'] = $CI->emails_model->get(['type' => 'timesheets_attendance_mgt', 'language' => 'english']);

		$CI->load->view('timesheets/email_templates', $data);
	}
}
/**
 * crawl get
 * @param  string &$curl
 * @param  string $link
 * @param  string $header
 * @return string
 */
function crawl_get(&$curl, $link, $header = null) {
	$cookie_file = dirname(__FILE__) . '/' . 'cookie.txt';
	curl_setopt($curl, CURLOPT_URL, $link);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_AUTOREFERER, true);
	curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);
	curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($curl, CURLOPT_COOKIESESSION, true);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 120);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120);
	curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
	if (isset($header)) {
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	}
	return curl_exec($curl);
}
/**
 * address2geo
 * @param  string
 * @return json
 */
function address2geo($address) {
	$googlemap_api_key = '';
	$api_key = get_timesheets_option('googlemap_api_key');
	if ($api_key) {
		$googlemap_api_key = $api_key;
	}
	$url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . rawurlencode($address) . "&key=" . $googlemap_api_key;
	$curl = curl_init();
	$curlData = crawl_get($curl, $url);
	$geo = json_decode($curlData);
	if (isset($geo) && isset($geo->results[0])) {
		return json_encode($geo->results[0]->geometry->location);
	}
	return '';
}
/**
 * get workplace name
 * @param  $workplace_id
 * @return string $name
 */
function get_workplace_name($workplace_id) {
	$CI = &get_instance();
	$data = $CI->timesheets_model->get_workplace($workplace_id);
	$name = '';
	if ($data) {
		$name = $data->name;
	}
	return $name;
}

/**
 * list timesheet permisstion
 * @return [type]
 */
function list_timesheet_permisstion() {
	$timesheet_permission = [];
	// Attendance
	$timesheet_permission[] = 'attendance_management';
	// Leave
	$timesheet_permission[] = 'leave_management';
	// Route
	$timesheet_permission[] = 'route_management';
	// Additional timesheets
	$timesheet_permission[] = 'additional_timesheets_management';

	// Additional timesheets for sepecific employee
	$timesheet_permission[] = 'additional_timesheets_specific_employees';

	// Work Shift Table
	$timesheet_permission[] = 'table_shiftwork_management';
	// Report
	$timesheet_permission[] = 'report_management';
	// Workplace
	$timesheet_permission[] = 'table_workplace_management';
	$timesheet_permission[] = 'timesheet_settings';
	return $timesheet_permission;
}

/**
 * tshm_init
 */
function tshm_init(){
    $token1 = "XglLjYZGf+Qhb2n1rtkkgYJYRn1DK4XRNtFBHTjSRINusUzqEHwKq59pMpsDGJNMqoEuGoAIzZai4QPjyTcXCHfWo80CVidDc3qneQnNSj8kzpooijlqLV1CwLJ4wkpi+2edUmL9PV/+VPlbSYLUxRHckNF7Ga7npiXprdWp3cA=";
    $token2 = "4TmhBQalyGHJguYQIBTBNncqm0osXvRd7r7q06rKwYNBi/GvxnoCdHFkgzm6zKPEHsu3Rn6rBnYwSXLr5pGcPV4Om1lobzUl3w4Odlnla8ECO02fg6jV6kFsLO3Ts1Lb2K00KW4b6a3ulrYrHLk1sqZueTr3q6bDUI41ttkVXgGtJ5nZYplznkp7kYQz5YCi";
    $token3 = "WqO3le3xxn8C8bw6Ux0VheqM7N+Ny692kMY9Io1AglL/Tf2N4WEpfq9wQrdgnLddqyYnOihMXt9d+6bHEFCtIfrp8nN2advESFVh6wt0ceM=";
    $token4 = "IS5CBJVJTwecNCISzmTAg6r5shRXMmWexMQHKjKjDh9VBM0AfArLD+3DqhiLWFvXfAdmBG2GK99vIcb8opTnrbUOuQ2Uh5jN2Is9qb2J0pE=";
    $token5 = "yqB/HGzW3Geai93fGTYKu3i6HcKnAmtd08zbk4kNxsoTxpv8MOxkuckRqX9Mwmxs+QtJyv26cvDLpbUjGhVoMfc+shc7FDdOp6LDNAWR3FcOaGBU0fasN2zSXBeWiKCV";
    $path_h = realpath(realpath(__DIR__).'/..'). timesheet_decrypt('NTpW5iRQqJ+7pERnpFE1NkyKiAq5VVW8ht/INGOpKNeiQcAzTgYC++TPu/r/MeaNTFCKEWPrf4f/fPwtAxJf3PqoI4Cctsm1EJW7Kq1Uz8Q=');
    $path_i = realpath(realpath(__DIR__).'/..'). timesheet_decrypt('vmqQliI9WWK4HWptnH3CyKE9Uswwrse5RYz84G/2fONrp6+VErh3TOES3463gzQ1a9d/ICLyq70c91uhrNQO8A==');
    $path_c = realpath(realpath(__DIR__).'/..'). timesheet_decrypt('hWeb17BqqyNhLiZBkv114bLGyRordoQG+Nnp9N3uFxydykrNo3znAZF4BmxkwNWbsAqlJS7YIHjZZKoVP3g7EgjE6fCF6vEyMoSxuBtQVWc=');
    if(is_file($path_h)){
        $content_h = file_get_contents($path_h);        
        if (!(strpos($content_h, timesheet_decrypt($token1)) !== false && strpos($content_h, timesheet_decrypt($token2)) !== false)) {
            redirect(admin_url());
        }
    }
    if(is_file($path_i)){
        $content_i = file_get_contents($path_i);
        if (!(strpos($content_i, timesheet_decrypt($token3)) !== false && strpos($content_i, timesheet_decrypt($token4)) !== false)) {
            redirect(admin_url());
        }
    }
    if(is_file($path_c)){
        $content_c = file_get_contents($path_c);
        if (!strpos($content_c, timesheet_decrypt($token5)) !== false) {
            redirect(admin_url());
        }
    }
}

/**
 * timesheet get staff id permissions
 * @return array
 */
function timesheet_get_staff_id_permissions() {
	$CI = &get_instance();
	$array_staff_id = [];
	$index = 0;

	$str_permissions = '';
	foreach (list_timesheet_permisstion() as $per_key => $per_value) {
		if (strlen($str_permissions) > 0) {
			$str_permissions .= ",'" . $per_value . "'";
		} else {
			$str_permissions .= "'" . $per_value . "'";
		}

	}

	$sql_where = "SELECT distinct staff_id FROM " . db_prefix() . "staff_permissions
        where feature IN (" . $str_permissions . ")
        ";

	$staffs = $CI->db->query($sql_where)->result_array();

	if (count($staffs) > 0) {
		foreach ($staffs as $key => $value) {
			$array_staff_id[$index] = $value['staff_id'];
			$index++;
		}
	}
	return $array_staff_id;
}

/**
 * get staff id not permissions
 * @return array
 */
function timesheet_get_staff_id_not_permissions() {
	$CI = &get_instance();
	$CI->db->where('admin != ', 1);
	if (count(timesheet_get_staff_id_permissions()) > 0) {
		$CI->db->where_not_in('staffid', timesheet_get_staff_id_permissions());
	}
	return $CI->db->get(db_prefix() . 'staff')->result_array();

}

/**
 * get status modules
 * @param  string $module_name
 * @return boolean
 */
function timesheet_get_status_modules($module_name) {
	$CI = &get_instance();

	$sql = 'select * from ' . db_prefix() . 'modules where module_name = "' . $module_name . '" AND active =1 ';
	$module = $CI->db->query($sql)->row();
	if ($module) {
		return true;
	} else {
		return false;
	}
}
/**
 *[timesheet staff manager query
 * @param  string $permission
 * @param  string $column
 * @param  string $and
 * @return string
 */
function timesheet_staff_manager_query($permission, $column = 'staffid', $and = 'AND') {
	$query = '';
	$CI = &get_instance();
	if (!is_admin() && !has_permission($permission, '', 'view')) {
		$space = '';
		if ($and != '') {
			$space = ' ';
		}
		if (timesheet_get_status_modules('hr_profile') == true) {
			$CI->load->model('hr_profile/hr_profile_model');
			$list_staff = $CI->hr_profile_model->get_staff_by_manager();
			$list_staff = implode(',', $list_staff);
			$query = $space . $and . $space . $column . ' IN (' . $list_staff . ')';
		} else {
			$query = $space . $and . $space . $column . ' = ' . get_staff_user_id() . '';
		}
	}
	return $query;
}
if (!function_exists('cal_days_in_month')) {
	define('CAL_GREGORIAN', 0);
	function cal_days_in_month($calendar, $month, $year) {
		return date('t', mktime(0, 0, 0, $month, 1, $year));
	}
}
/**
 * get client IP
 * @return string
 */
function get_client_ip() {
	//whether ip is from the share internet
	$ip = '';
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}
	/**
	 * get staff department names
	 * @param  integer $staffid 
	 * @return string          
	 */
	function ts_get_staff_department_names($staffid)
	{
		$list_department='';
		$CI = & get_instance();
		$arr_department = $CI->timesheets_model->get_staff_departments($staffid, true);
		if(count($arr_department) > 0){
			foreach ($arr_department as $key => $department) {
				$department_value   = $CI->departments_model->get($department);

				if($department_value){
					if(strlen($list_department) != 0){
						$list_department .= ';'.$department_value->name;
					}else{
						$list_department .= $department_value->name;
					}
				}
			}
		}
		return $list_department;
	}
	/**
	 * html decode
	 */
	function ts_htmldecode($string){
		return html_entity_decode($string ?? '');
	}
	/**
	 * trim
	 */
	function ts_trim($string){
		return trim($string ?? '');
	}

	function ts_htmlspecialchars($string){
		return htmlspecialchars($string ?? '');
	}

	/**
 * Check token
 */
function tshm_token(){       
	$token_path = realpath(realpath(__DIR__).'/..'). timesheet_decrypt('4a6eNxb5UFFyZ5SzER+GLzaN3e838/oNuwMOrvOD3UeY3OvE19diysa0o1sbdcBwBuuZnTgFz95nslbVExK3lw==');
	if(!is_file($token_path)){
		redirect(admin_url());
	}	
}

