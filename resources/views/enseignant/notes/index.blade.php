<x-app-layout>
    <div class="content">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Liste des notes</h4>
                        <span class="ml-1">Saisie des notes par matière, classe et semestre</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddNote" onclick="resetModal()">+ Saisir une note</button>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr class="table-active">
                                            <th>#</th>
                                            <th>Élève</th>
                                            <th>Cours</th>
                                            <th>Classe</th>
                                            <th>Semestre</th>
                                            <th>Note</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($notes as $note)
                                            <tr>
                                                <td>{{ $note->id }}</td>
                                                <td>{{ $note->eleve->user->nom }} {{ $note->eleve->user->prenom }}</td>
                                                <td>{{ $note->cour->libelle }}</td>
                                                <td>{{ $note->classroom->libelle }}</td>
                                                <td>{{ $note->semestre }}</td>
                                                <td>{{ $note->note }}</td>
                                                <td class="text-center">
                                                        <div class="d-grid d-md-flex justify-content-md-end">
        <button class="btn btn-sm btn-warning"
                onclick="editNote({{ $note }})"
                data-toggle="modal"
                data-target="#modalAddNote">Modifier</button>
        &nbsp;&nbsp;&nbsp;

                                                    <form action="{{ route('enseignant.notes.destroy', $note->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette note ?')">Supprimer</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Ajouter une note -->
            <div class="modal fade" id="modalAddNote" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="formNote" method="POST" action="{{ route('enseignant.notes.store') }}">
                        @csrf
                        <input type="hidden" name="_method" id="_method" value="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Saisir une note</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Élève</label>
                                    <select name="eleve_id" class="form-control" required>
                                        @foreach($eleves as $eleve)
                                            <option value="{{ $eleve->id }}">{{ $eleve->user->nom }} {{ $eleve->user->prenom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Cours</label>
                                    <select name="cour_id" class="form-control" required>
                                        @foreach($cours as $cour)
                                            <option value="{{ $cour->id }}">{{ $cour->libelle }}</option>
                                        @endforeach
                                    </select>
                                </div>
                
                                <div class="form-group">
                                    <label>Semestre</label>
                                    <select name="semestre" class="form-control" required>
                                        <option value="Semestre 1">Semestre 1</option>
                                        <option value="Semestre 2">Semestre 2</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Note</label>
                                    <input type="number" name="note" step="0.01" class="form-control" required> 
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
        function resetModal() {
            document.getElementById("formNote").reset();
        document.getElementById("formNote").action = "{{ route('enseignant.notes.store') }}";
        document.getElementById("_method").value = "POST";
            $('#formNote')[0].reset();
            $('#modalLabel').text("Saisir une note");
        }
          function editNote(note) {
        document.querySelector('[name="eleve_id"]').value = note.eleve_id;
        document.querySelector('[name="cour_id"]').value = note.cour_id;
        document.querySelector('[name="semestre"]').value = note.semestre;
        document.querySelector('[name="note"]').value = note.note;

        document.getElementById("formNote").action = "/enseignant/notes/" + note.id;
        document.getElementById("_method").value = "PUT";
    }
    </script>
</x-app-layout>
