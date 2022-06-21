<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #3c4b64">
    <!-- Brand Logo -->
    <a href="/admin" class="brand-link">
        <img src="{{asset('adminlte/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo"
        class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">TRANG CHỦ</span>
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

                @if(\Illuminate\Support\Facades\Auth::user()->utype == 'MASTER' ||
                    \Illuminate\Support\Facades\Auth::user()->utype == 'ADM' ||
                    \Illuminate\Support\Facades\Auth::user()->utype == 'COURSE' ||
                    \Illuminate\Support\Facades\Auth::user()->utype == 'FACULTY' ||
                    \Illuminate\Support\Facades\Auth::user()->utype == 'EDU')
                <li class="nav-item">
                    <a href="{{ route('faculties.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Khoa
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('classes.listFaculty') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Lớp học
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('students.listFaculty') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Sinh Viên
                        </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('lecturers.listFaculty') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Giảng Viên
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('examManagements.listFaculty') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Đề Thi
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('questionStores.listFaculty') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Ngân Hàng Câu Hỏi
                        </p>
                    </a>
                </li>
                @endif

                @if(\Illuminate\Support\Facades\Auth::user()->utype == 'LECTURERS')
                <li class="nav-item">
                    <a href="{{ route('supervisor.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Lịch coi thi
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('lecturers.profile') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Thông tin cá nhân
                        </p>
                    </a>
                </li>
                @endif

                @if(\Illuminate\Support\Facades\Auth::user()->utype == 'MASTER')
                <li class="nav-item">
                    <a href="{{ route('accounts.listFaculty') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Quản lý tài khoản
                        </p>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('password.changePassword') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Đổi mật khẩu
                        </p>
                    </a>
                </li>

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
