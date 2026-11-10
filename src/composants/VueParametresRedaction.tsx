import React, { useState } from 'react';
import { 
  User, Mail, Phone, Lock, Key, Trash2, AlertTriangle, CheckCircle, 
  ShieldCheck, ShieldAlert, Sparkles, RefreshCw, X
} from 'lucide-react';

interface Props {
  surNaviguer: (vue: any) => void;
}

export default function VueParametresRedaction({ surNaviguer }: Props) {
  // Profil de rédaction simulé
  const [pseudo, setPseudo] = useState('Claire Delamare');
  const [email, setEmail] = useState('claire.delamare@cargo-press.com');
  const [telephone, setTelephone] = useState('+33 1 42 68 00 00');
  const [bio, setBio] = useState('Senior correspondent covering multimodal logistics, TEN-T rail corridors, and smart container ports.');
  
  // Mots de passe
  const [mdpActuel, setMdpActuel] = useState('');
  const [nouveauMdp, setNouveauMdp] = useState('');
  const [confirmationMdp, setConfirmationMdp] = useState('');
  
  // Permissions administratives : suppression de compte activée ou désactivée par l'admin
  const [suppressionCompteActiveParAdmin, setSuppressionCompteActiveParAdmin] = useState(false);
  const [modaleSuppressionOuverte, setModaleSuppressionOuverte] = useState(false);
  const [mdpConfirmationSuppression, setMdpConfirmationSuppression] = useState('');

  // Messages de retour
  const [notification, setNotification] = useState<{ type: 'succes' | 'erreur'; message: string } | null>(null);

  const afficherNotification = (type: 'succes' | 'erreur', message: string) => {
    setNotification({ type, message });
    setTimeout(() => setNotification(null), 5000);
  };

  const handleSauvegarderProfil = (e: React.FormEvent) => {
    e.preventDefault();
    if (!pseudo.trim() || !email.trim()) {
      afficherNotification('erreur', 'Journalist handle and official email are required.');
      return;
    }
    afficherNotification('succes', `Profile updated successfully! Byline set to "${pseudo}".`);
  };

  const handleChangerMotDePasse = (e: React.FormEvent) => {
    e.preventDefault();
    if (!mdpActuel) {
      afficherNotification('erreur', 'Please provide your current password.');
      return;
    }
    if (nouveauMdp.length < 8) {
      afficherNotification('erreur', 'The new password must be at least 8 characters long.');
      return;
    }
    if (nouveauMdp !== confirmationMdp) {
      afficherNotification('erreur', 'New password and confirmation do not match.');
      return;
    }
    setMdpActuel('');
    setNouveauMdp('');
    setConfirmationMdp('');
    afficherNotification('succes', 'Your password has been changed successfully.');
  };

  const handleConfirmerSuppressionCompte = (e: React.FormEvent) => {
    e.preventDefault();
    if (!mdpConfirmationSuppression) {
      afficherNotification('erreur', 'Please type your password to confirm deletion.');
      return;
    }
    setModaleSuppressionOuverte(false);
    setMdpConfirmationSuppression('');
    afficherNotification('succes', 'Your account has been deleted permanently. Redirecting to login...');
  };

  return (
    <div className="space-y-6 pb-12">
      {/* Notifications */}
      {notification && (
        <div className={`p-4 rounded-xl border flex items-center justify-between text-xs font-semibold shadow-sm transition-all ${
          notification.type === 'succes' 
            ? 'bg-emerald-50 border-emerald-200 text-emerald-900' 
            : 'bg-red-50 border-red-200 text-red-900'
        }`}>
          <div className="flex items-center gap-2.5">
            {notification.type === 'succes' ? (
              <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0" />
            ) : (
              <AlertTriangle className="w-4 h-4 text-cargo-red shrink-0" />
            )}
            <span>{notification.message}</span>
          </div>
          <button onClick={() => setNotification(null)} className="text-gray-400 hover:text-gray-700">
            <X className="w-4 h-4" />
          </button>
        </div>
      )}

      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
          <div className="flex items-center gap-2 mb-1">
            <span className="w-2.5 h-2.5 rounded-full bg-[#D4201A]"></span>
            <span className="text-xs font-bold uppercase tracking-wider text-gray-500">Newsroom Desk &bull; Editorial Profile</span>
          </div>
          <h1 className="text-2xl font-black text-gray-900 tracking-tight">Account & Security Settings</h1>
          <p className="text-xs text-gray-500">Manage your journalist handle, email credentials, password, and security preferences.</p>
        </div>

        {/* Admin Policy Switch Simulator */}
        <div className="bg-white p-2.5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-3">
          <div className="text-right">
            <div className="text-[10px] font-bold uppercase tracking-wider text-gray-400">Admin Permission Simulation</div>
            <div className="text-xs font-bold text-gray-800">
              {suppressionCompteActiveParAdmin ? 'Self-Deletion: ACTIVE' : 'Self-Deletion: DISABLED'}
            </div>
          </div>
          <button
            type="button"
            onClick={() => setSuppressionCompteActiveParAdmin(!suppressionCompteActiveParAdmin)}
            style={{ backgroundColor: suppressionCompteActiveParAdmin ? '#B3140F' : '#14151C' }}
            className="px-3 py-1.5 rounded-lg text-xs font-bold text-white uppercase tracking-wider transition hover:opacity-90"
            title="Toggle admin permission to test account deletion visibility"
          >
            {suppressionCompteActiveParAdmin ? 'Disable (Admin)' : 'Enable (Admin)'}
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Left Column (2 Cols): Profile & Password Forms */}
        <div className="lg:col-span-2 space-y-6">
          {/* 1. Identity & Profile Form */}
          <div className="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div className="px-6 py-4 bg-gray-50/75 border-b border-gray-200 flex items-center justify-between">
              <div className="flex items-center gap-3">
                <div className="w-8 h-8 rounded-lg bg-gray-900 text-white flex items-center justify-center">
                  <User className="w-4 h-4" />
                </div>
                <div>
                  <h3 className="font-black text-sm uppercase tracking-wider text-gray-900">Editorial Identity & Profile</h3>
                  <p className="text-xs text-gray-500">Update your public journalist handle (pseudo) and official email address</p>
                </div>
              </div>
              <span className="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700 rounded border border-gray-200">
                Staff Writer
              </span>
            </div>

            <form onSubmit={handleSauvegarderProfil} className="p-6 space-y-5">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                  <label className="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Journalist Handle / Pseudo *
                  </label>
                  <input
                    type="text"
                    value={pseudo}
                    onChange={e => setPseudo(e.target.value)}
                    required
                    placeholder="e.g. Claire Delamare"
                    className="w-full text-sm px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                  />
                  <p className="text-[11px] text-gray-400 mt-1">Appears as your public byline on logistics dispatch articles.</p>
                </div>

                <div>
                  <label className="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Official Email Address *
                  </label>
                  <input
                    type="email"
                    value={email}
                    onChange={e => setEmail(e.target.value)}
                    required
                    placeholder="e.g. claire.delamare@cargo-press.com"
                    className="w-full text-sm px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                  />
                  <p className="text-[11px] text-gray-400 mt-1">Used for login, desk alerts, and article moderation notes.</p>
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                <div>
                  <label className="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Desk Direct Phone
                  </label>
                  <input
                    type="text"
                    value={telephone}
                    onChange={e => setTelephone(e.target.value)}
                    placeholder="e.g. +33 1 42 68 00 00"
                    className="w-full text-sm px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                  />
                </div>
                <div>
                  <label className="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Editorial Role
                  </label>
                  <div className="px-3.5 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-sm text-gray-700 font-mono flex items-center justify-between">
                    <span className="font-bold uppercase text-xs">Staff Journalist</span>
                    <span className="text-[10px] text-gray-500 uppercase">Managed by Admin</span>
                  </div>
                </div>
              </div>

              <div>
                <label className="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                  Editorial Bio / Specialization
                </label>
                <textarea
                  rows={3}
                  value={bio}
                  onChange={e => setBio(e.target.value)}
                  className="w-full text-sm px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                />
              </div>

              <div className="flex items-center justify-end pt-3 border-t border-gray-100">
                <button
                  type="submit"
                  style={{ backgroundColor: '#D4201A', color: '#ffffff' }}
                  className="px-5 py-2.5 text-xs font-bold uppercase tracking-wider bg-[#D4201A] hover:bg-[#B3140F] rounded-lg shadow-sm transition flex items-center gap-2 cursor-pointer"
                >
                  <CheckCircle className="w-4 h-4 text-white" />
                  <span>Save Profile Changes</span>
                </button>
              </div>
            </form>
          </div>

          {/* 2. Password & Security Form */}
          <div className="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div className="px-6 py-4 bg-gray-50/75 border-b border-gray-200 flex items-center justify-between">
              <div className="flex items-center gap-3">
                <div className="w-8 h-8 rounded-lg bg-gray-900 text-white flex items-center justify-center">
                  <Lock className="w-4 h-4" />
                </div>
                <div>
                  <h3 className="font-black text-sm uppercase tracking-wider text-gray-900">Security & Password</h3>
                  <p className="text-xs text-gray-500">Ensure your account is protected by an enterprise-grade password (min. 8 characters)</p>
                </div>
              </div>
              <span className="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-800 rounded border border-emerald-200">
                BCrypt Encrypted
              </span>
            </div>

            <form onSubmit={handleChangerMotDePasse} className="p-6 space-y-5">
              <div>
                <label className="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                  Current Password *
                </label>
                <input
                  type="password"
                  value={mdpActuel}
                  onChange={e => setMdpActuel(e.target.value)}
                  required
                  placeholder="Enter your existing account password"
                  className="w-full text-sm px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                />
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-1">
                <div>
                  <label className="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    New Secure Password *
                  </label>
                  <input
                    type="password"
                    value={nouveauMdp}
                    onChange={e => setNouveauMdp(e.target.value)}
                    required
                    placeholder="Min. 8 characters"
                    className="w-full text-sm px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                  />
                </div>

                <div>
                  <label className="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Confirm New Password *
                  </label>
                  <input
                    type="password"
                    value={confirmationMdp}
                    onChange={e => setConfirmationMdp(e.target.value)}
                    required
                    placeholder="Re-type new password"
                    className="w-full text-sm px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                  />
                </div>
              </div>

              <div className="flex items-center justify-end pt-3 border-t border-gray-100">
                <button
                  type="submit"
                  style={{ backgroundColor: '#D4201A', color: '#ffffff' }}
                  className="px-5 py-2.5 text-xs font-bold uppercase tracking-wider bg-[#D4201A] hover:bg-[#B3140F] rounded-lg shadow-sm transition flex items-center gap-2 cursor-pointer"
                >
                  <Key className="w-4 h-4 text-white" />
                  <span>Update Password</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        {/* Right Column (1 Col): Overview & Conditional Delete Card */}
        <div className="space-y-6">
          {/* Account Overview */}
          <div className="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-4">
            <div className="flex items-center gap-3 pb-3 border-b border-gray-100">
              <div className="w-10 h-10 rounded-full bg-gray-900 text-white flex items-center justify-center font-black text-sm uppercase">
                {pseudo.substring(0, 2)}
              </div>
              <div className="min-w-0 flex-1">
                <div className="font-bold text-sm text-gray-900 truncate">{pseudo}</div>
                <div className="text-xs text-gray-500 truncate font-mono">{email}</div>
              </div>
            </div>

            <div className="space-y-2.5 text-xs">
              <div className="flex items-center justify-between text-gray-600">
                <span>Editorial Role</span>
                <span className="font-bold text-gray-900 uppercase font-mono">Staff Journalist</span>
              </div>
              <div className="flex items-center justify-between text-gray-600">
                <span>Account Status</span>
                <span className="font-bold text-emerald-600 uppercase flex items-center gap-1">
                  <span className="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  Active Desk
                </span>
              </div>
              <div className="flex items-center justify-between text-gray-600">
                <span>Self-Deletion Allowed</span>
                <span className={`font-bold uppercase ${suppressionCompteActiveParAdmin ? 'text-cargo-red' : 'text-gray-400'}`}>
                  {suppressionCompteActiveParAdmin ? 'Yes (Admin Enabled)' : 'No (Admin Disabled)'}
                </span>
              </div>
            </div>
          </div>

          {/* Conditional Danger Zone: Delete Account */}
          {suppressionCompteActiveParAdmin ? (
            <div className="bg-white rounded-xl border border-red-200 shadow-sm overflow-hidden animate-fadeIn">
              <div className="px-6 py-4 bg-red-50/75 border-b border-red-200 flex items-center justify-between">
                <div className="flex items-center gap-3">
                  <div className="w-8 h-8 rounded-lg bg-[#D4201A] text-white flex items-center justify-center">
                    <Trash2 className="w-4 h-4" />
                  </div>
                  <div>
                    <h3 className="font-black text-sm uppercase tracking-wider text-red-900">Danger Zone: Delete Account</h3>
                    <p className="text-xs text-red-700">Enabled by Administrator</p>
                  </div>
                </div>
                <span className="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-800 rounded border border-red-300">
                  Feature Active
                </span>
              </div>

              <div className="p-6 space-y-4">
                <div className="p-4 bg-red-50 border border-red-200 rounded-lg text-xs text-red-800 space-y-2">
                  <div className="font-bold uppercase tracking-wider flex items-center gap-2">
                    <AlertTriangle className="w-4 h-4 text-cargo-red shrink-0" />
                    <span>Permanent & Irreversible Account Action</span>
                  </div>
                  <p>
                    Deleting your account will immediately revoke all access to the CARGO Newsroom Desk.
                  </p>
                </div>

                <button
                  type="button"
                  onClick={() => setModaleSuppressionOuverte(true)}
                  style={{ backgroundColor: '#D4201A', color: '#ffffff' }}
                  className="w-full py-2.5 text-xs font-bold uppercase tracking-wider bg-[#D4201A] hover:bg-[#B3140F] rounded-lg shadow-sm transition flex items-center justify-center gap-2 cursor-pointer"
                >
                  <Trash2 className="w-4 h-4 text-white" />
                  <span>Delete Editorial Account</span>
                </button>
              </div>
            </div>
          ) : (
            <div className="bg-gray-50 rounded-xl border border-gray-200 p-5 text-center text-xs text-gray-400">
              <ShieldCheck className="w-6 h-6 text-gray-300 mx-auto mb-2" />
              <p className="font-semibold text-gray-600 mb-1">Account Deletion Protection</p>
              <p>Self-service account deletion is currently deactivated by the administrator for data preservation.</p>
            </div>
          )}
        </div>
      </div>

      {/* Confirmation Modal */}
      {modaleSuppressionOuverte && (
        <div className="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
          <div className="relative w-full max-w-md bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden">
            <div className="bg-gray-900 text-white px-6 py-4 flex items-center justify-between">
              <div className="flex items-center gap-2">
                <span className="w-2.5 h-2.5 rounded-full bg-[#D4201A]"></span>
                <h3 className="font-black text-sm uppercase tracking-wider">Confirm Account Deletion</h3>
              </div>
              <button onClick={() => setModaleSuppressionOuverte(false)} className="text-gray-400 hover:text-white">
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleConfirmerSuppressionCompte} className="p-6 space-y-4">
              <div className="p-3 bg-red-50 border border-red-200 rounded-lg text-xs text-red-900 flex items-start gap-2.5">
                <AlertTriangle className="w-5 h-5 text-cargo-red shrink-0 mt-0.5" />
                <div>
                  <strong className="block font-bold uppercase mb-0.5">Are you absolutely sure?</strong>
                  <span>Your credentials will be permanently deactivated and your session ended.</span>
                </div>
              </div>

              <div>
                <label className="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                  Enter Your Password to Confirm *
                </label>
                <input
                  type="password"
                  value={mdpConfirmationSuppression}
                  onChange={e => setMdpConfirmationSuppression(e.target.value)}
                  required
                  placeholder="Type your password to verify"
                  className="w-full text-sm px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cargo-red focus:border-cargo-red transition"
                />
              </div>

              <div className="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                <button
                  type="button"
                  onClick={() => setModaleSuppressionOuverte(false)}
                  className="px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  style={{ backgroundColor: '#D4201A', color: '#ffffff' }}
                  className="px-5 py-2 text-xs font-bold uppercase tracking-wider bg-[#D4201A] hover:bg-[#B3140F] rounded-lg shadow-sm transition flex items-center gap-2"
                >
                  <Trash2 className="w-4 h-4 text-white" />
                  <span>Permanently Delete</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
