// ============================================================
// WATER SUPPLY ADMIN — Dashboard
// ============================================================

import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../hooks/useAuth';
import { getDashboardStats } from '../../services/api';
import './Dashboard.css';

const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

// ── Icons ─────────────────────────────────────────────────
const IcTruck    = () => <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>;
const IcUserPlus = () => <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>;
const IcUsers    = () => <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>;
const IcUserX    = () => <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="18" y1="8" x2="23" y2="13"/><line x1="23" y1="8" x2="18" y2="13"/></svg>;
const IcBike     = () => <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="5.5" cy="17.5" r="3.5"/><circle cx="18.5" cy="17.5" r="3.5"/><path d="M15 6a1 1 0 0 0-1-1h-2"/><path d="M8.5 17.5L12 10l4 4 2.5 3.5"/><path d="M12 10l2-4"/></svg>;
const IcBag      = () => <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>;
const IcArrow    = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>;
const IcDrop     = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0 0 14 0c0-4.5-7-12-7-12z"/></svg>;

const STAT_CARDS = [
  { key: 'todayDeliveries', label: 'Today Deliveries', icon: <IcTruck />,    color: '#3B82F6', bg: '#EFF6FF', to: '/deliveries' },
  { key: 'activeCustomers', label: 'Active Customers', icon: <IcUsers />,    color: '#10B981', bg: '#ECFDF5', to: '/customers/active' },
  { key: 'inactiveCustomers', label: 'In-Active Customers', icon: <IcUserX />, color: '#F59E0B', bg: '#FFFBEB', to: '/customers/inactive' },
  { key: 'deliveryBoys',    label: 'Delivery Boys',   icon: <IcBike />,     color: '#8B5CF6', bg: '#F5F3FF', to: '/delivery-boys' },
  { key: 'counterSales',    label: 'Counter Sales',   icon: <IcBag />,      color: '#EF4444', bg: '#FEF2F2', to: '/counter-sales' },
];

const QUICK_LINKS = [
  { label: 'Register Customer', icon: <IcUserPlus />, to: '/customers/register', desc: 'Add a new customer' },
  { label: 'Add Delivery Boy',  icon: <IcBike />,     to: '/delivery-boys/add',  desc: 'Register delivery staff' },
  { label: 'Counter Sale',      icon: <IcBag />,      to: '/counter-sale',       desc: 'Record a counter sale' },
];

export default function DashboardPage() {
  const { user }  = useAuth();
  const navigate  = useNavigate();
  const todayName = days[new Date().getDay()];
  const todayDate = new Date().toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' });

  const [stats,   setStats]   = useState({});
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    getDashboardStats()
      .then(res => setStats(res?.data ?? res ?? {}))
      .catch(() => {})
      .finally(() => setLoading(false));
  }, []);

  return (
    <div className="dashboard">

      {/* ── Welcome banner ───────────────────────────── */}
      <div className="dash-banner">
        <div className="dash-banner-left">
          <div className="dash-banner-icon"><IcDrop /></div>
          <div>
            <h1 className="dash-banner-title">
              Welcome back, <span>{user?.coName || user?.userName}</span>
            </h1>
            <p className="dash-banner-sub">Order Day {todayName} · {todayDate}</p>
          </div>
        </div>
      </div>

      {/* ── Stat cards ───────────────────────────────── */}
      <div className="dash-stats-grid">
        {STAT_CARDS.map(card => (
          <button
            key={card.key}
            className="stat-card"
            style={{ '--stat-color': card.color, '--stat-bg': card.bg }}
            onClick={() => navigate(card.to)}
          >
            <div className="stat-card-icon">{card.icon}</div>
            <div className="stat-card-body">
              <div className="stat-card-value">
                {loading ? <span className="stat-skeleton" /> : (stats[card.key] ?? '—')}
              </div>
              <div className="stat-card-label">{card.label}</div>
            </div>
            <div className="stat-card-arrow"><IcArrow /></div>
          </button>
        ))}
      </div>

      {/* ── Quick actions ────────────────────────────── */}
      <div className="dash-section">
        <h2 className="dash-section-title">Quick Actions</h2>
        <div className="dash-quick-grid">
          {QUICK_LINKS.map(link => (
            <button
              key={link.to}
              className="quick-card"
              onClick={() => navigate(link.to)}
            >
              <div className="quick-card-icon">{link.icon}</div>
              <div className="quick-card-body">
                <span className="quick-card-label">{link.label}</span>
                <span className="quick-card-desc">{link.desc}</span>
              </div>
              <IcArrow />
            </button>
          ))}
        </div>
      </div>

    </div>
  );
}
