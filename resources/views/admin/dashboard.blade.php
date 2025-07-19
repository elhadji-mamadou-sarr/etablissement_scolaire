<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord - Administrateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Bienvenue, {{ auth()->user()->name }}</h3>
                    <p class="mb-4">Vous êtes connecté en tant qu'administrateur.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800">Gestion des utilisateurs</h4>
                            <p class="text-sm text-blue-600">Gérer les élèves, enseignants et parents</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800">Gestion des classes</h4>
                            <p class="text-sm text-green-600">Organiser les classes et matières</p>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-lg">
                            <h4 class="font-semibold text-purple-800">Rapports généraux</h4>
                            <p class="text-sm text-purple-600">Consulter les statistiques globales</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>