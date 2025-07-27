<x-app-layout>
    <div class="content">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Mon Bulletin - {{ $semestre }}</h4>
                        <span class="ml-1">{{ $eleve->user->nom }} {{ $eleve->user->prenom }} - {{ $eleve->classroom->libelle }}</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <a href="{{ route('eleve-parent.bulletins') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Retour
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @if($bulletin)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Détail des notes</h5>
                                <div class="card-tools">
                                    <a href="{{ route('eleve-parent.bulletins.download', $semestre) }}" class="btn btn-rounded btn-info">
                                        <span class="btn-icon-left text-warning">
                                            <i class="fa fa-download color-warning"></i>
                                        </span>
                                        Télécharger
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Matière</th>
                                                <th>Type</th>
                                                <th>Note</th>
                                                <th>Coeff.</th>
                                                <th>Crédit</th>
                                                <th>Pondération</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bulletin['matieres'] as $matiere)
                                                @foreach($matiere['notes'] as $note)
                                                <tr>
                                                    @if($loop->first)
                                                    <td rowspan="{{ count($matiere['notes']) }}" class="align-middle">
                                                        {{ $matiere['matiere'] }}
                                                        <br>
                                                        <small class="text-muted">Moy: {{ $matiere['moyenne'] }}</small>
                                                    </td>
                                                    @endif
                                                    <td>{{ $note['type'] }}</td>
                                                    <td class="{{ $note['note'] < 10 ? 'text-danger' : 'text-success' }}">
                                                        <strong>{{ $note['note'] }}</strong>/20
                                                    </td>
                                                    <td>{{ $note['coefficient'] }}</td>
                                                    @if($loop->first)
                                                    <td rowspan="{{ count($matiere['notes']) }}" class="align-middle">
                                                        {{ $matiere['credit'] }}
                                                    </td>
                                                    <td rowspan="{{ count($matiere['notes']) }}" class="align-middle">
                                                        {{ $matiere['ponderation'] }}
                                                    </td>
                                                    @endif
                                                </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title">Statistiques</h5>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Moyenne Générale
                                                        <span class="badge bg-primary rounded-pill">{{ $bulletin['statistiques']['moyenne_generale'] }}</span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Mention
                                                        <span class="badge text-white bg-{{ $bulletin['statistiques']['mention'] === 'Insuffisant' ? 'danger' : 'primary' }} rounded-pill">
                                                            {{ $bulletin['statistiques']['mention'] }}
                                                        </span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Classement
                                                        <span class="badge bg-info rounded-pill">
                                                            {{ $bulletin['statistiques']['classement'] ?? 'N/A' }}
                                                        </span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Total Crédits
                                                        <span class="badge bg-secondary rounded-pill">
                                                            {{ $bulletin['statistiques']['total_credits'] }}
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title">Informations</h5>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        <strong>Date d'édition:</strong> {{ $bulletin['date_edition'] }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <strong>Classe:</strong> {{ $eleve->classroom->libelle }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <strong>Semestre:</strong> {{ $semestre }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i> Aucune note disponible pour ce semestre.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>