<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold text-dark">
            {{ __('Tableau de bord Administrateur') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <!-- Cartes Statistiques -->
        <div class="row g-4 mb-5">
            @foreach([
                ['label' => "Élèves", 'value' => $eleves, 'icon' => 'fas fa-user-graduate', 'color' => 'bg-primary'],
                ['label' => "Enseignants", 'value' => $enseignants, 'icon' => 'fas fa-chalkboard-teacher', 'color' => 'bg-success'],
                ['label' => "Classes", 'value' => $classes, 'icon' => 'fas fa-school', 'color' => 'bg-warning'],
                ['label' => "Matières", 'value' => $cours, 'icon' => 'fas fa-book', 'color' => 'bg-danger']
            ] as $stat)
                <div class="col-md-3">
                    <div class="card shadow text-white {{ $stat['color'] }}">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">{{ $stat['label'] }}</h5>
                                <h2 class="fw-bold">{{ $stat['value'] }}</h2>
                            </div>
                            <i class="{{ $stat['icon'] }} fa-2x"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Graphiques -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-light fw-bold">Bulletins par mois</div>
                    <div class="card-body">
                        <canvas id="chartBulletins"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-light fw-bold">Répartition des élèves par classe</div>
                    <div class="card-body">
                        <canvas id="chartClasses"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light fw-bold">Moyenne par matière</div>
                    <div class="card-body">
                        <canvas id="chartMoyennes"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const bulletinsChart = new Chart(document.getElementById('chartBulletins'), {
            type: 'line',
            data: {
                labels: {!! json_encode($bulletinsParMois->keys()->all()) !!},
                datasets: [{
                    label: 'Nombre de bulletins',
                    data: {!! json_encode($bulletinsParMois->values()->all()) !!},
                    fill: false,
                    borderColor: '#0d6efd',
                    tension: 0.4
                }]
            }
        });

        const repartitionChart = new Chart(document.getElementById('chartClasses'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($repartitionClasses->keys()->all()) !!},
                datasets: [{
                    data: {!! json_encode($repartitionClasses->values()->all()) !!},
                    backgroundColor: ['#0d6efd', '#dc3545', '#ffc107', '#198754', '#6f42c1']
                }]
            }
        });

        const moyenneChart = new Chart(document.getElementById('chartMoyennes'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($notesParCours->keys()->all()) !!},
                datasets: [{
                    label: 'Moyenne',
                    data: {!! json_encode($notesParCours->values()->all()) !!},
                    backgroundColor: '#198754'
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, max: 20 }
                }
            }
        });
    </script>
    @endpush
<<<<<<< HEAD
</x-app-layout>
=======
</x-app-layout>
>>>>>>> 95108481e75548f3db9fbe96237b47447cbd8715
