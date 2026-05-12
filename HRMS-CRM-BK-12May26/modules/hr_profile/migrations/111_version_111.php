<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_111 extends App_module_migration
{
	public function up()
	{
		
		$CI = &get_instance();
		add_option('hide_general_staff_information', 0, 1);
	}
}
