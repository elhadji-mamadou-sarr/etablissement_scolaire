@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Mes cours</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Matière</th>
                <th>Classe</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coursClassrooms as $item)
                <tr>
                    <td>{{ $item->cours }}</td>
                    <td>{{ $item->classe }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
