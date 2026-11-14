<x-guest-layout>
    {{-- En-tête Espace Lecteurs & Abonnés PRO --}}
    <div class="mb-6 text-center border-b border-gray-100 pb-4">
        <span class="inline-block bg-cargo-yellow text-cargo-dark text-[10px] font-heading font-black uppercase tracking-wider px-2 py-0.5 mb-2 rounded">
            Subscriber Portal
        </span>
        <h1 class="text-xl font-heading font-black text-cargo-dark">CARGO PRO Reader Access</h1>
        <p class="text-xs text-gray-500 mt-1">Sign in to read unlocked intelligence briefings and manage your subscription.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Subscriber Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full text-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="subscriber@company.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full text-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-cargo-red shadow-sm focus:ring-cargo-red" name="remember">
                <span class="ms-2 text-xs text-gray-600 font-medium">{{ __('Remember me on this browser') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-5 pt-3 border-t border-gray-100">
            @if (Route::has('password.request'))
                <a class="text-xs text-gray-500 hover:text-gray-900 underline" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <button type="submit" class="bg-cargo-red hover:bg-cargo-redDark text-white font-heading font-bold uppercase text-xs tracking-wider px-5 py-2.5 rounded transition">
                Sign In to CARGO PRO
            </button>
        </div>
    </form>

</x-guest-layout>
