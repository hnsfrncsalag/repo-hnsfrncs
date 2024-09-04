<div class="row vh-100 ">
    <div class="col-3 d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
            <a href="index.php" class="d-flex align-items-center ">
                <img class="bi me-2" width="55" height="55" role="img" aria-label="Bootstrap" src="petstaticon.svg">
                </img>
                <span class="fs-4 text-decoration-none">PETSTAT</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="dashboard1.php" class="nav-link active" aria-current="page">
                        <svg class="bi me-2" width="16" height="16">
                            <use xlink:href="#home"></use>
                        </svg>
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <form method="post" action="./BAOpetdashboard.php?active-tab=1">
                        <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                        <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                        <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                        <input type="hidden" name="active-tab" value="1">
                        <button class="nav-link link-dark bi me-2 ">My Pet Dashboard</button>
                    </form>
                </li>
                <li class="nav-item">
                    <form method="POST" action="reportCase.php" id="reportBiteCaseForm" style="display: inline;">
                        <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                        <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                        <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                        <input type="hidden" name="name" value="<?php echo $name; ?>">
                        <button class="nav-link link-dark bi me-2" type="submit" class="btn btn-manage">Report Case</button>
                    </form>
                </li>
                <li class="nav-item">
                    <form method="post" action="tabularBAO.php" style="display: inline;">
                        <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                        <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                        <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                        <input type="hidden" name="name" value="<?php echo $name; ?>">
                        <button type="submit" class="btn btn-manage">View Reports</button>
                    </form>
                <li class="nav-item"><a href="viewHeatmaps.php" class="btn btn-manage">View Heatmaps</a></li>
                </li>
                <li class="nav-item">
                    <form method="post" action="./dashboard1pet.php?active-tab=1" style="display: inline;">
                        <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                        <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                        <input type="hidden" name="name" value="<?php echo $name; ?>">
                        <button type="submit" class="btn btn-manage">Manage Pet</button>
                    </form>
                </li>
                <li class="nav-item">
                    <form method="post" action="./dashboardBiteCases.php?active-tab=1" style="display: inline;">
                        <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                        <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                        <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                        <input type="hidden" name="name" value="<?php echo $name; ?>">
                        <button type="submit" class="btn btn-manage">Manage Bite Cases</button>
                    </form>
                </li>
                <li>
                    <form method="post" action="./dashboardRabidCases.php?active-tab=1" style="display: inline;">
                        <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                        <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                        <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                        <input type="hidden" name="name" value="<?php echo $name; ?>">
                        <button type="submit" class="btn btn-manage">Manage Suspected Cases</button>
                    </form>
                </li>
                <li>
                    <form method="post" action="./dashboardDeathCases.php?active-tab=1" style="display: inline;">
                        <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                        <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                        <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                        <input type="hidden" name="name" value="<?php echo $name; ?>">
                        <button type="submit" class="btn btn-manage">Manage Death Cases</button>
                    </form>
                </li>
                <li>
                    <form method="post" action="createAccForResident.php" style="display: inline;">
                        <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                        <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                        <input type="hidden" name="name" value="<?php echo $name; ?>">
                        <button type="submit" class="btn btn-manage">Create Account</button>
                    </form>
                </li>

                <hr>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src=" " alt="" width="32" height="32" class="rounded-circle me-2">
                        <strong><?php echo isset($user['name']) ? $user['name'] : ''; ?></strong>
                    </a>
                    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                        <li><a class="dropdown-item" href="#">Sign out</a></li>
                    </ul>
                </div>

            </ul>
    </div>
    <div class="col-8">