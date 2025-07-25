<x-app-layout>
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl font-bold mb-4">Prévisualisation des Bulletins</h2>
        <table class="table-auto w-full border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">Nom</th>
                    <th class="px-4 py-2">Prénom</th>
                    <th class="px-4 py-2">Classe</th>
                    <th class="px-4 py-2">Action</th>
                    <th class="px-4 py-2">Ac</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($eleves as $eleve)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $eleve->user->nom }}</td>
                        <td class="px-4 py-2">{{ $eleve->user->prenom }}</td>
                        <td class="px-4 py-2">{{ $eleve->classroom->libelle ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.bulletins.preview', ['eleve' => $eleve->id, 'semestre' => 'Semestre 1']) }}">
    Voir le bulletin
</a>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
