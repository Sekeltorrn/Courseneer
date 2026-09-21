<?php

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<aside class="sidebar">

    <h2>Menu</h2>

    <ul>

        <li>
            <a href="/LabAct1/adminpage/dashboard.php"
                class="<?= ($currentPage == 'dashboard.php') ? 'active' : ''; ?>">
                Dashboard
            </a>
        </li>

        <li>
            <a href="/LabAct1/views/admin/students/list.php"
                class="<?= ($currentPage == 'studentlist.php' || $currentPage == 'list.php' || $currentPage == 'add.php') ? 'active' : ''; ?>">
                Students
            </a>
        </li>

        <li>
            <a href="/LabAct1/adminpage/courselist.php"
                class="<?= ($currentPage == 'courselist.php') ? 'active' : ''; ?>">
                Courses
            </a>
        </li>

        <li>
            <a href="/LabAct1/adminpage/instructorlist.php"
                class="<?= ($currentPage == 'instructorlist.php') ? 'active' : ''; ?>">
                Instructors
            </a>
        </li>

    </ul>

</aside>