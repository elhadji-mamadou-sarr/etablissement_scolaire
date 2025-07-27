<x-app-layout>
    <div class="content">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Mes Bulletins</h4>
                        <span class="ml-1">{{ $eleve->user->nom }} {{ $eleve->user->prenom }} - {{ $eleve->classroom->libelle }}</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                @foreach($semestres as $semestre)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">Bulletin {{ $semestre }}</h5>
                                            <p class="card-text">Consulter vos résultats pour le {{ $semestre }}</p>
                                            <a href="{{ route('eleve-parent.show', $semestre) }}" class="btn btn-primary">
                                                Voir le bulletin
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>