<script type="text/javascript">
    $(function () {
        'use strict';

        var zkbio_time_params = {
        };
        var zkbio_time_table = $('table.table-zkbio_time_table');
        var _table_api = initDataTable(zkbio_time_table, admin_url + 'timesheets/zkbio_time_table', [0], [0], zkbio_time_params, ['5', 'desc']);
        var hidden_columns = [0];
        $('.table-zkbio_time_table').DataTable().columns(hidden_columns).visible(false, false);

        var zkbio_time_synch_params = {
        };
        var zkbio_time_synch_table = $('table.table-zkbio_time_synch_table');
        var _table_api = initDataTable(zkbio_time_synch_table, admin_url + 'timesheets/zkbio_time_synch_table', [0], [0], zkbio_time_synch_params, ['0', 'desc']);
        var hidden_columns = [0];
        $('.table-zkbio_time_synch_table').DataTable().columns(hidden_columns).visible(false, false);


    });

    function setting_zkbio_time(name) {
        "use strict";

        if (name == 'kteco_integration' || name == 'kteco_create_employee') {
            var value = $('input[id="' + name + '"]').is(":checked");
        } else {
            var value = $('input[id="' + name + '"]').val();
        }

        var data = {};
        data.name = name;
        data.value = value;

        $.post(admin_url + 'timesheets/setting_zkbio_time', data).done(function (response) {
            response = JSON.parse(response);
            if (response.success == true) {
                alert_float('success', response.message);
            } else {
                alert_float('warning', response.message);

            }
        });
    }

    function zkbio_mapping_timekeeping(name) {
        "use strict";

        var data = {};

        $.post(admin_url + 'timesheets/zkbio_mapping_timekeeping', data).done(function (response) {
            response = JSON.parse(response);
            if (response.success == true) {
                alert_float('success', response.message);
            }else{
                alert_float('success', response.message);
            }
            $('.table-zkbio_time_table').DataTable().ajax.reload(null, false);
        });
    }

    function cal_timesheet_hours(){
        "use strict";

        $('#cal_timesheet_modal').modal('show');
        $('.edit-title').addClass('hide');
        $('.add-title').removeClass('hide');

    }

</script>
