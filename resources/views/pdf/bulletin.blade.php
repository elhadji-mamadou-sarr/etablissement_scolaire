<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin - {{ $eleve->user->nom }} {{ $eleve->user->prenom }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #666;
        }
        
        .student-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .student-info h3 {
            margin: 0 0 10px 0;
            color: #007bff;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-cell {
            display: table-cell;
            padding: 5px 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .info-label {
            font-weight: bold;
            width: 30%;
            color: #495057;
        }
        
        .notes-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .notes-table th {
            background-color: #343a40;
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
        }
        
        .notes-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #dee2e6;
            text-align: center;
        }
        
        .notes-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .matiere-cell {
            text-align: left !important;
            font-weight: bold;
            vertical-align: middle;
            background-color: #e9ecef !important;
        }
        
        .matiere-moyenne {
            font-size: 10px;
            color: #6c757d;
            font-weight: normal;
        }
        
        .note-success {
            color: #28a745;
            font-weight: bold;
        }
        
        .note-danger {
            color: #dc3545;
            font-weight: bold;
        }
        
        .statistiques {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        
        .stats-section {
            display: table-cell;
            width: 48%;
            padding: 0 1%;
            vertical-align: top;
        }
        
        .stats-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
        }
        
        .stats-card h4 {
            margin: 0 0 15px 0;
            color: #007bff;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 8px;
        }
        
        .stats-item {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            background-color: white;
            border-radius: 3px;
        }
        
        .stats-label {
            display: table-cell;
            font-weight: bold;
            color: #495057;
        }
        
        .stats-value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
        }
        
        .badge-primary {
            background-color: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        
        .badge-success {
            background-color: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        
        .badge-info {
            background-color: #17a2b8;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        
        .badge-secondary {
            background-color: #6c757d;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BULLETIN DE NOTES</h1>
        <h2>{{ $semestre }}</h2>
    </div>
    
    <div class="student-info">
        <h3>Informations de l'élève</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">Nom complet :</div>
                <div class="info-cell">{{ $eleve->user->nom }} {{ $eleve->user->prenom }}</div>
                <div class="info-cell info-label">Classe :</div>
                <div class="info-cell">{{ $eleve->classroom->libelle }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Semestre :</div>
                <div class="info-cell">{{ $semestre }}</div>
                <div class="info-cell info-label">Date d'édition :</div>
                <div class="info-cell">{{ $bulletin['date_edition'] }}</div>
            </div>
        </div>
    </div>
    
    <table class="notes-table">
        <thead>
            <tr>
                <th style="width: 20%;">Matière</th>
                <th style="width: 15%;">Type</th>
                <th style="width: 12%;">Note</th>
                <th style="width: 10%;">Coeff.</th>
                <th style="width: 10%;">Crédit</th>
                <th style="width: 13%;">Pondération</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bulletin['matieres'] as $matiere)
                @foreach($matiere['notes'] as $index => $note)
                <tr>
                    @if($index === 0)
                    <td rowspan="{{ count($matiere['notes']) }}" class="matiere-cell">
                        {{ $matiere['matiere'] }}
                        <div class="matiere-moyenne">Moy: {{ $matiere['moyenne'] }}/20</div>
                    </td>
                    @endif
                    <td>{{ $note['type'] }}</td>
                    <td class="{{ $note['note'] < 10 ? 'note-danger' : 'note-success' }}">
                        {{ $note['note'] }}/20
                    </td>
                    <td>{{ $note['coefficient'] }}</td>
                    @if($index === 0)
                    <td rowspan="{{ count($matiere['notes']) }}" style="vertical-align: middle;">
                        {{ $matiere['credit'] }}
                    </td>
                    <td rowspan="{{ count($matiere['notes']) }}" style="vertical-align: middle;">
                        {{ $matiere['ponderation'] }}
                    </td>
                    @endif
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    
    <div class="statistiques">
        <div class="stats-section">
            <div class="stats-card">
                <h4>Statistiques</h4>
                <div class="stats-item">
                    <div class="stats-label">Moyenne Générale</div>
                    <div class="stats-value">
                        <span class="badge-primary">{{ $bulletin['statistiques']['moyenne_generale'] }}/20</span>
                    </div>
                </div>
                <div class="stats-item">
                    <div class="stats-label">Mention</div>
                    <div class="stats-value">
                        <span class="badge-{{ $bulletin['statistiques']['mention'] === 'Insuffisant' ? 'danger' : 'success' }}">
                            {{ $bulletin['statistiques']['mention'] }}
                        </span>
                    </div>
                </div>
                <div class="stats-item">
                    <div class="stats-label">Classement</div>
                    <div class="stats-value">
                        <span class="badge-info">{{ $bulletin['statistiques']['classement'] ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="stats-item">
                    <div class="stats-label">Total Crédits</div>
                    <div class="stats-value">
                        <span class="badge-secondary">{{ $bulletin['statistiques']['total_credits'] }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="stats-section">
            <div class="stats-card">
                <h4>Résumé par matière</h4>
                @foreach($bulletin['matieres'] as $matiere)
                <div class="stats-item">
                    <div class="stats-label">{{ $matiere['matiere'] }}</div>
                    <div class="stats-value">
                        <span class="badge-{{ $matiere['moyenne'] < 10 ? 'danger' : 'success' }}">
                            {{ $matiere['moyenne'] }}/20
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Système de gestion scolaire - Bulletin officiel</p>
    </div>
</body>
</html>