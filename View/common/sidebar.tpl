<aside class="main-sidebar">
    <section class="sidebar" style="height: auto">
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview {if $activePage == 'dashboard'} active {/if}">
                <a href="/seatmap/index.php">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview {if $activePage == 'user'} active {/if}">
                <a href="/seatmap/user/index">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="treeview {if $activePage == 'seatmap'} active {/if}">
                <a href="/seatmap/seatmap/index">
                    <i class="fa fa-sitemap" aria-hidden="true"></i>
                    <span>Seatmap</span>
                </a>
            </li>
            <li class="treeview {if $activePage == 'group'} active {/if}">
                <a href="/seatmap/group/index">
                    <i class="fa fa-sitemap" aria-hidden="true"></i>
                    <span>Group</span>
                </a>
            </li>
        </ul>
    </section>
</aside>