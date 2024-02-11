<!--aside open-->
<div class="app-sidebar app-sidebar2">
    <div class="app-sidebar__logo">
        <a class="header-brand" href="{{route('admin.dashboard')}}">
            <img src="{{url('assets/images/FMA-retina-logo-rev.png')}}" class="header-brand-img desktop-lgo" alt="Commish  logo">
            <img src="{{url('assets/images/FMA-retina-logo-rev.png')}}" class="header-brand-img dark-logo" alt="Commish logo">
            <img src="{{url('assets/images/FMA-retina-logo-rev.png')}}" class="header-brand-img mobile-logo" alt="Commish logo">
            <img src="{{url('assets/images/FMA-retina-logo-rev.png')}}" class="header-brand-img darkmobile-logo" alt="Commish logo">
        </a>
    </div>
</div>
<aside class="app-sidebar app-sidebar3 is-expanded overflow-auto">
    <div class="app-sidebar__user">
        <div class="dropdown user-pro-body text-center">
            <div class="user-pic">
                @if (auth()->user()->image && \Storage::disk('public')->exists('profile/images/' . auth()->user()->image))
                    <img src="{{asset('storage/profile/images/'.auth()->user()->image)}}" alt="user-img" class="avatar-xl rounded-circle mb-1">
                @else
                    <img src="{{url('assets/images/users/jason.jpg')}}" alt="user-img" class="custom-avatar-size rounded-circle mb-1 img-fluid">
                @endif
            </div>
            <div class="user-info">
                <h5 class=" mb-1 font-weight-bold">{{auth()->user()->fname}} {{auth()->user()->lname}}</h5>
            </div>
        </div>
    </div>
    <ul class="side-menu">
        <li class="slide {{request()->segment(2) == "dashboard" ? 'mm-active':""}}">
            <a href="{{route('admin.dashboard')}}" class="side-menu__item">
                <i class="ion-ios7-home side-menu__icon"></i>
                <span class="side-menu__label">Dashboard</span>
            </a>
        </li>

        @if($user_is_admin == 1 || in_array('contacts',$module_permissions))
            <li class="slide {{request()->segment(2) == "contact" ? 'mm-active':""}}">
                <a href="#"  class="side-menu__item" data-toggle="slide" >
                    <i class="ion-person-stalker side-menu__icon"></i>

                    <span class="side-menu__label">Contact</span><i class="angle fa fa-angle-right"></i>

                </a>
                <ul class="slide-menu {{request()->segment(2) == "contact" ? 'mm-show':""}}">
                    <li >
                        <a href="{{route('admin.contact.list')}}" class="slide-item {{request()->segment(3) == "list"
                         ? 'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            List
                        </a>
                    </li>

                    <li>
                        <a href="{{route('admin.contact.add')}}" class="slide-item {{request()->segment(3) == "add" ?
                         'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            Add Client
                        </a>
                    </li>
                    <li>
                        <a href="{{route('referrer_add')}}" class="slide-item {{request()->segment(3) == "add" ?
                         'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            Add Referrer
                        </a>
                    </li>
                    <li>
                        <a href="{{route('admin.brokers.add')}}" class="slide-item {{request()->segment(3) == "add" ?
                         'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            Add Broker
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if($user_is_admin == 1 || in_array('deals',$module_permissions)  || in_array
        ('lenders-commission-schedules',$module_permissions)  || in_array('refferor-commission-schedules',$module_permissions))
            <li class="slide {{request()->segment(2) == "deals"  || request()->segment(2) ==
                "lenders-commission-schedules"  || request()->segment(2) == "refferor-commission-schedules" ? 'mm-active':""}}">
                <a href="#" class="side-menu__item" data-toggle="slide">
                    <i class="ion-clipboard side-menu__icon"></i>

                    <span class="side-menu__label">Deals</span><i class="angle fa fa-angle-right"></i>

                </a>
                <ul class="slide-menu {{request()->segment(2) == "deals" ? 'mm-show':""}}">
                    <li>
                        <a href="{{route('admin.deals.list')}}" class="slide-item {{request()->segment(3) == "list" ?
                         'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            List
                        </a>
                    </li>

                    <li>
                        <a href="{{route('admin.deals.add')}}" class="slide-item {{request()->segment(3) == "add" ?
                        'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            Add
                        </a>
                    </li>
                    <li>
                        <a href="{{route('admin.deals.commissions')}}" class=" slide-item {{request()->segment(3) ==
                        "commissions" ? 'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            Import Deals Data
                        </a>
                    </li>
                     <li>
                        <a href="{{route('admin.deals.dealMissingList')}}" class=" slide-item {{request()->segment(3) ==
                        "commissions" ? 'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            Missing Ref. No
                        </a>
                    </li>

                </ul>
            </li>
        @endif


        @if($user_is_admin == 1 || (in_array('users',$module_permissions) || in_array('abp',
        $module_permissions) || in_array('contact-role',$module_permissions) || in_array('products',
        $module_permissions) || in_array('industries',$module_permissions) || in_array('lenders',
        $module_permissions) || in_array('expense-type',$module_permissions) || in_array('services',
        $module_permissions) || in_array('referral-sources',$module_permissions) || in_array('processors',
        $module_permissions) || in_array('commission-types',$module_permissions) ))
            <li class="slide {{(request()->segment(2) == "abp" || request()->segment(2) == "relationship" || request()->segment(2) == "contactrole" || request()
            ->segment(2) == "products" || request()->segment(2) == "industry" || request()->segment(2) == "lenders" || request()->segment(2) == "expensetype" || request()->segment(2) == "service" ||request()->segment(2) == "referral" || request()->segment(2) == "processor"  || request()->segment(2) == "commission-types"  || request()->segment(2) == "commissions"  || request()->segment(2) == "refferor-commission-schedules"  ) ? 'mm-active':""}}">
                <a href="#" class="side-menu__item" data-toggle="slide" >
                    <i class="ion-map side-menu__icon"></i>

                    <span class="side-menu__label">Masters</span><i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu {{(request()->segment(2) == "abp" ) ? 'mm-show':""}}">
                    @if($user_is_admin == 1 || (in_array('users',$module_permissions)))
                        <li>
                            <a href="{{route('admin.user')}}" class="slide-item {{request()->segment(2) == "user" ?
                            'mm-active':""}}">
                                <i class="metismenu-icon"></i>
                                User
                            </a>
                        </li>
                    @endif
                    @if($user_is_admin == 1 || (in_array('relationship',$module_permissions)))
                        <li>
                            <a href="{{route('admin.relationship')}}" class="slide-item {{request()->segment(2) ==
                        "relationship" ? 'mm-active':""}}">
                                <i class="metismenu-icon"></i>
                                Relationship
                            </a>
                        </li>
                    @endif
                    @if($user_is_admin == 1 || (in_array('products',$module_permissions)))
                        <li>
                            <a href="{{route('admin.products')}}" class="slide-item {{request()->segment(2) ==
                            "products" ? 'mm-active':""}}">
                                <i class="metismenu-icon"></i>
                                Products
                            </a>
                        </li>
                    @endif
                    @if($user_is_admin == 1 || (in_array('industries',$module_permissions)))
                        <li>
                            <a href="{{route('admin.industry')}}" class="slide-item {{request()->segment(2) ==
                            "industry" ? 'mm-active':""}}">
                                <i class="metismenu-icon"></i>
                                Industry
                            </a>
                        </li>
                    @endif
                    @if($user_is_admin == 1 || (in_array('lenders',$module_permissions)))
                        <li>
                            <a href="{{route('admin.lenders')}}" class="slide-item {{request()->segment(2) ==
                            "lenders" ? 'mm-active':""}}">
                                <i class="metismenu-icon"></i>
                                Lenders
                            </a>
                        </li>
                    @endif
                    @if($user_is_admin == 1 || (in_array('commission-types',$module_permissions)))
                        <li>
                            <a href="{{route('admin.commissiontypes')}}" class="slide-item {{request()->segment(2) ==
                                    "commission-types" ? 'mm-active':""}}">
                                <i class="metismenu-icon"></i>
                                Commission Types
                            </a>
                        </li>
                    @endif
                    @if($user_is_admin == 1 || (in_array('lenders-commission-schedules',$module_permissions)))
                        <li>
                            <a href="{{route('admin.lendercommissionschedule')}}" class="slide-item {{request()
                            ->segment(2) ==
                                "lenders-commission-schedules" ? 'mm-active':""}}">
                                <i class="metismenu-icon"></i>
                                Commission Schedule
                            </a>
                        </li>
                    @endif


                        @if($user_is_admin == 1 || (in_array('refferor-commission-schedules',$module_permissions)))
                            <li>
                                <a href="{{route('admin.refferorcommissionschedule')}}" class="slide-item {{request()
                            ->segment(2) ==
                                "refferor-commission-schedules" ? 'mm-active':""}}">
                                    <i class="metismenu-icon"></i>
                                    Referrer Commission Schedule
                                </a>
                            </li>
                        @endif

                    @if($user_is_admin == 1 || (in_array('services',$module_permissions)))
                        <li>
                            <a href="{{route('admin.service')}}" class="slide-item {{request()->segment(2) ==
                            "service" ? 'mm-active':""}}">
                                <i class="metismenu-icon"></i>
                                Services
                            </a>
                        </li>
                    @endif
                    @if($user_is_admin == 1 || (in_array('loantypes',$module_permissions)))
                        <li>
                            <a href="{{route('admin.loantype')}}" class="slide-item {{request()->segment(2) ==
                            "processor" ? 'mm-active':""}}">
                                <i class="metismenu-icon"></i>
                                Loan Types
                            </a>
                        </li>
                    @endif

                </ul>
            </li>
        @endif
        <li class="slide {{(request()->segment(2) == "reports")  ? 'mm-active':""}}">
            <a href="#" class="side-menu__item" data-toggle="slide" >
                <i class="ion-map side-menu__icon"></i>
                <span class="side-menu__label">Reports</span><i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu {{request()->segment(2) == "reports" ? 'mm-show':""}}">
                @if($user_is_admin == 1 || in_array('reports',$module_permissions))
                    <li>
                        <a href="{{ route('admin.broker.index') }}" class="slide-item {{request()->segment(3) ==  "broker" ? 'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            Broker
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.fm_direct.index') }}" class="slide-item {{(request()->segment(3) ==  "fm-direct") ? 'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            FM Direct
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.lender.index') }}" class="slide-item {{request()->segment(3) ==  "lender" ? 'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            Lender
                        </a>
                    </li>

                   <!--  <li>
                        <a href="{{ route('admin.outstanding.index') }}" class="slide-item {{request()->segment(3) ==  "outstanding" ? 'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            Commission Outstanding
                        </a>
                    </li> 
                    <li>
                        <a href="{{ route('admin.reconciliation.index') }}" class="slide-item {{request()->segment(3) ==  "reconciliation" ? 'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            Reconciliation
                        </a>
                    </li>
                   <li>
                        <a href="#" class="slide-item {{request()->segment(3) ==  "referrer" ? 'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            Referrer
                        </a>
                    </li>-->

                    <li>
                        <a href="{{ route('admin.referrors.index') }}" class="slide-item {{ request()->segment(3) == 'referror' ? 'mm-active' : '' }}">
                            <i class="metismenu-icon"></i>
                            Referrer
                        </a>
                    </li>

                @endif
            </ul>
        </li>
        <li class="slide {{request()->segment(2) == "setting" ? 'mm-active':""}}">
            <a href="#" class="side-menu__item" data-toggle="slide">
                <i class="ion-settings side-menu__icon"></i>

                <span class="side-menu__label">Settings</span><i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu {{request()->segment(2) == "setting" ? 'mm-show':""}}">
                <li>
                    <a href="{{route('admin.setting.profile')}}" class="slide-item {{request()->segment(3) ==
                    "profile" ? 'mm-active':""}}">
                        <i class="metismenu-icon"></i>
                        Profile
                    </a>
                </li>
                @if($user_is_admin == 1 || in_array('settings',$module_permissions))
                    <li>
                        <a href="{{route('admin.entity.edit')}}" class="slide-item {{request()->segment(3) ==  "entity" ? 'mm-active':""}}">
                            <i class="metismenu-icon"></i>
                            Entity
                        </a>
                    </li>
                @endif
            </ul>
        </li>
        <li>
            <a href="{{route('logout')}}" class="side-menu__item">
                <i class="ion-power side-menu__icon"></i>

                <span class="side-menu__label">Logout</span>
            </a>
        </li>
    </ul>
<!--     <div class="app-sidebar-help">
        <div class="dropdown text-center">
            <div class="help d-flex">
                <a href="#" class="nav-link p-0 help-dropdown" data-toggle="dropdown">
                    <span class="font-weight-bold">Help Info</span> <i class="fa fa-angle-down ml-2"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow p-4">
                    <div class="border-bottom pb-3">
                        <h4 class="font-weight-bold">Help</h4>
                        <a class="text-primary d-block" href="#">Knowledge base</a>
                        <a class="text-primary d-block" href="#">Contact@info.com</a>
                        <a class="text-primary d-block" href="#">88 8888 8888</a>
                    </div>
                    <div class="border-bottom pb-3 pt-3 mb-3">
                        <p class="mb-1">Your Fax Number</p>
                        <a class="font-weight-bold" href="#">88 8888 8888</a>
                    </div>
                    <a class="text-primary" href="#">Logout</a>
                </div>

            </div>
        </div>
    </div> -->
</aside>
<!--aside closed-->
<style>
.custom-avatar-size {
    width: 100px; /* Adjust the width as needed */
    height: 100px; /* Adjust the height as needed */
    padding: 2px;
}
</style>