<aside class="left-sidebar" data-sidebarbg="skin6">
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item">
                    <a class="sidebar-link  {{ request()->routeis('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}" aria-expanded="false"><i data-feather="home"
                            class="feather-icon"></i><span class="hide-menu">Dashboard</span></a>
                </li>
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Pages</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeis('user.create') ? 'active' : '' }}"
                        href="{{ route('user.create') }}" aria-expanded="false"><i data-feather="tag"
                            class="feather-icon"></i><span class="hide-menu">Manage Users
                        </span></a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeis('user.list') ? 'active' : '' }}"
                        href="{{ route('user.list') }}" aria-expanded="false"><i data-feather="tag"
                            class="feather-icon"></i><span class="hide-menu">Users List
                        </span></a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeis('contracts.list') ? 'active' : '' }}"
                        href="{{ route('contracts.list') }}" aria-expanded="false"><i data-feather="tag"
                            class="feather-icon"></i><span class="hide-menu">Contracts List
                        </span></a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeis('invoice.list') ? 'active' : '' }}"
                        href="{{ route('invoice.list') }}" aria-expanded="false"><i data-feather="tag"
                            class="feather-icon"></i><span class="hide-menu">invoices
                        </span></a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
