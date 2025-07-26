<x-app-layout>
    <div class="content">
        <div class="container-fluid">

            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Liste des bulletins</h4>
                        <span class="ml-1">Gestion des bulletins</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    
                    
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
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Classe</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eleves as $eleve)
                                            <tr>
                                                <td>{{ $eleve->user->nom }}</td>
                                                <td>{{ $eleve->user->prenom }}</td>
                                                <td>{{ $eleve->classroom->libelle ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.bulletins.preview', ['eleve' => $eleve->id, 'semestre' => 'Semestre 1']) }}" 
                                                       class="btn btn-sm btn-primary">
                                                        Voir le bulletin
                                                    </a>
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
        </div>
    </div>
</x-app-layout>