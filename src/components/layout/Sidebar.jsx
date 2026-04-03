// ============================================================
// WATER SUPPLY ADMIN — Sidebar
// Admin sees all nav items; Delivery Boy sees only Deliveries
// ============================================================

import { NavLink, useNavigate } from 'react-router-dom';
import { useAuth } from '../../hooks/useAuth';
import { logout } from '../../services/api';
import './Sidebar.css';

// ── Icons ─────────────────────────────────────────────────
const IcDashboard   = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>;
const IcDelivery    = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>;
const IcCustomers   = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>;
const IcUserPlus    = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>;
const IcUserCheck   = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/></svg>;
const IcUserX       = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="18" y1="8" x2="23" y2="13"/><line x1="23" y1="8" x2="18" y2="13"/></svg>;
const IcBike        = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="5.5" cy="17.5" r="3.5"/><circle cx="18.5" cy="17.5" r="3.5"/><path d="M15 6a1 1 0 0 0-1-1h-2"/><path d="M8.5 17.5L12 10l4 4 2.5 3.5"/><path d="M12 10l2-4"/></svg>;
const IcShoppingBag = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>;
const IcReceipt     = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16l3-2 2 2 2-2 2 2 2-2 3 2V4a2 2 0 0 0-2-2z"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="16" y2="14"/></svg>;
const IcWallet      = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4z"/></svg>;
const IcChevron     = ({open}) => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" style={{transform: open ? 'rotate(180deg)' : 'none', transition:'transform 0.2s'}}><polyline points="6 9 12 15 18 9"/></svg>;
const IcDrop        = () => <svg width="28" height="28" viewBox="0 0 24 24" fill="rgba(255,255,255,0.9)"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0 0 14 0c0-4.5-7-12-7-12z"/></svg>;

import { useState } from 'react';

// Nav structure — only shown to admin (userTypeId === 1)
const ADMIN_NAV = [
  { label: 'Dashboard',         icon: <IcDashboard />,   to: '/dashboard' },
  { label: 'Deliveries',        icon: <IcDelivery />,    to: '/deliveries' },
  {
    label: 'Customers',
    icon: <IcCustomers />,
    children: [
      { label: 'Register Customer', icon: <IcUserPlus />,  to: '/customers/register' },
      { label: 'Active',            icon: <IcUserCheck />, to: '/customers/active' },
      { label: 'In-Active',         icon: <IcUserX />,     to: '/customers/inactive' },
    ],
  },
  { label: 'Add Delivery Boy',  icon: <IcBike />,        to: '/delivery-boys/add' },
  { label: 'All Delivery Boys', icon: <IcBike />,        to: '/delivery-boys' },
  { label: 'Counter Sale',      icon: <IcShoppingBag />, to: '/counter-sale' },
  { label: 'All Counter Sales', icon: <IcReceipt />,     to: '/counter-sales' },
  { label: 'Expenses',          icon: <IcWallet />,      to: '/expenses' },
];

const DELIVERY_BOY_NAV = [
  { label: 'Deliveries', icon: <IcDelivery />, to: '/deliveries' },
];

export default function Sidebar({ collapsed, onCollapse, mobileOpen }) {
  const { user, signOut } = useAuth();
  const navigate           = useNavigate();
  const [openGroups, setOpenGroups] = useState({ Customers: true });

  const isAdmin  = user?.userTypeId === 1;
  const navItems = isAdmin ? ADMIN_NAV : DELIVERY_BOY_NAV;

  const toggleGroup = (label) =>
    setOpenGroups(prev => ({ ...prev, [label]: !prev[label] }));

  const handleLogout = async () => {
    try { await logout(); } catch {}
    signOut();
    navigate('/login');
  };

  return (
    <aside className={`sidebar ${collapsed ? 'sidebar--collapsed' : ''} ${mobileOpen ? 'mobile-open' : ''}`}>

      {/* ── Brand ───────────────────────────────── */}
      <div className="sidebar-brand">
        <div className="sidebar-brand-icon"><IcDrop /></div>
        {!collapsed && (
          <div className="sidebar-brand-name">
            Water Supply
            <span>Management</span>
          </div>
        )}
        <button className="sidebar-collapse-btn" onClick={onCollapse} aria-label="Toggle sidebar">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
            {collapsed
              ? <><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></>
              : <><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></>
            }
          </svg>
        </button>
      </div>

      {/* ── User chip ───────────────────────────── */}
      {!collapsed && (
        <div className="sidebar-user">
          <div className="sidebar-user-avatar">
            {(user?.coName || user?.userName || 'A')[0].toUpperCase()}
          </div>
          <div className="sidebar-user-info">
            <span className="sidebar-user-name">{user?.coName || user?.userName}</span>
            <span className="sidebar-user-role">{isAdmin ? 'Admin' : 'Delivery Boy'}</span>
          </div>
        </div>
      )}

      {/* ── Nav ─────────────────────────────────── */}
      <nav className="sidebar-nav">
        {navItems.map((item) => {
          if (item.children) {
            const open = openGroups[item.label];
            return (
              <div key={item.label} className="sidebar-group">
                <button
                  className="sidebar-nav-item sidebar-group-toggle"
                  onClick={() => toggleGroup(item.label)}
                >
                  <span className="sidebar-nav-icon">{item.icon}</span>
                  {!collapsed && (
                    <>
                      <span className="sidebar-nav-label">{item.label}</span>
                      <span className="sidebar-group-chevron"><IcChevron open={open} /></span>
                    </>
                  )}
                </button>
                {open && !collapsed && (
                  <div className="sidebar-group-children">
                    {item.children.map(child => (
                      <NavLink
                        key={child.to}
                        to={child.to}
                        className={({ isActive }) =>
                          `sidebar-nav-item sidebar-child-item ${isActive ? 'active' : ''}`
                        }
                      >
                        <span className="sidebar-nav-icon">{child.icon}</span>
                        <span className="sidebar-nav-label">{child.label}</span>
                      </NavLink>
                    ))}
                  </div>
                )}
              </div>
            );
          }

          return (
            <NavLink
              key={item.to}
              to={item.to}
              className={({ isActive }) =>
                `sidebar-nav-item ${isActive ? 'active' : ''}`
              }
            >
              <span className="sidebar-nav-icon">{item.icon}</span>
              {!collapsed && <span className="sidebar-nav-label">{item.label}</span>}
            </NavLink>
          );
        })}
      </nav>

      {/* ── Logout ──────────────────────────────── */}
      <div className="sidebar-footer">
        <button className="sidebar-logout-btn" onClick={handleLogout}>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          {!collapsed && <span>Logout</span>}
        </button>
      </div>

    </aside>
  );
}
