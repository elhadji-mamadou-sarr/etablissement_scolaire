<section>
    <header>
        <h2 class="h5 text-dark">Mettre à jour le mot de passe</h2>
        <p class="text-muted small">Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester sécurisé.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">Mot de passe actuel</label>
            <input type="password" class="form-control" id="update_password_current_password" name="current_password" autocomplete="current-password">
            <x-input-error class="text-danger mt-1" :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label">Nouveau mot de passe</label>
            <input type="password" class="form-control" id="update_password_password" name="password" autocomplete="new-password">
            <x-input-error class="text-danger mt-1" :messages="$errors->updatePassword->get('password')" />
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input type="password" class="form-control" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password">
            <x-input-error class="text-danger mt-1" :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        @if (session('status') === 'password-updated')
            <span class="text-success small ms-3">Enregistré.</span>
        @endif
    </form>
</section>