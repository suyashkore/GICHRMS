<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <?php if(has_permission('items','','delete')){ ?>
            <!-- <a href="#" data-toggle="modal" data-table=".table-invoice-items" data-target="#items_bulk_actions" class="hide bulk-actions-btn table-btn"><?php echo _l('bulk_actions'); ?></a>-->
             <div class="modal fade bulk_actions" id="items_bulk_actions" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
               <div class="modal-content">
                <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
               </div>
               <div class="modal-body">
                 <?php if(has_permission('leads','','delete')){ ?>
                   <div class="checkbox checkbox-danger">
                    <input type="checkbox" name="mass_delete" id="mass_delete">
                    <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                  </div>
                  <!-- <hr class="mass_delete_separator" /> -->
                <?php } ?>
              </div>
              <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
               <a href="#" class="btn btn-info" onclick="items_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
             </div>
           </div>
           <!-- /.modal-content -->
         </div>
         <!-- /.modal-dialog -->
       </div>
       <!-- /.modal -->
     <?php } ?>
     <?php hooks()->do_action('before_items_page_content'); ?>
     <?php //if(has_permission('items','','create')){ ?>
       <div class="_buttons">
        <a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#business_modal">Add Business Data</a>
        <!--<a href="#" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#groups"><?php echo _l('item_groups'); ?></a>
        <a href="#" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#maingroups"><?php echo _l('item_main_groups'); ?></a>
        <a href="#" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#subgroups"><?php echo _l('item_sub_groups'); ?></a>-->
        <a href="<?php echo admin_url('business_data/import'); ?>" class="btn btn-info pull-left mleft5">Import Data</a>
      </div>
      <div class="clearfix"></div>
      <hr class="hr-panel-heading" />
    <?php //} ?>
    <?php
    $table_data = [];

    /*if(has_permission('items','','delete')) {
      $table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="invoice-items"><label></label></div>';
    }*/

    $table_data = array_merge($table_data, array(
        'Name',
        'Email',
        'Mobile',
      "Created Date",
      "Updated Date",
      "Status",
      /*_l('invoice_item_long_description'),*/
     /* _l('invoice_items_list_rate'),
      _l('tax_1'),
      _l('tax_2'),*/
      /*_l('unit')*/
      /*"Start Day",*/
      /*'Active'*/
      ));

    
    $table_data = array_merge($table_data, array(
        
      'Action'
      ));
    render_datatable($table_data,'business-table'); ?>
  </div>
</div>
</div>
</div>
</div>
</div>
<?php $this->load->view('admin/business_data/add_model'); ?>

<!-- Item Division Model -->

<div class="modal fade" id="groups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo _l('item_groups'); ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php if(has_permission('items','','create')){ ?>
          <div class="input-group">
            <input type="text" name="item_group_name" id="item_group_name" class="form-control" placeholder="<?php echo _l('item_group_name'); ?>">
            <span class="input-group-btn">
              <button class="btn btn-info p7" type="button" id="new-item-group-insert"><?php echo _l('save'); ?></button>
            </span>
          </div>
          <hr />
        <?php } ?>
        <div class="row">
         <div class="container-fluid">
          <table class="table dt-table table-items-groups" data-order-col="0" data-order-type="asc">
            <thead>
              <tr>
                <th><?php echo _l('sr_no'); ?></th>
                <th><?php echo _l('item_group_name'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($items_groups as $group){ ?>
                <tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
                  <td data-order="<?php echo $group['id']; ?>"><?php echo $group['id']; ?></td>
                  <td data-order="<?php echo $group['name']; ?>">
                    <span class="group_name_plain_text"><?php echo $group['name']; ?></span>
                    <div class="group_edit hide">
                     <div class="input-group">
                      <input type="text" class="form-control">
                      <span class="input-group-btn">
                        <button class="btn btn-info p8 update-item-group" type="button"><?php echo _l('submit'); ?></button>
                      </span>
                    </div>
                  </div>
                  <div class="row-options">
                    <?php if(has_permission('items','','edit')){ ?>
                      <a href="#" class="edit-item-group">
                        <?php echo _l('edit'); ?>
                      </a>
                    <?php } ?>
                    <?php if(has_permission('items','','delete')){ ?>
                      | <a href="<?php echo admin_url('invoice_items/delete_group/'.$group['id']); ?>" class="delete-item-group _delete text-danger">
                        <?php echo _l('delete'); ?>
                      </a>
                    <?php } ?>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
  </div>
</div>
</div>
</div>

<!-- End Item Division -->

<!-- Item Main Group -->
<div class="modal fade" id="maingroups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo _l('item_main_groups'); ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php if(has_permission('items','','create')){ ?>
        <div class="row">
        <!--<div class="col-md-4">
            
                <input type="text" name="item_main_group_id" id="item_main_group_id" class="form-control" placeholder="<?php echo _l('item_main_group_id'); ?>">
            
        </div>-->
        <div class="col-md-4">
            
            <input type="text" name="item_main_group_name" id="item_main_group_name" class="form-control" placeholder="<?php echo _l('item_main_group_name'); ?>">
            
          
        </div>
        
        <div class="col-md-4">
            <span class="btn" style="top: -7px;position: relative;">
              <button class="btn btn-info p7" type="button" id="new-item-main-group-insert"><?php echo _l('save'); ?></button>
            </span>
        </div>
        </div>
          
          <hr />
        <?php } ?>
        <div class="row">
         <div class="container-fluid">
          <table class="table dt-table table-items-groups" data-order-col="0" data-order-type="asc">
            <thead>
              <tr>
                <th><?php echo _l('id'); ?></th>
                <!--<th><?php echo _l('item_main_group_id'); ?></th>-->
                <th><?php echo _l('item_main_group'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($items_main_groups as $group){ ?>
                <tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
                  <td data-order="<?php echo $group['id']; ?>"><?php echo $group['id']; ?></td>
                  <!--<td data-order="<?php echo $group['item_main_group_id']; ?>"><?php echo $group['item_main_group_id']; ?></td>-->
                  <td data-order="<?php echo $group['name']; ?>">
                    <span class="group_name_plain_text"><?php echo $group['name']; ?></span>
                    <div class="main_group_edit hide">
                     <div class="input-group">
                      <input type="text" class="form-control">
                      <span class="input-group-btn">
                        <button class="btn btn-info p8 update-item-main-group" type="button"><?php echo _l('submit'); ?></button>
                      </span>
                    </div>
                  </div>
                  <div class="row-options">
                    <?php if(has_permission('items','','edit')){ ?>
                      <a href="#" class="edit-item-main-group">
                        <?php echo _l('edit'); ?>
                      </a>
                    <?php } ?>
                    <?php if(has_permission('items','','delete')){ ?>
                      | <a href="<?php echo admin_url('invoice_items/delete_main_group/'.$group['id']); ?>" class="delete-item-group _delete text-danger">
                        <?php echo _l('delete'); ?>
                      </a>
                    <?php } ?>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
  </div>
</div>
</div>
</div>

<!-- End Item Main Group-->

<!-- Item Sub Group -->

<div class="modal fade" id="subgroups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo _l('item_sub_groups'); ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php if(has_permission('items','','create')){ ?>
        <div class="row">
        <div class="col-md-4">
            
                <!--<input type="text" name="item_main_group_id" id="item_main_group_id" class="form-control" placeholder="<?php echo _l('item_main_group_id'); ?>">-->
            <?php 
            //print_r($items_main_groups);
            $s_attrs = array('data-none-selected-text'=>'Select Main Group');
                     $selected = '';
            echo render_select('item_main_group_id1',$items_main_groups,array('item_main_group_id','name'),'',$selected,$s_attrs); ?>
            <!--<select id="item_main_group_id1" name="item_main_group_id1" class="form-control">
                <?php 
                foreach ($items_main_groups as $key => $value) {
                        # code...
                        ?>
                    <option value="<?php echo $value['item_main_group_id'];?>"><?php echo $value["name"];?></option>
                        <?php
                    }
                ?>
                
            </select>-->
            
        </div>
        <div class="col-md-4">
            
            <input type="text" name="item_main_group_name" id="item_sub_group_name" class="form-control" placeholder="<?php echo _l('item_sub_group_name'); ?>">
            
          
        </div>
        
        <div class="col-md-4">
            <span class="btn" style="top: -7px;position: relative;">
              <button class="btn btn-info p7" type="button" id="new-item-sub-group-insert"><?php echo _l('save'); ?></button>
            </span>
        </div>
        </div>
          
          <hr />
        <?php } ?>
        <div class="row">
         <div class="container-fluid">
          <table class="table dt-table table-items-groups" data-order-col="0" data-order-type="asc">
            <thead>
              <tr>
                <th><?php echo _l('id'); ?></th>
                <th><?php echo _l('item_main_group_name'); ?></th>
                <th><?php echo _l('item_sub_group'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($items_sub_groups as $group){ ?>
                <tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
                  <td data-order="<?php echo $group['id']; ?>"><?php echo $group['id']; ?></td>
                  <td data-order="<?php echo $group['main_group_id']; ?>"><?php $ss = get_main_group_name($group['main_group_id']);
                  echo $ss->name; ?></td>
                  <td data-order="<?php echo $group['name']; ?>">
                    <span class="group_name_plain_text"><?php echo $group['name']; ?></span>
                    <div class="main_group_edit hide">
                     <div class="input-group">
                      <input type="text" class="form-control">
                      <span class="input-group-btn">
                        <button class="btn btn-info p8 update-item-main-group" type="button"><?php echo _l('submit'); ?></button>
                      </span>
                    </div>
                  </div>
                  <div class="row-options">
                    <?php if(has_permission('items','','edit')){ ?>
                      <a href="#" class="edit-item-main-group">
                        <?php echo _l('edit'); ?>
                      </a>
                    <?php } ?>
                    <?php if(has_permission('items','','delete')){ ?>
                      | <a href="<?php echo admin_url('invoice_items/delete_main_group/'.$group['id']); ?>" class="delete-item-group _delete text-danger">
                        <?php echo _l('delete'); ?>
                      </a>
                    <?php } ?>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
  </div>
</div>
</div>
</div>

<!-- End Item Sub Group-->

<?php init_tail(); ?>
<script>
  $(function(){

    var notSortableAndSearchableItemColumns = [];
    <?php if(has_permission('items','','delete')){ ?>
      notSortableAndSearchableItemColumns.push(0);
    <?php } ?>

    initDataTable('.table-business-table', admin_url+'business_data/table', notSortableAndSearchableItemColumns, notSortableAndSearchableItemColumns,'undefined',[1,'asc']);

    if(get_url_param('groups_modal')){
       // Set time out user to see the message
       setTimeout(function(){
         $('#groups').modal('show');
       },1000);
     }
     
    
    // Item Division Add 
     $('#new-item-group-insert').on('click',function(){
      var group_name = $('#item_group_name').val();
      if(group_name != ''){
        $.post(admin_url+'invoice_items/add_group',{name:group_name}).done(function(){
         window.location.href = admin_url+'invoice_items?groups_modal=true';
       });
      }
    });
    
    if(get_url_param('main_groups_modal')){
       // Set time out user to see the message
       setTimeout(function(){
         $('#maingroups').modal('show');
       },1000);
     }
     
     if(get_url_param('sub_groups_modal')){
       // Set time out user to see the message
       setTimeout(function(){
         $('#subgroups').modal('show');
       },1000);
     }
    
    
    // Item Main Group add
    $('#new-item-main-group-insert').on('click',function(){
      var main_group_name = $('#item_main_group_name').val();
      //var main_group_id = $('#item_main_group_id').val();
      if(main_group_name != ''){
        $.post(admin_url+'invoice_items/add_main_group',{name:main_group_name}).done(function(){
         window.location.href = admin_url+'invoice_items?main_groups_modal=true';
       });
      }
    });
    
   
    // Item Sub Group add
    $('#new-item-sub-group-insert').on('click',function(){
      var group_name = $('#item_sub_group_name').val();
      var main_group_id = $('#item_main_group_id1').val();
      //var main_group_id = $( "#item_main_group_id:selected" ).text();
      //alert(main_group_id);
      if(group_name != ''){
        $.post(admin_url+'invoice_items/add_sub_group',{name:group_name, id:main_group_id}).done(function(){
         window.location.href = admin_url+'invoice_items?sub_groups_modal=true';
       });
      }
    });
    

     $('body').on('click','.edit-item-group',function(e){
      e.preventDefault();
      var tr = $(this).parents('tr'),
      group_id = tr.attr('data-group-row-id');
      tr.find('.group_name_plain_text').toggleClass('hide');
      tr.find('.group_edit').toggleClass('hide');
      tr.find('.group_edit input').val(tr.find('.group_name_plain_text').text());
    });

     $('body').on('click','.update-item-group',function(){
      var tr = $(this).parents('tr');
      var group_id = tr.attr('data-group-row-id');
      name = tr.find('.group_edit input').val();
      if(name != ''){
        $.post(admin_url+'invoice_items/update_group/'+group_id,{name:name}).done(function(){
         window.location.href = admin_url+'invoice_items';
       });
      }
    });
    
     $('body').on('click','.edit-item-main-group',function(e){
      e.preventDefault();
      var tr = $(this).parents('tr'),
      group_id = tr.attr('data-group-row-id');
      tr.find('.group_name_plain_text').toggleClass('hide');
      tr.find('.main_group_edit').toggleClass('hide');
      tr.find('.main_group_edit input').val(tr.find('.group_name_plain_text').text());
    });

     $('body').on('click','.update-item-main-group',function(){
      var tr = $(this).parents('tr');
      var group_id = tr.attr('data-group-row-id');
      name = tr.find('.main_group_edit input').val();
      if(name != ''){
        $.post(admin_url+'invoice_items/update_main_group/'+group_id,{name:name}).done(function(){
         //window.location.href = admin_url+'invoice_items';
         window.location.href = admin_url+'invoice_items?main_groups_modal=true';
       });
      }
    });
    
   });
  function items_bulk_action(event) {
    if (confirm_delete()) {
      var mass_delete = $('#mass_delete').prop('checked');
      var ids = [];
      var data = {};

      if(mass_delete == true) {
        data.mass_delete = true;
      }

      var rows = $('.table-invoice-items').find('tbody tr');
      $.each(rows, function() {
        var checkbox = $($(this).find('td').eq(0)).find('input');
        if (checkbox.prop('checked') === true) {
          ids.push(checkbox.val());
        }
      });
      data.ids = ids;
      $(event).addClass('disabled');
      setTimeout(function() {
        $.post(admin_url + 'invoice_items/bulk_action', data).done(function() {
          window.location.reload();
        }).fail(function(data) {
          alert_float('danger', data.responseText);
        });
      }, 200);
    }
  }
 </script>
</body>
</html>
