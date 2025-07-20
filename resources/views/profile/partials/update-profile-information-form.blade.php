<section>
    <header>
        <h2 class="h5 text-dark">Informations du profil</h2>
        <p class="text-muted small">Mettez à jour les informations de votre compte et votre adresse email.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name">
            <x-input-error class="text-danger mt-1" :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
            <x-input-error class="text-danger mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="small text-dark">
                        Votre adresse email n'est pas vérifiée.
                        <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline">
                            Cliquez ici pour renvoyer l'email de vérification.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success small mt-2">Un nouveau lien de vérification a été envoyé à votre adresse email.</div>
                    @endif
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        @if (session('status') === 'profile-updated')
            <span class="text-success small ms-3">Enregistré.</span>
        @endif
    </form>
</section>