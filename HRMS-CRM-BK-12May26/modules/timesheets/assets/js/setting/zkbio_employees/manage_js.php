<script type="text/javascript">
    $(function () {
        'use strict';

        var zkbio_employee_params = {
        };
        var zkbio_employee_table = $('table.table-zkbio_employee_table');
        var _table_api = initDataTable(zkbio_employee_table, admin_url + 'timesheets/zkbio_employee_table', [0], [0], zkbio_employee_params, ['1', 'asc']);
        var hidden_columns = [0];
        $('.table-zkbio_employee_table').DataTable().columns(hidden_columns).visible(false, false);

        $(".table-zkbio_employee_table").DataTable().on('draw', function () {
            init_selectpicker();
        });

        $("body").on("change", 'select[name="table-select-assignees"]', function () {
            var data = {};
            data.assignee = $(this).val();
            if (data.assignee !== "") {
                data.emp_id = $(this).attr("data-task-id");
                $.post(admin_url + "timesheets/zkbio_mapping_employee_manual", data).done(function (
                    response
                ) {
                    $(".table-zkbio_employee_table").DataTable().ajax.reload(null, false);
                });
            }
        });

    });

    function zkbio_mapping_employee(name) {
        "use strict";

        var data = {};

        $.post(admin_url + 'timesheets/zkbio_mapping_employee', data).done(function (response) {
            response = JSON.parse(response);
            if (response.success == true) {
                alert_float('success', response.message);
            }
            $(".table-zkbio_employee_table").DataTable().ajax.reload(null, false);
        });
    }

    function remove_mapping_employee(id, task_id, tabel_task) {
        if (confirm_delete()) {
            requestGetJSON("timesheets/remove_mapping_employee/" + id + "/" + task_id).done(
                function (response) {
                    if (response.success === true || response.success == "true") {
                        alert_float("success", response.message);
                        $(".table-zkbio_employee_table").DataTable().ajax.reload(null, false);

                    }
                }
            );
        }
    }


</script>