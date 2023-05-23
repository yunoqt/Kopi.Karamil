<?php

require_once '../classes/DBConnection.php';
$UDF_call = new DBConnection();

$select_status = $UDF_call->select_order_limit('schedule_list', 'id', 100);

?>
<style>
  .user-img {
    position: absolute;
    height: 27px;
    width: 27px;
    object-fit: cover;
    left: -7%;
    top: -12%;
  }

  .btn-rounded {
    border-radius: 50px;
  }
  .counter{
      width: 22px;
      height: 22px;
      line-height: 22px;
      margin-right: 5px;
      display: inline-block;
      border-radius: 5px;
      background-color: red;
      text-align: center;
      font-size: 13px;
  }
  #acc {
    margin-top: -7px;
  }
  nav {
  font-family: Arial, Helvetica, sans-serif;
  }
</style>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark shadow text-sm bg-gradient-navy">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="<?php echo base_url ?>" class="nav-link"><?php echo (!isMobileDevice()) ? $_settings->info('name') : $_settings->info('short_name'); ?> - Admin</a>
    </li>
  </ul>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
      <div class="dropdown-toggle text-light" id="noti_count" style="cursor: pointer;" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="counter" id="counter">0</span><i class="fas fa-bell" style="font-size: 20px;"></i>
      </div>
      <div class="dropdown-menu overflow-h-menu dropdown-menu-right" >
        <div class="notification">
          <a href="<?php echo base_url ?>admin/?page=scheduled_item"></a> 
          <?php if ($select_status) {
            foreach ($select_status as $se_noti) {
              $currentTime = time();
              $notificationTime = strtotime($se_noti['time']);

              // Check if the current time is greater than or equal to the notification time
              if ($currentTime >= $notificationTime) {
                // Add the "triggered" class to the notification item
                echo '<div class="dropdown-item triggered">';
              } else {
                echo '<div class="dropdown-item">';
              }

              echo '<h6>' . $se_noti['name'] . '</h6>';
              echo '<span>' . $se_noti['item'] . '</span> <br>';
              echo '<span>' . $se_noti['time'] . '</span>';
              echo '<hr class="mt-1 mb-1">';
              echo '</div>';
            }
          } ?>
        </div>
      </div>
    </li>
    <li class="nav-item" id="acc">
      <div class="btn-group nav-link">
        <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
          <span><img src="<?php echo validate_image($_settings->userdata('avatar')) ?>" class="img-circle elevation-2 user-img" alt="User Image"></span>
          <span class="ml-3"><?php echo ucwords($_settings->userdata('firstname') . ' ' . $_settings->userdata('lastname')) ?></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu" role="menu">
          <a class="dropdown-item" href="<?php echo base_url . 'admin/?page=user' ?>"><span class="fa fa-user"></span> My Account</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo base_url . '/classes/Login.php?f=logout' ?>"><span class="fas fa-sign-out-alt"></span> Logout</a>
        </div>
      </div>
    </li>
    <li class="nav-item">

    </li>
  </ul>
</nav>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    // Schedule the notification based on the provided time
    scheduleNotification();

    $('#noti_count').on('click', function() {
      counter = 0;
      $('.counter').text(counter).hide();
    });
    function scheduleNotification() {
      $('.notification .dropdown-item').each(function() {
        var time = $(this).find('span:last').text().trim();
        var currentTime = new Date();
        var scheduledTime = new Date();

        var timeParts = time.split(':');
        scheduledTime.setHours(parseInt(timeParts[0]));
        scheduledTime.setMinutes(parseInt(timeParts[1]));
        scheduledTime.setSeconds(0);

        // Convert the time to milliseconds for comparison
        var currentTimeMs = currentTime.getTime();
        var scheduledTimeMs = scheduledTime.getTime();

        if (currentTimeMs >= scheduledTimeMs) {
          // Perform notification action here
          triggerNotification($(this));
        } else {
          var timeDifference = scheduledTimeMs - currentTimeMs;
          setTimeout(function() {
            triggerNotification($(this));
          }.bind(this), timeDifference);
        }
      });
    }

    function triggerNotification(notificationItem) {
      // Perform the notification action here
      console.log('Notification triggered!');

      // You can also update the UI or perform any other action you need
      var counter = parseInt($('#counter').text());
      counter++;
      $('#counter').text(counter).show();
      notificationItem.addClass('notification-triggered');
    }
  });
</script>
