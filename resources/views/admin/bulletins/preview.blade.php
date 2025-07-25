<x-app-layout>
    <div class="container">
        <h2>Bulletin de {{ $eleve->user->nom }} {{ $eleve->user->prenom }} – {{ $semestre }}</h2>

        @if ($bulletin)
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Matière</th>
                        <th>Note</th>
                        <th>Crédit</th>
                        <th>Note Pondérée</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bulletin['details'] as $detail)
                        <tr>
                            <td>{{ $detail['matiere'] }}</td>
                            <td>{{ $detail['note'] }}</td>
                            <td>{{ $detail['coefficient'] }}</td>
                            <td>{{ $detail['ponderee'] }}</td>
                            <td> 
                        
                                <button class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#modalEditNote"
                                        onclick="editNote({{ $detail['note_id'] }}, '{{ $detail['note'] }}')">
                                    Modifier
                                </button>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>

            <p><strong>Moyenne Générale :</strong> {{ $bulletin['moyenne_generale'] }}</p>
            <p><strong>Mention :</strong> {{ $bulletin['mention'] }}</p>
        @else
            <p>Aucune note trouvée pour ce semestre.</p>
        @endif
       
    </div>
   <!-- Modal Édition de Note -->
<div class="modal fade" id="modalEditNote" tabindex="-1" role="dialog" aria-labelledby="modalNoteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formEditNote" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la note</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="note_id" id="note_id">
                    <div class="form-group">
                        <label for="note">Note</label>
                        <input type="number" step="0.01" name="note" id="note" class="form-control" required>
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

<script>
    function editNote(id, note) {
        document.getElementById('note_id').value = id;
        document.getElementById('note').value = note;
        document.getElementById('formEditNote').action = "/admin/notes/" + id;
    }
</script>


</x-app-layout>
