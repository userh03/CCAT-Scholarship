<?php
// Function to determine if the current page is active
function isPageActive($pageName)
{
    $currentPage = basename($_SERVER['PHP_SELF']);
    // Check for exact match or match with additional parameters
    return ($currentPage === $pageName || strpos($currentPage, $pageName) !== false) ? 'active' : '';
}
?>

<ul class="app-menu" style="font-size: 16px;">
    <li>
        <a class="app-menu__item <?php echo isPageActive('developer-dashboard.php'); ?>" href="developer-dashboard.php">
            <i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span>
        </a>
    </li>
    <li class="treeview">
        <a class="app-menu__item <?php echo isPageActive('developer-add-edit-admin.php'); ?>" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Admin Setting</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
            <li><a class="treeview-item <?php echo isPageActive('developer-add-edit-admin.php'); ?>" href="developer-add-edit-admin.php"><i class="icon fa fa-circle-o"></i>Add/Edit Admin</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a class="app-menu__item <?php echo isPageActive('developer-add-edit-coordinator.php'); ?>" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Coordinator Setting</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
            <li><a class="treeview-item <?php echo isPageActive('developer-add-edit-coordinator.php'); ?>" href="developer-add-edit-coordinator.php"><i class="icon fa fa-circle-o"></i>Add/Edit Coordinator</a></li>
        </ul>
    </li>
    <li class="treeview"><a class="app-menu__item <?php echo isPageActive('developer-add-edit-students.php') || isPageActive('developer-add-edit-grades.php') ? 'active' : ''; ?>" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Student Setting</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
        <li><a class="treeview-item <?php echo isPageActive('developer-add-edit-students.php')?>" href="developer-add-edit-students.php"><i class="icon fa fa-circle-o"></i>Add/Edit Students</a></li>
        <li><a class="treeview-item <?php echo isPageActive('developer-add-edit-grades.php')?>" href="developer-add-edit-grades.php"><i class="icon fa fa-circle-o"></i>Add/Edit Grades</a></li>
      </ul>
    </li>
    <li><a class="app-menu__item <?php echo isPageActive('developer-statistics.php'); ?>" href="developer-statistics.php"><i class="app-menu__icon fa fa-pie-chart"></i><span class="app-menu__label">Statistics</span></a></li>
    <li class="treeview">
        <a class="app-menu__item <?php echo isPageActive('developer-tables.php') || isPageActive('developer-tables_approved.php') || isPageActive('developer-tables_denied.php') || isPageActive('developer-tables-tes.php') || isPageActive('developer-tables-tdp.php') || isPageActive('developer-tables-ts.php') ? 'active' : ''; ?>" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-th-list"></i>
            <span class="app-menu__label">Applicants</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
            <li>
                <a class="treeview-item <?php echo isPageActive('developer-tables.php'); ?>" href="developer-tables.php">
                    <i class="icon fa fa-circle-o"></i>Pending Applicants
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('developer-tables_approved.php'); ?>" href="developer-tables_approved.php">
                    <i class="icon fa fa-circle-o"></i>Approved Applicants
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('developer-tables_denied.php'); ?>" href="developer-tables_denied.php">
                    <i class="icon fa fa-circle-o"></i>Denied Applicants
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('developer-tables-tes.php'); ?>" href="developer-tables-tes.php">
                    <i class="icon fa fa-circle-o"></i>TES Applicants
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('developer-tables-tdp.php'); ?>" href="developer-tables-tdp.php">
                    <i class="icon fa fa-circle-o"></i>TDP Applicants
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('developer-tables-ts.php'); ?>" href="developer-tables-ts.php">
                    <i class="icon fa fa-circle-o"></i>Talent/Service Applicants
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a class="app-menu__item <?php echo isPageActive('developer-user-content.php') || isPageActive('developer-user-content-announce.php') || isPageActive('developer-user-FAQ.php') ? 'active' : ''; ?>" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-file-text"></i>
            <span class="app-menu__label">Pages</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
            <li>
                <a class="treeview-item <?php echo isPageActive('developer-user-content.php'); ?>" href="developer-user-content.php">
                    <i class="icon fa fa-circle-o"></i>Edit/Update News and Updates
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('developer-user-content-announce.php'); ?>" href="developer-user-content-announce.php">
                    <i class="icon fa fa-circle-o"></i>Edit/Update Announcements
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('developer-user-FAQ.php'); ?>" href="developer-user-FAQ.php">
                    <i class="icon fa fa-circle-o"></i>Edit Frequently Asked Questions
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a class="app-menu__item <?php echo isPageActive('developer-viewfeedbacks.php'); ?>" href="developer-viewfeedbacks.php">
            <i class="app-menu__icon fa fa-commenting"></i><span class="app-menu__label">View Feedbacks</span>
        </a>
    </li>
    <li>
        <a class="app-menu__item <?php echo isPageActive('developer-viewpastapplicants.php'); ?>" href="developer-viewpastapplicants.php">
            <i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">View Past Applicants</span>
        </a>
    </li>
    <li>
    <li><a class="app-menu__item <?php echo isPageActive('developer-docs.php'); ?>" href="developer-docs.php"><i class="app-menu__icon fa fa-file-code-o"></i><span class="app-menu__label">Upload Data</span></a></li>
    <li><a class="app-menu__item <?php echo isPageActive('developer-control.php'); ?>" href="developer-control.php"><i class="app-menu__icon fa fa-file-code-o"></i><span class="app-menu__label">Control</span></a></li>
    <li class="treeview">
        <a class="app-menu__item <?php echo isPageActive('developer-admin-logs.php') || isPageActive('developer-logs.php') || isPageActive('developer-logs-devs.php') ? 'active' : ''; ?>" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-th-list"></i>
            <span class="app-menu__label">Logs</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
            <li>
                <li>
                    <a class="treeview-item <?php echo isPageActive('developer-logs-devs.php'); ?>" href="developer-logs-devs.php">
                        <i class="icon fa fa-circle-o"></i>Developer Logs
                    </a>
                </li>
                <a class="treeview-item <?php echo isPageActive('developer-admin-logs.php'); ?>" href="developer-admin-logs.php">
                    <i class="icon fa fa-circle-o"></i>Status Logs
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('developer-logs.php'); ?>" href="developer-logs.php">
                    <i class="icon fa fa-circle-o"></i>Admin Logs
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php echo isPageActive('developer-rubbish-coordinator.php') || isPageActive('developer-rubbish.php') || isPageActive('developer-rubbish-admin.php') ? 'active' : ''; ?>" style="background: #780000;">
        <a class="app-menu__item" data-toggle="treeview" href="">
            <i class="app-menu__icon fa fa-trash-o" style="color: white;"></i>
            <span class="app-menu__label" style="color: white;">Account Bin</span>
            <i class="treeview-indicator fa fa-angle-right" style="color: white;"></i>
        </a>
        <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
            <li>
                <a class="treeview-item <?php echo isPageActive('developer-rubbish-admin.php'); ?>" href="developer-rubbish-admin.php">
                    <i class="icon fa fa-circle-o"></i>Admin Account Bin
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('developer-rubbish-coordinator.php'); ?>" href="developer-rubbish-coordinator.php">
                    <i class="icon fa fa-circle-o"></i>Coordinator Account Bin
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('developer-rubbish.php'); ?>" href="developer-rubbish.php">
                    <i class="icon fa fa-circle-o"></i>Student Account Bin
                </a>
            </li>
        </ul>
    </li>
</ul>

<style>
    .app-menu__item.active {
        color: white;
    }
</style>
