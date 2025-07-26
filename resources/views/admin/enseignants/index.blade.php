<x-app-layout>
    <div class="content">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Liste des enseignants</h4>
                        <span class="ml-1">Gestion des enseignants</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddEdit" onclick="resetModal()">+ Ajouter un enseignant</button>
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
                                    <thead class="thead-dark">
                                        <tr class="table-active">
                                            <th>ID</th>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Cours</th>
                                            <th>Classes</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($enseignants as $enseignant)
                                            <tr>
                                                <td>{{ $enseignant->id }}</td>
                                                <td>{{ $enseignant->user->nom }} {{ $enseignant->user->prenom }}</td>
                                                <td>{{ $enseignant->user->email }}</td>
                                               <td colspan="2">
                                                    @foreach ($enseignant->coursClassrooms() as $item)
                                                        <div class="mb-1">
                                                            <span class="badge badge-info">{{ $item->cours }}</span> → 
                                                            <span class="badge badge-secondary">{{ $item->classe }}</span>
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-grid g-4 d-md-flex justify-content-md-end">
                                                        <button 
                                                            type="button" 
                                                            class="btn btn-sm btn-warning text-white" 
                                                            data-toggle="modal" 
                                                            data-target="#modalAddEdit"
                                                            onclick='editEnseignant(@json($enseignant), @json($enseignant->cours->pluck("id")), @json($enseignant->classrooms->pluck("id")))'>
                                                            Modifier
                                                        </button> &nbsp;&nbsp;&nbsp;
                                                        <form action="{{ route('admin.enseignants.destroy', $enseignant->id) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet enseignant ?')">Supprimer</button>
                                                            
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

            <!-- Modal Ajouter/Modifier Enseignant -->
            <div class="modal fade" id="modalAddEdit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="formEnseignant" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="_method" value="POST">
                        <input type="hidden" name="_method" id="_method" value="POST">
                        <input type="hidden" name="enseignant_id" id="enseignant_id">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Ajouter un enseignant</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group"><label>Nom</label><input type="text" name="nom" id="nom" class="form-control" required></div>
                                <div class="form-group"><label>Prénom</label><input type="text" name="prenom" id="prenom" class="form-control" required></div>
                                <div class="form-group"><label>Email</label><input type="email" name="email" id="email" class="form-control" required></div>
                                <div class="form-group"><label>Téléphone</label><input type="text" name="telephone" id="telephone" class="form-control" required></div>
                                <div class="form-group"><label>Adresse</label><input type="text" name="addresse" id="addresse" class="form-control" required></div>
                                <div class="form-group"><label>Date de naissance</label><input type="date" name="date_naissance" id="date_naissance" class="form-control" required></div>
                                <div class="form-group"><label>Lieu de naissance</label><input type="text" name="lieu" id="lieu" class="form-control" required></div>
                                <div class="form-group">
                                    <label>Sexe</label>
                                    <select name="sexe" id="sexe" class="form-control" required>
                                        <option value="M">Masculin</option>
                                        <option value="F">Féminin</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Cours</label>
                                    <select name="cours[]" id="cours" class="form-control" multiple required>
                                        @foreach($cours as $c)
                                            <option value="{{ $c->id }}">{{ $c->libelle }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Classes</label>
                                    <select name="classrooms[]" id="classrooms" class="form-control" multiple required>
                                        @foreach($classrooms as $cl)
                                            <option value="{{ $cl->id }}">{{ $cl->libelle }}</option>
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
    function editEnseignant(enseignant, coursIds, classIds) {
        // Remplir les champs du formulaire
        $('#nom').val(enseignant.user.nom);
        $('#prenom').val(enseignant.user.prenom);
        $('#email').val(enseignant.user.email);
        $('#telephone').val(enseignant.user.telephone);
        $('#addresse').val(enseignant.user.addresse);
        $('#date_naissance').val(enseignant.user.date_naissane);
        $('#lieu').val(enseignant.user.lieu);
        $('#sexe').val(enseignant.user.sexe);
        $('#enseignant_id').val(enseignant.id);

        // Sélectionner les options
        $('#cours').val(coursIds).change();
        $('#classrooms').val(classIds).change();

        // Modifier l'action du formulaire
        $('#formEnseignant').attr('action', `/admin/enseignants/${enseignant.id}`);
        $('#_method').val('PUT');

        $('#modalLabel').text("Modifier l'enseignant");
    }

    function resetModal() {
        $('#formEnseignant')[0].reset();
        $('#formEnseignant').attr('action', "{{ route('admin.enseignants.store') }}");
        $('#_method').val('POST');
        $('#enseignant_id').val('');
        $('#cours').val([]).change();
        $('#classrooms').val([]).change();
        $('#modalLabel').text("Ajouter un enseignant");
    }
</script>

</x-app-layout>
