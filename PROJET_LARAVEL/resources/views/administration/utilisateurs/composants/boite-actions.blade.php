{{-- Composant des actions contextuelles pour chaque ligne utilisateur --}}
@php
    $estMoiMeme = ($user->id === Auth::id());
@endphp

<div class="flex items-center justify-end gap-2">
    {{-- Edit User Details Dropdown Modal --}}
    <details class="relative inline-block text-left">
        <summary class="cursor-pointer px-2.5 py-1 rounded text-xs font-bold uppercase tracking-wider bg-gray-100 hover:bg-gray-200 text-gray-700 transition list-none border border-gray-300">
            Edit
        </summary>
        <div class="absolute right-0 mt-2 w-80 sm:w-96 bg-white border border-gray-300 rounded-lg shadow-2xl p-5 z-40 text-left">
            <div class="flex items-center justify-between pb-3 border-b border-gray-200 mb-3">
                <span class="font-heading font-black text-xs text-gray-900 uppercase tracking-wider">
                    Edit Account #{{ $user->id }}
                </span>
                <span class="text-[10px] text-gray-400 font-mono">{{ $user->email }}</span>
            </div>

            <form action="{{ route('admin.utilisateurs.update', $user->id) }}" method="POST" class="space-y-3">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-0.5">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full bg-gray-50 border border-gray-300 rounded px-2.5 py-1.5 text-xs text-gray-900 focus:bg-white focus:outline-none">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-0.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full bg-gray-50 border border-gray-300 rounded px-2.5 py-1.5 text-xs text-gray-900 focus:bg-white focus:outline-none">
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-0.5">RBAC Role</label>
                    <select name="role" {{ $estMoiMeme ? 'disabled' : '' }}
                            class="w-full bg-gray-50 border border-gray-300 rounded px-2.5 py-1.5 text-xs text-gray-900 focus:bg-white focus:outline-none font-semibold">
                        @foreach($rolesTraductions as $cle => $libelle)
                            <option value="{{ $cle }}" {{ $user->role === $cle ? 'selected' : '' }}>{{ $libelle }}</option>
                        @endforeach
                    </select>
                    @if($estMoiMeme)
                        <input type="hidden" name="role" value="{{ $user->role }}">
                        <span class="text-[10px] text-gray-400 italic">Self-role modification disabled.</span>
                    @endif
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-0.5">Phone Number</label>
                    <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}"
                           placeholder="Optional"
                           class="w-full bg-gray-50 border border-gray-300 rounded px-2.5 py-1.5 text-xs text-gray-900 focus:bg-white focus:outline-none">
                </div>

                {{-- Active Status --}}
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-0.5">Account Status</label>
                    <select name="est_actif" {{ $estMoiMeme ? 'disabled' : '' }}
                            class="w-full bg-gray-50 border border-gray-300 rounded px-2.5 py-1.5 text-xs text-gray-900 font-semibold">
                        <option value="1" {{ $user->est_actif ? 'selected' : '' }}>Active (Login Permitted)</option>
                        <option value="0" {{ ! $user->est_actif ? 'selected' : '' }}>Deactivated (Access Blocked)</option>
                    </select>
                    @if($estMoiMeme)
                        <input type="hidden" name="est_actif" value="1">
                        <span class="text-[10px] text-gray-400 italic">Self-deactivation disabled.</span>
                    @endif
                </div>

                {{-- Optional New Password --}}
                <div class="pt-2 border-t border-gray-100">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-0.5">
                        New Password (Leave blank to keep current)
                    </label>
                    <input type="password" name="nouveau_mot_de_passe" minlength="8" placeholder="Min. 8 characters"
                           class="w-full bg-gray-50 border border-gray-300 rounded px-2.5 py-1.5 text-xs text-gray-900 focus:bg-white focus:outline-none">
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="submit" class="bg-cargo-red hover:bg-red-700 text-white py-1.5 px-4 rounded text-xs font-bold uppercase tracking-wider transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </details>

    {{-- Delete Account Button --}}
    @if(! $estMoiMeme)
        <form action="{{ route('admin.utilisateurs.destroy', $user->id) }}" method="POST"
              onsubmit="return confirm('Are you sure you want to delete account #{{ $user->id }} ({{ $user->email }})? This action will permanently disable their access.');"
              class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-2.5 py-1 rounded text-xs font-bold uppercase tracking-wider bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 transition"
                    title="Delete user account">
                Delete
            </button>
        </form>
    @else
        <span class="px-2.5 py-1 rounded text-xs font-bold uppercase tracking-wider text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed"
              title="You cannot delete your own active account">
            You
        </span>
    @endif
</div>
