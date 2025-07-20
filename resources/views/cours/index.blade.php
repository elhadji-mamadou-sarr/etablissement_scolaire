<x-app-layout>
    <div class="content">
        <div class="container-fluid">

            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Liste des cours</h4>
                        <span class="ml-1">Gestion des cours</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddEdit" onclick="resetModal()">+ Ajouter un cours</button>
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
                                <table id="example" class="display" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Libellé</th>
                                            <th>Crédit</th>
                                            <th>Volume</th>
                                            <th>Semestre</th>
                                            <th class="float-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cours as $cour)
                                            <tr>
                                                <td>{{ $cour->id }}</td>
                                                <td>{{ $cour->libelle }}</td>
                                                <td>{{ $cour->credit }}</td>
                                                <td>{{ $cour->volume }}</td>
                                                <td>{{ $cour->semestre }}</td>
                                                <td class="text-center">
                                                    <div class="d-grid g-4 d-md-flex justify-content-md-end">
                                                        <button class="btn btn-sm btn-warning" 
                                                        onclick="editCour({{ $cour }})" 
                                                        data-toggle="modal" 
                                                        data-target="#modalAddEdit">Modifier</button>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <form action="{{ route('admin.cours.destroy', $cour->id) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                                                        </form>
                                                    </div>
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

            <!-- Modal Ajouter/Modifier -->
            <div class="modal fade" id="modalAddEdit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="formCour" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="_method" value="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Ajouter / Modifier un cours</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Libellé</label>
                                    <input type="text" name="libelle" id="libelle" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Crédit</label>
                                    <input type="number" name="credit" id="credit" class="form-control" step="0.01" required>
                                </div>
                                <div class="form-group">
                                    <label>Volume</label>
                                    <input type="number" name="volume" id="volume" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Semestre</label>
                                    <input type="text" name="semestre" id="semestre" class="form-control">
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

    @push('scripts')
    <script>
        function resetModal() {
            document.getElementById("formCour").reset();
            document.getElementById("formCour").action = "{{ route('admin.cours.store') }}";
            document.getElementById("_method").value = "POST";
        }

        function editCour(cour) {
            document.getElementById("libelle").value = cour.libelle;
            document.getElementById("credit").value = cour.credit;
            document.getElementById("volume").value = cour.volume;
            document.getElementById("semestre").value = cour.semestre;

            document.getElementById("formCour").action = "/admin/cours/" + cour.id;
            document.getElementById("_method").value = "PUT";
        }
    </script>
    @endpush

</x-app-layout>
