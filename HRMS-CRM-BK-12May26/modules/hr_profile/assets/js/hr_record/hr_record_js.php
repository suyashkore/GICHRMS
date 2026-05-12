
<script>
	$(function(){
		'use strict';
		var StaffServerParams = {
			"status_work": "[name='status_work[]']",
			"hr_profile_deparment": "[name='hr_profile_deparment']",
			"staff_role": "[name='staff_role[]']",
			"staff_teammanage": "input[name='staff_dep_tree']",
		};
		var table_staff = $('table.table-table_staff');
		initDataTable(table_staff,admin_url + 'hr_profile/table', [0],[0], StaffServerParams, [1, 'desc']);
		set_hide_column('table-table_staff', 'table-table_staff_hide_column', false);

		//hide first column
		 var hidden_columns = [];
		 $('.table-table_staff').DataTable().columns(hidden_columns).visible(false, false);

		$.each(StaffServerParams, function() {
			$('#hr_profile_deparment').on('change', function() {
				table_staff.DataTable().ajax.reload()
				.columns.adjust()
				.responsive.recalc();
			});
		});
				//staff role
				$.each(StaffServerParams, function() {
					$('#staff_role').on('change', function() {
						table_staff.DataTable().ajax.reload()
						.columns.adjust()
						.responsive.recalc();
					});
				});
				//combotree filter by team manage
				$('#staff_dep_tree').on('change', function() {
					$('#staff_tree').val(tree_dep.getSelectedItemsId());
					table_staff.DataTable().ajax.reload()
					.columns.adjust()
					.responsive.recalc();
				});
				$.each(StaffServerParams, function() {
					$('#status_work').on('change', function() {
						table_staff.DataTable().ajax.reload()
						.columns.adjust()
						.responsive.recalc();
					});
				});

			//combotree
			var tree_dep_derpartment = $('#hrm_derpartment_tree').comboTree({
				source : <?php echo new_html_entity_decode($dep_tree) ?>
			});

			//staff combotree
			var tree_dep = $('#staff_dep_tree').comboTree({
				source : <?php echo new_html_entity_decode($staff_dep_tree);?>
			});

		})
//staff role end  
function delete_staff_member(id){
	'use strict';
	$('#delete_staff').modal('show');
	$('#transfer_data_to').find('option').prop('disabled',false);
	$('#transfer_data_to').find('option[value="'+id+'"]').prop('disabled',true);
	$('#delete_staff .delete_id input').val(id);
	$('#transfer_data_to').selectpicker('refresh');
}

var nodeTemplate = function(data) { 
	'use strict';

	if(data.name){
		return `
		<div class="staff-chart-background-color">
		${data.image}${data.name}
		</div>
		<div class="content chart_company_name"><i class=${data.dp_user_icon} class="staff-chart-margin"></i>  ${data.job_position_name}</div>
		<div class="content"><i class=${data.dp_icon} class="staff-chart-margin"></i>  ${data.departmentname}</div>
		`;
	}else{
		return `
		<div class="staff-chart-background-color">
		${data.image}${data.name}
		</div>
		<div class="content chart_company_name"><i class=${data.dp_user_icon} class="staff-chart-margin"></i>${data.title}</div>
		`;
	}
};

//load staff chart
window.onload = function () {
	'use strict';

	var img_dir = site_url + 'uploads/company/favicon.png';
	var ds = {
		'image':'<img class="img_logo" src=" '+img_dir+' ">' ,
		'name': '',
		'title': '<p class="title_company"><?php echo get_option('invoice_company_name'); ?></p>',
		'departmentname': '',
		'children': <?php echo new_html_entity_decode($staff_members_chart); ?>
	};
	var oc = $('#staff_chart').orgchart({
		'data' :ds ,
		'nodeTemplate': nodeTemplate,
		'pan': true,
		'zoom': true,
		nodeContent: "title",
		verticalLevel: 4,
		visibleLevel: 4,
		'toggleSiblingsResp': true,
		'createNode': function(node, data) {
			node.on('click', function(event) {
				if (!$(event.target).is('.edge, .toggleBtn')) {
					var this_obj = $(this);
					var chart_obj = this_obj.closest('.orgchart');
					var newX = window.parseInt((chart_obj.outerWidth(true)/2) - (this_obj.offset().left - chart_obj.offset().left) - (this_obj.outerWidth(true)/2));
					var newY = window.parseInt((chart_obj.outerHeight(true)/2) - (this_obj.offset().top - chart_obj.offset().top) - (this_obj.outerHeight(true)/2));
					chart_obj.css('transform', 'matrix(1, 0, 0, 1, ' + newX + ', ' + newY + ')');
				}
			});
		}
	});
};

function staff_bulk_actions(){
	'use strict';
	$('#table_staff_bulk_actions').modal('show');
}

function staff_delete_bulk_action(event) {
	'use strict';
	if (confirm_delete()) {
		var mass_delete = $('#mass_delete').prop('checked');

		if(mass_delete == true){
			var ids = [];
			var data = {};
			data.mass_delete = true;
   			data.rel_type = 'hrm_staff';

			var rows = $('#table-table_staff').find('tbody tr');
			$.each(rows, function() {
				var checkbox = $($(this).find('td').eq(0)).find('input');
				if (checkbox.prop('checked') === true) {
					ids.push(checkbox.val());
				}
			});
			data.ids = ids;
			$(event).addClass('disabled');
		
   			setTimeout(function() {
   				$.post(admin_url + 'hr_profile/hrm_delete_bulk_action', data).done(function() {
   					window.location.reload();
   				}).fail(function(data) {
   					$('#table_contract_bulk_actions').modal('hide');
   					alert_float('danger', data.responseText);
   				});
   			}, 200);

		}else{
			window.location.reload();
		}
	}
}

function hr_profile_add_staff(staff_id, role_id, add_new) {
	"use strict";

	$("#modal_wrapper").load("<?php echo admin_url('hr_profile/hr_profile/member_modal'); ?>", {
		slug: 'create',
		staff_id: staff_id,
		role_id: role_id,
		add_new: add_new
	}, function() {
		if ($('.modal-backdrop.fade').hasClass('in')) {
			$('.modal-backdrop.fade').remove();
		}
		if ($('#appointmentModal').is(':hidden')) {
			$('#appointmentModal').modal({
				show: true
			});
		}
	});

	init_selectpicker();
	$(".selectpicker").selectpicker('refresh');
}


function hr_profile_update_staff_manage_view(staff_id) {
	"use strict";

	$("#modal_wrapper").load("<?php echo admin_url('hr_profile/hr_profile/member_modal'); ?>", {
		slug: 'update',
		staff_id: staff_id,
		manage_staff: 'manage_staff'
	}, function() {
		if ($('.modal-backdrop.fade').hasClass('in')) {
			$('.modal-backdrop.fade').remove();
		}
		if ($('#appointmentModal').is(':hidden')) {
			$('#appointmentModal').modal({
				show: true
			});
		}
	});

	init_selectpicker();
	$(".selectpicker").selectpicker('refresh');
}

function view_staff_chart(){
	'use strict';
	$('#staff_chart_view').modal('show');
}


function staff_export_item(){
	"use strict";
	var ids = [];
	var data = {};

	data.mass_delete = true;
	data.rel_type = 'staff_list';

	var rows = $('#table-table_staff').find('tbody tr');
	$.each(rows, function() {
		var checkbox = $($(this).find('td').eq(0)).find('input');
		if (checkbox.prop('checked') === true) {
			ids.push(checkbox.val());
		}
	});
	data.ids = ids;

	$(event).addClass('disabled');

	if(data.ids.length > 0){
	setTimeout(function() {
		$.post(admin_url + 'hr_profile/create_staff_sample_file', data).done(function(response) {
			response = JSON.parse(response);
			if(response.success == true){
				alert_float('success', "<?php echo _l("create_export_file_success") ?>");

				$('#dowload_items').removeClass('hide');
				$('.hr_export_staff').addClass('hide');

				$('#dowload_items').attr({target: '_blank', 
					href  : site_url +response.filename});

			}else{
				alert_float('success', "<?php echo _l("create_export_file_fails") ?>");

			}

		}).fail(function(data) {


		});
	}, 200);
	}else{
		alert_float('warning', "<?php echo _l("please_select_the_employee_you_want_to_export_to_excel") ?>");

	}

}

// hidden columns
function set_hide_column(table_id, cookie_name, hide_fist_column){
    "use strict";
    var html = '';
    html += '<span class="sort-column">';
    html += '<a href="javascript:void(0)" class="selectBox" onclick="show_check_boxes(this)">';
    html += '<i class="fa fa-columns"></i>';
    html += '<div class="overSelect"></div>';
    html += '</a>';
    html += '<div id="list-checkboxes">';
    var list_tb_header = $('#'+table_id).find('tr th');
    for(let i = 0; i < list_tb_header.length; i++){
        if(hide_fist_column == true){
            if(i > 0){
                html += '<div class="checkbox-fade fade-in-primary">';
                html += '<label>';
                html += '<input type="checkbox" name="column['+i+']" id="column['+i+']" value="'+i+'" onchange="change_hidden_column(this,\''+table_id+'\',\''+cookie_name+'\')" checked>';
                html += '<span class="cr">';
                html += '<i class="cr-icon icofont icofont-ui-check txt-primary"></i>';
                html += '</span>';
                html += '<span>'+list_tb_header.eq(i).text()+'</span>';
                html += '</label>';
                html += '</div>';
            }
        }
        else{
            html += '<div class="checkbox-fade fade-in-primary">';
            html += '<label>';
            html += '<input type="checkbox" name="column['+i+']" id="column['+i+']" value="'+i+'" onchange="change_hidden_column(this,\''+table_id+'\',\''+cookie_name+'\')" checked>';
            html += '<span class="cr">';
            html += '<i class="cr-icon icofont icofont-ui-check txt-primary"></i>';
            html += '</span>';
            html += '<span>'+list_tb_header.eq(i).text()+'</span>';
            html += '</label>';
            html += '</div>';
        }
    }
    html += '</div>';
    html += '</span>';
    $('#'+table_id+'_wrapper').find('.dataTables_filter').append(html);
    set_hidden_column_from_ck(table_id, cookie_name, hide_fist_column);
}

function set_hidden_column_from_ck(table_id, cookie_name, hide_fist_column){
    "use strict";
    var table = $('#'+table_id).DataTable();
    if(hide_fist_column == true){
        table.column(0).visible( false, false );
    }
    var list_column_ck = getCookie(cookie_name);
    var id_list = list_column_ck.split(',');
    if(id_list.length > 0){
        var list_checkbox = $('#'+table_id+'_filter #list-checkboxes input[type="checkbox"]');
        for (let i = 0; i < list_checkbox.length; i++) {
            var obj = list_checkbox.eq(i);
            var obj_val = obj.val();
            let hide = 0;
            for (let j = 0; j < id_list.length; j++) {
                var index = id_list[j];
                if((index != '') && (index == obj_val)){
                    obj.prop('checked', false);
                    table.column(index).visible( false, false );
                    hide = 1;
                    break;
                }
            }
        }
    }
    table.columns.adjust().draw( false );
    document.getElementById(table_id).removeAttribute("style");
}

function getCookie(cname) {
    "use strict";
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function add_cookie(cname, cvalue, exdays) {
    "use strict";
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function show_check_boxes(el) {
    "use strict";
    var parent = $(el).closest('.sort-column');
    var dropdown = parent.find('#list-checkboxes');
    if (dropdown.hasClass('d-block')) {
        dropdown.removeClass('d-block');
    } else {
        dropdown.addClass('d-block');
    }
}

function change_hidden_column(el, table_id, cookie_name){
    "use strict";
    var table = $('#'+table_id).DataTable();
    var input = $(el);
    var value = input.val();
    var list_column_ck = getCookie(cookie_name);
    if(input.is(':checked')){
        var id_list = list_column_ck.split(',');
        list_column_ck = '';
        $.each(id_list, function(index, val) { 
            if((val != '') && (val != value)){
                list_column_ck += val+',';
            }
        });
        if(list_column_ck != ''){
            list_column_ck = rtrim(list_column_ck);
        }
        table.column(value).visible( true, true );
    }
    else{
        if(list_column_ck == ''){
            list_column_ck = value;
        }
        else{
            list_column_ck = list_column_ck+','+value;
        }
        table.column(value).visible( false, false );
    }

    table.columns.adjust().draw( false );
    document.getElementById(table_id).removeAttribute("style");
    add_cookie(cookie_name,list_column_ck,365);
}
function rtrim(str){
    return str.replace(/\,+$/, '');
}

</script>