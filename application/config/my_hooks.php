<?php
defined('BASEPATH') or exit('No direct script access allowed');

hooks()->add_action('admin_init', 'load_my_custom_helper');

function load_my_custom_helper()
{
    $CI = &get_instance();
    $CI->load->helper('my_functions');
}