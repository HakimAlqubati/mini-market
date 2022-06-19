<nav class="navbar navbar-default navbar-fixed-top navbar-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button class="hamburger btn-link">
                <span class="hamburger-inner"></span>
            </button>
            @section('breadcrumbs')
                <ol class="breadcrumb hidden-xs">
                    @php
                        $segments = array_filter(explode('/', str_replace(route('voyager.dashboard'), '', Request::url())));
                        $url = route('voyager.dashboard');
                    @endphp
                    @if (count($segments) == 0)
                        <li class="active"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}
                        </li>
                    @else
                        <li class="active">
                            <a><i class="voyager-boat"></i>
                                {{ __('voyager::generic.dashboard') }}</a>
                        </li>
                        @foreach ($segments as $segment)
                            @php
                                $url .= '/' . $segment;
                            @endphp
                            @if ($loop->last)
                                <li>{{ ucfirst(urldecode($segment)) }}</li>
                            @else
                                <li>
                                    <a>{{ ucfirst(urldecode($segment)) }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ol>
            @show
        </div>


        <ul class="nav navbar-nav @if (__('voyager::generic.is_rtl') == 'true') navbar-left @else navbar-right @endif">
            <li class="dropdown profile">
                <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button"
                    aria-expanded="false"><img
                        src="https://png.pngtree.com/png-clipart/20190705/original/pngtree-vvector-notification-icon-png-image_4232478.jpg"
                        class="profile-img">

                    <span style="font-weight: bold">
                        <?php
                        
                        $notifications = auth()
                            ->user()
                            ->unreadnotifications()
                            ->get();
                        echo count($notifications);
                        ?>
                    </span>

                    <span class="caret"></span></a>
                <ul class="dropdown-menu dropdown-menu-animated">
                    <li class="profile-img">

                        <div class="profile-body">
                            <h5> {{ __('new_orders_notifications_title') }} </h5>
                        </div>
                    </li>
                    <li class="divider"></li>
                    <?php
                    
                  foreach ($notifications as   $value) { 
                 ?>
                    <li style="text-align: left">
                        <h4>
                            <a href=" <?php echo url('/'); ?>/admin/orders/<?php echo $value->data['order_id']; ?>">

                                {{ $value->data['title'] }}
                            </a>
                        </h4>
                        <p>
                            {{ $value->data['body'] }}
                        </p>
                    </li>
                    <?php 
                 } ?>
                </ul>
            </li>
            <li class="dropdown profile">
                <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button"
                    aria-expanded="false"><img src="{{ $user_avatar }}" class="profile-img"> <span
                        class="caret"></span></a>
                <ul class="dropdown-menu dropdown-menu-animated">
                    <li class="profile-img">
                        <img src="{{ $user_avatar }}" class="profile-img">
                        <div class="profile-body">
                            <h5>{{ Auth::user()->name }}</h5>
                            <h6>{{ Auth::user()->email }}</h6>
                        </div>
                    </li>
                    <li class="divider"></li>
                    <?php $nav_items = config('voyager.dashboard.navbar_items'); ?>
                    @if (is_array($nav_items) && !empty($nav_items))
                        @foreach ($nav_items as $name => $item)
                            <li {!! isset($item['classes']) && !empty($item['classes']) ? 'class="' . $item['classes'] . '"' : '' !!}>
                                @if (isset($item['route']) && $item['route'] == 'voyager.logout')
                                    <form action="{{ route('voyager.logout') }}" method="POST">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger btn-block">
                                            @if (isset($item['icon_class']) && !empty($item['icon_class']))
                                                <i class="{!! $item['icon_class'] !!}"></i>
                                            @endif
                                            {{ __($name) }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ isset($item['route']) && Route::has($item['route']) ? route($item['route']) : (isset($item['route']) ? 'https://www.cloudsnap.tech/spices/sys/dashboard' : '#') }}"
                                        {!! isset($item['target_blank']) && $item['target_blank'] ? 'target="_blank"' : '' !!}>
                                        @if (isset($item['icon_class']) && !empty($item['icon_class']))
                                            <i class="{!! $item['icon_class'] !!}"></i>
                                        @endif
                                        {{ __($name) }}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </li>

            <li style="padding-top: 20px;">


                @if (Config::get('app.locale') == 'ar')
                    <form action="{{ url('/') . '/set-english-locale' }}" method="POST">
                        @csrf
                        <input type="submit" value="English">
                    </form>
                @endif

                @if (Config::get('app.locale') == 'en')
                    <form action="{{ url('/') . '/set-arabic-locale' }}" method="POST">
                        @csrf
                        <input type="submit" value="عربي">
                    </form>
                @endif
            </li>

            {{-- For language switcher --}}
            {{-- <li class="dropdown profile">
                <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button"
                    aria-expanded="false"><img src="{{ $user_avatar }}" class="profile-img"> <span
                        class="caret"></span></a>
                <ul class="dropdown-menu dropdown-menu-animated">

                    <li><a href="#">EN</a></li>
                    <li class="divider"></li>
                    <li><a href="#">AR</a></li>


                </ul>
            </li> --}}
        </ul>
    </div>
</nav>
