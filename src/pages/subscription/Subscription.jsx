// ============================================================
// WATER SUPPLY ADMIN — Subscription
// GET  /get_subscription_packages
// GET  /get_support  (to find WhatsApp / phone / email contact)
// Subscription is requested via WhatsApp message (no POST API)
// ============================================================

import { useState, useEffect } from 'react';
import { useAuth } from '../../hooks/useAuth';
import { getSubscriptionPackages, getSupport } from '../../services/api';
import './Subscription.css';

// ── Icons ─────────────────────────────────────────────────
const IcCheck   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12"/></svg>;
const IcStar    = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>;
const IcClock   = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>;
const IcWA      = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>;
const IcPhone   = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 6 6l.97-.97a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>;
const IcMail    = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,12 2,6"/></svg>;
const IcShield  = () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>;

// ── Helpers ───────────────────────────────────────────────
const fmtDate = (str) => {
  if (!str) return '—';
  const d = new Date(str);
  return isNaN(d) ? str : d.toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' });
};

const addDays = (days) => {
  const d = new Date();
  d.setDate(d.getDate() + days);
  return fmtDate(d);
};

// Pick best contact from support list: WhatsApp first, then phone, then email
function pickContact(supportList) {
  if (!Array.isArray(supportList)) return null;
  const wa    = supportList.find(s => s.type === 'phone' && s.is_whatsapp);
  const phone = supportList.find(s => s.type === 'phone');
  const email = supportList.find(s => s.type === 'email');
  return wa ?? phone ?? email ?? null;
}

function buildWhatsAppUrl(contact, message) {
  const encoded = encodeURIComponent(message);
  if (!contact) return null;
  if (contact.is_whatsapp || contact.type === 'phone') {
    const num = contact.value.replace(/\D/g, '');
    return `https://wa.me/${num}?text=${encoded}`;
  }
  if (contact.type === 'email') {
    return `mailto:${contact.value}?subject=Subscription Request&body=${encoded}`;
  }
  return null;
}

// ── Active subscription banner ────────────────────────────
function ActiveBanner({ user }) {
  return (
    <div className="sub-active-banner">
      <div className="sub-active-icon"><IcStar /></div>
      <div className="sub-active-info">
        <span className="sub-active-title">Ad-Free Active</span>
        <span className="sub-active-expiry">
          <IcClock /> Expires {fmtDate(user.expiryDate)}
        </span>
      </div>
      <span className="sub-active-badge">Premium</span>
    </div>
  );
}

// ── Package card ──────────────────────────────────────────
function PackageCard({ pkg, selected, onSelect }) {
  const months   = pkg.duration_months ?? 0;
  const expiry   = addDays(months * 30);

  return (
    <button
      className={`pkg-card ${selected ? 'selected' : ''}`}
      onClick={() => onSelect(pkg.id)}
      type="button"
    >
      {selected && <span className="pkg-selected-check"><IcCheck /></span>}
      <div className="pkg-card-top">
        <span className="pkg-title">{pkg.title}</span>
        <span className="pkg-price">₨ {Number(pkg.price).toLocaleString()}</span>
      </div>
      <div className="pkg-duration">
        <IcClock />
        {months} {months === 1 ? 'Month' : 'Months'} · Expires {expiry}
      </div>
      {pkg.description && (
        <p className="pkg-desc">{pkg.description}</p>
      )}
    </button>
  );
}

// ── Main ──────────────────────────────────────────────────
export default function SubscriptionPage() {
  const { user } = useAuth();

  const [packages,    setPackages]    = useState([]);
  const [contact,     setContact]     = useState(null);
  const [selectedId,  setSelectedId]  = useState(null);
  const [loading,     setLoading]     = useState(true);
  const [error,       setError]       = useState('');

  const isSubscribed = user?.isAddFree === 1;

  useEffect(() => {
    Promise.all([getSubscriptionPackages(), getSupport()])
      .then(([pkgRes, supRes]) => {
        const pkgs = Array.isArray(pkgRes?.data) ? pkgRes.data : [];
        setPackages(pkgs);
        if (pkgs.length) setSelectedId(pkgs[0].id);
        setContact(pickContact(supRes?.data));
      })
      .catch(e => setError(e.message || 'Failed to load'))
      .finally(() => setLoading(false));
  }, []);

  const handleSubscribe = () => {
    const pkg = packages.find(p => p.id === selectedId);
    if (!pkg) return;

    const months  = pkg.duration_months ?? 0;
    const expiry  = addDays(months * 30);

    const message =
      `Subscription Request\n\n` +
      `📦 Package: ${pkg.title}\n` +
      `💰 Price: ₨${pkg.price}\n` +
      `⏱️ Duration: ${months} ${months === 1 ? 'Month' : 'Months'}\n` +
      `📅 Expires On: ${expiry}\n\n` +
      `👤 Company: ${user?.coName ?? ''}\n` +
      `📞 Phone: ${user?.phoneNo ?? ''}\n\n` +
      `${pkg.description ?? ''}`;

    const url = buildWhatsAppUrl(contact, message);
    if (url) window.open(url, '_blank', 'noopener');
  };

  // Contact icon + label for the CTA button
  const ctaIcon  = contact?.is_whatsapp ? <IcWA />
                 : contact?.type === 'phone' ? <IcPhone />
                 : <IcMail />;
  const ctaLabel = contact?.is_whatsapp ? 'Request via WhatsApp'
                 : contact?.type === 'phone' ? 'Request via Call'
                 : 'Request via Email';

  return (
    <div className="sub-page">

      {/* Header */}
      <div className="sub-header">
        <div className="sub-header-icon"><IcShield /></div>
        <div>
          <h1 className="page-title">Subscription</h1>
          <p className="page-sub">Manage your plan and remove ads</p>
        </div>
      </div>

      {/* Active subscription status */}
      {isSubscribed
        ? <ActiveBanner user={user} />
        : (
          <div className="sub-inactive-banner">
            <IcShield />
            <div>
              <span className="sub-inactive-title">No Active Subscription</span>
              <span className="sub-inactive-sub">Choose a package below to remove ads</span>
            </div>
          </div>
        )
      }

      {/* Packages */}
      {loading ? (
        <div className="sub-state"><div className="spinner-ring"/><span>Loading packages…</span></div>
      ) : error ? (
        <div className="sub-state error"><span>{error}</span></div>
      ) : packages.length === 0 ? (
        <div className="sub-state"><span>No packages available.</span></div>
      ) : (
        <>
          <div className="sub-section-title">Choose a Package</div>
          <div className="pkg-list">
            {packages.map(pkg => (
              <PackageCard
                key={pkg.id}
                pkg={pkg}
                selected={selectedId === pkg.id}
                onSelect={setSelectedId}
              />
            ))}
          </div>

          <button
            className="sub-cta-btn"
            onClick={handleSubscribe}
            disabled={!selectedId || !contact}
          >
            {ctaIcon} {ctaLabel}
          </button>

          {contact && (
            <p className="sub-contact-hint">
              You'll be connected to {contact.value}
            </p>
          )}
        </>
      )}

    </div>
  );
}
