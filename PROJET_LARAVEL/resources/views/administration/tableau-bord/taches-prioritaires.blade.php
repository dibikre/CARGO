{{-- Actions rapides & Tâches prioritaires (Remplace la rangée de doublons - Point 4) --}}
<div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Tâches Prioritaires & Opérations Récentes</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
        {{-- Tâche 1 : Corridors --}}
        <div class="p-3 rounded border {{ $corridorsPerturbes->count() > 0 ? 'bg-amber-50 border-amber-200' : 'bg-gray-50 border-gray-200' }} flex flex-col justify-between">
            <div>
                <div class="font-bold text-gray-900">Surveillance des Corridors</div>
                <div class="text-gray-600 mt-1">
                    @if($corridorsPerturbes->count() > 0)
                        <strong class="text-amber-800">{{ $corridorsPerturbes->count() }} flux sous perturbation</strong>
                    @else
                        <span class="text-emerald-700 font-medium">Flux réguliers ({{ $totalCorridors }} surveillés)</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('admin.corridors.index') }}" class="text-cargo-red font-semibold hover:underline mt-2 inline-block">Mettre à jour &rarr;</a>
        </div>

        {{-- Tâche 2 : Audience & Newsletter --}}
        <div class="p-3 rounded border bg-gray-50 border-gray-200 flex flex-col justify-between">
            <div>
                <div class="font-bold text-gray-900">Audience Newsletter</div>
                <div class="text-gray-600 mt-1">
                    <strong>{{ $totalNewsletter }}</strong> professionnels inscrits
                </div>
            </div>
            <a href="{{ route('admin.newsletter.index') }}" class="text-cargo-red font-semibold hover:underline mt-2 inline-block">Voir les abonnés &rarr;</a>
        </div>

        {{-- Tâche 3 : Matières Premières --}}
        <div class="p-3 rounded border bg-gray-50 border-gray-200 flex flex-col justify-between">
            <div>
                <div class="font-bold text-gray-900">Matières Premières</div>
                <div class="text-gray-600 mt-1">
                    <strong>{{ $totalMatieres }}</strong> commodités en cours de cotation
                </div>
            </div>
            <a href="{{ route('admin.matieres-premieres.index') }}" class="text-cargo-red font-semibold hover:underline mt-2 inline-block">Actualiser les cours &rarr;</a>
        </div>

        {{-- Tâche 4 : Accès et Monétisation --}}
        <div class="p-3 rounded border bg-gray-50 border-gray-200 flex flex-col justify-between">
            <div>
                <div class="font-bold text-gray-900">Statut du Paywall</div>
                <div class="text-gray-600 mt-1">
                    @if($abonnementsActifs)
                        <span class="text-emerald-700 font-semibold">Formules PRO actives</span>
                    @else
                        <span class="text-teal-700 font-semibold">Accès libre débloqué à 100%</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('admin.abonnements.index') }}" class="text-cargo-red font-semibold hover:underline mt-2 inline-block">Régler l'accès &rarr;</a>
        </div>
    </div>
</div>
