@section('title', __('Login') . ' | Zdrava')
@section('description', 'Вход на сайт Zdrava')
<div class="px-6 py-12 md:px-12 text-gray-800 text-center lg:text-left">
    <div class="container mx-auto xl:px-32">
        <div class="grid lg:grid-cols-2 gap-12 items-start">
            <div class="mt-12 lg:mt-0">
                <h1 class="text-5xl md:text-6xl xl:text-7xl font-bold tracking-tight mb-12 dark:text-white">
                    {{ __('Already with us?') }}<br />
                    <span class="text-blue-600 dark:text-gray-400">{{ __('Welcome back!') }}</span>
                </h1>
                <p class="text-gray-600 dark:text-orange-600">
                    {{ __('We\'re miss you') }}
                </p>
            </div>
            <div class="mb-12 lg:mb-0">
                <div class="block rounded-lg shadow-lg bg-white px-6 py-12 md:px-12">
                    <form wire:submit.prevent="login">
                        <input wire:model="email" value="{{ old('email') }}" type="email" class="form-control block w-full px-3 py-1.5 mb-6 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" placeholder="{{ __('Email address') }}" required/>
                        <input wire:model="password" type="password" autocomplete="password" class="form-control block w-full px-3 py-1.5 mb-6 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" placeholder="{{ __('Password') }}" required/>
                        <div class="form-check flex justify-center mb-6">
                            <input wire:model="rememberMe" value="true" class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" checked>
                            <label class="form-check-label inline-block text-gray-800">
                                {{ __('Remember me') }}
                            </label>
                        </div>
                        <button type="submit" data-mdb-ripple="true" data-mdb-ripple-color="light" class="inline-block px-6 py-2.5 mb-6 w-full bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">
                            {{ __('Login') }}
                        </button>
                        <a href="{{ route('auth.password.request') }}" class="mb-6">{{ __('Forgot your password?') }}</a>
                        {{-- Messages --}}
                        @if (session()->get('success'))
                            @livewire('toast.success')
                        @endif
                        {{-- Validation Errors --}}
                        @if ($this->getErrorBag()->any())
                            @livewire('toast.errors')
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
