// ============================================================
// WATER SUPPLY ADMIN — Topbar
// Shows expiry date, today's date, company name
// ============================================================

import { useAuth } from '../../hooks/useAuth';
import './Topbar.css';

export default function Topbar({ onMenuClick }) {
  const { user } = useAuth();

  const today = new Date().toLocaleDateString('en-GB', {
    weekday: 'short', day: '2-digit', month: 'short', year: 'numeric',
  });

  const expiry = user?.expiryDate
    ? new Date(user.expiryDate).toLocaleDateString('en-GB', {
        day: '2-digit', month: 'short', year: 'numeric',
      })
    : null;

  const isExpired = user?.expiryDate && new Date(user.expiryDate) < new Date();

  return (
    <header className="topbar">
      {/* Mobile menu toggle */}
      <button className="topbar-menu-btn" onClick={onMenuClick} aria-label="Open menu">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
      </button>

      <div className="topbar-meta">
        {expiry && (
          <span className={`topbar-expiry ${isExpired ? 'expired' : ''}`}>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            {isExpired ? 'Expired' : 'Expires'}: {expiry}
          </span>
        )}
        <span className="topbar-date">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          {today}
        </span>
      </div>
    </header>
  );
}
