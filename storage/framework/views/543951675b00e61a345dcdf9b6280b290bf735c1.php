<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-header text-center">
            <a class="navbar-brand" href="">
                <!-- Logo icon --><b>
                    <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                    <!-- Dark Logo icon -->
                    <?php
                    $logo = \App\Models\Logo::latest()->first()
                    ?>
                    <img src="<?php echo e(asset($logo->logo_image??null)); ?>" alt="homepage" height="70px" width="70px" class="dark-logo" />
                    <!-- Light Logo icon -->
                    <img src="<?php echo e(asset($logo->logo_image??null)); ?>" alt="homepage" height="70px" width="70px"  class="light-logo" />
                </b>
                <!--End Logo icon -->
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav me-auto">
                <!-- This is  -->
                <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>

            </ul>
            <!-- ============================================================== -->
            <!-- User profile and search -->
            <!-- ============================================================== -->
            <ul class="navbar-nav my-lg-0">
                <!-- ============================================================== -->
                <li class="nav-item dropdown u-pro">
                    <?php
                        $adminProfileImage = \App\Models\AdminProfileImage::where('user_id', Auth::user()->id)->first();
                    ?>
                    <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php if($adminProfileImage): ?>
                            <img src="<?php echo e(asset($adminProfileImage->admin_profile_image??null)); ?>" class="img-fluid me-2" style="width:30px; height: 30px">
                        <?php else: ?>
                            <img src="<?php echo e(asset('profile/default_profile.png')); ?>" class="img-fluid me-2" style="width:30px; height: 30px">
                        <?php endif; ?>
                        <span class="hidden-md-down"><?php echo e(Auth::User()->name); ?> &nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
</svg></span> </a>
                    <div class="dropdown-menu dropdown-menu-end animated flipInY">

                        <!-- text-->
                        <a href="#" class="dropdown-item">রোল: <?php echo e(Auth::User()->role->role_name); ?></a>
                        <a href="<?php echo e(route('profile.settings')); ?>" class="dropdown-item">প্রোফাইল</a>
                        <a href="<?php echo e(route('profileimage.settings')); ?>" class="dropdown-item">প্রোফাইল ইমেজ</a>
                        <a href="<?php echo e(route('admin.logout')); ?>" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logoutForm').submit()"> লগ আউট</a>
                        <!-- text-->
                        <form action="<?php echo e(route('admin.logout')); ?>" id="logoutForm" method="POST">
                            <?php echo csrf_field(); ?>
                        </form>

                    </div>
                </li>

                <!-- ============================================================== -->
                <!-- End User Profile -->
                <!-- ============================================================== -->

            </ul>
        </div>
    </nav>
</header>
<?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/include/header.blade.php ENDPATH**/ ?>