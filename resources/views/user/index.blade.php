<x-app-layout>
    <div class="content">
        <div class="container-fluid">
            <div class="row page-titles mx-0">

                <div class="col-sm-4 p-md-0">
                    <div class="welcome-text">
                        <h4>Utilisateurs</h4>
                        <span class="ml-1">Gestion des utilisateurs</span>
                    </div>
                </div>

                <div class="col-sm-4 p-md-0">
                    <form method="GET">
                        <select name="role" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Filtrer par rôle --</option>
                            <option value="administrateur" {{ request('role') == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                            <option value="enseignant" {{ request('role') == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                            <option value="eleve_parent" {{ request('role') == 'eleve_parent' ? 'selected' : '' }}>Élève/Parent</option>
                        </select>
                    </form>
                </div>

                <div class="col-sm-4 p-md-0 d-flex justify-content-sm-end">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalUser" onclick="addUser()">+ Ajouter</button>
                </div>

            </div>

            <div class="row mb-3">
              
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header"><h4 class="card-title">Liste des utilisateurs</h4></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr class="table-active">
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->nom }}</td>
                                    <td>{{ $user->prenom }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->getRoleLabel() }}</td>
                                    <td class="text-center">
                                        <div class="d-grid g-4 d-md-flex justify-content-md-end">
                                            <button class="btn btn-sm btn-warning text-white" onclick='editUser(@json($user))' data-toggle="modal" data-target="#modalUser">Modifier</button>
                                            &nbsp; &nbsp; &nbsp;
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Supprimer</button>
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

            <!-- Modal -->
            <div class="modal fade" id="modalUser" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form method="POST" id="formUser">
                        @csrf
                        <input type="hidden" name="_method" id="form_method" value="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Utilisateur</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom</label>
                                    <input type="text" name="nom" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" name="prenom" class="form-control" required>
                                </div>
                                <div class="col-md-6  mt-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-6  mt-3">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" name="telephone" class="form-control">
                                </div>
                                <div class="col-md-6  mt-3">
                                    <label class="form-label">Adresse</label>
                                    <input type="text" name="addresse" class="form-control">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label>Sexe</label>
                                        <select class="form-control" ame="sexe" id="sel1">            
                                            <option value="">Sélectionner</option>
                                            <option value="homme">Homme</option>
                                            <option value="femme">Femme</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6  mt-3">
                                    <div class="form-group">
                                        <label>Rôle</label>
                                        <select name="role" class="form-control" onchange="toggleEleveFields(this.value)">
                                            <option value="administrateur">Administrateur</option>
                                            <option value="enseignant">Enseignant</option>
                                            <option value="eleve_parent">Élève/Parent</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 eleve-fields d-none">
                                    <label class="form-label">Date de naissance</label>
                                    <input type="date" name="date_naissane" class="form-control">
                                </div>
                                <div class="col-md-6 eleve-fields d-none">
                                    <label class="form-label">Lieu de naissance</label>
                                    <input type="text" name="lieu" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function toggleEleveFields(role) {
            document.querySelectorAll('.eleve-fields').forEach(el => {
                el.classList.toggle('d-none', role !== 'eleve_parent');
            });
        }

        function addUser() {
            const form = document.getElementById('formUser');
            form.reset();
            form.action = "{{ route('admin.users.store') }}";
            document.getElementById('form_method').value = 'POST';
            toggleEleveFields('');
        }

        function editUser(user) {
            const form = document.getElementById('formUser');
            form.action = `/admin/users/${user.id}`;
            document.getElementById('form_method').value = 'PUT';

            for (let key in user) {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) input.value = user[key];
            }

            toggleEleveFields(user.role);
        }
    </script>
</x-app-layout>
