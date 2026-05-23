<div class="header">
    <div class="main-header">

        <div class="header-left active">
            <a href="{{ route('ecard.dashboard') }}" class="logo logo-normal">
                @if($settings && $settings->ecardseva_logo)
                    <img src="{{ asset('storage/'.$settings->ecardseva_logo) }}" alt="Logo">
                @elseif($settings && $settings->logo)
                    <img src="{{ asset('storage/'.$settings->logo) }}" alt="Logo">
                @else
                    <span class="text-white fw-bold fs-4">E-Card</span>
                @endif
            </a>
            <a href="{{ route('ecard.dashboard') }}" class="logo logo-white">
                @if($settings && $settings->ecardseva_logo)
                    <img src="{{ asset('storage/'.$settings->ecardseva_logo) }}" alt="Logo">
                @elseif($settings && $settings->logo)
                    <img src="{{ asset('storage/'.$settings->logo) }}" alt="Logo">
                @else
                    <span class="text-white fw-bold fs-4">E-Card</span>
                @endif
            </a>
            <a href="{{ route('ecard.dashboard') }}" class="logo-small">
                 @if($settings && $settings->ecardseva_favicon)
                    <img src="{{ asset('storage/'.$settings->ecardseva_favicon) }}" alt="Logo">
                @elseif($settings && $settings->favicon)
                    <img src="{{ asset('storage/'.$settings->favicon) }}" alt="Logo">
                @else
                    <span class="text-white fw-bold fs-4">E</span>
                @endif
            </a>
            <a href="{{ route('ecard.dashboard') }}" class="logo-small-white">
                @if($settings && $settings->ecardseva_favicon)
                    <img src="{{ asset('storage/'.$settings->ecardseva_favicon) }}" alt="Logo">
                @elseif($settings && $settings->favicon)
                    <img src="{{ asset('storage/'.$settings->favicon) }}" alt="Logo">
                @else
                    <span class="text-white fw-bold fs-4">E</span>
                @endif
            </a>
        </div>

        <a id="mobile_btn" class="mobile_btn" href="#sidebar">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>

        <ul class="nav user-menu">

            <!-- Search -->
            <li class="nav-item nav-searchinputs">
                <div class="top-nav-search">
                    <a href="javascript:void(0);" class="responsive-search">
                        <i class="ti ti-search"></i>
                    </a>
                    <form action="#" class="dropdown">
                        <div class="searchinputs input-group dropdown-toggle" id="dropdownMenuClickable" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <input type="text" placeholder="Search">
                            <div class="search-addon">
                                <span><i class="ti ti-search"></i></span>
                            </div>
                        </div>
                    </form>
                </div>
            </li>
            <!-- /Search -->

            <!-- Select Store -->
            <li class="nav-item dropdown has-arrow main-drop select-store-dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle nav-link select-store" data-bs-toggle="dropdown">
                    <span class="user-info">
                        <span class="user-letter">
                            <img src="{{ asset('backend_assets/assets/img/icons/company-icon-14.svg') }}" alt="Store Logo" class="img-fluid">
                        </span>
                        <span class="user-detail">
                            <span class="user-name">E-Card Seva</span>
                        </span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="{{ asset('backend_assets/assets/img/icons/company-icon-14.svg') }}" alt="Store Logo" class="img-fluid"> E-Card Seva
                    </a>
                </div>
            </li>
            <!-- /Select Store -->

            <li class="nav-item nav-item-box">
                <a href="javascript:void(0);" id="btnFullscreen">
                    <i class="ti ti-maximize"></i>
                </a>
            </li>

            <!-- Notifications -->
            <li class="nav-item dropdown nav-item-box">
                <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                    <i class="ti ti-bell"></i>
                    <!-- <span class="badge rounded-pill">1</span> -->
                </a>
                <div class="dropdown-menu notifications">
                    <div class="topnav-dropdown-header">
                        <h5 class="notification-title">Notifications</h5>
                        <a href="javascript:void(0)" class="clear-noti">Mark all as read</a>
                    </div>
                    <div class="noti-content">
                        <ul class="notification-list">
                            <!-- Placeholder for notifications -->
                            <li class="notification-message">
                                <a href="javascript:void(0);">
                                    <div class="media d-flex">
                                        <div class="flex-grow-1">
                                            <p class="noti-details"><span class="noti-title">Welcome</span> to E-Card Dashboard</p>
                                            <p class="noti-time">Now</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="topnav-dropdown-footer d-flex align-items-center gap-3">
                        <a href="javascript:void(0);" class="btn btn-primary btn-md w-100">View all</a>
                    </div>
                </div>
            </li>
            <!-- /Notifications -->

            <li class="nav-item nav-item-box">
                <a href="{{ route('ecard.profile.index') }}"><i class="ti ti-settings"></i></a>
            </li>

            <!-- User Profile -->
            @php
                $currentUser = auth('ecard')->user() ?? auth()->user();
                $avatarUrl = null;

                if ($currentUser) {
                    $rawProfileImage = $currentUser->profile_image ?? null;
                    if ($rawProfileImage) {
                        $rawProfileImage = preg_replace('#^/?storage/#', '', (string) $rawProfileImage);
                        $rawProfileImage = ltrim((string) $rawProfileImage, '/');

                        if ($rawProfileImage && \Illuminate\Support\Facades\Storage::disk('public')->exists($rawProfileImage)) {
                            $avatarUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($rawProfileImage);
                        } elseif ($rawProfileImage) {
                            $avatarUrl = asset($rawProfileImage);
                        }
                    }

                    if (! $avatarUrl && ($currentUser->id ?? null)) {
                        $prefix = 'avatars/ecard_'.$currentUser->id.'.';
                        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                            $candidate = $prefix.$ext;
                            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($candidate)) {
                                $avatarUrl = asset('storage/'.$candidate);
                                break;
                            }
                        }
                    }
                }

                $displayName = trim((string) (optional($currentUser)->full_name ?? ((optional($currentUser)->first_name ?? '').' '.(optional($currentUser)->last_name ?? ''))));
                if ($displayName === '') {
                    $displayName = (string) (optional($currentUser)->name ?? 'User');
                }
                $initial = strtoupper(substr((string) (optional($currentUser)->first_name ?? optional($currentUser)->name ?? 'U'), 0, 1));
            @endphp
            <li class="nav-item dropdown has-arrow main-drop profile-nav">
                <a href="javascript:void(0);" class="nav-link userset" data-bs-toggle="dropdown">
                    <span class="user-info p-0">
                        <span class="user-letter">
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="Img" class="img-fluid">
                            @else
                                <span class="avatar-text">{{ $initial }}</span>
                            @endif
                        </span>
                    </span>
                </a>
                <div class="dropdown-menu menu-drop-user">
                    <div class="profilename">
                        <div class="profileset">
                            <span class="user-img">
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="Img">
                                @else
                                    <span class="avatar-text avatar-text-lg">{{ $initial }}</span>
                                @endif
                                <span class="status online"></span>
                            </span>
                            <div class="profilesets">
                                <h6>{{ $displayName }}</h6>
                                <h5>{{ optional($currentUser)->status ?? 'Member' }}</h5>
                            </div>
                        </div>
                        <hr class="m-0">
                        <a class="dropdown-item" href="{{ route('ecard.profile.index') }}"> <i class="me-2"  data-feather="user"></i> My Profile</a>
                        <hr class="m-0">
                        <a href="javascript:void(0);" onclick="confirmLogout(event)" class="dropdown-item logout pb-0">
                            <img src="{{ asset('backend_assets/assets/img/icons/log-out.svg') }}" class="me-2" alt="img">Logout
                        </a>
                        <form id="logout-form" action="{{ route('ecard.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </li>
            <!-- /User Profile -->
        </ul>
    </div>
</div>

<script>
    function confirmLogout(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to logout from the application?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Logout!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        })
    }
</script>
