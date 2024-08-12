<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <!-- Logo or Home link -->
        <div class="text-white text-lg font-semibold">
            <a href="/" class="hover:text-white transition duration-200">Home</a>
        </div>

        <!-- Navigation links -->
        <div class="flex space-x-4 p-5">
            <a href="{{ route('clubinfo') }}" class="text-white hover:text-white transition duration-200">Clubinfo</a>
            <a href="{{ route('calendar') }}" class="text-white hover:text-white transition duration-200">Calendar</a>
            <a href="{{ route('teams') }}" class="text-white hover:text-white transition duration-200">Teams</a>
            <a href="{{ route('sponsors') }}" class="text-white hover:text-white transition duration-200">Sponsors</a>
            <a href="{{ route('contact') }}" class="text-white hover:text-white transition duration-200">Contact</a>
            <a href="{{ route('fanshop') }}" class="text-white hover:text-white transition duration-200">Fanshop</a>
        </div>

        <!-- Authentication links -->
        <div class="flex space-x-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-gray-300 hover:text-white transition duration-200">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition duration-200">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-gray-300 hover:text-white transition duration-200">
                            Register
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>