<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold text-dark">
            {{ __('Tableau de bord - Enseignant') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <!-- Carte d'accueil -->
        <div class="alert alert-info d-flex justify-content-between align-items-center shadow-sm">
            <div>
                <h5 class="mb-0">Bienvenue, {{ auth()->user()->prenom }} {{ auth()->user()->nom }}</h5>
                <small>Vous êtes connecté en tant qu’enseignant.</small>
            </div>
            <i class="fas fa-chalkboard-teacher fa-2x text-primary"></i>
        </div>

        <!-- Cartes statistiques -->
        <div class="row g-4 mt-3 mb-5">
            <div class="col-md-6">
                <div class="card shadow text-white bg-success">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Mes classes</h5>
                            <h2 class="fw-bold">{{ $classrooms->count() }}</h2>
                        </div>
                        <i class="fas fa-school fa-2x"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow text-white bg-primary">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Mes matières</h5>
                            <h2 class="fw-bold">{{ $cours->count() }}</h2>
                        </div>
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détail : listes -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-light fw-bold">Mes classes</div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse($classrooms as $class)
                                <li class="list-group-item"><i class="fas fa-chevron-right me-2 text-muted"></i>{{ $class }}</li>
                            @empty
                                <li class="list-group-item text-muted">Aucune classe assignée</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-light fw-bold">Mes matières</div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse($cours as $cour)
                                <li class="list-group-item"><i class="fas fa-chevron-right me-2 text-muted"></i>{{ $cour }}</li>
                            @empty
                                <li class="list-group-item text-muted">Aucune matière assignée</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
