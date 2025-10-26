<nav class="bg-white border-b border-gray-200">
    <div class="container mx-auto flex flex-wrap items-center justify-between p-4">
        <a href="{{ route('home') }}" class="flex items-center space-x-2">
            <img src="{{ asset('images/logo.png') }}" class="h-8 w-8" alt="Logo">
            <span class="text-xl font-bold text-gray-900">{{ config('app.name', 'My Blog') }}</span>
        </a>

        <button data-collapse-toggle="navbar-main" type="button"
            class="inline-flex items-center p-2 w-10 h-10 justify-center text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none"
            aria-controls="navbar-main" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>

        <div class="hidden w-full md:flex md:w-auto" id="navbar-main">
            <ul class="flex flex-col md:flex-row md:space-x-8 mt-4 md:mt-0 text-sm font-medium">
                <li><a href="{{ route('home') }}"
                        class="block py-2 px-3 rounded {{ request()->routeIs('home') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">Home</a>
                </li>
                <li><a href="{{ route('posts.index') }}"
                        class="block py-2 px-3 rounded {{ request()->routeIs('posts.*') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">Posts</a>
                </li>
            </ul>

            <form action="{{ route('posts.index') }}" method="GET" class="flex items-center mt-3 md:mt-0 md:ml-6">
                <input type="text" name="q" placeholder="Search..." value="{{ request('q') }}"
                    class="border border-gray-300 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2.5 rounded-r-lg">Search</button>
            </form>

            <div class="mt-3 md:mt-0 md:ml-6 flex items-center space-x-4">
                @guest
                    <a href="{{ route('auth.login.form') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                    <a href="{{ route('auth.register.form') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Register</a>
                @else
                    <button id="user-menu-button" data-dropdown-toggle="userDropdown"
                        class="flex items-center text-sm rounded-full focus:ring-4 focus:ring-gray-300" type="button">
                        <span class="sr-only">Open user menu</span>
                        <img src="{{ Auth::user()->profile_photo_url ?? asset('images/default-avatar.png') }}"
                            alt="user photo" class="w-8 h-8 rounded-full">
                    </button>

                    <div id="userDropdown"
                        class="hidden z-50 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow">
                        <div class="px-4 py-3">
                            <span class="block text-sm text-gray-900">{{ Auth::user()->name }}</span>
                            <span class="block text-sm text-gray-500 truncate">{{ Auth::user()->email }}</span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li><a href="{{ route('auth.profile.show') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a></li>
                            <li>
                                <form action="{{ route('auth.logout.current') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>
