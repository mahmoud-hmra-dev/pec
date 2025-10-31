<!-- Main Sidebar Container -->
<style>
    ul .line>a:after {
        content: "";
        position: absolute;
        width: 100%;
        height: 1px;
        bottom: -6px;
        left: 0;
        background: linear-gradient(90deg, rgba(237, 85, 59, 0.0618557) 0%, #ffffff 49.48%, rgba(237, 85, 59, 0) 100%);
        transition: all 0.3s ease-in-out 0s;
    }
</style>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{route('home')}}" class="brand-link">
        <img src="{{asset('images/logo.png')}}" alt="Website Logo" class="d-block" style="opacity: .8;width: 39%;margin: 0 auto;">
    </a>
    <div class="sidebar" style="overflow: hidden">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @can(\App\Enums\PermissionEnum::MANAGE_USERS)
                    <li class="nav-item line">
                        <a href="{{route('users.index')}}" class="nav-link {{ route('users.index') == request()->fullUrl() ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                @endcan
                    @can(\App\Enums\PermissionEnum::VIEW_PATIENTS)
                        <li class="nav-item line">
                            <a href="{{route('enroll_patient.index')}}" class="nav-link {{ route('enroll_patient.index') == request()->fullUrl() ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-plus"></i>
                                <p>
                                    Enroll Patient
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can(\App\Enums\PermissionEnum::VIEW_PROGRAMS)
                        <li class="nav-item line">
                            <a href="{{route('programs.index')}}" class="nav-link {{ \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('programs.index')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-object-group"></i>
                                <p>
                                    Programs
                                </p>
                            </a>
                        </li>
                    @endcan


                    @can(\App\Enums\PermissionEnum::VIEW_VISITS)
                        <li class="nav-item line {{ \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('calls-and-visits-upcoming.index')) ? 'menu-open' : '' }}">
                            <a href="{{route('calls-and-visits-upcoming.index')}}" class="nav-link {{ \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('calls-and-visits-upcoming.index' )) || \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('calls-and-visits-completed.index' )) || \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('foc.index' )) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    Tasks
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a href="{{route('calls-and-visits-completed.index')}}" class="nav-link pl-4 {{ \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('calls-and-visits-completed.index' )) ? 'active' : '' }}">
                                        <p>
                                            Calls & Visits Completed
                                        </p>
                                    </a></li>
                                <li class="nav-item"><a href="{{route('calls-and-visits-upcoming.index')}}" class="nav-link pl-4 {{ \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('calls-and-visits-upcoming.index' )) ? 'active' : '' }}">
                                        <p>
                                            Calls & Visits Upcoming
                                        </p>
                                    </a></li>
                                <li class="nav-item"><a href="{{route('foc.index')}}" class="nav-link pl-4 {{ \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('foc.index' )) ? 'active' : '' }}">
                                        <p>
                                            FOC
                                        </p>
                                    </a></li>
                            </ul>
                        </li>
                    @endcan

                    @can(\App\Enums\PermissionEnum::VIEW_CLIENTS)
                        <li class="nav-item line {{ \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('clients.index')) ? 'menu-open' : '' }}">
                            <a href="{{route('clients.index')}}" class="nav-link {{ \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('clients.index')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Clients
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a href="{{route('clients.index')}}" class="nav-link pl-4 {{ route('clients.index') == request()->fullUrl() ? 'active' : '' }}"><i class="nav-icon fas fa-user"></i><p>Clients</p></a></li>
                                @can(\App\Enums\PermissionEnum::MANAGE_DOCUMENTS)<li class="nav-item"><a href="{{route('documenttypes.index')}}" class="nav-link {{ route('documenttypes.index') == request()->fullUrl() ? 'active' : '' }}">
                                        <p>
                                            Document Types
                                        </p>
                                    </a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can(\App\Enums\PermissionEnum::VIEW_ServiceProvider)
                        <li class="nav-item line {{ \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('service-providers.index')) ? 'menu-open' : '' }}">
                            <a href="{{route('service-providers.index')}}" class="nav-link {{ \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('service-providers.index')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Service providers
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can(\App\Enums\PermissionEnum::VIEW_ServiceProvider)
                                    <li class="nav-item"><a href="{{route('service-providers.index')}}" class="nav-link pl-4 {{ route('service-providers.index') == request()->fullUrl() ? 'active' : '' }}"><p>Service providers</p></a></li>
                                @endcan
                                @can(\App\Enums\PermissionEnum::VIEW_ServiceProvider)
                                    <li class="nav-item"><a href="{{route('service-types.index')}}" class="nav-link pl-4 {{ route('service-types.index') == request()->fullUrl() ? 'active' : '' }}"><p>Service types</p></a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                @can(\App\Enums\PermissionEnum::VIEW_SafetyReport)
                        <li class="nav-item line">
                            <a href="{{route('safety-reports.index')}}" class="nav-link {{ \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('safety-reports.index')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file"></i>
                                <p>
                                    Safety reports
                                </p>
                            </a>
                        </li>
                    @endcan

                @can(\App\Enums\PermissionEnum::MANAGE_COUNTRIES)
                <li class="nav-item line">
                    <a href="{{route('countries.index')}}" class="nav-link {{ route('countries.index') == request()->fullUrl() ? 'active' : '' }}">
                        <i class="nav-icon fas fa-flag"></i>
                        <p>
                            Countries
                        </p>
                    </a>
                </li>
                @endcan

                @can(\App\Enums\PermissionEnum::VIEW_HOSPITALS)
                <li class="nav-item line">
                    <a href="{{route('hospitals.index')}}" class="nav-link {{ route('hospitals.index') == request()->fullUrl() ? 'active' : '' }}">
                        <i class="nav-icon fas fa-hospital"></i>
                        <p>
                            Hospitals
                        </p>
                    </a>
                </li>
                @endcan
                    @can(\App\Enums\PermissionEnum::VIEW_DOCTORS)
                        <li class="nav-item line">
                            <a href="{{route('doctors.index')}}" class="nav-link {{ route('doctors.index') == request()->fullUrl() ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-md"></i>
                                <p>
                                    Doctors
                                </p>
                            </a>
                        </li>
                    @endcan
                @can(\App\Enums\PermissionEnum::VIEW_PHARMACIES)
                <li class="nav-item line">
                    <a href="{{route('pharmacies.index')}}" class="nav-link {{ route('pharmacies.index') == request()->fullUrl() ? 'active' : '' }}">
                        <i class="nav-icon fas fa-first-aid"></i>
                        <p>
                            Pharmacies
                        </p>
                    </a>
                </li>
                @endcan

                @can(\App\Enums\PermissionEnum::VIEW_DRUGS)
                <li class="nav-item line">
                    <a href="{{route('drugs.index')}}" class="nav-link {{ \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('drugs.index')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-pills"></i>
                        <p>
                            Drugs
                        </p>
                    </a>
                </li>
                @endcan
                    @can(\App\Enums\PermissionEnum::MANAGE_USERS)
                        <li class="nav-item line">
                            <a href="{{route('roles.index')}}" class="nav-link {{ \Illuminate\Support\Str::startsWith(request()->fullUrl(),route('roles.index')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-bookmark"></i>
                                <p>
                                    Roles
                                </p>
                            </a>
                        </li>
                    @endcan



            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
