<?php
// Function to determine if the current page is active
function isPageActive($pageName)
{
    $currentPage = basename($_SERVER['PHP_SELF']);
    return ($currentPage === $pageName) ? 'active' : '';
}
?>

<ul class="app-menu" style="font-size: 16px;">
    <li>
        <a class="app-menu__item <?php echo isPageActive('admin-dashboard.php'); ?>" href="admin-dashboard.php">
            <i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span>
        </a>
    </li>
    <li class="treeview">
        <a class="app-menu__item <?php echo isPageActive('admin-add-edit-coordinator.php'); ?>" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Coordinator Setting</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-add-edit-coordinator.php'); ?>" href="admin-add-edit-coordinator.php">
                    <i class="icon fa fa-circle-o"></i>Add/Edit Coordinator
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a class="app-menu__item <?php echo isPageActive('admin-add-edit-students.php'); ?>" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Student Setting</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-add-edit-students.php'); ?>" href="admin-add-edit-students.php">
                    <i class="icon fa fa-circle-o"></i>Add/Edit Students
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a class="app-menu__item <?php echo isPageActive('admin-statistics.php'); ?>" href="admin-statistics.php">
            <i class="app-menu__icon fa fa-pie-chart"></i><span class="app-menu__label">Statistics</span>
        </a>
    </li>
    <li class="treeview">
        <a class="app-menu__item <?php echo isPageActive('admin-tables.php') || isPageActive('admin-tables-approved.php') || isPageActive('admin-tables-denied.php') || isPageActive('admin-tables-tdp.php') || isPageActive('admin-tables-tes.php') || isPageActive('admin-tables-ts.php') ? 'active' : ''; ?>" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Applicants</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-tables.php'); ?>" href="admin-tables.php">
                    <i class="icon fa fa-circle-o"></i>Pending Applicants
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-tables-approved.php'); ?>" href="admin-tables-approved.php">
                    <i class="icon fa fa-circle-o"></i>Approved Applicants
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-tables-denied.php'); ?>" href="admin-tables-denied.php">
                    <i class="icon fa fa-circle-o"></i>Denied Applicants
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-tables-tes.php'); ?>" href="admin-tables-tes.php">
                    <i class="icon fa fa-circle-o"></i>TES Applicants
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-tables-tdp.php'); ?>" href="admin-tables-tdp.php">
                    <i class="icon fa fa-circle-o"></i>TDP Applicants
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-tables-ts.php'); ?>" href="admin-tables-ts.php">
                    <i class="icon fa fa-circle-o"></i>Talent/Service Applicants
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a class="app-menu__item <?php echo isPageActive('admin-user-content.php') || isPageActive('admin-user-content-announce.php') || isPageActive('admin-user-FAQ.php') ? 'active' : ''; ?>" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-file-text"></i><span class="app-menu__label">Pages</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-user-content.php'); ?>" href="admin-user-content.php">
                    <i class="icon fa fa-circle-o"></i>Edit/Update News and Updates
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-user-content-announce.php'); ?>" href="admin-user-content-announce.php">
                    <i class="icon fa fa-circle-o"></i>Edit/Update Announcements
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-user-FAQ.php'); ?>" href="admin-user-FAQ.php">
                    <i class="icon fa fa-circle-o"></i>Edit Frequently Asked Questions
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a class="app-menu__item <?php echo isPageActive('admin-viewfeedbacks.php'); ?>" href="admin-viewfeedbacks.php">
            <i class="app-menu__icon fa fa-commenting"></i><span class="app-menu__label">View Feedbacks</span>
        </a>
    </li>
    <li>
        <a class="app-menu__item <?php echo isPageActive('admin-viewpastapplicants.php'); ?>" href="admin-viewpastapplicants.php">
            <i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">View Past Applicants</span>
        </a>
    </li>
    <li>
        <a class="app-menu__item <?php echo isPageActive('admin-docs.php'); ?>" href="admin-docs.php">
            <i class="app-menu__icon fa fa-file-code-o"></i><span class="app-menu__label">Upload Data</span>
        </a>
    </li>
    <li>
        <a class="app-menu__item <?php echo isPageActive('admin-control.php'); ?>" href="admin-control.php">
            <i class="app-menu__icon fa fa-file-code-o"></i><span class="app-menu__label">Control</span>
        </a>
    </li>
    <li class="treeview">
        <a class="app-menu__item <?php echo isPageActive('admin-logs.php') || isPageActive('admin-admin_logs.php') ? 'active' : ''; ?>" data-toggle="treeview" href="">
            <i class="app-menu__icon fa fa-book"></i>
            <span class="app-menu__label">Logs</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-logs.php'); ?>" href="admin-logs.php">
                    <i class="icon fa fa-circle-o"></i>Status Logs
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-admin_logs.php'); ?>" href="admin-admin_logs.php">
                    <i class="icon fa fa-circle-o"></i>Admin Logs
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview" style="background: #780000;">
        <a class="app-menu__item <?php echo isPageActive('admin-rubbish-coordinator.php') || isPageActive('admin-rubbish.php') ? 'active' : ''; ?>" data-toggle="treeview" href="">
            <i class="app-menu__icon fa fa-trash-o" style="color: white;"></i>
            <span class="app-menu__label" style="color: white;">Account Bin</span>
            <i class="treeview-indicator fa fa-angle-right" style="color: white;"></i>
        </a>
        <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-rubbish-coordinator.php'); ?>" href="admin-rubbish-coordinator.php">
                    <i class="icon fa fa-circle-o"></i>Coordinator Account Bin
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('admin-rubbish.php'); ?>" href="admin-rubbish.php">
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
