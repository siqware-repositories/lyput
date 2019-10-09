<div class="sidebar sidebar-dark sidebar-main sidebar-fixed sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->


    <!-- Sidebar content -->
    <div class="sidebar-content">
        <!-- User menu -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="#"><img src="{{asset(Auth::user()->picture)}}" width="38" height="38" class="rounded-circle" alt=""></a>
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">{{Auth::user()->name}}</div>
                        <div class="font-size-xs opacity-50">
                            <i class="icon-pin font-size-sm"></i> &nbsp;Serei Sophaon, KH
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="#" class="text-white"><i class="icon-cog3"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->
        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            @if(Auth::check())
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'super_admin')
                    <ul class="nav nav-sidebar" data-nav-type="accordion">

                        <!-- Main -->
                        <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i></li>
                        <li class="nav-item">
                            <a href="/" class="nav-link {{request()->is('/')?'active':''}}">
                                <i class="icon-home4"></i>
                                <span>ផ្ទាំងដើម</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('product.index')}}" class="nav-link {{request()->is('product*')?'active':''}}">
                                <i class="icon-cube2"></i>
                                <span>ទំនិញ</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('invoice.index')}}" class="nav-link {{request()->is('invoice*')?'active':''}}">
                                <i class="icon-pencil"></i>
                                <span>វិក័យបត្រ</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('ap-ar.index')}}" class="nav-link {{request()->is('ap-ar*')?'active':''}}">
                                <i class="icon-cash"></i>
                                <span>បំណុល / សំណង</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('employee.index')}}" class="nav-link {{request()->is('employee*')?'active':''}}">
                                <i class="icon-cash"></i>
                                <span>ប្រាក់ខែបុគ្គលិក</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('budget.index')}}" class="nav-link {{request()->is('budget*')?'active':''}}">
                                <i class="icon-pencil"></i>
                                <span>ចំណូលចំណាយ</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('account.index')}}" class="nav-link {{request()->is('account*')?'active':''}}">
                                <i class="icon-calculator"></i>
                                <span>គណនី</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('media')}}" class="nav-link {{request()->is('media')?'active':''}}">
                                <i class="icon-images2"></i>
                                <span>មេឌៀ</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('user.index')}}" class="nav-link {{request()->is('user')?'active':''}}">
                                <i class="icon-users"></i>
                                <span>អ្នកប្រើប្រាស់</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('excel-import.index')}}" class="nav-link  {{request()->is('excel-import')?'active':''}}">
                                <i class="icon-file-excel"></i>
                                <span>Excel Import</span>
                            </a>
                        </li>
                        <!-- /main -->
                    </ul>
                @endif
            @endif
                @if(Auth::check())
                    @if (Auth::user()->role == 'user')
                        <ul class="nav nav-sidebar" data-nav-type="accordion">
                            <li class="nav-item">
                                <a href="{{route('product.index')}}" class="nav-link {{request()->is('product*')?'active':''}}">
                                    <i class="icon-cube2"></i>
                                    <span>ទំនិញ</span>
                                </a>
                            </li>
                        </ul>
                    @endif
                @endif
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>