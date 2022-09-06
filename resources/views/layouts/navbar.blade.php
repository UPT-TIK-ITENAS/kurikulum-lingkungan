<div class="header-navbar-shadow"></div>
<nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top ">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">
                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                </div>
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link"
                            href="javascript:void(0);" data-toggle="dropdown">
                            <div class="user-nav d-sm-flex d-none"><span class="user-name">
                                    @if(isset($data['nmdosMSDOS']))
                                    {{ $data['nmdosMSDOS'] }}{{$data['gelarMSDOS']}}
                                    @else
                                    {{$data['name']}}
                                    @endif
                                </span><span class="user-status text-muted">Available</span></div><span><img
                                    class="round" src="{{ asset('images/profile/Akun.png') }}" alt="avatar" height="40"
                                    width="40"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pb-0"><a class="dropdown-item" href=""><i
                                    class="bx bx-user mr-50"></i> Edit Profile</a><a class="dropdown-item"
                                href="app-email.html"><i class="bx bx-envelope mr-50"></i> My
                                Inbox</a><a class="dropdown-item" href="app-todo.html"><i
                                    class="bx bx-check-square mr-50"></i> Task</a><a class="dropdown-item"
                                href="app-chat.html"><i class="bx bx-message mr-50"></i> Chats</a>
                            <div class="dropdown-divider mb-0"></div><a class="dropdown-item"
                                href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"><i class="bx bx-power-off mr-50"></i>
                                Logout

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>