<x-app-layout>
    <div class="content">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Mes Cours</h4>
                        <span class="ml-1">Liste de tous mes cours attribués</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <!-- Vous pourriez ajouter un bouton d'action ici si nécessaire -->
                    <!-- <button class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Nouveau Cours
                    </button> -->
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @include('alerts')

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Filtrer les cours</h5>
                            <form method="GET" class="form-inline">
                                <div class="form-group mr-2">
                                    <select name="classe" class="form-control">
                                        <option value="">Toutes les classes</option>
                                        @foreach($classes as $classe)
                                            <option value="" >
                                              
                                                {{-- {{ $classe->libelle }} --}}
                                            </option>
                                        @endforeach
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
                                            <th>Matière</th>
                                            <th>Classe</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($classes as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->cours }}</td>
                                                <td>{{ $item->classe }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <!-- Exemple d'actions possibles -->
                                                        <a href="#" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye"></i> Voir
                                                        </a>
                                                        <!--
                                                        <form action="#" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                    onclick="return confirm('Confirmer la suppression ?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                        -->
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Aucun cours trouvé</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Si vous avez une pagination -->
                            <!-- <div class="mt-3">
                                {{-- {{ $classes->links() }} --}}
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>