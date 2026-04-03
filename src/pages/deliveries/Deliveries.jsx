// ============================================================
// WATER SUPPLY ADMIN — Deliveries
// Admin: Today tab + All tab with pagination + search + sort
// Delivery Boy: Today tab only
// Address: plain text shown as-is; latlng object → extract
//   address string + show Google Maps button
// ============================================================

import { useState, useEffect, useCallback, useRef } from 'react';
import { useAuth } from '../../hooks/useAuth';
import { getDeliveries } from '../../services/api';
import './Deliveries.css';

// ── Icons ─────────────────────────────────────────────────
const IcSearch   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>;
const IcMapPin   = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>;
const IcSort     = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>;
const IcSortAsc  = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>;
const IcChevronL = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="15 18 9 12 15 6"/></svg>;
const IcChevronR = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="9 18 15 12 9 6"/></svg>;
const IcX        = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>;

const PAGE_SIZE = 25;

// ── Address parser ─────────────────────────────────────────
// Handles: plain string, JSON string with {type,lat,lng,address}
function parseAddress(raw) {
  if (!raw) return { text: '—', coords: null };
  if (typeof raw === 'object') {
    return {
      text:   raw.address || `${raw.lat}, ${raw.lng}`,
      coords: raw.type === 'latlng' ? { lat: raw.lat, lng: raw.lng } : null,
    };
  }
  // Try JSON parse
  try {
    const parsed = JSON.parse(raw);
    if (parsed && typeof parsed === 'object') {
      return {
        text:   parsed.address || `${parsed.lat}, ${parsed.lng}`,
        coords: parsed.type === 'latlng' ? { lat: parsed.lat, lng: parsed.lng } : null,
      };
    }
  } catch {}
  return { text: raw, coords: null };
}

function openMaps(lat, lng) {
  window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank', 'noopener');
}

// ── Address cell ───────────────────────────────────────────
function AddressCell({ raw }) {
  const { text, coords } = parseAddress(raw);
  return (
    <span className="address-cell">
      <span className="address-text">{text}</span>
      {coords && (
        <button
          className="map-btn"
          onClick={() => openMaps(coords.lat, coords.lng)}
          title="Open in Google Maps"
        >
          <IcMapPin /> Maps
        </button>
      )}
    </span>
  );
}

// ── Main component ────────────────────────────────────────
export default function DeliveriesPage() {
  const { user }   = useAuth();
  const isAdmin    = user?.userTypeId === 1;

  const [filter,   setFilter]   = useState('today');   // 'today' | 'all'
  const [page,     setPage]     = useState(1);
  const [sort,     setSort]     = useState('desc');    // 'asc' | 'desc'
  const [search,   setSearch]   = useState('');
  const [keyword,  setKeyword]  = useState('');        // committed search
  const [data,     setData]     = useState([]);
  const [hasMore,  setHasMore]  = useState(false);
  const [loading,  setLoading]  = useState(false);
  const [error,    setError]    = useState('');

  const searchRef = useRef(null);

  const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
  const todayName = days[new Date().getDay()];

  const fetchData = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const res = await getDeliveries({
        filter,
        page,
        sort,
        keyword: keyword || undefined,
      });
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

  // Reset to page 1 when filter/sort/keyword changes
  useEffect(() => { setPage(1); }, [filter, sort, keyword]);

  const handleSearch = (e) => {
    e.preventDefault();
    setKeyword(search.trim());
  };

  const clearSearch = () => {
    setSearch('');
    setKeyword('');
    searchRef.current?.focus();
  };

  const toggleSort = () => setSort(s => s === 'desc' ? 'asc' : 'desc');

  // ── Row SR offset ──────────────────────────────────────
  const srOffset = (page - 1) * PAGE_SIZE;

  return (
    <div className="deliveries-page">

      {/* ── Header ─────────────────────────────────────── */}
      <div className="page-header">
        <div>
          <h1 className="page-title">
            {filter === 'today' ? `Order Day ${todayName}` : 'All Deliveries'}
          </h1>
          <p className="page-sub">
            Welcome {user?.coName || user?.userName}
          </p>
        </div>
      </div>

      {/* ── Tabs (admin only) ──────────────────────────── */}
      {isAdmin && (
        <div className="tab-bar">
          <button
            className={`tab-btn ${filter === 'today' ? 'active' : ''}`}
            onClick={() => setFilter('today')}
          >
            Today
          </button>
          <button
            className={`tab-btn ${filter === 'all' ? 'active' : ''}`}
            onClick={() => setFilter('all')}
          >
            All
          </button>
        </div>
      )}

      {/* ── Toolbar ────────────────────────────────────── */}
      <div className="table-toolbar">
        <form className="search-form" onSubmit={handleSearch}>
          <div className="search-input-wrap">
            <span className="search-icon"><IcSearch /></span>
            <input
              ref={searchRef}
              type="text"
              className="search-input"
              placeholder="Search by name, phone…"
              value={search}
              onChange={e => setSearch(e.target.value)}
            />
            {search && (
              <button type="button" className="search-clear" onClick={clearSearch}>
                <IcX />
              </button>
            )}
          </div>
          <button type="submit" className="btn-search">Search</button>
        </form>

        <button className="btn-sort" onClick={toggleSort} title={`Sort ${sort === 'desc' ? 'oldest first' : 'newest first'}`}>
          {sort === 'desc' ? <IcSort /> : <IcSortAsc />}
          {sort === 'desc' ? 'Newest' : 'Oldest'}
        </button>
      </div>

      {/* ── Table ──────────────────────────────────────── */}
      <div className="table-card">
        {loading ? (
          <div className="table-state">
            <div className="spinner-ring" />
            <span>Loading deliveries…</span>
          </div>
        ) : error ? (
          <div className="table-state error">
            <span>{error}</span>
            <button className="btn-retry" onClick={fetchData}>Retry</button>
          </div>
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
                      <span className={`badge-type ${row.type == 1 ? 'can' : 'bottle'}`}>
                        {row.type == 1 ? 'Can' : 'Bottle'}
                      </span>
                    </td>
                    <td className="td-date">{row.datee || row.date || '—'}</td>
                    <td className="td-status">
                      <span className="badge-status not-completed">Not completed</span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {/* ── Pagination ─────────────────────────────────── */}
      {!loading && data.length > 0 && (
        <div className="pagination">
          <button
            className="page-btn"
            disabled={page === 1}
            onClick={() => setPage(p => p - 1)}
          >
            <IcChevronL /> Prev
          </button>
          <span className="page-indicator">Page {page}</span>
          <button
            className="page-btn"
            disabled={!hasMore}
            onClick={() => setPage(p => p + 1)}
          >
            Next <IcChevronR />
          </button>
        </div>
      )}

    </div>
  );
}
