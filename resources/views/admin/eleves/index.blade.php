<!-- resources/views/admin/eleves/index.blade.php -->
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
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddEleve" onclick="resetModal()">+ Ajouter un élève</button>
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
                                                    <form action="{{ route('admin.eleves.destroy', $eleve->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet élève ?')">Supprimer</button>
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

            <!-- Modal Ajouter Élève -->
            <div class="modal fade" id="modalAddEleve" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST" action="{{ route('admin.eleves.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Ajouter un élève</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group"><label>Nom</label><input type="text" name="nom" class="form-control" required></div>
                                <div class="form-group"><label>Prénom</label><input type="text" name="prenom" class="form-control" required></div>
                                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                                <div class="form-group"><label>Téléphone</label><input type="text" name="telephone" class="form-control" required></div>
                                <div class="form-group"><label>Adresse</label><input type="text" name="addresse" class="form-control" required></div>
                                <div class="form-group"><label>Date de naissance</label><input type="date" name="date_naissance" class="form-control" required></div>
                                <div class="form-group"><label>Lieu de naissance</label><input type="text" name="lieu" class="form-control" required></div>
                                <div class="form-group">
                                    <label>Sexe</label>
                                    <select name="sexe" class="form-control" required>
                                        <option value="M">Masculin</option>
                                        <option value="F">Féminin</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Classe</label>
                                    <select name="classroom_id" class="form-control" required>
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
</x-app-layout>
