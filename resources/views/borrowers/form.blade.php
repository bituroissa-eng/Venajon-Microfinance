<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('borrowers.index') }}" class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">
                    {{ isset($borrower) ? 'Edit Borrower' : 'Register Borrower' }}
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    {{ isset($borrower) ? 'Update borrower information' : 'Add a new borrower to the system' }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if($errors->any())
            <div class="alert-error animate-slide-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <div>
                    <p class="font-semibold mb-1">Please fix the following errors:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form action="{{ isset($borrower) ? route('borrowers.update', $borrower) : route('borrowers.store') }}"
                  method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @if(isset($borrower)) @method('PUT') @endif

                {{-- ── Borrower Details ─────────────────────────────────── --}}
                <div class="card p-6 space-y-5 animate-slide-up">
                    <p class="form-section-title">
                        <span class="inline-flex items-center gap-2">
                            <span class="w-5 h-5 rounded-md bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-primary-600 dark:text-primary-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                            </span>
                            Borrower Details
                        </span>
                    </p>

                    <div class="form-row">
                        <div class="field">
                            <label for="first_name">First Name <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $borrower->first_name ?? '') }}" placeholder="e.g. Kelvin" required>
                            @error('first_name')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $borrower->middle_name ?? '') }}" placeholder="Optional">
                        </div>
                        <div class="field">
                            <label for="last_name">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $borrower->last_name ?? '') }}" placeholder="e.g. Joseph" required>
                            @error('last_name')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="phone">Phone Number <span class="text-red-500">*</span></label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $borrower->phone ?? '') }}" placeholder="+255 700 000 000" required>
                            @error('phone')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $borrower->email ?? '') }}" placeholder="kelvin@example.com">
                            @error('email')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="nida_no">NIDA Number <span class="text-red-500">*</span></label>
                            <input type="text" id="nida_no" name="nida_no" value="{{ old('nida_no', $borrower->nida_no ?? '') }}" placeholder="19XXXXXXXXXXXXXXXXX" required>
                            @error('nida_no')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Photo upload --}}
                    <div class="field">
                        <label>Borrower Photo</label>
                        <div class="mt-1 flex items-center gap-4">
                            @if(isset($borrower) && $borrower->picture_path)
                                <img src="{{ asset('storage/'.$borrower->picture_path) }}" class="w-16 h-16 rounded-xl object-cover ring-2 ring-slate-200 dark:ring-slate-600">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="picture" accept="image/*"
                                    class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 dark:file:bg-primary-900/30 dark:file:text-primary-300 hover:file:bg-primary-100 transition-all">
                                @if(isset($borrower) && $borrower->picture_path)
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Current photo is set. Upload a new file to replace it.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Sponsor Details ───────────────────────────────────── --}}
                <div class="card p-6 space-y-5 animate-slide-up">
                    <p class="form-section-title">
                        <span class="inline-flex items-center gap-2">
                            <span class="w-5 h-5 rounded-md bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-violet-600 dark:text-violet-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                            </span>
                            Sponsor / Guarantor Details
                        </span>
                    </p>

                    <div class="form-row">
                        <div class="field">
                            <label for="sponsor_name">Sponsor Full Name <span class="text-red-500">*</span></label>
                            <input type="text" id="sponsor_name" name="sponsor_name" value="{{ old('sponsor_name', $borrower->sponsor_name ?? '') }}" placeholder="Full name" required>
                            @error('sponsor_name')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="sponsor_phone">Sponsor Phone <span class="text-red-500">*</span></label>
                            <input type="tel" id="sponsor_phone" name="sponsor_phone" value="{{ old('sponsor_phone', $borrower->sponsor_phone ?? '') }}" placeholder="+255 700 000 000" required>
                            @error('sponsor_phone')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field sm:col-span-2">
                            <label for="sponsor_nida">Sponsor NIDA Number <span class="text-red-500">*</span></label>
                            <input type="text" id="sponsor_nida" name="sponsor_nida" value="{{ old('sponsor_nida', $borrower->sponsor_nida ?? '') }}" placeholder="19XXXXXXXXXXXXXXXXX" required>
                            @error('sponsor_nida')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="field">
                        <label>Sponsor Photo</label>
                        <div class="mt-1 flex items-center gap-4">
                            @if(isset($borrower) && $borrower->sponsor_picture_path)
                                <img src="{{ asset('storage/'.$borrower->sponsor_picture_path) }}" class="w-16 h-16 rounded-xl object-cover ring-2 ring-slate-200 dark:ring-slate-600">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="sponsor_picture" accept="image/*"
                                    class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-violet-50 file:text-violet-700 dark:file:bg-violet-900/30 dark:file:text-violet-300 hover:file:bg-violet-100 transition-all">
                                @if(isset($borrower) && $borrower->sponsor_picture_path)
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Current photo is set. Upload a new file to replace it.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Actions ───────────────────────────────────────────── --}}
                <div class="flex items-center gap-3">
                    <button type="submit" class="btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ isset($borrower) ? 'Update Borrower' : 'Register Borrower' }}
                    </button>
                    <a href="{{ route('borrowers.index') }}" class="btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
