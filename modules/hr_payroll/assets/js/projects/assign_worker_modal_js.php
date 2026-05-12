<script type="text/javascript">
    $(function(){
        'use strict';

        $( document ).ready(function() {
            assign_worker_appFormValidator(true);
        });

        
        $("#add_edit_assign_worker input[name='is_bonus']").on("change", function () {
            "use strict";

            var is_bonus = $("input[name='is_bonus']").prop('checked');
            if(is_bonus){
                $('.bonus_hide').removeClass('hide');
                $('.hourly_rate_hide').addClass('hide');
            }else{
                $('.bonus_hide').addClass('hide');
                $('.hourly_rate_hide').removeClass('hide');
            }

            assign_worker_appFormValidator();
        });

        $("#add_edit_assign_worker select[name='staff_id[]']").on("change", function () {
            "use strict";

            var staff_id = $("select[name='staff_id[]']").val();
            var data = {};
            data.staff_id = staff_id;
            console.log('staff_id', staff_id.length);
            $("#add_edit_assign_worker input[name='hourly_rate']").val(0);

            if(staff_id.length == 1){
                $.post(admin_url + "hr_payroll/get_hourly_rate_by_staff", data).done(function (response) {
                    response = JSON.parse(response);

                    if (response) {
                        $("#add_edit_assign_worker input[name='hourly_rate']").val(response.hourly_rate);
                    }
                });
            }
        });

    });

    function SubmitHandler(form) {
        "use strict";

        form = $('#add_edit_assign_worker');
        
        var assign_worker_id = 0;
        var formURL = form[0].action;
        var formData = new FormData($(form)[0]);

        $('#box-loading').show();
        $('.assign_worker_submit_button').attr( "disabled", "disabled" );

        $.ajax({
            type: $(form).attr("method"),
            data: formData,
            mimeType: $(form).attr("enctype"),
            contentType: false,
            cache: false,
            processData: false,
            url: formURL,
        }).done(function(response) {
            var response = JSON.parse(response);
            $('#box-loading').addClass('hide');

            if(response.success == true || response.success == 'true'){
                alert_float('success', response.message);
            }
            $("#assign_workerModal").modal("hide");
            $('.table-table_assign_worker').DataTable().ajax.reload();
            
        });
        return false;
    }

    function assign_worker_appFormValidator(create) {
        "use strict";

        var is_bonus = $("input[name='is_bonus']").prop('checked');
        if(is_bonus){

            $('#add_edit_assign_worker').appFormValidator({
                rules: {
                    'staff_id[]': 'required',
                    date_assign: 'required',
                    bonus_amount: 'required',
                },
                onSubmit: SubmitHandler,
                messages: {
                },
            });

        }else{

            $('#add_edit_assign_worker').appFormValidator({
                rules: {
                    'staff_id[]': 'required',
                    date_assign: 'required',
                    hours: 'required',
                    hourly_rate: 'required',
                },
                onSubmit: SubmitHandler,
                messages: {
                },
            });
        }
    }


</script>