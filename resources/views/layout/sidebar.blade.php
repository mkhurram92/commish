<style>
    .app-sidebar,
    .app-sidebar2,
    .app-sidebar3 {
        width: 280px;
        /* Increase the width as needed */
    }

    .app-content {
        margin-left: 280px;
        /* Adjust the content margin to match the sidebar width */
    }

    .side-menu__item {
        padding-left: 20px;
        /* Adjust the padding if necessary */
    }

    .custom-avatar-size {
        width: 100px;
        height: 100px;
        padding: 2px;
    }
</style>
<!--aside open-->
<div class="app-sidebar app-sidebar2">
    <div class="app-sidebar__logo">
        <a class="header-brand" href="{{ route('admin.dashboard') }}">

        </a>
    </div>
</div>
<aside class="app-sidebar app-sidebar3 is-expanded overflow-auto">
    <div class="app-sidebar__user">
        <div class="dropdown user-pro-body text-center">
            <div class="user-pic">
                @if (auth()->user()->image && \Storage::disk('public')->exists('profile/images/' . auth()->user()->image))
                    <img src="{{ asset('assets/images/' . auth()->user()->image) }}" alt="user-img"
                        class="avatar-xl rounded-circle mb-1">
                @else
                    <img src="{{ url('assets/images/users/jason.jpg') }}" alt="user-img"
                        class="custom-avatar-size rounded-circle mb-1 img-fluid">
                @endif
            </div>
            <div class="user-info">
                <h5 class=" mb-1 font-weight-bold">{{ auth()->user()->fname }} {{ auth()->user()->lname }}</h5>
            </div>
        </div>
    </div>
    <ul class="side-menu">
        <li class="slide {{ request()->segment(2) == 'dashboard' ? 'mm-active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="side-menu__item">
                <i class="ion-ios7-home side-menu__icon"></i>
                <span class="side-menu__label">Dashboard</span>
            </a>
        </li>

        @if (
            $user_is_admin == 1 ||
                //in_array('Contacts', $module_permissions) ||
                in_array('contacts-list', $module_permissions) ||
                in_array('contacts-add', $module_permissions))
            <li class="slide {{ request()->segment(2) == 'contacts' ? 'mm-active' : '' }}">
                <a href="#" class="side-menu__item" data-toggle="slide">
                    <i class="ion-person-stalker side-menu__icon"></i>

                    <span class="side-menu__label">Contact</span><i class="angle fa fa-angle-right"></i>

                </a>
                <ul class="slide-menu {{ request()->segment(2) == 'contacts' ? 'mm-show' : '' }}">
                    @if ($user_is_admin == 1 || in_array('contacts-list', $module_permissions))
                        <li>
                            <a href="{{ route('admin.contact.list') }}"
                                class="slide-item {{ request()->segment(3) == 'contacts-list' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Search
                            </a>
                        </li>
                    @endif
                    @if ($user_is_admin == 1 || in_array('contacts-add', $module_permissions))
                        <li>
                            <a href="{{ route('admin.contact.add') }}"
                                class="slide-item {{ request()->segment(3) == 'contacts-add' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Add Client
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (
            $user_is_admin == 1 ||
                /**in_array('Deals', $module_permissions) ||**/
                in_array('deals-list', $module_permissions) ||
                in_array('deals-add', $module_permissions))
            <li class="slide {{ request()->segment(2) == 'deals' ? 'mm-active' : '' }}">
                <a href="#" class="side-menu__item" data-toggle="slide">
                    <i class="ion-clipboard side-menu__icon"></i>
                    <span class="side-menu__label">Deals</span><i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu {{ request()->segment(2) == 'deals' ? 'mm-show' : '' }}">
                    @if ($user_is_admin == 1 || in_array('deals-list', $module_permissions))
                        <li>
                            <a href="{{ route('admin.deals.list') }}"
                                class="slide-item {{ request()->segment(3) == 'deals-list' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Search
                            </a>
                        </li>
                    @endif
                    @if ($user_is_admin == 1 || in_array('deals-add', $module_permissions))
                        <li>
                            <a href="{{ route('admin.deals.add') }}"
                                class="slide-item {{ request()->segment(3) == 'deals-add' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Add Deal
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (
            $user_is_admin == 1 ||
                /**in_array('Commissions', $module_permissions) ||**/
                in_array('deals-commissions', $module_permissions) ||
                in_array('deals-missinglist', $module_permissions) ||
                in_array('commission-types', $module_permissions) ||
                in_array('lenders-commission-schedules', $module_permissions) ||
                in_array('refferor-commission-schedules', $module_permissions))
            <li class="slide {{ request()->segment(2) == 'commissions' ? 'mm-active' : '' }}">
                <a href="#" class="side-menu__item" data-toggle="slide">
                    <i class="ion-cash side-menu__icon"></i>

                    <span class="side-menu__label">Commissions</span><i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu {{ request()->segment(2) == 'commissions' ? 'mm-show' : '' }}">
                    @if ($user_is_admin == 1 || in_array('deals-commissions', $module_permissions))
                        <li>
                            <a href="{{ route('admin.deals.commissions') }}"
                                class=" slide-item {{ request()->segment(3) == 'deals-commissions' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Import Deals Data
                            </a>
                        </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('deals-missinglist', $module_permissions))
                        <li>
                            <a href="{{ route('admin.deals.dealMissingList') }}"
                                class=" slide-item {{ request()->segment(3) == 'deals-missinglist' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Missing Ref. No
                            </a>
                        </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('commission-types', $module_permissions))
                        <li>
                            <a href="{{ route('admin.commissiontypes') }}"
                                class="slide-item {{ request()->segment(2) == 'commission-types' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Commission Types
                            </a>
                        </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('lenders-commission-schedules', $module_permissions))
                        <li>
                            <a href="{{ route('admin.lendercommissionschedule') }}"
                                class="slide-item {{ request()->segment(2) == 'lenders-commission-schedules' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Commission Schedule
                            </a>
                        </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('refferor-commission-schedules', $module_permissions))
                        <li>
                            <a href="{{ route('admin.refferorcommissionschedule') }}"
                                class="slide-item {{ request()->segment(2) == 'refferor-commission-schedules' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Referrer Commission Schedule
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if (
            $user_is_admin == 1 ||
                /**in_array('Commissions', $module_permissions) ||**/
                in_array('deals-commissions', $module_permissions) ||
                in_array('deals-missinglist', $module_permissions) ||
                in_array('commission-types', $module_permissions) ||
                in_array('lenders-commission-schedules', $module_permissions) ||
                in_array('refferor-commission-schedules', $module_permissions))
            <li class="slide {{ request()->segment(2) == 'master' ? 'mm-active' : '' }}">
                <a href="#" class="side-menu__item" data-toggle="slide">
                    <i class="ion-settings side-menu__icon"></i>

                    <span class="side-menu__label">Master</span><i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu {{ request()->segment(2) == 'master' ? 'mm-show' : '' }}">
                    @if ($user_is_admin == 1 || in_array('referrer_add', $module_permissions))
                        <li>
                            <a href="{{ route('referrer_add') }}"
                                class="slide-item {{ request()->segment(3) == 'referrer_add' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Add Referrer
                            </a>
                        </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('brokers-add', $module_permissions))
                        <li>
                            <a href="{{ route('admin.brokers.add') }}"
                                class="slide-item {{ request()->segment(3) == 'brokers-add' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Add Broker
                            </a>
                        </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('users-list', $module_permissions))
                        <li>
                            <a href="{{ route('admin.user') }}"
                                class="slide-item {{ request()->segment(2) == 'users-list' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                User
                            </a>
                        </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('relationship-list', $module_permissions))
                        <li>
                            <a href="{{ route('admin.relationship') }}"
                                class="slide-item {{ request()->segment(2) == 'relationship' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Relationship
                            </a>
                        </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('products-list', $module_permissions))
                        <li>
                            <a href="{{ route('admin.products') }}"
                                class="slide-item {{ request()->segment(2) == 'products' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Products
                            </a>
                        </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('industry-list', $module_permissions))
                        <li>
                            <a href="{{ route('admin.industry') }}"
                                class="slide-item {{ request()->segment(2) == 'industry' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Industry
                            </a>
                        </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('lenders-list', $module_permissions))
                        <li>
                            <a href="{{ route('admin.lenders') }}"
                                class="slide-item {{ request()->segment(2) == 'lenders-list' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Lenders
                            </a>
                        </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('service-list', $module_permissions))
                        <li>
                            <a href="{{ route('admin.service') }}"
                                class="slide-item {{ request()->segment(2) == 'service-list' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Services
                            </a>
                        </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('processor-list', $module_permissions))
                        <li>
                            <a href="{{ route('admin.loantype') }}"
                                class="slide-item {{ request()->segment(2) == 'processor-list' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Loan Types
                            </a>
                        </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('profile', $module_permissions))
                        <li>
                            <a href="{{ route('admin.setting.profile') }}"
                                class="slide-item {{ request()->segment(3) == 'profile' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Profile
                            </a>
                        </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('entity', $module_permissions))
                        <li>
                            <a href="{{ route('admin.entity.edit') }}"
                                class="slide-item {{ request()->segment(3) == 'entity' ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Entity
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (
            $user_is_admin == 1 ||
                in_array('broker-index', $module_permissions) ||
                in_array('deals-missinglist', $module_permissions) ||
                in_array('commission-types', $module_permissions) ||
                in_array('lenders-commission-schedules', $module_permissions) ||
                in_array('refferor-commission-schedules', $module_permissions))
            <li class="slide {{ request()->segment(2) == 'Reports' ? 'mm-active' : '' }}">
                <a href="#" class="side-menu__item" data-toggle="slide">
                    <i class="ion-compose side-menu__icon"></i>
                    <span class="side-menu__label">Reports</span><i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu {{ request()->segment(2) == 'reports' ? 'mm-show' : '' }}">
                    @if ($user_is_admin == 1 || in_array('broker-index', $module_permissions))
                    <li>
                        <a href="{{ route('admin.broker.index') }}"
                            class="slide-item {{ request()->segment(3) == 'broker-index' ? 'mm-active' : '' }}">
                            <i class="metismenu-icon"></i>
                            Broker Invoices
                        </a>
                    </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('fm_direct-index', $module_permissions))
                    <li>
                        <a href="{{ route('admin.fm_direct.index') }}"
                            class="slide-item {{ request()->segment(3) == 'fm_direct-index' ? 'mm-active' : '' }}">
                            <i class="metismenu-icon"></i>
                            Reports
                        </a>
                    </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('lender-index', $module_permissions))
                    <li>
                        <a href="{{ route('admin.lender.index') }}"
                            class="slide-item {{ request()->segment(3) == 'lender-index' ? 'mm-active' : '' }}">
                            <i class="metismenu-icon"></i>
                            Lender
                        </a>
                    </li>
                    @endif

                    @if ($user_is_admin == 1 || in_array('referrors-index', $module_permissions))
                    <li>
                        <a href="{{ route('admin.referrors.index') }}"
                            class="slide-item {{ request()->segment(3) == 'referrors-index' ? 'mm-active' : '' }}">
                            <i class="metismenu-icon"></i>
                            Referrer Invoices
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
        @endif
        <li>
            <a href="{{ route('logout') }}" class="side-menu__item">
                <i class="ion-power side-menu__icon"></i>

                <span class="side-menu__label">Logout</span>
            </a>
        </li>
    </ul>
</aside>
<!--aside closed-->
<style>
    .custom-avatar-size {
        width: 100px;
        /* Adjust the width as needed */
        height: 100px;
        /* Adjust the height as needed */
        padding: 2px;
    }
</style>
