@extends('administration.layouts.admin')

@section('titre', 'Security Audit Trail')

@section('contenu')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-heading font-black text-gray-900 tracking-tight">Security & Activity Audit Trail</h1>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Immutable logging for administrative actions, authentication attempts & role escalations</p>
            </div>

            @if(Auth::user()->role === 'super_admin')
                <form action="{{ route('admin.securite.purger') }}" method="POST" onsubmit="return confirm('Purge audit records older than 90 days?');">
                    @csrf
                    <button type="submit" class="bg-gray-800 hover:bg-black text-white px-3 py-2 rounded text-xs font-heading font-bold uppercase tracking-wider transition">
                        Purge Logs Older Than 90 Days
                    </button>
                </form>
            @endif
        </div>

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('admin.securite.index') }}" class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex flex-wrap gap-4 items-center">
            <select name="type_log" class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs focus:ring-1 focus:ring-cargo-red focus:outline-none">
                <option value="">All Log Categories</option>
                @foreach ($typesLogs as $type)
                    <option value="{{ $type }}" {{ $logFiltre === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                @endforeach
            </select>

            <select name="evenement" class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs focus:ring-1 focus:ring-cargo-red focus:outline-none">
                <option value="">All Events</option>
                @foreach ($evenements as $evt)
                    <option value="{{ $evt }}" {{ $evenementFiltre === $evt ? 'selected' : '' }}>{{ $evt }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-cargo-red hover:bg-cargo-redDark text-white px-4 py-2 rounded text-xs font-heading font-bold uppercase tracking-wider">
                Filter Logs
            </button>

            @if ($logFiltre || $evenementFiltre)
                <a href="{{ route('admin.securite.index') }}" class="text-xs text-gray-500 hover:text-gray-900 underline">Reset Filters</a>
            @endif
        </form>

        {{-- Audit Table --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase tracking-wider font-semibold">
                            <th class="p-3.5">Timestamp</th>
                            <th class="p-3.5">Category</th>
                            <th class="p-3.5">Event</th>
                            <th class="p-3.5">Description</th>
                            <th class="p-3.5">Operator</th>
                            <th class="p-3.5">Origin IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse ($journaux as $journal)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3.5 font-mono text-[11px] text-gray-500 whitespace-nowrap">
                                    {{ $journal->created_at->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="p-3.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                        @if($journal->log_name === 'securite') bg-red-100 text-red-800
                                        @elseif($journal->log_name === 'authentification') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $journal->log_name }}
                                    </span>
                                </td>
                                <td class="p-3.5 font-mono text-[11px] font-semibold text-gray-800">
                                    {{ $journal->event ?? '—' }}
                                </td>
                                <td class="p-3.5 font-medium text-gray-900">
                                    <div>{{ $journal->description }}</div>
                                    @if (!empty($journal->properties))
                                        <details class="text-[10px] text-gray-500 mt-1">
                                            <summary class="cursor-pointer text-cargo-red hover:underline">View technical payload</summary>
                                            <pre class="bg-gray-900 text-gray-100 p-2 rounded mt-1 font-mono text-[10px] overflow-x-auto">{{ json_encode($journal->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </details>
                                    @endif
                                </td>
                                <td class="p-3.5">
                                    <span class="font-semibold text-gray-900">{{ $journal->auteur->name ?? 'System' }}</span>
                                    @if($journal->auteur)
                                        <span class="block text-[10px] text-gray-500 font-mono">{{ $journal->auteur->email }}</span>
                                    @endif
                                </td>
                                <td class="p-3.5 font-mono text-[11px] text-gray-600">
                                    {{ $journal->properties['ip'] ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500">No audit logs matching this search criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($journaux->hasPages())
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    {{ $journaux->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
