@extends('layouts.app')

@section('title', 'Mes Chantiers')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header Simple -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-home mr-3 text-blue-600"></i>Mes Chantiers
        </h1>
        <h2 class="text-xl text-gray-700 mt-2">Bonjour {{ Auth::user()->name }} !</h2>
        <p class="text-gray-500 mt-1">Suivez l'avancement de vos projets en temps réel</p>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $mes_chantiers->count() }}</div>
            <div class="text-sm text-gray-600">Total projets</div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <div class="text-3xl font-bold text-blue-600 mb-1">{{ $mes_chantiers->where('statut', 'en_cours')->count() }}</div>
            <div class="text-sm text-gray-600">En cours</div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <div class="text-3xl font-bold text-green-600 mb-1">{{ $mes_chantiers->where('statut', 'termine')->count() }}</div>
            <div class="text-sm text-gray-600">Terminés</div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($mes_chantiers->avg('avancement_global') ?? 0, 0) }}%</div>
            <div class="text-sm text-gray-600">Avancement moyen</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Zone principale des chantiers -->
        <div class="lg:col-span-2 space-y-6">
            @forelse($mes_chantiers as $chantier)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                    <div class="border-l-4 {{ $chantier->statut == 'termine' ? 'border-green-500' : ($chantier->statut == 'en_cours' ? 'border-blue-500' : 'border-gray-400') }}">
                        <!-- Header -->
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span class="text-2xl">
                                        @switch($chantier->statut)
                                            @case('planifie')
                                                📋
                                                @break
                                            @case('en_cours')
                                                🏗️
                                                @break
                                            @case('termine')
                                                ✅
                                                @break
                                            @default
                                                🏠
                                        @endswitch
                                    </span>
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900">{{ $chantier->titre }}</h3>
                                        <p class="text-gray-600">{{ $chantier->description ?: 'Aucune description disponible' }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $chantier->getStatutBadgeClass() }}">
                                    {{ $chantier->getStatutTexte() }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div class="lg:col-span-2 space-y-6">
                                    <!-- Informations commercial -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                            <i class="fas fa-user-tie mr-2 text-blue-500"></i>
                                            Votre commercial
                                        </h4>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                                <span class="text-white font-medium text-sm">{{ strtoupper(substr($chantier->commercial->name, 0, 2)) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $chantier->commercial->name }}</p>
                                                @if($chantier->commercial->telephone)
                                                    <div class="text-sm text-gray-600">
                                                        <span class="inline-flex items-center">
                                                            <i class="fas fa-phone mr-1 text-blue-500"></i>
                                                            {{ $chantier->commercial->telephone }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Informations projet -->
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        @if($chantier->date_debut)
                                            <div class="flex justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">Début :</span>
                                                <span class="font-medium">{{ $chantier->date_debut->format('d/m/Y') }}</span>
                                            </div>
                                        @endif
                                        @if($chantier->date_fin_prevue)
                                            <div class="flex justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">Fin prévue :</span>
                                                <span class="font-medium {{ $chantier->isEnRetard() ? 'text-red-600' : '' }}">{{ $chantier->date_fin_prevue->format('d/m/Y') }}</span>
                                            </div>
                                        @endif
                                        @if($chantier->budget)
                                            <div class="flex justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">Budget :</span>
                                                <span class="font-medium">{{ number_format($chantier->budget, 0, ',', ' ') }} €</span>
                                            </div>
                                        @endif
                                        @if($chantier->date_fin_effective)
                                            <div class="flex justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">Terminé le :</span>
                                                <span class="font-medium text-green-600">{{ $chantier->date_fin_effective->format('d/m/Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Étapes -->
                                    @if($chantier->etapes && $chantier->etapes->count() > 0)
                                        <div class="space-y-3">
                                            <h4 class="font-medium text-gray-900 flex items-center">
                                                <i class="fas fa-tasks mr-2 text-blue-500"></i>
                                                Étapes du projet ({{ $chantier->etapes->count() }})
                                            </h4>
                                            
                                            <div class="space-y-2">
                                                @foreach($chantier->etapes->take(4) as $etape)
                                                    <div class="flex items-center space-x-3 p-3 {{ $etape->terminee ? 'bg-green-50 border-l-4 border-green-500' : ($etape->pourcentage > 0 ? 'bg-blue-50 border-l-4 border-blue-500' : 'bg-gray-50') }} rounded-lg">
                                                        @if($etape->terminee)
                                                            <i class="fas fa-check-circle text-green-500"></i>
                                                            <span class="flex-1 text-gray-500 line-through">{{ $etape->nom }}</span>
                                                        @elseif($etape->pourcentage > 0)
                                                            <i class="fas fa-circle text-blue-500"></i>
                                                            <span class="flex-1 text-gray-700">{{ $etape->nom }}</span>
                                                        @else
                                                            <i class="far fa-circle text-gray-400"></i>
                                                            <span class="flex-1 text-gray-600">{{ $etape->nom }}</span>
                                                        @endif
                                                        <span class="text-xs font-medium {{ $etape->terminee ? 'text-green-600' : ($etape->pourcentage > 0 ? 'text-blue-600' : 'text-gray-500') }}">
                                                            {{ number_format($etape->pourcentage, 0) }}%
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Sidebar droite -->
                                <div class="space-y-4">
                                    <!-- Progression -->
                                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                                        <h4 class="font-medium text-gray-900 mb-3">Avancement global</h4>
                                        <div class="relative">
                                            <div class="w-24 h-24 mx-auto">
                                                <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                                                    <path class="text-gray-200" stroke="currentColor" stroke-width="3" fill="none"
                                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                                    <path class="text-{{ $chantier->statut == 'termine' ? 'green' : 'blue' }}-500" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"
                                                          stroke-dasharray="{{ $chantier->avancement_global ?? 0 }}, 100"
                                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                                </svg>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <span class="text-xl font-bold text-gray-900">{{ number_format($chantier->avancement_global ?? 0, 0) }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-2">
                                            @if($chantier->statut == 'termine')
                                                Terminé avec succès
                                            @elseif($chantier->avancement_global > 50)
                                                En bonne voie
                                            @else
                                                Début de projet
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <!-- Documents récents -->
                                    @if($chantier->documents && $chantier->documents->count() > 0)
                                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                                            <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                                <i class="fas fa-folder mr-2 text-blue-500"></i>
                                                Documents ({{ $chantier->documents->count() }})
                                            </h4>
                                            <div class="space-y-2">
                                                @foreach($chantier->documents->take(3) as $document)
                                                    <a href="{{ route('documents.download', $document) }}" class="flex items-center space-x-2 p-2 bg-gray-50 rounded hover:bg-gray-100 transition-colors">
                                                        <i class="{{ $document->getIconeType() }} {{ $document->isImage() ? 'text-green-500' : ($document->type_mime === 'application/pdf' ? 'text-red-500' : 'text-blue-500') }}"></i>
                                                        <div class="flex-1">
                                                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($document->nom_original, 20) }}</div>
                                                            <div class="text-xs text-gray-500">{{ $document->getTailleFormatee() }}</div>
                                                        </div>
                                                        <i class="fas fa-download text-gray-400"></i>
                                                    </a>
                                                @endforeach
                                                @if($chantier->documents->count() > 3)
                                                    <div class="text-center pt-2">
                                                        <a href="{{ route('chantiers.show', $chantier) }}#documents" class="text-xs text-blue-600 hover:text-blue-700">
                                                            + {{ $chantier->documents->count() - 3 }} autres documents
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Messages de statut spéciaux -->
                            @if($chantier->statut == 'termine')
                                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-start space-x-3">
                                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                        <div>
                                            <h6 class="font-semibold text-green-800">Projet terminé avec succès !</h6>
                                            <p class="text-green-700 text-sm mt-1">Nous espérons que vous êtes satisfait du résultat. N'hésitez pas à nous contacter pour vos futurs projets.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($chantier->isEnRetard())
                                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex items-start space-x-3">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mt-1"></i>
                                        <div>
                                            <h6 class="font-semibold text-yellow-800">Projet en retard</h6>
                                            <p class="text-yellow-700 text-sm mt-1">Le chantier accuse un retard. Votre commercial vous contactera prochainement pour vous informer de la nouvelle planification.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Actions -->
                            <div class="flex flex-wrap gap-3 justify-center mt-6 pt-6 border-t border-gray-200">
                                <a href="{{ route('chantiers.show', $chantier) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye mr-2"></i>
                                    Voir le détail
                                </a>
                                <a href="{{ route('messages.create') }}?recipient_id={{ $chantier->commercial_id }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-comment mr-2"></i>
                                    Contacter {{ $chantier->commercial->name }}
                                </a>
                                @if($chantier->documents && $chantier->documents->count() > 0)
                                    <a href="{{ route('chantiers.show', $chantier) }}#documents" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-download mr-2"></i>
                                        Documents
                                    </a>
                                @endif
                                @if($chantier->statut == 'termine')
                                    <button onclick="evaluerProjet({{ $chantier->id }})" class="inline-flex items-center px-4 py-2 bg-white border border-yellow-300 text-yellow-700 font-medium rounded-lg hover:bg-yellow-50 transition-colors">
                                        <i class="fas fa-star mr-2"></i>
                                        Noter ce projet
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 text-center py-12">
                    <div class="p-6">
                        <i class="fas fa-hard-hat text-6xl text-gray-400 mb-6"></i>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Aucun chantier en cours</h4>
                        <p class="text-gray-500 mb-6">Vous n'avez pas encore de chantiers assignés. Contactez notre équipe commerciale pour démarrer votre projet.</p>
                        <a href="{{ route('messages.create') }}?subject=Demande de devis" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition-colors duration-150">
                            <i class="fas fa-plus mr-2"></i>Demander un devis
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Sidebar droite -->
        <div class="space-y-6">
            <!-- Notifications -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="font-medium text-gray-900 flex items-center">
                        <i class="fas fa-bell mr-2 text-blue-500"></i>
                        Dernières nouvelles
                    </h3>
                    @if($notifications->where('lu', false)->count() > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 bg-blue-500 text-white text-xs font-bold rounded-full">{{ $notifications->where('lu', false)->count() }}</span>
                    @endif
                </div>
                <div class="p-6 space-y-4">
                    @forelse($notifications->take(3) as $notification)
                        <div class="p-3 {{ !$notification->lu ? 'bg-blue-50 border-l-4 border-blue-500' : 'bg-gray-50' }} rounded-lg">
                            <h4 class="font-medium text-{{ !$notification->lu ? 'blue' : 'gray' }}-900 text-sm">{{ $notification->titre }}</h4>
                            <p class="text-{{ !$notification->lu ? 'blue' : 'gray' }}-700 text-sm mt-1">{{ Str::limit($notification->message, 50) }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-{{ !$notification->lu ? 'blue' : 'gray' }}-600 flex items-center">
                                    <i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </span>
                                @if(!$notification->lu)
                                    <span class="text-xs font-medium text-blue-800 bg-blue-100 px-2 py-1 rounded">Nouveau</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucune notification récente</p>
                    @endforelse
                    
                    @if($notifications->count() > 0)
                        <div class="text-center pt-3">
                            <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                Voir toutes les notifications
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Contact rapide -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="font-medium text-gray-900 flex items-center">
                        <i class="fas fa-phone mr-2 text-blue-500"></i>
                        Contact rapide
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $commercialPrincipal = $mes_chantiers->first()?->commercial;
                    @endphp
                    @if($commercialPrincipal)
                        <!-- Commercial principal -->
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-blue-500 text-white rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-xl font-semibold">{{ strtoupper(substr($commercialPrincipal->name, 0, 2)) }}</span>
                            </div>
                            <h4 class="font-medium text-gray-900">{{ $commercialPrincipal->name }}</h4>
                            <p class="text-gray-600 text-sm">Votre commercial</p>
                        </div>
                        
                        <div class="space-y-3">
                            @if($commercialPrincipal->telephone)
                                <a href="tel:{{ $commercialPrincipal->telephone }}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-phone mr-2"></i>
                                    {{ $commercialPrincipal->telephone }}
                                </a>
                            @endif
                            
                            <a href="{{ route('messages.create') }}?recipient_id={{ $commercialPrincipal->id }}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-envelope mr-2"></i>
                                Envoyer un message
                            </a>
                            
                            <a href="{{ route('messages.create') }}?subject=Nouveau projet" class="w-full inline-flex items-center justify-center px-4 py-3 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Nouveau projet
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 mb-4">Aucun commercial assigné</p>
                            <a href="{{ route('messages.create') }}?subject=Support général" class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-comments mr-2"></i>
                                Support général
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Statistiques -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="font-medium text-gray-900 flex items-center">
                        <i class="fas fa-chart-pie mr-2 text-blue-500"></i>
                        Mes statistiques
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $mes_chantiers->count() }}</div>
                            <div class="text-sm text-gray-600">Total chantiers</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">{{ $mes_chantiers->where('statut', 'termine')->count() }}</div>
                            <div class="text-sm text-gray-600">Terminés</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ $mes_chantiers->where('statut', 'en_cours')->count() }}</div>
                            <div class="text-sm text-gray-600">En cours</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($mes_chantiers->avg('avancement_global') ?? 0, 0) }}%</div>
                            <div class="text-sm text-gray-600">Avancement moyen</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Fonction pour évaluer un projet
function evaluerProjet(chantierId) {
    // Utiliser SweetAlert2 si disponible, sinon prompt natif
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Évaluer ce projet',
            html: `
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Note sur 5</label>
                    <select id="note" class="block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Choisissez une note</option>
                        <option value="5">⭐⭐⭐⭐⭐ Excellent (5/5)</option>
                        <option value="4">⭐⭐⭐⭐ Très bien (4/5)</option>
                        <option value="3">⭐⭐⭐ Bien (3/5)</option>
                        <option value="2">⭐⭐ Passable (2/5)</option>
                        <option value="1">⭐ Insuffisant (1/5)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire (optionnel)</label>
                    <textarea id="commentaire" class="block w-full rounded-md border-gray-300 shadow-sm" rows="3" placeholder="Partagez votre expérience..."></textarea>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Envoyer l\'évaluation',
            cancelButtonText: 'Annuler',
            preConfirm: () => {
                const note = document.getElementById('note').value;
                const commentaire = document.getElementById('commentaire').value;
                
                if (!note) {
                    Swal.showValidationMessage('Veuillez sélectionner une note');
                    return false;
                }
                
                return { note: note, commentaire: commentaire };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Envoyer l'évaluation au serveur
                fetch(`/api/chantiers/${chantierId}/evaluation`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(result.value)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Merci !', 'Votre évaluation a été enregistrée.', 'success');
                    } else {
                        Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    Swal.fire('Merci !', 'Votre évaluation a été prise en compte.', 'success');
                });
            }
        });
    } else {
        // Fallback avec prompt natif
        const note = prompt('Donnez une note de 1 à 5 pour ce projet:');
        if (note && note >= 1 && note <= 5) {
            // Envoyer via formulaire
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/chantiers/${chantierId}/evaluation`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="note" value="${note}">
                <input type="hidden" name="type" value="experience_globale">
            `;
            document.body.appendChild(form);
            form.submit();
        } else if (note) {
            alert('Veuillez entrer une note entre 1 et 5');
        }
    }
}

// Animation des cartes au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Animation progressive des cartes
    const cards = document.querySelectorAll('.lg\\:col-span-2 > div');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease-out';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150);
    });
    
    // Animation des statistiques
    const stats = document.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-4 > div');
    stats.forEach((stat, index) => {
        const number = stat.querySelector('.text-3xl');
        if (number) {
            const finalValue = parseInt(number.textContent);
            let currentValue = 0;
            const increment = Math.ceil(finalValue / 30);
            
            const counter = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    currentValue = finalValue;
                    clearInterval(counter);
                }
                number.textContent = currentValue + (number.textContent.includes('%') ? '%' : '');
            }, 50);
        }
    });
});

// Fonction pour rafraîchir les données
function rafraichirDonnees() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Actualisation...',
            text: 'Mise à jour des données en cours',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        setTimeout(() => {
            Swal.close();
            window.location.reload();
        }, 2000);
    } else {
        window.location.reload();
    }
}

// Gestion des erreurs de réseau
window.addEventListener('online', function() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Connexion rétablie',
            text: 'Vous êtes de nouveau en ligne',
            timer: 3000,
            showConfirmButton: false
        });
    }
});

window.addEventListener('offline', function() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'Connexion perdue',
            text: 'Vérifiez votre connexion internet',
            timer: 3000,
            showConfirmButton: false
        });
    }
});

// Auto-refresh des notifications (toutes les 5 minutes)
setInterval(function() {
    fetch('/api/notifications/count')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.bg-blue-500.text-white.text-xs.font-bold.rounded-full');
            if (badge && data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'flex';
            } else if (badge && data.count === 0) {
                badge.style.display = 'none';
            }
        })
        .catch(error => console.log('Erreur lors de la vérification des notifications:', error));
}, 300000); // 5 minutes

// Raccourcis clavier
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + R pour rafraîchir
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        rafraichirDonnees();
    }
    
    // Échap pour fermer les modales
    if (e.key === 'Escape') {
        // Fermer SweetAlert2 si ouvert
        if (typeof Swal !== 'undefined' && Swal.isVisible()) {
            Swal.close();
        }
    }
});

// Amélioration de l'accessibilité
document.querySelectorAll('a, button').forEach(element => {
    if (!element.getAttribute('aria-label') && element.textContent.trim()) {
        element.setAttribute('aria-label', element.textContent.trim());
    }
});

console.log('Dashboard Client Laravel chargé avec succès');
</script>
@endsection

@push('styles')
<style>
/* Styles spécifiques pour le dashboard client */
.hover\:shadow-md:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Animation de chargement pour les liens */
a[href]:not([href="#"]):hover {
    transition: all 0.2s ease;
}

/* Styles pour les progressions circulaires */
.progress-circle {
    transition: stroke-dasharray 0.6s ease-in-out;
}

/* Amélioration des focus pour l'accessibilité */
button:focus,
a:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Animation de pulsation pour les éléments importants */
.pulse-on-new {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .7;
    }
}

/* Responsive amélioré */
@media (max-width: 640px) {
    .lg\:col-span-2 {
        grid-column: span 1;
    }
    
    .grid-cols-2 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}

/* Print styles */
@media print {
    .no-print,
    .lg\:col-span-1:last-child {
        display: none !important;
    }
    
    .bg-gray-50,
    .bg-blue-50,
    .bg-green-50,
    .bg-yellow-50 {
        background-color: #f9fafb !important;
    }
}
</style>
@endpush