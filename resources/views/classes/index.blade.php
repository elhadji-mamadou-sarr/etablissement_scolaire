<x-app-layout>
    <div class="content">
        <div class="container-fluid">

            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Liste des classes</h4>
                        <span class="ml-1">Gestion des classes</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 d-flex justify-content-sm-end">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddEdit" onclick="resetModal()">+ Ajouter une classe</button>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Classes</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Libellé</th>
                                            <th>Description</th>
                                            <th>Cours associés</th>
                                            <th class="float-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classrooms as $classroom)
                                            <tr>
                                                <td>{{ $classroom->id }}</td>
                                                <td>{{ $classroom->libelle }}</td>
                                                <td>{{ $classroom->description }}</td>
                                                <td>
                                                    @foreach ($classroom->cours as $cour)
                                                        <span class="badge badge-info">{{ $cour->libelle }}</span>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-grid g-4 d-md-flex justify-content-md-end">
                                                        <button class="btn btn-sm btn-warning"
                                                            onclick="editClassroom({{ $classroom }})"
                                                            data-toggle="modal"
                                                            data-target="#modalAddEdit">Modifier</button>

                                                        <form action="{{ route('admin.classrooms.destroy', $classroom->id) }}" method="POST" style="display:inline-block;">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Supprimer cette classe ?')">Supprimer</button>
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

            <!-- Modal Ajouter / Modifier -->
            <div class="modal fade" id="modalAddEdit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="formClassroom" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="_method" value="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Ajouter / Modifier une classe</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Libellé</label>
                                    <input type="text" name="libelle" id="libelle" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" id="description" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Cours associés</label>
                                    <select name="cours[]" id="cours" multiple>
                                        @foreach ($cours as $cour)
                                            <option value="{{ $cour->id }}">{{ $cour->libelle }}</option>
                                        @endforeach
                                    </select>
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
            document.getElementById("formClassroom").reset();
            document.getElementById("formClassroom").action = "{{ route('admin.classrooms.store') }}";
            document.getElementById("_method").value = "POST";
            $('#cours').val([]).trigger('change');
        }

        function editClassroom(classroom) {
            document.getElementById("libelle").value = classroom.libelle;
            document.getElementById("description").value = classroom.description;
            document.getElementById("formClassroom").action = "/admin/classrooms/" + classroom.id;
            document.getElementById("_method").value = "PUT";

            const selectedCours = classroom.cours.map(c => c.id);
            $('#cours').val(selectedCours).trigger('change');
        }
    </script>
</x-app-layout>
