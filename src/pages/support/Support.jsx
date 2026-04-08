// ============================================================
// WATER SUPPLY ADMIN — Support / Contact
// GET /get_support
// ============================================================

import { useState, useEffect } from 'react';
import { getSupport } from '../../services/api';
import './Support.css';

// ── Icons ─────────────────────────────────────────────────
const IcPhone    = () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 6 6l.97-.97a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>;
const IcMail     = () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,12 2,6"/></svg>;
const IcGlobe    = () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>;
const IcFacebook = () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>;
const IcExternal = () => <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>;
// WhatsApp SVG (brand icon)
const IcWhatsApp = () => (
  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
  </svg>
);

// ── Type config ───────────────────────────────────────────
const TYPE_CONFIG = {
  phone:  { icon: <IcPhone />,    color: '#3B82F6', bg: '#EFF6FF', label: 'Call' },
  email:  { icon: <IcMail />,     color: '#10B981', bg: '#ECFDF5', label: 'Email' },
  social: { icon: <IcFacebook />, color: '#1877F2', bg: '#EBF5FF', label: 'Visit' },
  web:    { icon: <IcGlobe />,    color: '#8B5CF6', bg: '#F5F3FF', label: 'Open' },
};

function getHref(item) {
  if (item.is_whatsapp) return `https://wa.me/${item.value.replace(/\D/g, '')}`;
  if (item.type === 'phone')  return `tel:${item.value}`;
  if (item.type === 'email')  return `mailto:${item.value}`;
  return item.value; // social / web — direct URL
}

// ── Support Card ──────────────────────────────────────────
function SupportCard({ item }) {
  const cfg     = TYPE_CONFIG[item.type] ?? TYPE_CONFIG.web;
  const isWA    = item.is_whatsapp;
  const href    = getHref(item);
  const isExt   = item.type === 'social' || item.type === 'web' || isWA;

  return (
    <a
      className="support-card"
      href={href}
      target={isExt ? '_blank' : undefined}
      rel={isExt ? 'noopener noreferrer' : undefined}
    >
      {/* Left icon */}
      <div
        className="support-card-icon"
        style={{ '--sc-color': isWA ? '#25D366' : cfg.color, '--sc-bg': isWA ? '#DCFCE7' : cfg.bg }}
      >
        {isWA ? <IcWhatsApp /> : cfg.icon}
      </div>

      {/* Info */}
      <div className="support-card-info">
        <span className="support-card-title">{item.title}</span>
        <span className="support-card-value">{item.value}</span>
      </div>

      {/* Badges + arrow */}
      <div className="support-card-right">
        {isWA && (
          <span className="support-badge whatsapp">
            <IcWhatsApp /> WhatsApp
          </span>
        )}
        <span className="support-action-label" style={{ color: isWA ? '#25D366' : cfg.color }}>
          {isWA ? 'Chat' : cfg.label} <IcExternal />
        </span>
      </div>
    </a>
  );
}

// ── Page ──────────────────────────────────────────────────
export default function SupportPage() {
  const [items,   setItems]   = useState([]);
  const [loading, setLoading] = useState(true);
  const [error,   setError]   = useState('');

  const load = () => {
    setLoading(true);
    getSupport()
      .then(res => setItems(Array.isArray(res?.data) ? res.data : []))
      .catch(e  => setError(e.message || 'Failed to load'))
      .finally(()=> setLoading(false));
  };

  useEffect(() => { load(); }, []);

  return (
    <div className="sup-page">

      {/* Header */}
      <div className="sup-header">
        <div className="sup-header-icon">
          <IcPhone />
        </div>
        <div>
          <h1 className="page-title">Contact & Support</h1>
          <p className="page-sub">Reach out via any of the channels below</p>
        </div>
      </div>

      {loading ? (
        <div className="sup-state"><div className="spinner-ring"/><span>Loading…</span></div>
      ) : error ? (
        <div className="sup-state error"><span>{error}</span><button className="btn-retry" onClick={load}>Retry</button></div>
      ) : items.length === 0 ? (
        <div className="sup-state"><span>No support contacts available.</span></div>
      ) : (
        <div className="sup-list">
          {items.map((item, i) => <SupportCard key={i} item={item} />)}
        </div>
      )}

    </div>
  );
}
