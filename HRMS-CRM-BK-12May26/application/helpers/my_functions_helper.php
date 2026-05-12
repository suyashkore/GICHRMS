<?php

// Version 2.3.0 and above
hooks()->add_filter('before_get_task_statuses','my_add_custom_task_status');

// Prior to version 2.3.0
// Uncomment the code below and remove the code above if you are using version older then 2.3.0
// add_action('before_get_task_statuses','my_add_custom_task_status');


function my_add_custom_task_status($current_statuses){
    // Push new status to the current statuses
    $current_statuses[] = array(
           'id'=>50, // new status with id 50
           'color'=>'#989898',
           'name'=>'Old Task Pending For Feedback',
           'order'=>10,
           'filter_default'=>true, // true or false

        );
    // Push another status (delete this code if you need to add only 1 status)
    /*$current_statuses[] = array(
          'id'=>51, //new status with new id 51
          'color'=>'#be51e0',
          'name'=>'Ready For Production',
          'order'=>11,
          'filter_default'=>true // true or false
        );*/

    // Return the statuses
    return $current_statuses;
}

// add_action('after_render_single_aside_menu', 'my_custom_menu_items'); function my_custom_menu_items($order)
// {
    
//         if (is_admin()) {
//             echo '<li><a href="#"><i class="fa fa-area-chart menu-icon" aria-hidden="true"></i>Leads Summary</a></li>';
//         }
    
// }

hooks()->add_action('admin_init', 'my_custom_menu_admin_items');

function my_custom_menu_admin_items()
{
    $CI = &get_instance();

    $CI->app_menu->add_sidebar_menu_item('custom-menu-unique-id', [
        'name'     => 'Lead Summary', // The name if the item
        'href'     => 'leads/lead_summary', // URL of the item
        'position' => 10, // The menu position, see below for default positions.
        'icon'     => 'fa fa-area-chart', // Font awesome icon
    ]);
}

/**
 * Register sidebar hook
 */

add_action('app_admin_footer', 'add_gic_topbar_link_js');

function add_gic_topbar_link_js()
{
    ?>
    <script>
    (function () {
        var navbar = document.querySelector('.navbar-nav.navbar-right');
        if (!navbar) return;

        // Prevent duplicate insert
        if (document.getElementById('gic-topbar-link')) return;

        var li = document.createElement('li');
        li.id = 'gic-topbar-link';

        var a = document.createElement('a');
        a.href = 'https://chat.google.com/room/AAAALLdbsFs?cls=7';
        a.target = '_blank';
        a.rel = 'noopener noreferrer';
        a.innerHTML = '<i class="fa fa-users"></i> <span class="hidden-xs">GIC Official</span>';

        li.appendChild(a);
        navbar.prepend(li); // puts it on the LEFT of top bar
    })();
    </script>
    <?php
}