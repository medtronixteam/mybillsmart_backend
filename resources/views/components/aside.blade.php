<aside class="left-sidebar" data-sidebarbg="skin6">
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- Dashboard -->
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeis('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}" aria-expanded="false">
                        <i data-feather="home" class="feather-icon"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <!-- Divider -->
                <li class="list-divider"></li>

                <!-- Pages Section -->
                <li class="nav-small-cap"><span class="hide-menu">Pages</span></li>

                <!-- Manage Users with Submenu -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow {{ request()->routeis('user.*') ? 'active' : '' }}"
                        href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="users" class="feather-icon"></i>
                        <span class="hide-menu">Manage Users</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->routeis('user.create') ? 'active' : '' }}"
                                href="{{ route('user.create') }}">
                                <span class="hide-menu">Create User</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->routeis('user.list') ? 'active' : '' }}"
                                href="{{ route('user.list') }}">
                                <span class="hide-menu">Users List</span>
                            </a>
                        </li>
                    </ul>
                </li>
                 <!-- Manage Users with Submenu -->
                 <li class="sidebar-item">
                    <a class="sidebar-link has-arrow {{ request()->routeis('user.*') ? 'active' : '' }}"
                        href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="file-text" class="feather-icon"></i>
                        <span class="hide-menu">Reports</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->routeis('contracts.list') ? 'active' : '' }}"
                                href="{{ route('contracts.list') }}">
                                <span class="hide-menu">Recent Contracts</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->routeis('invoice.list') ? 'active' : '' }}"
                                href="{{ route('invoice.list') }}">
                                <span class="hide-menu">Recent Invoices</span>
                            </a>
                        </li>
                    </ul>
                </li>
                   <!-- Manage Users with Submenu -->
                   <li class="sidebar-item">
                    <a class="sidebar-link has-arrow {{ request()->routeis('payments') ? 'active' : '' }} {{ request()->routeis('subscriptions') ? 'active' : '' }}"
                        href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="repeat" class="feather-icon"></i>
                        <span class="hide-menu">Payments Reports</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->routeis('payments') ? 'active' : '' }}"
                                href="{{ route('payments') }}">
                                <span class="hide-menu">Payments</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->routeis('subscriptions') ? 'active' : '' }}"
                                href="{{ route('subscriptions') }}">
                                <span class="hide-menu">Subscriptions</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Plans -->
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeis('plans') ? 'active' : '' }}"
                        href="{{ route('plans') }}" aria-expanded="false">
                        <i data-feather="package" class="feather-icon"></i>
                        <span class="hide-menu">Manage Plans</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeis('agrrements') ? 'active' : '' }}"
                        href="{{ route('agreements') }}" aria-expanded="false"><i data-feather="tag"
                            class="feather-icon"></i><span class="hide-menu">Agreements
                        </span></a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeis('documents') ? 'active' : '' }}"
                        href="{{ route('documents') }}" aria-expanded="false"><i data-feather="tag"
                            class="feather-icon"></i><span class="hide-menu">Train BOT
                        </span></a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeis('whatsapp') ? 'active' : '' }}"
                        href="{{ route('whatsapp') }}" aria-expanded="false"><i data-feather="tag"
                            class="feather-icon"></i><span class="hide-menu">WP Manager
                        </span></a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
