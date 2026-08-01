// ============================================================
// WATER SUPPLY ADMIN — Deliveries
// Admin: Today tab + All tab
// Delivery Boy: Today tab only
// Deliver action: POST /save_bottle_data
// (Water Plant module is separate — uses /plant_order, not this)
// ============================================================

import { useState, useEffect, useCallback, useRef } from 'react';
import { useAuth } from '../../hooks/useAuth';
import { getDeliveries, saveBottleData } from '../../services/api';
import './Deliveries.css';

// ── Icons ─────────────────────────────────────────────────
const IcSearch    = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>;
const IcMapPin    = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>;
const IcSort      = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>;
const IcSortAsc   = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>;
const IcChevronL  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="15 18 9 12 15 6"/></svg>;
const IcChevronR  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="9 18 15 12 9 6"/></svg>;
const IcX         = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>;
const IcTruck     = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>;
const IcXClose    = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>;
const IcDrop      = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0 0 14 0c0-4.5-7-12-7-12z"/></svg>;
const IcRupee     = () => <span className="ic-rupee">₨</span>;
const IcInfo      = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{flexShrink:0}}><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>;
const IcCalendar  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>;
const IcClock     = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>;
const IcCheckCircle = () => <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>;
const IcWhatsApp  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>;

const PAGE_SIZE = 25;

// Normalize a customer phone for wa.me — digits only; local numbers
// starting with 0 get the Pakistan country code (matches the mobile app's
// customer number format)
function waPhone(raw) {
  const digits = String(raw || '').replace(/\D/g, '');
  if (!digits) return '';
  return digits.startsWith('0') ? `92${digits.replace(/^0+/, '')}` : digits;
}

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

// ── Deliver Modal ──────────────────────────────────────────
function DeliverModal({ delivery, currentUser, onClose, onDelivered }) {
  const [filled,   setFilled]   = useState('');
  const [empty,    setEmpty]    = useState('');
  const [amount,   setAmount]   = useState('');
  const [date,     setDate]     = useState('');   // yyyy-mm-dd, blank = today
  const [time,     setTime]     = useState('');   // HH:mm, blank = now
  const [showBackdate, setShowBackdate] = useState(false);
  const [errors,   setErrors]   = useState({});
  const [apiErr,   setApiErr]   = useState('');
  const [loading,  setLoading]  = useState(false);
  const [success,  setSuccess]  = useState(false);

  const name       = `${delivery.first_name || ''} ${delivery.last_name || ''}`.trim();
  const clientId   = delivery.client_id ?? delivery.id;
  const isDelBoy   = currentUser?.userTypeId === 2;

  // Limit backdate picker to last 30 days, not future
  const maxDate = new Date().toISOString().slice(0, 10);
  const minDate = (() => {
    const d = new Date();
    d.setDate(d.getDate() - 30);
    return d.toISOString().slice(0, 10);
  })();

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!filled.trim() || isNaN(Number(filled))) {
      setErrors({ filled: 'Required' });
      return;
    }
    setErrors({});
    setApiErr('');
    setLoading(true);
    try {
      const payload = {
        client_id:       clientId,
        filled_deliver:  filled,
        empty_recieved:  empty  || '0',
        amount_recieved: amount || '0',
      };
      if (isDelBoy) payload.delivery_boy = currentUser.userName;
      if (date) payload.delivery_date = date;
      if (time) payload.delivery_time = `${time}:00`;

      await saveBottleData(payload);
      setSuccess(true);
    } catch (err) {
      setApiErr(err.message || 'Delivery failed. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  // ── Success / Receipt screen ─────────────────────────────
  if (success) {
    // Receipt math + message format mirror the mobile app
    const price         = Number(delivery.price)       || 0;
    const filledQty     = Number(filled)               || 0;
    const emptyQty      = Number(empty)                || 0;
    const amountRec     = Number(amount)               || 0;
    const prevBottle    = Number(delivery.bottle_blnc) || 0;
    const prevAmount    = Number(delivery.amount_blnc) || 0;
    const bottleBalance = prevBottle + filledQty - emptyQty;
    const currentAmount = price * filledQty;
    const totalAmount   = currentAmount + prevAmount;
    const amountBalance = totalAmount - amountRec;

    const receiptMsg = [
      `Customer: ${name}`,
      `Your Deposit: ${delivery.deposit ?? 0}`,
      '',
      `Filled Delivered: ${filledQty}`,
      `Empty Received: ${emptyQty}`,
      `Bottle Balance: ${bottleBalance}`,
      '',
      `Current Amount: ₨${currentAmount.toFixed(2)}`,
      `Total Amount: ₨${totalAmount.toFixed(2)}`,
      `Amount Received: ₨${amountRec}`,
      `Amount Balance: ₨${amountBalance.toFixed(2)}`,
      '',
      `Contact: ${currentUser?.phoneNo || ''}`,
      currentUser?.coName || '',
    ].join('\n');

    const waNumber = waPhone(delivery.number);
    const sendWhatsApp = () => {
      window.open(`https://wa.me/${waNumber}?text=${encodeURIComponent(receiptMsg)}`, '_blank', 'noopener');
    };

    return (
      <div className="dlv-backdrop" onClick={onDelivered}>
        <div className="dlv-modal" onClick={e => e.stopPropagation()}>
          <div className="dlv-receipt">
            <div className="dlv-receipt-icon"><IcCheckCircle /></div>
            <h3 className="dlv-receipt-title">Delivery Recorded!</h3>
            <p className="dlv-receipt-sub">{name}</p>
            <div className="dlv-receipt-grid">
              <div className="dlv-receipt-item">
                <span className="dlv-receipt-label">Filled</span>
                <span className="dlv-receipt-value">{filled || 0}</span>
              </div>
              <div className="dlv-receipt-item">
                <span className="dlv-receipt-label">Empty</span>
                <span className="dlv-receipt-value">{empty || 0}</span>
              </div>
              <div className="dlv-receipt-item">
                <span className="dlv-receipt-label">Received</span>
                <span className="dlv-receipt-value">₨ {amount || 0}</span>
              </div>
            </div>
            {waNumber && (
              <button className="dlv-whatsapp-btn" onClick={sendWhatsApp}>
                <IcWhatsApp /> Send Receipt on WhatsApp
              </button>
            )}
            <button className="btn-ghost" style={{width:'100%', justifyContent:'center', marginTop:'var(--space-2)'}} onClick={onDelivered}>
              Done
            </button>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="dlv-backdrop" onClick={onClose}>
      <div className="dlv-modal" onClick={e => e.stopPropagation()}>

        <div className="dlv-modal-header">
          <div className="dlv-modal-header-left">
            <div className="dlv-modal-icon"><IcTruck /></div>
            <div>
              <h3 className="dlv-modal-title">Make Delivery</h3>
              <p className="dlv-modal-sub">{name}</p>
            </div>
          </div>
          <button className="dlv-close" onClick={onClose}><IcXClose /></button>
        </div>

        {/* Current balances */}
        <div className="dlv-balances">
          <div className="dlv-bal-card blue">
            <span className="dlv-bal-label"><IcDrop /> Bottle Bal.</span>
            <span className="dlv-bal-value">{delivery.bottle_blnc ?? 0}</span>
          </div>
          <div className="dlv-bal-card orange">
            <span className="dlv-bal-label"><IcRupee /> Amount Bal.</span>
            <span className="dlv-bal-value">₨ {delivery.amount_blnc ?? 0}</span>
          </div>
        </div>

        <form className="dlv-modal-body" onSubmit={handleSubmit} noValidate>
          {apiErr && <div className="dlv-api-error">{apiErr}</div>}

          {/* Backdate toggle */}
          <button
            type="button"
            className="dlv-backdate-note"
            onClick={() => setShowBackdate(v => !v)}
          >
            <IcInfo />
            <span>Optional: select a past date/time if you forgot to record this earlier</span>
          </button>

          {showBackdate && (
            <div className="dlv-grid-2">
              <div className="form-group">
                <label className="dlv-label"><IcCalendar /> Date</label>
                <input type="date" className="dlv-input dlv-input-sm"
                  value={date} onChange={e => setDate(e.target.value)}
                  min={minDate} max={maxDate} />
              </div>
              <div className="form-group">
                <label className="dlv-label"><IcClock /> Time</label>
                <input type="time" className="dlv-input dlv-input-sm"
                  value={time} onChange={e => setTime(e.target.value)} />
              </div>
            </div>
          )}

          <div className="form-group">
            <label className="dlv-label">Filled Delivered <span className="req">*</span></label>
            <input type="number" className={`dlv-input ${errors.filled ? 'error' : ''}`}
              placeholder="0" value={filled} onChange={e => { setFilled(e.target.value); setErrors({}); }}
              autoFocus min="0" />
            {errors.filled && <span className="dlv-hint">{errors.filled}</span>}
          </div>

          <div className="dlv-grid-2">
            <div className="form-group">
              <label className="dlv-label">Empty Received</label>
              <input type="number" className="dlv-input"
                placeholder="0 (optional)" value={empty} onChange={e => setEmpty(e.target.value)} min="0" />
            </div>
            <div className="form-group">
              <label className="dlv-label">Amount Received (₨)</label>
              <input type="number" className="dlv-input"
                placeholder="0 (optional)" value={amount} onChange={e => setAmount(e.target.value)} min="0" />
            </div>
          </div>

          <div className="dlv-footer">
            <button type="button" className="btn-ghost" onClick={onClose}>Cancel</button>
            <button type="submit" className="dlv-submit" disabled={loading}>
              {loading ? <><span className="spinner-xs"/>Saving…</> : <><IcTruck /> Add Delivery</>}
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
  const [deliverModal, setDeliverModal] = useState(null);

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

  const handleDelivered = () => {
    setDeliverModal(null);
    fetchData();
  };

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
      
      <div className="tab-bar">
          <button className={`tab-btn ${filter === 'today' ? 'active' : ''}`} onClick={() => setFilter('today')}>Today</button>
          <button className={`tab-btn ${filter === 'all' ? 'active' : ''}`} onClick={() => setFilter('all')}>All</button>
        </div>

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
                      <button className="deliver-btn" onClick={() => setDeliverModal(row)} title="Make delivery">
                        <IcTruck /> Deliver
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

      {deliverModal && (
        <DeliverModal
          delivery={deliverModal}
          currentUser={user}
          onClose={() => setDeliverModal(null)}
          onDelivered={handleDelivered}
        />
      )}

    </div>
  );
}
