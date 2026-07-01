<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-200 leading-tight tracking-tight">
            {{ isset($user) ? 'Edit User' : 'Create User' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100">
                <div class="p-6 text-slate-900 dark:text-slate-100 max-w-xl">
                    <form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" method="POST">
                        @csrf
                        @if(isset($user))
                            @method('PUT')
                        @endif

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Role</label>
                            <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Select Role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ (old('role', isset($user) ? $user->roles->first()?->name : '') == $role->name) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Password {{ isset($user) ? '(Leave blank to keep current)' : '' }}</label>
                            <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" {{ isset($user) ? '' : 'required' }}>
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" {{ isset($user) ? '' : 'required' }}>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg hover:from-blue-700 hover:to-blue-600 shadow-md hover:shadow-lg transition-all duration-200 font-medium">Save</button>
                            <a href="{{ route('users.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
