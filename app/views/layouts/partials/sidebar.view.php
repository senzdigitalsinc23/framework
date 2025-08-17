

    <!-- Sidebar -->
    <?php
        function isMenuActive($prefix) {
            return strpos($_SERVER['REQUEST_URI'], $prefix) !== false ? 'show' : '';
        }
        function isLinkActive($uri) {
            return strpos($_SERVER['REQUEST_URI'], $uri) !== false ? 'active text-primary' : '';
        }
    ?>

    <div class="bg-dark text-white vh-100 p-3">
        <h4 class="text-white mb-4">Admin Panel</h4>

        <!-- Dashboard -->
        <a href="/web/admin" class="nav-link text-white mb-2 <?= isLinkActive('/web/admin') ?>">
            <?=icon('speedometer2') ?> Dashboard
        </a>

        <!-- Students -->
         
        <div class="accordion" id="sidebarMenu">
            <div class="accordion-item bg-dark border-0 mb-0">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#studentsMenu">
                        <?=icon('people-fill') ?> Students
                    </button>
                </h2>
                <div id="studentsMenu" class="accordion-collapse collapse <?= isMenuActive('/web/admin/students') ?> text-white" data-bs-parent="#sidebarMenu">
                    <div class="accordion-body p-0">
                        <a href="/web/admin/students" class="nav-link ps-4 <?= isLinkActive('/web/admin/students') ?>"> <?=icon('person-fill-gear')?> Manage Students</a>
                        <a href="/web/admin/students/import" class="nav-link ps-4 <?= isLinkActive('/web/admin/students/import') ?>"><?=icon('cloud-arrow-down-fill')?> Import</a>
                        <a href="/web/admin/students/export" class="nav-link ps-4 <?= isLinkActive('/web/admin/students/export') ?>"><?=icon('cloud-arrow-up-fill')?> Export</a>
                    </div>
                </div>
            </div>
       
            <!-- Staff -->
            <div class="accordion-item bg-dark border-0">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#staffMenu">
                        <?=icon('person-badge-fill') ?> Staff
                    </button>
                </h2>
                <div id="staffMenu" class="accordion-collapse collapse <?= isMenuActive('/web/admin/staff') ?> text-white" data-bs-parent="#sidebarMenu">
                    <div class="accordion-body p-0">
                        <a href="/web/admin/staff" class="nav-link ps-4 <?= isLinkActive('/web/admin/staff') ?>">Manage Staff</a>
                        <a href="/web/admin/staff/import" class="nav-link ps-4 <?= isLinkActive('/web/admin/staff/import') ?>">Import</a>
                        <a href="/web/admin/staff/export" class="nav-link ps-4 <?= isLinkActive('/web/admin/staff/export') ?>">Export</a>
                    </div>
                </div>
            </div>

            <!-- Finance -->
            <div class="accordion-item bg-dark border-0">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#financeMenu">
                        <?=icon('cash-coin') ?> Finance
                    </button>
                </h2>
                <div id="financeMenu" class="accordion-collapse collapse <?= isMenuActive('/web/admin/finance') ?> text-white" data-bs-parent="#sidebarMenu">
                    <div class="accordion-body p-0">
                        <a href="/web/admin/finance/fees" class="nav-link ps-4 <?= isLinkActive('/web/admin/finance/fees') ?>">Fees</a>
                        <a href="/web/admin/finance/payments" class="nav-link ps-4 <?= isLinkActive('/web/admin/finance/payments') ?>">Payments</a>
                        <a href="/web/admin/finance/reports" class="nav-link ps-4 <?= isLinkActive('/web/admin/finance/reports') ?>">Reports</a>
                    </div>
                </div>
            </div>

            <!-- Auth Management -->
            <div class="accordion-item bg-dark border-0">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#authMenu">
                        <?=icon('shield-lock-fill') ?> Auth
                    </button>
                </h2>
                <div id="authMenu" class="accordion-collapse collapse <?= isMenuActive('/web/admin') ?> text-white" data-bs-parent="#sidebarMenu">
                    <div class="accordion-body p-0">
                        <a href="/web/admin/users" class="nav-link ps-4 <?= isLinkActive('/web/admin/users') ?>">Users</a>
                        <a href="/web/admin/roles" class="nav-link ps-4 <?= isLinkActive('/web/admin/roles') ?>">Roles</a>
                        <a href="/web/admin/permissions" class="nav-link ps-4 <?= isLinkActive('/web/admin/permissions') ?>">Permissions</a>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="accordion-item bg-dark border-0">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#settingsMenu">
                        <?=icon('gear-fill') ?> Settings
                    </button>
                </h2>
                <div id="settingsMenu" class="accordion-collapse collapse <?= isMenuActive('/web/admin/settings') ?> text-white " data-bs-parent="#sidebarMenu">
                    <div class="accordion-body p-0">
                        <a href="/web/admin/settings/general" class="nav-link ps-4 <?= isLinkActive('/web/admin/settings/general') ?>">General</a>
                        <a href="/web/admin/settings/profile" class="nav-link ps-4 <?= isLinkActive('/web/admin/settings/profile') ?>">Profile</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logout -->
        <a href="/web/logout" class="nav-link text-white mt-4">
            <?=icon('box-arrow-right') ?>  
        </a>
    </div>
