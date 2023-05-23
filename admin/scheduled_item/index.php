<?php if($_settings->chk_flashdata('success')): ?>
<script>
  alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline rounded-0 card-navy">
  <div class="card-header">
    <h3 class="card-title">List of Scheduled Items</h3>
    <div class="card-tools">
      <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
    </div>
  </div>
  <div class="card-body">
        <div class="container-fluid">
      <table class="table table-hover table-striped table-bordered" id="list">
        <colgroup>
          <col width="5%">
          <col width="15%">
          <col width="20%">
          <col width="30%">
          <col width="15%">
          <col width="15%">
        </colgroup>
        <thead>
          <tr>
            <th>#</th>
            <th>Date Created</th>
            <th>Name</th>
            <th>Description</th>
            <th>Time</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
            $qry = $conn->query("SELECT * from schedule_list order by name asc ");
            while($row = $qry->fetch_assoc()):
          ?>
            <tr>
              <td class="text-center"><?php echo $i++; ?></td>
              <td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
              <td><?php echo $row['name'] ?></td>
              <td><p class="m-0 truncate-1"><?= $row['item'] ?></p></td>
              <td><?php echo $row['time'] ?></td>
              <td align="center">
                 <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                              Action
                            <span class="sr-only">Toggle Dropdown</span>
                          </button>
                          <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
                          </div>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
  $('.delete_data').click(function(){
    _conf("Are you sure to delete this Data permanently?","delete_schedules",[$(this).attr('data-id')])
  })
  $('#create_new').click(function(){
    uni_modal("<i class='fa fa-plus'></i> Add New Data","scheduled_item/manage_item.php")
  })
  $('.view_data').click(function(){
    uni_modal("Data Details","scheduled_item/view_item.php?id="+$(this).attr('data-id'),'mid-large')
  })
  $('.table th,.table td').addClass('px-1 py-0 align-middle')
  $('.table').dataTable();
})
function delete_schedules($id){
  start_loader();
  $.ajax({
    url:_base_url_+"classes/Master.php?f=delete_schedules",
    method:"POST",
    data:{id: $id},
    dataType:"json",
    error:err=>{
      console.log(err)
      alert_toast("An error occured.",'error');
      end_loader();
    },
    success:function(resp){
      if(typeof resp== 'object' && resp.status == 'success'){
        location.reload();
      }else{
        alert_toast("An error occured.",'error');
        end_loader();
      }
    }
  })
}
</script>
