<nav class="navbar navbar-static-top" role="navigation">
  <!-- Sidebar toggle button-->
  <a href="<?php echo base_url(); ?>assets/#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
    <span class="sr-only">Toggle navigation </span>
  </a>
  <!-- Navbar Right Menu -->
  <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
      <!-- User Account Menu -->
      <li class="dropdown user user-menu">
        <!-- Menu Toggle Button -->
        <a href="<?php echo base_url(); ?>assets/#" class="dropdown-toggle" data-toggle="dropdown">
          <!-- The user image in the navbar-->
          <img src="<?php echo base_url(); ?>assets/img/user.png" class="user-image" id="profil_img" alt="User Image">
          <!-- hidden-xs hides the username on small devices so only the image appears. -->
          <span class="hidden-xs"><?php echo $userdata->nama; ?></span>
        </a>
        <ul class="dropdown-menu">
          <!-- The user image in the menu -->
          <li class="user-header">
            <img src="<?php echo base_url(); ?>assets/img/user.png" class="img-circle" id="profil_img2" alt="User Image">

            <p>
              <?php echo $userdata->nama; ?>
            </p>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
			<?php if ($userdata->akses!="admin"){?>
            <div class="pull-left">
              <a href="<?php echo base_url('ProfileUser'); ?>" class="btn btn-default btn-flat">Profile</a>
            </div>
			<?php }?>
            <div class="pull-right">
              <a href="<?php echo base_url('Auth/logout'); ?>" class="btn btn-default btn-flat" id="sign-out">Sign out</a>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>