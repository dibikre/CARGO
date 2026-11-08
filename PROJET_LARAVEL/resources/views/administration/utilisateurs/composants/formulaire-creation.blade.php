{{-- Composant modal/tiroir de creation d'un utilisateur --}}
<details id="modal-creation-utilisateur" class="group bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-6">
    <summary class="px-5 py-3.5 bg-gray-50 hover:bg-gray-100 cursor-pointer flex items-center justify-between transition list-none">
        <div class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-cargo-red text-white flex items-center justify-center text-xs font-bold font-mono">+</span>
            <span class="font-heading font-black text-sm text-gray-900 uppercase tracking-wider">Add New User Account</span>
        </div>
        <span class="text-xs font-semibold text-cargo-red group-open:rotate-180 transition-transform">
            &#9660;
        </span>
    </summary>

    <form method="POST" action="{{ route('admin.utilisateurs.store') }}" class="p-6 bg-white border-t border-gray-200 space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- Name --}}
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    Full Name <span class="text-cargo-red">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       placeholder="e.g. Sarah Jenkins"
                       class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-cargo-red focus:outline-none">
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    Email Address <span class="text-cargo-red">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       placeholder="e.g. sarah.jenkins@cargo-logistics.com"
                       class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-cargo-red focus:outline-none">
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    Initial Password <span class="text-cargo-red">*</span>
                </label>
                <input type="password" id="password" name="password" required minlength="8"
                       placeholder="Min. 8 characters"
                       class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-cargo-red focus:outline-none">
            </div>

            {{-- System Role --}}
            <div>
                <label for="role" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    RBAC Role <span class="text-cargo-red">*</span>
                </label>
                <select id="role" name="role" required
                        class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-cargo-red focus:outline-none font-semibold">
                    <option value="lecteur" {{ old('role') === 'lecteur' ? 'selected' : '' }}>Reader (Public Visitor)</option>
                    <option value="abonne" {{ old('role') === 'abonne' ? 'selected' : '' }}>PRO Subscriber (Paid Access)</option>
                    <option value="redacteur" {{ old('role') === 'redacteur' ? 'selected' : '' }}>Writer (Draft Submissions)</option>
                    <option value="journaliste" {{ old('role') === 'journaliste' ? 'selected' : '' }}>Journalist (Staff Reporter)</option>
                    <option value="redacteur_en_chef" {{ old('role') === 'redacteur_en_chef' ? 'selected' : '' }}>Editor-in-Chief (Publishing Approval)</option>
                    <option value="administrateur" {{ old('role') === 'administrateur' ? 'selected' : '' }}>Administrator (System Operations)</option>
                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Administrator (Full Privileges)</option>
                </select>
            </div>

            {{-- Telephone --}}
            <div>
                <label for="telephone" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    Phone Number (Optional)
                </label>
                <input type="text" id="telephone" name="telephone" value="{{ old('telephone') }}"
                       placeholder="e.g. +33 1 42 68 00 00"
                       class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-cargo-red focus:outline-none">
            </div>

            {{-- Active Status --}}
            <div class="flex items-center gap-2 pt-6">
                <input type="hidden" name="est_actif" value="0">
                <input type="checkbox" id="est_actif" name="est_actif" value="1" {{ old('est_actif', '1') === '1' ? 'checked' : '' }}
                       class="rounded border-gray-300 text-cargo-red focus:ring-cargo-red">
                <label for="est_actif" class="text-xs font-bold text-gray-800">
                    Activate account immediately
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
            <button type="submit" class="bg-cargo-red hover:bg-red-700 text-white font-heading font-bold text-xs uppercase tracking-wider px-5 py-2 rounded shadow transition">
                Create Account
            </button>
        </div>
    </form>
</details>
