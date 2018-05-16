<nav class="navbar fx-navbar-sub">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="fx-nav-options">
                    <li class="dashboard icon icon-quickstart">
                        <a href="/dashboard">&nbsp</a>
                    </li>
                    <li class="<?php echo is_page('password-checkpoint') ? 'current-menu-item' : '';?>">
                        <a href="<?php echo site_url('password-checkpoint');?>">
                            <span class="number">1</span>
                            <span class="text">Update Password</span>
                        </a>
                    </li>
                    <li class="<?php echo is_page('checkout') ? 'current-menu-item' : '';?>">
                        <a href="#">
                            <span class="number">2</span>
                            <span class="text">Update Billing</span>
                        </a>
                    </li>
                    <li class="<?php echo is_page('dashboard') ? 'current-menu-item' : '';?>">
                        <a href="<?php echo site_url('dashboard');?>">
                            <span class="number">3</span>
                            <span class="text">Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>