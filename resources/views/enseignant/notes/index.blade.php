<x-app-layout>
    <div class="content">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Gestion des Notes</h4>
                        <span class="ml-1">Saisie et modification des notes</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddNote" onclick="resetModal()">
                        <i class="fas fa-plus mr-1"></i> Nouvelle Note
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-12">

                    @include('alerts')

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Filtrer les notes</h5>
                            <form method="GET" class="form-inline">
                                <div class="form-group mr-2">
                                    <select name="classe" class="form-control">
                                        <option value="">Toutes les classes</option>
                                        @foreach($classes as $classe)
                                            <option value="{{ $classe->id }}" {{ request('classe') == $classe->id ? 'selected' : '' }}>
                                                {{ $classe->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <select name="cours" class="form-control">
                                        <option value="">Tous les cours</option>
                                        @foreach($cours as $cour)
                                            <option value="{{ $cour->id }}" {{ request('cours') == $cour->id ? 'selected' : '' }}>
                                                {{ $cour->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <select name="semestre" class="form-control">
                                        <option value="">Tous semestres</option>
                                        <option value="Semestre 1" {{ request('semestre') == 'Semestre 1' ? 'selected' : '' }}>Semestre 1</option>
                                        <option value="Semestre 2" {{ request('semestre') == 'Semestre 2' ? 'selected' : '' }}>Semestre 2</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter mr-1"></i> Filtrer
                                </button>
                            </form>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Élève</th>
                                            <th>Matière</th>
                                            <th>Classe</th>
                                            <th>Type</th>
                                            <th>Note</th>
                                            <th>Semestre</th>
                                            <th>Date</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($notes as $note)
                                            <tr>
                                                <td>{{ $note->id }}</td>
                                                <td>{{ $note->eleve->user->nom }} {{ $note->eleve->user->prenom }}</td>
                                                <td>{{ $note->cour->libelle }}</td>
                                                <td>{{ $note->classroom->libelle }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $note->type_note == 'examen' ? 'danger' : 'primary' }}">
                                                        {{ ucfirst($note->type_note) }}
                                                    </span>
                                                </td>
                                                <td class="{{ $note->note < 10 ? 'text-danger' : 'text-success' }}">
                                                    <strong>{{ $note->note }}</strong>/20
                                                    <small class="text-muted">(coeff. {{ $note->coefficient }})</small>
                                                </td>
                                                <td>{{ $note->semestre }}</td>
                                                <td>{{ $note->date_evaluation->format('d/m/Y') }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-warning" 
                                                                onclick="editNote({{ json_encode($note) }})"
                                                                data-toggle="modal"
                                                                data-target="#modalAddNote">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('enseignant.notes.destroy', $note->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                    onclick="return confirm('Confirmer la suppression ?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">Aucune note trouvée</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $notes->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajout/Modification Note -->
    <div class="modal fade" id="modalAddNote" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="formNote" method="POST">
                @csrf
                <input type="hidden" name="_method" id="_method" value="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Saisir une note</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Élève *</label>
                                    <select name="eleve_id" class="form-control select2" required>
                                        @foreach($eleves as $eleve)
                                            <option value="{{ $eleve->id }}">
                                                {{ $eleve->user->nom }} {{ $eleve->user->prenom }} - {{ $eleve->classroom->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Matière *</label>
                                    <select name="cour_id" class="form-control select2" required>
                                        @foreach($cours as $cour)
                                            <option value="{{ $cour->id }}">{{ $cour->libelle }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Type de note *</label>
                                    <select name="type_note" class="form-control" required>
                                        <option value="devoir">Devoir</option>
                                        <option value="composition">Composition</option>
                                        <option value="examen">Examen</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Semestre *</label>
                                    <select name="semestre" class="form-control" required>
                                        <option value="Semestre 1">Semestre 1</option>
                                        <option value="Semestre 2">Semestre 2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Coefficient *</label>
                                    <input type="number" name="coefficient" step="0.1" min="0.1"
                                           class="form-control" value="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Note *</label>
                                    <input type="number" name="note" step="0.01" min="0" max="20" 
                                           class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date d'évaluation *</label>
                                    <input type="date" name="date_evaluation" 
                                           class="form-control" 
                                           value="{{ date('Y-m-d') }}" 
                                           max="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Commentaire</label>
                            <textarea name="commentaire" class="form-control" rows="2"></textarea>
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

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: "Sélectionner...",
                allowClear: true
            });
        });

        function resetModal() {
            $('#formNote')[0].reset();
            $('#formNote').attr('action', "{{ route('enseignant.notes.store') }}");
            $('#_method').val('POST');
            $('#modalLabel').text("Saisir une note");
            $('.select2').trigger('change');
        }

        function editNote(note) {
            $('#formNote').attr('action', `/enseignant/notes/${note.id}`);
            $('#_method').val('PUT');
            $('#modalLabel').text("Modifier la note");

            $('[name="eleve_id"]').val(note.eleve_id).trigger('change');
            $('[name="cour_id"]').val(note.cour_id).trigger('change');
            $('[name="type_note"]').val(note.type_note);
            $('[name="semestre"]').val(note.semestre);
            $('[name="note"]').val(note.note);
            $('[name="coefficient"]').val(note.coefficient);
            $('[name="date_evaluation"]').val(note.date_evaluation.split('T')[0]);
            $('[name="commentaire"]').val(note.commentaire || '');
        }
    </script>
    @endpush
</x-app-layout>