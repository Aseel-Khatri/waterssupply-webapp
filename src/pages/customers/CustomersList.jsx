// ============================================================
// WATER SUPPLY ADMIN — Customers List
// Active (status=0) + Inactive (status=1) tabs
// ============================================================

import { useState, useEffect, useCallback, useRef } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import { getCustomers, toggleCustomerStatus, updateDeliveryDays } from '../../services/api';
import './CustomersList.css';

// ── Icons ─────────────────────────────────────────────────
const IcSearch  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>;
const IcX       = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>;
const IcSort    = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>;
const IcSortAsc = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>;
const IcMapPin  = () => <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>;
const IcPhone   = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 6 6l.97-.97a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>;
const IcEdit    = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>;
const IcSwap    = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>;
const IcCalendar= () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>;
const IcInfo    = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>;
const IcChevL   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="15 18 9 12 15 6"/></svg>;
const IcChevR   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="9 18 15 12 9 6"/></svg>;
const IcCheck   = () => <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12"/></svg>;
const IcPlus    = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>;

const PAGE_SIZE = 25;
const DAYS = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

// ── Address parser (same logic as Flutter) ────────────────
function parseAddress(raw) {
  if (!raw) return '—';
  try {
    const parsed = typeof raw === 'string' ? JSON.parse(raw) : raw;
    if (parsed && typeof parsed === 'object' && parsed.address) return parsed.address;
  } catch {}
  return raw;
}

// ── Avatar color from name ────────────────────────────────
const AVATAR_COLORS = ['#3B82F6','#10B981','#8B5CF6','#F59E0B','#EF4444','#06B6D4','#EC4899'];
function avatarColor(name) {
  let h = 0;
  for (let i = 0; i < (name||'').length; i++) h = name.charCodeAt(i) + ((h << 5) - h);
  return AVATAR_COLORS[Math.abs(h) % AVATAR_COLORS.length];
}

// ── Delivery Days Dialog ──────────────────────────────────
function DeliveryDaysDialog({ customer, onClose, onSave }) {
  const existing = customer.days_of_giving
    ? (Array.isArray(customer.days_of_giving)
        ? customer.days_of_giving
        : customer.days_of_giving.split(',').map(d => d.trim()))
    : [];

  const [selected, setSelected] = useState(existing);
  const [saving,   setSaving]   = useState(false);
  const [error,    setError]    = useState('');

  const toggle = (day) =>
    setSelected(p => p.includes(day) ? p.filter(d => d !== day) : [...p, day]);

  const handleSave = async () => {
    setSaving(true);
    setError('');
    try {
      await updateDeliveryDays(String(customer.id), selected);
      onSave(selected);
      onClose();
    } catch (e) {
      setError(e.message || 'Failed to update days');
    } finally {
      setSaving(false);
    }
  };

  return (
    <div className="modal-backdrop" onClick={onClose}>
      <div className="modal-box" onClick={e => e.stopPropagation()}>
        <div className="modal-header">
          <h3 className="modal-title">Delivery Days</h3>
          <p className="modal-sub">{customer.first_name} {customer.last_name}</p>
        </div>
        <div className="days-grid-modal">
          {DAYS.map(day => (
            <button
              key={day}
              type="button"
              className={`day-chip-modal ${selected.includes(day) ? 'active' : ''}`}
              onClick={() => toggle(day)}
            >
              {selected.includes(day) && <span className="day-check"><IcCheck /></span>}
              {day}
            </button>
          ))}
        </div>
        {error && <p className="modal-error">{error}</p>}
        <div className="modal-footer">
          <button className="btn-ghost" onClick={onClose}>Cancel</button>
          <button className="btn-primary-sm" onClick={handleSave} disabled={saving}>
            {saving ? <><span className="spinner-xs"/>Saving…</> : 'Save'}
          </button>
        </div>
      </div>
    </div>
  );
}

// ── Confirm Dialog ────────────────────────────────────────
function ConfirmDialog({ message, onConfirm, onCancel, loading }) {
  return (
    <div className="modal-backdrop" onClick={onCancel}>
      <div className="modal-box confirm-box" onClick={e => e.stopPropagation()}>
        <p className="confirm-msg">{message}</p>
        <div className="modal-footer">
          <button className="btn-ghost" onClick={onCancel}>Cancel</button>
          <button className="btn-danger-sm" onClick={onConfirm} disabled={loading}>
            {loading ? <><span className="spinner-xs"/>…</> : 'Confirm'}
          </button>
        </div>
      </div>
    </div>
  );
}

// ── Customer Card ─────────────────────────────────────────
function CustomerCard({ customer, status, onEdit, onToggleStatus, onDaysDialog }) {
  const [menuOpen, setMenuOpen] = useState(false);
  const menuRef = useRef(null);
  const navigate = useNavigate();

  const name    = `${customer.first_name || ''} ${customer.last_name || ''}`.trim();
  const address = parseAddress(customer.address);
  const initial = (name[0] || '?').toUpperCase();
  const color   = avatarColor(name);

  // Close menu on outside click
  useEffect(() => {
    if (!menuOpen) return;
    const handler = (e) => { if (menuRef.current && !menuRef.current.contains(e.target)) setMenuOpen(false); };
    document.addEventListener('mousedown', handler);
    return () => document.removeEventListener('mousedown', handler);
  }, [menuOpen]);

  const menuAction = (fn) => { setMenuOpen(false); fn(); };

  return (
    <div className="customer-card" onClick={() => navigate(`/customers/${customer.id}`)}>
      {/* Avatar */}
      <div className="cust-avatar" style={{ background: color }}>
        {initial}
      </div>

      {/* Info */}
      <div className="cust-info">
        <div className="cust-name-row">
          <span className="cust-name">{name}</span>
          <span className="cust-price">₨ {customer.price}</span>
        </div>
        <div className="cust-address-row">
          <IcMapPin />
          <span className="cust-address">{address}</span>
        </div>
        <div className="cust-meta-row">
          <span className={`badge-type ${customer.type == 1 ? 'can' : 'bottle'}`}>
            {customer.type == 1 ? 'Can' : 'Bottle'}
          </span>
          {customer.number && (
            <span className="cust-phone"><IcPhone /> {customer.number}</span>
          )}
        </div>
      </div>

      {/* Action menu */}
      <div className="cust-menu-wrap" ref={menuRef} onClick={e => e.stopPropagation()}>
        <button
          className={`cust-menu-btn ${menuOpen ? 'open' : ''}`}
          onClick={() => setMenuOpen(v => !v)}
          aria-label="Actions"
        >
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <circle cx="12" cy="5" r="1" fill="currentColor"/><circle cx="12" cy="12" r="1" fill="currentColor"/><circle cx="12" cy="19" r="1" fill="currentColor"/>
          </svg>
        </button>

        {menuOpen && (
          <div className="cust-dropdown">
            <button className="dd-item" onClick={() => menuAction(() => navigate(`/customers/${customer.id}`))}>
              <IcInfo /> Details
            </button>
            <button className="dd-item" onClick={() => menuAction(() => window.open(`tel:${customer.number}`))}>
              <IcPhone /> Call
            </button>
            <button className="dd-item" onClick={() => menuAction(() => onEdit(customer))}>
              <IcEdit /> Edit
            </button>
            <button className="dd-item" onClick={() => menuAction(() => onToggleStatus(customer))}>
              <IcSwap /> {status === 0 ? 'Mark Inactive' : 'Mark Active'}
            </button>
            <button className="dd-item" onClick={() => menuAction(() => onDaysDialog(customer))}>
              <IcCalendar /> Delivery Days
            </button>
          </div>
        )}
      </div>
    </div>
  );
}

// ── Main Page ─────────────────────────────────────────────
export default function CustomersListPage() {
  const navigate = useNavigate();
  const location = useLocation();
  const [tab, setTab] = useState(location.pathname.includes('inactive') ? 1 : 0);
  const [activeState,   setActiveState]   = useState({ page:1, sort:'desc', search:'', keyword:'', data:[], hasMore:false, loading:false, error:'' });
  const [inactiveState, setInactiveState] = useState({ page:1, sort:'desc', search:'', keyword:'', data:[], hasMore:false, loading:false, error:'' });

  // Dialogs
  const [daysDialog,    setDaysDialog]    = useState(null); // customer object
  const [confirmDialog, setConfirmDialog] = useState(null); // {customer, status}
  const [toggling,      setToggling]      = useState(false);

  const getState    = useCallback(() => tab === 0 ? activeState   : inactiveState,   [tab, activeState, inactiveState]);
  const setState    = useCallback((fn) => tab === 0 ? setActiveState(fn) : setInactiveState(fn), [tab]);

  const searchRef = useRef(null);

  // ── Fetch ────────────────────────────────────────────────
  const fetchCustomers = useCallback(async (tabOverride, stateSnapshot) => {
    const s   = stateSnapshot;
    const st  = tabOverride ?? tab;
    const setter = st === 0 ? setActiveState : setInactiveState;

    setter(p => ({ ...p, loading: true, error: '' }));
    try {
      const res  = await getCustomers({ status: st, page: s.page, sort: s.sort, keyword: s.keyword || undefined });
      const list = Array.isArray(res?.data?.customers) ? res.data.customers : [];
      setter(p => ({ ...p, data: list, hasMore: list.length >= PAGE_SIZE, loading: false }));
    } catch (e) {
      setter(p => ({ ...p, error: e.message || 'Failed to load', loading: false }));
    }
  }, [tab]);

  // Fetch on mount and when page/sort/keyword/tab changes
  useEffect(() => {
    const s = tab === 0 ? activeState : inactiveState;
    fetchCustomers(tab, s);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [tab, activeState.page, activeState.sort, activeState.keyword, inactiveState.page, inactiveState.sort, inactiveState.keyword]);

  const s = getState();

  const handleSearch = (e) => {
    e.preventDefault();
    setState(p => ({ ...p, keyword: p.search.trim(), page: 1 }));
  };

  const clearSearch = () => {
    setState(p => ({ ...p, search: '', keyword: '', page: 1 }));
    searchRef.current?.focus();
  };

  const toggleSort = () => {
    setState(p => ({ ...p, sort: p.sort === 'desc' ? 'asc' : 'desc', page: 1 }));
  };

  // ── Toggle status ────────────────────────────────────────
  const handleToggleStatus = async () => {
    const { customer, status } = confirmDialog;
    setToggling(true);
    try {
      await toggleCustomerStatus(String(customer.id), status);
      // Remove from current tab list
      setState(p => ({ ...p, data: p.data.filter(c => c.id !== customer.id) }));
      setConfirmDialog(null);
    } catch (e) {
      alert(e.message || 'Failed to update status');
    } finally {
      setToggling(false);
    }
  };

  // ── Days dialog save ─────────────────────────────────────
  const handleDaysSaved = (customerId, newDays) => {
    setState(p => ({
      ...p,
      data: p.data.map(c => c.id === customerId ? { ...c, days_of_giving: newDays } : c),
    }));
  };

  return (
    <div className="cl-page">

      {/* ── Header ───────────────────────────────── */}
      <div className="cl-header">
        <div>
          <h1 className="page-title">Customers</h1>
          <p className="page-sub">Manage your customer base</p>
        </div>
        <button className="btn-add" onClick={() => navigate('/customers/register')}>
          <IcPlus /> Add Customer
        </button>
      </div>

      {/* ── Tabs ─────────────────────────────────── */}
      <div className="tab-bar">
        <button className={`tab-btn ${tab === 0 ? 'active' : ''}`} onClick={() => setTab(0)}>
          Active
        </button>
        <button className={`tab-btn ${tab === 1 ? 'active' : ''}`} onClick={() => setTab(1)}>
          In-Active
        </button>
      </div>

      {/* ── Toolbar ──────────────────────────────── */}
      <div className="table-toolbar">
        <form className="search-form" onSubmit={handleSearch}>
          <div className="search-input-wrap">
            <span className="search-icon"><IcSearch /></span>
            <input
              ref={searchRef}
              type="text"
              className="search-input"
              placeholder="Search by name, phone…"
              value={s.search}
              onChange={e => setState(p => ({ ...p, search: e.target.value }))}
            />
            {s.search && (
              <button type="button" className="search-clear" onClick={clearSearch}><IcX /></button>
            )}
          </div>
          <button type="submit" className="btn-search">Search</button>
        </form>
        <button className="btn-sort" onClick={toggleSort}>
          {s.sort === 'desc' ? <IcSort /> : <IcSortAsc />}
          {s.sort === 'desc' ? 'Newest' : 'Oldest'}
        </button>
      </div>

      {/* ── List ─────────────────────────────────── */}
      {s.loading ? (
        <div className="cl-state">
          <div className="spinner-ring" /><span>Loading…</span>
        </div>
      ) : s.error ? (
        <div className="cl-state error">
          <span>{s.error}</span>
          <button className="btn-retry" onClick={() => fetchCustomers(tab, s)}>Retry</button>
        </div>
      ) : s.data.length === 0 ? (
        <div className="cl-state">
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" style={{color:'var(--color-text-muted)'}}><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          <span>No customers found</span>
        </div>
      ) : (
        <div className="cl-list">
          {s.data.map(customer => (
            <CustomerCard
              key={customer.id}
              customer={customer}
              status={tab}
              onEdit={(c) => navigate(`/customers/edit/${c.id}`, { state: { customer: c } })}
              onToggleStatus={(c) => setConfirmDialog({ customer: c, status: tab })}
              onDaysDialog={(c) => setDaysDialog(c)}
            />
          ))}
        </div>
      )}

      {/* ── Pagination ───────────────────────────── */}
      {!s.loading && s.data.length > 0 && (
        <div className="pagination">
          <button className="page-btn" disabled={s.page === 1} onClick={() => setState(p => ({ ...p, page: p.page - 1 }))}>
            <IcChevL /> Prev
          </button>
          <span className="page-indicator">Page {s.page}</span>
          <button className="page-btn" disabled={!s.hasMore} onClick={() => setState(p => ({ ...p, page: p.page + 1 }))}>
            Next <IcChevR />
          </button>
        </div>
      )}

      {/* ── Dialogs ──────────────────────────────── */}
      {daysDialog && (
        <DeliveryDaysDialog
          customer={daysDialog}
          onClose={() => setDaysDialog(null)}
          onSave={(days) => handleDaysSaved(daysDialog.id, days)}
        />
      )}
      {confirmDialog && (
        <ConfirmDialog
          message={`Mark ${confirmDialog.customer.first_name} as ${confirmDialog.status === 0 ? 'inactive' : 'active'}?`}
          onConfirm={handleToggleStatus}
          onCancel={() => setConfirmDialog(null)}
          loading={toggling}
        />
      )}

    </div>
  );
}
