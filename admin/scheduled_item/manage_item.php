<?php

require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from schedule_list where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="container-fluid">
  <form action="" id="schedule-form" method="post">
    <input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
    <div class="form-group">
      <label for="name" class="control-label">Title</label>
      <input type="text" name="name" id="name" class="form-control form-control-sm rounded-0" value="<?php echo isset($name) ? $name : ''; ?>"  required/>
    </div>
    <div class="form-group">
      <label for="item" class="control-label">Item</label>
      <textarea type="text" name="item" id="item" class="form-control form-control-sm rounded-0" required><?php echo isset($item) ? $item : ''; ?></textarea>
    </div>
    <div class="form-group">
      <label for="time" class="control-label">Time</label>
      <input type="time" name="time" id="time" class="form-control form-control-sm rounded-0" value="<?php echo isset($time) ? $time : ''; ?>"  required/>
    </div>
  </form>
</div>
<script>
  $(document).ready(function(){
    $('#schedule-form').submit(function(e){
      e.preventDefault();
            var _this = $(this)
       $('.err-msg').remove();
      start_loader();
      $.ajax({
        url:_base_url_+"classes/Master.php?f=save_schedule",
        data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
        error:err=>{
          console.log(err)
          alert_toast("An error occured",'error');
          end_loader();
        },
        success:function(resp){
          if(typeof resp =='object' && resp.status == 'success'){
            location.reload()
          }else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body").animate({ scrollTop: _this.closest('.card').offset().top }, "fast");
                            end_loader()
                    }else{
            alert_toast("An error occured",'error');
            end_loader();
                        console.log(resp)
          }
        }
      })
    })
  })
</script>
