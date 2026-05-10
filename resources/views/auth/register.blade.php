<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Role Selection Cards --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-3">I am joining as...</label>
            <div class="grid grid-cols-3 gap-3" x-data="{ selected: '{{ old('role', 'customer') }}' }">

                {{-- Customer --}}
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="customer" class="sr-only"
                           x-on:change="selected = 'customer'"
                           {{ old('role', 'customer') === 'customer' ? 'checked' : '' }}>
                    <div class="border-2 rounded-xl p-3 text-center transition-all duration-200"
                         :class="selected === 'customer'
                            ? 'border-orange-500 bg-orange-50 text-orange-700'
                            : 'border-gray-200 bg-white text-gray-500 hover:border-orange-300'">
                        <div class="text-2xl mb-1">🛍️</div>
                        <div class="text-xs font-semibold leading-tight">Customer</div>
                    </div>
                </label>

                {{-- Product Manager --}}
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="product_manager" class="sr-only"
                           x-on:change="selected = 'product_manager'"
                           {{ old('role') === 'product_manager' ? 'checked' : '' }}>
                    <div class="border-2 rounded-xl p-3 text-center transition-all duration-200"
                         :class="selected === 'product_manager'
                            ? 'border-orange-500 bg-orange-50 text-orange-700'
                            : 'border-gray-200 bg-white text-gray-500 hover:border-orange-300'">
                        <div class="text-2xl mb-1">📦</div>
                        <div class="text-xs font-semibold leading-tight">Product Mgr</div>
                    </div>
                </label>

                {{-- Admin --}}
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="admin" class="sr-only"
                           x-on:change="selected = 'admin'"
                           {{ old('role') === 'admin' ? 'checked' : '' }}>
                    <div class="border-2 rounded-xl p-3 text-center transition-all duration-200"
                         :class="selected === 'admin'
                            ? 'border-orange-500 bg-orange-50 text-orange-700'
                            : 'border-gray-200 bg-white text-gray-500 hover:border-orange-300'">
                        <div class="text-2xl mb-1">⚙️</div>
                        <div class="text-xs font-semibold leading-tight">Admin</div>
                    </div>
                </label>

            </div>
            @error('role')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
