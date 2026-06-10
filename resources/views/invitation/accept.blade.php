<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Accept Invitation — {{ config('app.name') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-12">

    <div class="w-full max-w-md">

        {{-- Logo / brand --}}
        <div class="mb-8 text-center">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600">
                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                </svg>
            </div>
            <span class="text-xl font-bold tracking-tight text-gray-900">{{ config('app.name') }}</span>
        </div>

        @if ($expired)
            {{-- Expired / invalid token --}}
            <div class="rounded-2xl bg-white p-8 shadow-sm text-center">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <h1 class="text-lg font-bold text-gray-900">Invitation Expired</h1>
                <p class="mt-2 text-sm text-gray-500">
                    This invitation link has expired or is invalid.<br>
                    Please contact your administrator for a new invitation.
                </p>
                <a
                    href="{{ route('login') }}"
                    class="mt-6 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-700"
                >
                    ← Return to login
                </a>
            </div>

        @else
            {{-- Accept invitation form --}}
            <div class="rounded-2xl bg-white p-8 shadow-sm">
                <h1 class="text-xl font-bold text-gray-900">Activate your account</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Set your name and password to get started.
                </p>

                {{-- Invited email (readonly) --}}
                <div class="mt-5 rounded-lg bg-gray-50 px-4 py-3 text-sm text-gray-700">
                    <span class="font-medium text-gray-500">Email:</span>
                    {{ $user->email }}
                </div>

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                        <ul class="space-y-0.5 text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('invitation.accept.store', request()->route('token')) }}"
                    class="mt-5 space-y-5"
                >
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Full Name
                        </label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            autofocus
                            value="{{ old('name') }}"
                            placeholder="Jane Doe"
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm placeholder:text-gray-400
                                   focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20
                                   @error('name') border-red-400 @enderror"
                        />
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm
                                   focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20
                                   @error('password') border-red-400 @enderror"
                        />
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirm Password
                        </label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm
                                   focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        />
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700"
                    >
                        Activate My Account
                    </button>
                </form>
            </div>
        @endif

    </div>

</body>
</html>
