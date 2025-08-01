<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord - Enseignant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Bienvenue, {{ auth()->user()->name }}</h3>
                    <p class="mb-4">Vous êtes connecté en tant qu'enseignant.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800">Mes classes</h4>
                            <p class="text-sm text-blue-600">Gérer mes classes et élèves</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800">Saisie des notes</h4>
                            <p class="text-sm text-green-600">Saisir et modifier les notes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>