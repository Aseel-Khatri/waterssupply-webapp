// ============================================================
// WATER SUPPLY ADMIN — Deliveries
// Admin: Today tab + All tab
// Delivery Boy: Today tab only
// Deliver action: POST /plant_order
// ============================================================

import { useState, useEffect, useCallback, useRef } from 'react';
import { useAuth } from '../../hooks/useAuth';
import { getDeliveries, submitPlantOrder } from '../../services/api';
import './Deliveries.css';

// ── Icons ─────────────────────────────────────────────────
const IcSearch   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>;
const IcMapPin   = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>;
const IcSort     = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>;
const IcSortAsc  = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>;
const IcChevronL = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="15 18 9 12 15 6"/></svg>;
const IcChevronR = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="9 18 15 12 9 6"/></svg>;
const IcX        = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>;
const IcTruck    = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>;
const IcXClose   = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>;

const PAGE_SIZE = 25;

// ── Address parser ────────────────────────────────────────
function parseAddress(raw) {
  if (!raw) return { text: '—', coords: null };
  if (typeof raw === 'object') {
    return {
      text: raw.address || `${raw.lat}, ${raw.lng}`,
      coords: raw.type === 'latlng' ? { lat: raw.lat, lng: raw.lng } : null,
    };
  }
  try {
    const parsed = JSON.parse(raw);
    if (parsed && typeof parsed === 'object') {
      return {
        text: parsed.address || `${parsed.lat}, ${parsed.lng}`,
        coords: parsed.type === 'latlng' ? { lat: parsed.lat, lng: parsed.lng } : null,
      };
    }
  } catch {}
  return { text: raw, coords: null };
}

function AddressCell({ raw }) {
  const { text, coords } = parseAddress(raw);
  return (
    <span className="address-cell">
      <span className="address-text">{text}</span>
      {coords && (
        <button className="map-btn" onClick={() => window.open(`https://www.google.com/maps?q=${coords.lat},${coords.lng}`, '_blank', 'noopener')} title="Open in Google Maps">
          <IcMapPin /> Maps
        </button>
      )}
    </span>
  );
}

// ── Deliver Modal ─────────────────────────────────────────
function DeliverModal({ delivery, userId, onClose, onDelivered }) {
  const [form, setForm] = useState({ filled: '', empty: '', amount: '' });
  const [errors, setErrors] = useState({});
  const [apiErr, setApiErr] = useState('');
  const [loading, setLoading] = useState(false);

  const set = (f) => (e) => {
    setForm(p => ({ ...p, [f]: e.target.value }));
    setErrors(p => ({ ...p, [f]: '' }));
    setApiErr('');
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const errs = {};
    if (!form.filled.trim() || isNaN(Number(form.filled))) errs.filled = 'Required';
    setErrors(errs);
    if (Object.keys(errs).length) return;

    setLoading(true);
    try {
      await submitPlantOrder({
        user_id:   String(userId),
        plant_id:  String(delivery.id),
        refil_rec: form.filled || '0',
        empty_rec: form.empty || '0',
        am_rec:    form.amount || '0',
      });
      onDelivered();
    } catch (err) {
      setApiErr(err.message || 'Delivery failed');
    } finally {
      setLoading(false);
    }
  };

  const name = `${delivery.first_name || ''} ${delivery.last_name || ''}`.trim();

  return (
    <div className="dlv-backdrop" onClick={onClose}>
      <div className="dlv-modal" onClick={e => e.stopPropagation()}>
        <div className="dlv-modal-header">
          <div className="dlv-modal-header-left">
            <div className="dlv-modal-icon"><IcTruck /></div>
            <div>
              <h3 className="dlv-modal-title">Record Delivery</h3>
              <p className="dlv-modal-sub">{name}</p>
            </div>
          </div>
          <button className="dlv-close" onClick={onClose}><IcXClose /></button>
        </div>

        <form className="dlv-modal-body" onSubmit={handleSubmit} noValidate>
          {apiErr && <div className="dlv-api-error">{apiErr}</div>}

          <div className="dlv-grid-3">
            <div className="form-group">
              <label className="dlv-label">Filled Delivered <span className="req">*</span></label>
              <input type="number" className={`dlv-input ${errors.filled ? 'error' : ''}`}
                placeholder="0" value={form.filled} onChange={set('filled')} autoFocus min="0" />
              {errors.filled && <span className="dlv-hint">{errors.filled}</span>}
            </div>
            <div className="form-group">
              <label className="dlv-label">Empty Received</label>
              <input type="number" className="dlv-input"
                placeholder="0" value={form.empty} onChange={set('empty')} min="0" />
            </div>
            <div className="form-group">
              <label className="dlv-label">Amount (₨)</label>
              <input type="number" className="dlv-input"
                placeholder="0" value={form.amount} onChange={set('amount')} min="0" />
            </div>
          </div>

          <div className="dlv-footer">
            <button type="button" className="btn-ghost" onClick={onClose}>Cancel</button>
            <button type="submit" className="dlv-submit" disabled={loading}>
              {loading ? <><span className="spinner-xs"/>Saving…</> : <><IcTruck /> Confirm Delivery</>}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

// ── Main component ────────────────────────────────────────
export default function DeliveriesPage() {
  const { user } = useAuth();
  const isAdmin  = user?.userTypeId === 1;

  const [filter,   setFilter]   = useState('today');
  const [page,     setPage]     = useState(1);
  const [sort,     setSort]     = useState('desc');
  const [search,   setSearch]   = useState('');
  const [keyword,  setKeyword]  = useState('');
  const [data,     setData]     = useState([]);
  const [hasMore,  setHasMore]  = useState(false);
  const [loading,  setLoading]  = useState(false);
  const [error,    setError]    = useState('');
  const [deliverModal, setDeliverModal] = useState(null); // delivery row

  const searchRef = useRef(null);
  const days      = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
  const todayName = days[new Date().getDay()];

  const fetchData = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const res  = await getDeliveries({ filter, page, sort, keyword: keyword || undefined });
      const list = Array.isArray(res?.data?.deliveries) ? res.data.deliveries : [];
      setData(list);
      setHasMore(list.length >= PAGE_SIZE);
    } catch (err) {
      setError(err.message || 'Failed to load deliveries.');
    } finally {
      setLoading(false);
    }
  }, [filter, page, sort, keyword]);

  useEffect(() => { fetchData(); }, [fetchData]);
  useEffect(() => { setPage(1); }, [filter, sort, keyword]);

  const handleSearch = (e) => { e.preventDefault(); setKeyword(search.trim()); };
  const clearSearch  = () => { setSearch(''); setKeyword(''); searchRef.current?.focus(); };
  const toggleSort   = () => setSort(s => s === 'desc' ? 'asc' : 'desc');
  const srOffset     = (page - 1) * PAGE_SIZE;

  const typeLabel = (t) => t == 1 ? 'Can' : t == 2 ? 'Bottle' : t == 3 ? 'Other' : '—';
  const typeClass = (t) => t == 1 ? 'can' : t == 2 ? 'bottle' : 'other';

  return (
    <div className="deliveries-page">

      <div className="page-header">
        <div>
          <h1 className="page-title">
            {filter === 'today' ? `Order Day ${todayName}` : 'All Deliveries'}
          </h1>
          <p className="page-sub">Welcome {user?.coName || user?.userName}</p>
        </div>
      </div>

      {isAdmin && (
        <div className="tab-bar">
          <button className={`tab-btn ${filter === 'today' ? 'active' : ''}`} onClick={() => setFilter('today')}>Today</button>
          <button className={`tab-btn ${filter === 'all' ? 'active' : ''}`} onClick={() => setFilter('all')}>All</button>
        </div>
      )}

      <div className="table-toolbar">
        <form className="search-form" onSubmit={handleSearch}>
          <div className="search-input-wrap">
            <span className="search-icon"><IcSearch /></span>
            <input ref={searchRef} type="text" className="search-input" placeholder="Search by name, phone…"
              value={search} onChange={e => setSearch(e.target.value)} />
            {search && <button type="button" className="search-clear" onClick={clearSearch}><IcX /></button>}
          </div>
          <button type="submit" className="btn-search">Search</button>
        </form>
        <button className="btn-sort" onClick={toggleSort}>
          {sort === 'desc' ? <IcSort /> : <IcSortAsc />}
          {sort === 'desc' ? 'Newest' : 'Oldest'}
        </button>
      </div>

      <div className="table-card">
        {loading ? (
          <div className="table-state"><div className="spinner-ring" /><span>Loading deliveries…</span></div>
        ) : error ? (
          <div className="table-state error"><span>{error}</span><button className="btn-retry" onClick={fetchData}>Retry</button></div>
        ) : data.length === 0 ? (
          <div className="table-state">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" style={{color:'var(--color-text-muted)'}}><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <span>No deliveries found</span>
          </div>
        ) : (
          <div className="table-responsive">
            <table className="data-table">
              <thead>
                <tr>
                  <th>SR</th>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Address</th>
                  <th>Type</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                {data.map((row, i) => (
                  <tr key={row.id ?? i}>
                    <td className="td-sr">{srOffset + i + 1}</td>
                    <td className="td-id">{row.id}</td>
                    <td className="td-name">
                      <span className="customer-name">{row.first_name}{row.last_name ? ` ${row.last_name}` : ''}</span>
                    </td>
                    <td className="td-phone">{row.number || row.phone_number || '—'}</td>
                    <td className="td-address"><AddressCell raw={row.address} /></td>
                    <td className="td-type">
                      <span className={`badge-type ${typeClass(row.type)}`}>{typeLabel(row.type)}</span>
                    </td>
                    <td className="td-date">{row.datee || row.date || '—'}</td>
                    <td className="td-status">
                      <span className="badge-status not-completed">Not completed</span>
                    </td>
                    <td className="td-action">
                      <button className="deliver-btn" onClick={() => setDeliverModal(row)} title="Record delivery">
                        <IcTruck />
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {!loading && data.length > 0 && (
        <div className="pagination">
          <button className="page-btn" disabled={page === 1} onClick={() => setPage(p => p - 1)}><IcChevronL /> Prev</button>
          <span className="page-indicator">Page {page}</span>
          <button className="page-btn" disabled={!hasMore} onClick={() => setPage(p => p + 1)}>Next <IcChevronR /></button>
        </div>
      )}

      {/* Deliver modal */}
      {deliverModal && (
        <DeliverModal
          delivery={deliverModal}
          userId={user?.id}
          onClose={() => setDeliverModal(null)}
          onDelivered={() => { setDeliverModal(null); fetchData(); }}
        />
      )}

    </div>
  );
}
