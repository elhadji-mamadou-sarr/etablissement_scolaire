<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold fs-4 text-dark">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="mb-4 p-4 bg-white shadow rounded">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="mb-4 p-4 bg-white shadow rounded">
                @include('profile.partials.update-password-form')
            </div>

            {{-- <div class="mb-4 p-4 bg-white shadow rounded">
                @include('profile.partials.delete-user-form')
            </div> --}}
        </div>
    </div>
</x-app-layout>