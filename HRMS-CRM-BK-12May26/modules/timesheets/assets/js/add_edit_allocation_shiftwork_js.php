<script>
(function(){
  var hot;
  var list_type = <?php echo json_encode($shift_type); ?>;
  var setHeader = <?php echo json_encode($head_data); ?>;
  var dataObject = <?php echo json_encode($data_object); ?>;
      var keys = list_type.map(option => option.key);

  var staff = $('select[name="staff[]"]').val();
  var columns = [];
  "use strict";
      if(staff!= ''){
          columns = [{
            data: 'staffid',
            width: 250,
            type: 'text',
          },
          {
            data: 'staff',
            width: 250,
            type: 'text',
            readOnly: true,
          }];
          $.each(setHeader, function( index, value ) {
              if(index >= 2){
                columns.push({
                      data: value,
                      width: 250,
                      type: 'dropdown',
                      source: list_type.map(option => option.value), 
                      renderer: function (instance, td, row, col, prop, value, cellProperties) {
                        var option = list_type.find(option => option.key === value);
                        
                        Handsontable.renderers.TextRenderer.apply(this, arguments);
                        
                        td.innerHTML = option ? option.value : value;
                      },
                      validator: function(value, callback) {
                        var isValid = list_type.some(option => option.key === value);
                        callback(isValid); 
                      },
                });
              }
          });  
        }
        else{
          $.each(setHeader, function( index, value ) {
                columns.push({
                      data: value,
                      width: 250,
                      type: 'dropdown',
                      source: list_type.map(option => option.value), 
                      renderer: function (instance, td, row, col, prop, value, cellProperties) {
                        var option = list_type.find(option => option.key === value);
                        
                        Handsontable.renderers.TextRenderer.apply(this, arguments);
                        
                        td.innerHTML = option ? option.value : value;
                      },
                      validator: function(value, callback) {
                        var isValid = list_type.some(option => option.key === value);
                        callback(isValid); 
                      },
                });
          }); 
      }  

    var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;
    var hotSettings = {
      data: dataObject,
      columns: columns,
      contextMenu: true,
    manualRowMove: true,
    manualColumnMove: true,
    stretchH: 'all',
    autoWrapRow: true,
    rowHeights: 30,
    defaultRowHeight: 100,
    headerTooltips: true,
    maxRows: 200,
    minHeight:'100%',
    maxHeight:'500px',
    width: '100%',
    height: 1000,
    rowHeaders: true,
    autoColumnSize: {
      samplingRatio: 23
    },
    filters: true,
    manualRowResize: true,
    manualColumnResize: true,
    allowInsertRow: true,
    allowRemoveRow: true,
    columnHeaderHeight: 40,
    
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      width: '100%',
      autoWrapRow: true,
      rowHeights: 40,
      columnHeaderHeight: 40,
      minRows: 1,
      rowHeaders: true,
      colHeaders: setHeader,
      columnSorting: {
        indicator: true
      },
      autoColumnSize: {
        samplingRatio: 23
      },
      dropdownMenu: true,
      mergeCells: true,
      contextMenu: true,
      manualRowMove: true,
      manualColumnMove: true,
      multiColumnSorting: {
        indicator: true
      },
      afterChange: (changes) => {
          changes?.forEach(([row, prop, oldValue, newValue]) => {
                var option = list_type.find(option => option.value === newValue);
                if (option) {
                  hot.setDataAtRowProp(row, prop, option.key);
                }
              });
          },
      filters: true,
      manualRowResize: true,
      manualColumnResize: true
    };
    hot = new Handsontable(hotElement, hotSettings);
    if(staff!= ''){
        hot.updateSettings({
              hiddenColumns: {
              columns: [0],
              indicators: true
            }
        })  
    }
  $('input[name="type_shiftwork"], select[name="department[]"], select[name="role[]"], select[name="staff[]"], input[name="from_date"], input[name="to_date"]').change(function() {
        var val = $('input[name="type_shiftwork"]:checked').val();
        var department = $('select[name="department"]').val();
        var role = $('select[name="role"]').val();
        var staff = $('select[name="staff[]"]').val();
        var from_date = $('input[name="from_date"]').val();
        var to_date = $('input[name="to_date"]').val();

        var data = {};
        data.department = department;
        data.role = role;
        data.staff = staff;
        data.type_shiftwork = val;
        data.from_date = from_date;
        data.to_date = to_date;

        $.post(admin_url+'timesheets/get_hanson_shiftwork',data).done(function(response){
            response = JSON.parse(response);
              var staff = $('select[name="staff[]"]').val();
              if(staff!= ''){
                var columns = [{
                  data: 'staffid',
                  width: 250,
                  type: 'text',
                },
                {
                  data: 'staff',
                  width: 250,
                  type: 'text',
                  readOnly: true,
                }];
                $.each(response.head_data, function( index, value ) {
                    if(index >= 2){
                      columns.push({
                            data: value,
                            width: 250,
                      type: 'dropdown',
                      source: list_type.map(option => option.value), 
                      renderer: function (instance, td, row, col, prop, value, cellProperties) {
                        var option = list_type.find(option => option.key === value);
                        
                        Handsontable.renderers.TextRenderer.apply(this, arguments);
                        
                        td.innerHTML = option ? option.value : value;
                      },
                      validator: function(value, callback) {
                        var isValid = list_type.some(option => option.key === value);
                        callback(isValid); 
                      },
                      });
                    }
                }); 
                hot.updateSettings({
                      data: response.data_object,
                      columns: columns,
                      colHeaders: response.head_data,
                      hiddenColumns: {
                      columns: [0],
                      indicators: true
                    }
                })  
              }
              else{
                var columns = [];
                $.each(response.head_data, function( index, value ) {
                      columns.push({
                            data: value,
                            width: 250,
                      type: 'dropdown',
                      source: list_type.map(option => option.value), 
                      renderer: function (instance, td, row, col, prop, value, cellProperties) {
                        var option = list_type.find(option => option.key === value);
                        
                        Handsontable.renderers.TextRenderer.apply(this, arguments);
                        
                        td.innerHTML = option ? option.value : value;
                      },
                      validator: function(value, callback) {
                        var isValid = list_type.some(option => option.key === value);
                        callback(isValid); 
                      },
                      });
                }); 

                hot.updateSettings({
                        columns: columns,
                        data: response.data_object,
                        colHeaders: response.head_data,
                        hiddenColumns: {}
                }) 
            }           
        });
  });

  $('.save_detail_shift').on('click', function() {
    $('input[name="shifts_detail"]').val(hot.getData());   
  });

})(jQuery);

function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
  "use strict";
    var selectedId;
    var optionsList = cellProperties.chosenOptions.data;
    
    if(typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList.length) {
        Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
        return td;
    }

    var values = (value + "").split("|");
    value = [];
    for(var index = 0; index < optionsList.length; index++) {
        if (values.indexOf(optionsList[index].id + "") > -1) {
            selectedId = optionsList[index].id;
            value.push(optionsList[index].label);
        }
    }
    value = value.join(", ");
    Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
    return td;
  }

</script>