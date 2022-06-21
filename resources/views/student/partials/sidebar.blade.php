<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #3c4b64">
    <!-- Brand Logo -->
    <a href="/home" class="brand-link">
        <img src="{{asset('adminlte/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo"
        class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">STUDENT HOME</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">{{Auth::user()->email}}</a>
            </div>

        </div>


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Lịch Thi
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('profiles.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Thông tin cá nhân
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('profiles.changePassword') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Đổi mật khẩu
                        </p>
                    </a>
                </li>

{{--                <li class="nav-item menu-close">--}}
{{--                    <a href="#" class="nav-link " style="background-color: #3c4b64">--}}
{{--                        <i class="nav-icon fas fa-book-open"></i>--}}
{{--                        <p>--}}
{{--                            Môn Học--}}
{{--                            <i class="right fas fa-angle-left"></i>--}}
{{--                        </p>--}}
{{--                    </a>--}}
{{--                    <ul class="nav nav-treeview">--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{route('subjects.index')}}" class="nav-link ">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>Quản lí môn học</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{route('subject_classes.index')}}" class="nav-link">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>Gán môn học</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}

{{--                <li class="nav-item menu-close">--}}
{{--                    <a href="#" class="nav-link " style="background-color: #3c4b64">--}}
{{--                        <i class="nav-icon fas fa-address-book"></i>--}}
{{--                        <p>--}}
{{--                            Học Viên--}}
{{--                            <i class="right fas fa-angle-left"></i>--}}
{{--                        </p>--}}
{{--                    </a>--}}
{{--                    <ul class="nav nav-treeview">--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{route('students.index')}}" class="nav-link ">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>Quản lí học viên</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{route('student_classes.index')}}" class="nav-link">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>Gán học viên</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a href="{{ route('examManagements.index') }}" class="nav-link">--}}
{{--                        <i class="nav-icon fas fa-door-open"></i>--}}
{{--                        <p>--}}
{{--                            Đề Thi--}}
{{--                        </p>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item menu-close">--}}
{{--                    <a href="#" class="nav-link " style="background-color: #3c4b64">--}}
{{--                        <i class="nav-icon fas fa-address-book"></i>--}}
{{--                        <p>--}}
{{--                            Ngân hàng câu hỏi--}}
{{--                            <i class="right fas fa-angle-left"></i>--}}
{{--                        </p>--}}
{{--                    </a>--}}
{{--                    <ul class="nav nav-treeview">--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{route('groupQuestions.index')}}" class="nav-link ">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>Nhóm câu hỏi</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{route('questionStores.index')}}" class="nav-link">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>Câu hỏi</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}


{{--                <li class="nav-item menu-close">--}}
{{--                    <a href="#" class="nav-link " style="background-color: #3c4b64">--}}
{{--                        <i class="nav-icon fas fa-address-book"></i>--}}
{{--                        <p>--}}
{{--                            Quản lí nhân viên--}}
{{--                            <i class="right fas fa-angle-left"></i>--}}
{{--                        </p>--}}
{{--                    </a>--}}
{{--                    <ul class="nav nav-treeview">--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{route('users.index')}}" class="nav-link ">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>Tài Khoản</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{route('roles.index')}}" class="nav-link">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>Danh Sách Chức Vụ</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}

{{--                    </ul>--}}
{{--                </li>--}}
                <li class="nav-header"></li>
                        <li class="nav-item">
                            <a class="log-out-btn" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();"> Logout </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
