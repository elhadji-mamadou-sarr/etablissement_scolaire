<section class="mt-5">
    <header>
        <h2 class="h5 text-danger">{{ __('Delete Account') }}</h2>
        <p class="text-muted small">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}</p>
    </header>

    <button class="btn btn-danger mt-3" x-data="{}" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-4">
            @csrf
            @method('delete')

            <h5 class="text-danger">{{ __('Are you sure you want to delete your account?') }}</h5>
            <p class="text-muted small">{{ __('Please enter your password to confirm you would like to permanently delete your account.') }}</p>

            <div class="mb-3">
                <label for="password" class="form-label sr-only">{{ __('Password') }}</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Password') }}">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="text-danger mt-1" />
            </div>

            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-2" x-on:click="$dispatch('close')">{{ __('Cancel') }}</button>
                <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
            </div>
        </form>
    </x-modal>
</section>