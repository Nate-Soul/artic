
    <nav id="menu" class="navbar navbar-expand-sm bg-light navbar-light">
        <div class="container">
            <a href="../" class="navbar-brand">
                <img src="../images/logo/logo.png" alt=""/>
            </a>
            <button class="navbar-toggler" data-toggle="collapse" data-target="#menuCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuCollapse">
                <ul class="navbar-nav">
                    <li class="nav-item px-2">
                        <a class="nav-link act" href="./"> Dashboard </a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="posts.php"> Posts </a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="categories.php"> Categories </a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="members.php"> Members </a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="dropdown nav-item">
                        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"> Welcome Admin! </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="profile.php"><i class="fa fa-user"></i> Profile </a>
                            <a class="dropdown-item" href="settings.php"><i class="fa fa-cog"></i> settings </a>
                            <a class="dropdown-item" href="../core/logout.php"><i class="fa fa-sign-out"></i> Logout </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>