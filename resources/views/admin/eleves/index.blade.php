<x-app-layout>
    <div class="content">
        <div class="container-fluid">

            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Liste des élèves</h4>
                        <span class="ml-1">Gestion des élèves</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddEditEleve" onclick="resetModal()">+ Ajouter un élève</button>
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
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr class="table-active">
                                            <th>ID</th>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Classe</th>
                                            <th>Matricule</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eleves as $eleve)
                                            <tr>
                                                <td>{{ $eleve->id }}</td>
                                                <td>{{ $eleve->user->nom }} {{ $eleve->user->prenom }}</td>
                                                <td>{{ $eleve->user->email }}</td>
                                                <td>{{ $eleve->classroom->libelle }}</td>
                                                <td>{{ $eleve->matricule }}</td>
                                                <td class="text-center">
                                                    <div class="d-grid g-4 d-md-flex justify-content-md-end">
                                                        <button class="btn btn-sm btn-warning text-white" 
                                                            onclick="editEleve({{ json_encode($eleve) }}, {{ json_encode($eleve->user) }})" 
                                                            data-toggle="modal" 
                                                            data-target="#modalAddEditEleve">Modifier</button>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <form action="{{ route('admin.eleves.destroy', $eleve->id) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Supprimer cet élève ?')">Supprimer</button>
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

            <!-- Modal Ajouter/Modifier Élève -->
            <div class="modal fade" id="modalAddEditEleve" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="formEleve" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="_method" value="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Ajouter/Modifier un élève</h5>
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
                                    <label>Classe</label>
                                    <select name="classroom_id" id="classroom_id" class="form-control" required>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->libelle }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Justificatif (PDF/Image)</label>
                                    <input type="file" name="justificatif" class="form-control">
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
            document.getElementById("formEleve").reset();
            document.getElementById("formEleve").action = "{{ route('admin.eleves.store') }}";
            document.getElementById("_method").value = "POST";
            document.getElementById("modalLabel").innerText = "Ajouter un élève";
        }

        function editEleve(eleve, user) {
            document.getElementById("nom").value = user.nom;
            document.getElementById("prenom").value = user.prenom;
            document.getElementById("email").value = user.email;
            document.getElementById("telephone").value = user.telephone;
            document.getElementById("addresse").value = user.addresse;
            document.getElementById("date_naissance").value = user.date_naissane;
            document.getElementById("lieu").value = user.lieu;
            document.getElementById("sexe").value = user.sexe;
            document.getElementById("classroom_id").value = eleve.classroom_id;

            document.getElementById("formEleve").action = "/admin/eleves/" + eleve.id;
            document.getElementById("_method").value = "PUT";
            document.getElementById("modalLabel").innerText = "Modifier un élève";
        }
    </script>
    @endpush
</x-app-layout>