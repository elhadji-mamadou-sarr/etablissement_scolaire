<x-app-layout>


<div class="content">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Bulletin de {{ $eleve->user->nom }} {{ $eleve->user->prenom }}</h4>
                    <span class="ml-1">{{ $semestre }} - {{ $eleve->classroom->libelle ?? 'Classe non définie' }}</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                {{-- <a href="{{ route('admin.bulletins.export', ['eleve' => $eleve->id, 'semestre' => $semestre]) }}"  --}}
                   <a class="btn btn-success">
                    <i class="fas fa-file-export"></i> Exporter PDF
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @if ($bulletin)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Détail des notes</h5>
                            <div class="card-tools">
                                <span class="badge bg-primary">Moyenne: {{ $bulletin['statistiques']['moyenne_generale'] }}</span>
                                <span class="badge bg-{{ $bulletin['statistiques']['mention'] === 'Insuffisant' ? 'danger' : 'success' }}">
                                    {{ $bulletin['statistiques']['mention'] }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Matière</th>
                                            <th>Type</th>
                                            <th>Note</th>
                                            <th>Coeff.</th>
                                            <th>Crédit</th>
                                            <th>Pondérée</th>
                                            <th>Date</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bulletin['matieres'] as $matiere)
                                            @foreach ($matiere['notes'] as $note)
                                                <tr>
                                                    @if ($loop->first)
                                                        <td rowspan="{{ count($matiere['notes']) }}" class="align-middle">
                                                            {{ $matiere['matiere'] }}
                                                            <br>
                                                            <small class="text-muted">Moy: {{ $matiere['moyenne'] }}</small>
                                                        </td>
                                                    @endif
                                                    <td>{{ $note['type'] }}</td>
                                                    <td>{{ $note['note'] }}</td>
                                                    <td>{{ $note['coefficient'] }}</td>
                                                    @if ($loop->first)
                                                        <td rowspan="{{ count($matiere['notes']) }}" class="align-middle">
                                                            {{ $matiere['credit'] }}
                                                        </td>
                                                        <td rowspan="{{ count($matiere['notes']) }}" class="align-middle">
                                                            {{ $matiere['ponderation'] }}
                                                        </td>
                                                    @endif
                                                    <td>{{ $note['date'] }}</td>
                                                    <td class="text-center">
                                                        @can('update-note')
                                                            <button class="btn btn-sm btn-warning" 
                                                                    data-toggle="modal"
                                                                    data-target="#modalEditNote"
                                                                    onclick="editNote({{ $note['id'] }}, {{ $note['note'] }}, {{ $note['coefficient'] }})">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        @endcan
                                                    </td>
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
                                                    <span class="badge bg-{{ $bulletin['statistiques']['mention'] === 'Insuffisant' ? 'danger' : 'success' }} rounded-pill">
                                                        {{ $bulletin['statistiques']['mention'] }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Classement
                                                    <span class="badge bg-info rounded-pill">
                                                        {{-- {{ $bulletin['statistiques']['classement'] ?? 'N/A' }}/{{ $bulletin['statistiques']['effectif'] }} --}}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Appréciation</h5>
                                            <textarea class="form-control" rows="5" placeholder="Saisir une appréciation..."></textarea>
                                            <button class="btn btn-primary mt-2">Enregistrer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Aucune note trouvée pour ce semestre.
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal Édition de Note -->
        <div class="modal fade" id="modalEditNote" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form id="formEditNote" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier la note</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="note_id" id="note_id">
                            <div class="form-group">
                                <label for="note">Note</label>
                                <input type="number" step="0.01" min="0" max="20" 
                                       name="note" id="note" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="coefficient">Coefficient</label>
                                <input type="number" step="0.1" min="0.1" max="3" 
                                       name="coefficient" id="coefficient" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="commentaire">Commentaire</label>
                                <textarea name="commentaire" id="commentaire" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editNote(id, note, coefficient) {
        document.getElementById('note_id').value = id;
        document.getElementById('note').value = note;
        document.getElementById('coefficient').value = coefficient;
    }
</script>
    {{-- document.getElementById('formEditNote').action = "{{ route('admin.notes.update', '') }}/" + id; --}}

</x-app-layout>