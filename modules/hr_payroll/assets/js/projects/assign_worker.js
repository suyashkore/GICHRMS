(function($) {
	"use strict";

	var _project_id = $('input[name="_project_id"]').val();
	var InvoiceServerParams = {
		"_project_id": "input[name='_project_id']",
	};

	var table_assign_worker = $('.table-table_assign_worker');

	initDataTable(table_assign_worker, admin_url+'hr_payroll/table_assign_worker',[],[], InvoiceServerParams, [5 ,'desc']);

	$('.delivery_sm').DataTable().columns([0]).visible(false, false);

})(jQuery);

function assign_worker_modal(assign_worker_id) {
	"use strict";
	var _project_id = $('input[name="_project_id"]').val();

	$("#modal_wrapper").load(admin_url + "hr_payroll/load_assign_worker_modal", {
		assign_worker_id: assign_worker_id,
		_project_id: _project_id,
	}, function() {
		$("body").find('#assign_workerModal').modal({ show: true, backdrop: 'static' });
		init_selectpicker();
		init_datepicker();

	});

}

function delete_assign_worker(id) {
	"use strict";

	if (confirm_delete()) {
		$.post(admin_url + "hr_payroll/delete_assign_worker/" + id).done(function (response) {
			response = JSON.parse(response);

			if (response.success === true || response.success == "true") {
				alert_float('success', response.message)
				$('.table-table_assign_worker').DataTable().ajax.reload();
			}
		});
	}
}