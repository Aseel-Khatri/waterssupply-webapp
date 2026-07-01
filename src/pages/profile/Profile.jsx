// ============================================================
// WATER SUPPLY ADMIN — Profile
// GET /get_user_details  (bearer token — no body)
// Email update/verify → /verify-email
// ============================================================

import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../hooks/useAuth';
import { getUserDetails } from '../../services/api';
import './Profile.css';

// ── Icons ─────────────────────────────────────────────────
const IcUser    = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>;
const IcPhone   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 6 6l.97-.97a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>;
const IcBuilding= () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>;
const IcMail    = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,12 2,6"/></svg>;
const IcCalendar= () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>;
const IcShield  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>;
const IcChevR   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="9 18 15 12 9 6"/></svg>;
const IcCheck   = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12"/></svg>;
const IcAlert   = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>;

const fmtDate = (str) => {
  if (!str) return '—';
  const d = new Date(str);
  return isNaN(d) ? str : d.toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' });
};

const COLORS = ['#3B82F6','#10B981','#8B5CF6','#F59E0B','#EF4444','#06B6D4'];
function avatarColor(n) {
  let h = 0;
  for (let i = 0; i < (n||'').length; i++) h = n.charCodeAt(i) + ((h << 5) - h);
  return COLORS[Math.abs(h) % COLORS.length];
}

// ── Info row ──────────────────────────────────────────────
function InfoRow({ icon, label, value }) {
  return (
    <div className="prof-info-row">
      <span className="prof-info-icon">{icon}</span>
      <div className="prof-info-body">
        <span className="prof-info-label">{label}</span>
        <span className="prof-info-value">{value || '—'}</span>
      </div>
    </div>
  );
}

// ── Page ──────────────────────────────────────────────────
export default function ProfilePage() {
  const navigate        = useNavigate();
  const { user: authUser } = useAuth();

  const [profile,  setProfile]  = useState(null);
  const [loading,  setLoading]  = useState(true);
  const [error,    setError]    = useState('');

  const load = () => {
    setLoading(true);
    getUserDetails()
      .then(res => setProfile(res?.data ?? res ?? null))
      .catch(e  => setError(e.message || 'Failed to load profile'))
      .finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, []);

  // Merge API profile with authUser for email verified status
  const u = profile ?? authUser;
  const emailEmpty      = !u?.email?.trim();
  const emailUnverified = !emailEmpty && !u?.isEmailVerified && authUser?.isEmailVerified === false;
  const emailVerified   = !emailEmpty && (u?.isEmailVerified || authUser?.isEmailVerified);

  const name  = u?.coName || u?.userName || u?.user_name || '';
  const color = avatarColor(name);

  if (loading) return <div className="prof-state"><div className="spinner-ring"/><span>Loading…</span></div>;
  if (error)   return <div className="prof-state error"><span>{error}</span><button className="btn-retry" onClick={load}>Retry</button></div>;

  return (
    <div className="prof-page">

      {/* ── Avatar + name ──────────────────────── */}
      <div className="prof-hero">
        <div className="prof-avatar" style={{ background: color }}>
          {(name[0] || '?').toUpperCase()}
        </div>
        <div className="prof-hero-info">
          <h1 className="prof-name">{name}</h1>
          <span className="prof-role-badge">
            {authUser?.userTypeId === 1 ? 'Admin' : 'Delivery Boy'}
          </span>
        </div>
      </div>

      {/* ── Account details ────────────────────── */}
      <div className="prof-card">
        <h2 className="prof-card-title">Account Details</h2>
        <div className="prof-info-list">
          <InfoRow icon={<IcUser />}     label="Username"      value={u?.user_name ?? u?.userName} />
          <InfoRow icon={<IcBuilding />} label="Company"       value={u?.com_name  ?? u?.coName} />
          <InfoRow icon={<IcPhone />}    label="Phone"         value={u?.phone_number ?? u?.phoneNo} />
          <InfoRow icon={<IcCalendar />} label="Expiry Date"   value={fmtDate(u?.end_datee ?? u?.expiryDate)} />
          <InfoRow icon={<IcShield />}   label="Subscription"  value={u?.is_add_free === 1 ? 'Ad-Free (Premium)' : 'Free'} />
        </div>
      </div>

      {/* ── Email section ──────────────────────── */}
      <div className="prof-card">
        <h2 className="prof-card-title">Email Address</h2>

        <button className="prof-email-row" onClick={() => navigate('/verify-email')}>
          <div className="prof-email-left">
            <span className="prof-info-icon"><IcMail /></span>
            <div className="prof-email-body">
              {emailEmpty ? (
                <>
                  <span className="prof-email-label">Email Not Entered</span>
                  <span className="prof-email-hint">Tap to add your email address</span>
                </>
              ) : (
                <>
                  <span className="prof-email-label">{u.email}</span>
                  <span className={`prof-email-hint ${emailVerified ? 'verified' : 'unverified'}`}>
                    {emailVerified
                      ? <><IcCheck /> Verified</>
                      : <><IcAlert /> Not Verified — tap to verify</>
                    }
                  </span>
                </>
              )}
            </div>
          </div>
          <IcChevR />
        </button>
      </div>

    </div>
  );
}
