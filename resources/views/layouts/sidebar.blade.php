@php
    use App\Enums\UserRole;
@endphp

<!--**********************************
    Sidebar start
***********************************-->
<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">

            {{-- Commun à tous : Dashboard --}}
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="icon icon-app-store"></i>Dashboard
                </a>
            </li>

            {{-- Administrateur --}}
            @if(auth()->user()->role === UserRole::ADMINISTRATEUR)

            <li><a href="{{ route('admin.users.index') }}">
                <i class="icon icon-single-04"></i>Utilisateurs</a>
            </li>
            <li>
                <a href="{{ route('admin.classrooms.index') }}">
                <i class="icon icon-layout-25"></i>Classes</a>
            </li>
            <li>
                <a href="{{ route('admin.cours.index') }}">
                <i class="icon icon-globe-2"></i>Cours</a> 
            </li>
            <li>
                <a href="{{ route('admin.eleves.index') }}">
                <i class="icon icon-single-04"></i>Eleves</a>
            </li>
            <li>
                <a href="{{ route('admin.enseignants.index') }}">
                <i class="icon icon-user"></i>Enseignants</a>
            </li>
            <li>
                <a href="{{ route('admin.bulletins.index') }}"> 
                <i class="icon icon-layout-25"></i>Bulletins
                </a>
            </li>


            {{-- Enseignant --}}
            @elseif(auth()->user()->role === UserRole::ENSEIGNANT)
                <li>
                    <a href="{{ route('enseignant.dashboard') }}">
                        <i class="icon icon-home"></i>Accueil Enseignant
                    </a>
                </li>
                <li>
                    <a href="{{ route('enseignant.notes.index') }}">
                        <i class="icon icon-book-open"></i>Notes
                    </a>
                </li>


            {{-- Élève / Parent --}}
            @elseif(auth()->user()->role === UserRole::ELEVE_PARENT)
                <li>
                    <a href="{{ route('eleve-parent.bulletins') }}">
                        <i class="icon icon-docs"></i>Mes bulletins
                    </a>
                </li>
            @endif

        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->
