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
        <a class="app-menu__item <?php echo isPageActive('coordinator-dashboard.php'); ?>" href="coordinator-dashboard.php">
            <i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span>
        </a>
    </li>
    <li class="treeview">
        <a class="app-menu__item <?php echo isPageActive('coordinator-add-edit-students.php'); ?>" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Student Setting</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
            <li>
                <a class="treeview-item <?php echo isPageActive('coordinator-add-edit-students.php'); ?>" href="coordinator-add-edit-students.php">
                    <i class="icon fa fa-circle-o"></i>Add/Delete Students
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a class="app-menu__item <?php echo isPageActive('coordinator-tables.php') || isPageActive('coordinator-tables-approved.php') || isPageActive('coordinator-tables-denied.php') || isPageActive('coordinator-tables-tes.php') || isPageActive('coordinator-tables-tdp.php') ? 'active' : ''; ?>" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Applicants</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu" style="background: rgb(212, 212, 212);">
            <li>
                <a class="treeview-item <?php echo isPageActive('coordinator-tables.php'); ?>" href="coordinator-tables.php">
                    <i class="icon fa fa-circle-o"></i>Pending Applicants
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('coordinator-tables-approved.php'); ?>" href="coordinator-tables-approved.php">
                    <i class="icon fa fa-circle-o"></i>Approved Applicants
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('coordinator-tables-denied.php'); ?>" href="coordinator-tables-denied.php">
                    <i class="icon fa fa-circle-o"></i>Denied Applicants
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('coordinator-tables-tes.php'); ?>" href="coordinator-tables-tes.php">
                    <i class="icon fa fa-circle-o"></i>TES Applicants
                </a>
            </li>
            <li>
                <a class="treeview-item <?php echo isPageActive('coordinator-tables-tdp.php'); ?>" href="coordinator-tables-tdp.php">
                    <i class="icon fa fa-circle-o"></i>TDP Applicants
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
