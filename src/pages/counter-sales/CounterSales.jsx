// ============================================================
// WATER SUPPLY ADMIN — Counter Sales
// POST /get_counter_sale    { page, date }
// POST /add_counter_sale    { amount }
// POST /update_counter_sale { amount, sale_id, date_, new_sale_amount, old_sale_amount }
// POST /delete_counter_sale { sale_id, date_, sale_amount }
// ============================================================

import { useState, useEffect, useCallback } from 'react';
import {
  getCounterSales, addCounterSale, updateCounterSale, deleteCounterSale
} from '../../services/api';
import './CounterSales.css';

// ── Icons ─────────────────────────────────────────────────
const IcPlus   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>;
const IcEdit   = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>;
const IcTrash  = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>;
const IcCalendar=()=> <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>;
const IcX      = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>;
const IcChevL  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="15 18 9 12 15 6"/></svg>;
const IcChevR  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="9 18 15 12 9 6"/></svg>;

// ── Helpers ───────────────────────────────────────────────
const toApiDate = (d) =>
  `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;

const fmtDisplay = (d) =>
  new Date(d).toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' });

const fmtDatetime = (str) => {
  if (!str) return '—';
  const d = new Date(str);
  return isNaN(d) ? str : d.toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' });
};

// ── Sale Form Modal ───────────────────────────────────────
function SaleFormModal({ sale, onClose, onSaved }) {
  const isEdit = !!sale;
  const [amount,  setAmount]  = useState(sale?.amount?.toString() ?? '');
  const [error,   setError]   = useState('');
  const [apiErr,  setApiErr]  = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!amount.trim() || isNaN(Number(amount))) { setError('Enter a valid amount'); return; }
    setLoading(true);
    try {
      if (isEdit) {
        await updateCounterSale({
          amount,
          sale_id:         String(sale.id),
          date_:           sale.datee,
          new_sale_amount: amount,
          old_sale_amount: String(sale.amount),
        });
      } else {
        await addCounterSale({ amount });
      }
      onSaved();
      onClose();
    } catch (err) {
      setApiErr(err.message || 'Operation failed');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="cs-backdrop" onClick={onClose}>
      <div className="cs-modal" onClick={e => e.stopPropagation()}>
        <div className="cs-modal-header">
          <div className="cs-modal-header-left">
            <div className="cs-modal-icon">{isEdit ? <IcEdit /> : <IcPlus />}</div>
            <div>
              <h3 className="cs-modal-title">{isEdit ? 'Update Sale' : 'Add Counter Sale'}</h3>
              {isEdit && <p className="cs-modal-sub">{fmtDatetime(sale.datee)}</p>}
            </div>
          </div>
          <button className="cs-close" onClick={onClose}><IcX /></button>
        </div>

        <form className="cs-modal-body" onSubmit={handleSubmit} noValidate>
          {apiErr && <div className="cs-api-error">{apiErr}</div>}

          <div className="form-group">
            <label className="form-label">Amount (₨)</label>
            <div className="cs-amount-input-wrap">
              <span className="cs-rupee">₨</span>
              <input
                type="number"
                className={`cs-amount-input ${error ? 'error' : ''}`}
                placeholder="0"
                value={amount}
                onChange={e => { setAmount(e.target.value); setError(''); setApiErr(''); }}
                autoFocus
                min="0"
              />
            </div>
            {error && <span className="field-hint error-hint">{error}</span>}
          </div>

          <div className="cs-modal-footer">
            <button type="button" className="btn-ghost" onClick={onClose}>Cancel</button>
            <button type="submit" className="btn-primary-sm" disabled={loading}>
              {loading
                ? <><span className="spinner-xs"/>{isEdit ? 'Updating…' : 'Adding…'}</>
                : isEdit ? 'Update' : 'Add Sale'
              }
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

// ── Delete Confirm ────────────────────────────────────────
function DeleteConfirm({ sale, onConfirm, onCancel, loading }) {
  return (
    <div className="cs-backdrop" onClick={onCancel}>
      <div className="cs-modal confirm" onClick={e => e.stopPropagation()}>
        <div className="confirm-icon-wrap"><IcTrash /></div>
        <h3 className="confirm-title">Delete Sale?</h3>
        <p className="confirm-msg">Remove sale of <strong>₨ {Number(sale.amount).toLocaleString()}</strong> on {fmtDatetime(sale.datee)}? This cannot be undone.</p>
        <div className="cs-modal-footer">
          <button className="btn-ghost" onClick={onCancel}>Cancel</button>
          <button className="btn-danger-sm" onClick={onConfirm} disabled={loading}>
            {loading ? <><span className="spinner-xs"/>Deleting…</> : 'Delete'}
          </button>
        </div>
      </div>
    </div>
  );
}

// ── Main Page ─────────────────────────────────────────────
export default function CounterSalesPage() {
  const today = new Date();

  const [selectedDate, setSelectedDate] = useState(today);
  const [page,         setPage]         = useState(1);
  const [sales,        setSales]        = useState([]);
  const [totalAmount,  setTotalAmount]  = useState(0);
  const [hasMore,      setHasMore]      = useState(false);
  const [loading,      setLoading]      = useState(false);
  const [error,        setError]        = useState('');

  const [formModal,    setFormModal]    = useState(null); // null | {} (add) | sale (edit)
  const [delModal,     setDelModal]     = useState(null);
  const [deleting,     setDeleting]     = useState(false);

  const load = useCallback(async (pg = page, date = selectedDate) => {
    setLoading(true);
    setError('');
    try {
      const res = await getCounterSales({ page: String(pg), date: toApiDate(date) });
      const newSales   = Array.isArray(res?.data?.sales) ? res.data.sales : [];
      const total      = parseFloat(res?.data?.summary?.total_amount ?? 0) || 0;
      const more       = res?.data?.pagination?.has_more ?? false;
      setSales(newSales);
      setTotalAmount(total);
      setHasMore(more);
    } catch (e) {
      setError(e.message || 'Failed to load');
    } finally {
      setLoading(false);
    }
  }, [page, selectedDate]);

  useEffect(() => { load(); }, [page, selectedDate]);

  // Date nav helpers
  const prevDay = () => { const d = new Date(selectedDate); d.setDate(d.getDate()-1); setSelectedDate(d); setPage(1); };
  const nextDay = () => { const d = new Date(selectedDate); d.setDate(d.getDate()+1); setSelectedDate(d); setPage(1); };
  const isToday = toApiDate(selectedDate) === toApiDate(today);

  const handleDelete = async () => {
    setDeleting(true);
    try {
      await deleteCounterSale({ sale_id: String(delModal.id), date_: delModal.datee, sale_amount: String(delModal.amount) });
      setDelModal(null);
      load(page, selectedDate);
    } catch (e) { alert(e.message || 'Delete failed'); }
    finally { setDeleting(false); }
  };

  return (
    <div className="cs-page">

      {/* ── Header ──────────────────────────────── */}
      <div className="cs-header">
        <div>
          <h1 className="page-title">Counter Sales</h1>
          <p className="page-sub">Track daily counter transactions</p>
        </div>
        <button className="btn-add" onClick={() => setFormModal({})}>
          <IcPlus /> Add Sale
        </button>
      </div>

      {/* ── Date nav + summary strip ─────────────── */}
      <div className="cs-top-bar">
        {/* Date navigator */}
        <div className="cs-date-nav">
          <button className="date-nav-btn" onClick={prevDay}><IcChevL /></button>
          <div className="cs-date-display">
            <IcCalendar />
            <input
              type="date"
              className="cs-date-input"
              value={toApiDate(selectedDate)}
              onChange={e => { setSelectedDate(new Date(e.target.value + 'T00:00:00')); setPage(1); }}
            />
            <span className="cs-date-label">{fmtDisplay(selectedDate)}</span>
            {isToday && <span className="cs-today-badge">Today</span>}
          </div>
          <button className="date-nav-btn" onClick={nextDay} disabled={isToday}><IcChevR /></button>
        </div>

        {/* Summary */}
        <div className="cs-summary">
          <span className="cs-summary-label">Total</span>
          <span className="cs-summary-value">₨ {totalAmount.toLocaleString()}</span>
        </div>
      </div>

      {/* ── List ────────────────────────────────── */}
      {loading ? (
        <div className="cs-state"><div className="spinner-ring"/><span>Loading…</span></div>
      ) : error ? (
        <div className="cs-state error"><span>{error}</span><button className="btn-retry" onClick={() => load()}>Retry</button></div>
      ) : sales.length === 0 ? (
        <div className="cs-state">
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" style={{color:'var(--color-text-muted)'}}><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
          <span>No sales for this date</span>
        </div>
      ) : (
        <div className="cs-list">
          {sales.map((sale, i) => (
            <div className="cs-card" key={sale.id ?? i}>
              {/* Index bubble */}
              <div className="cs-card-index">{i + 1}</div>

              <div className="cs-card-body">
                <span className="cs-card-amount">₨ {Number(sale.amount).toLocaleString()}</span>
                <span className="cs-card-date">{fmtDatetime(sale.datee)}</span>
              </div>

              <div className="cs-card-actions">
                <button className="cs-action-btn edit"   onClick={() => setFormModal(sale)} title="Edit"><IcEdit /></button>
                <button className="cs-action-btn delete" onClick={() => setDelModal(sale)}  title="Delete"><IcTrash /></button>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* ── Pagination ──────────────────────────── */}
      {!loading && sales.length > 0 && (
        <div className="pagination">
          <button className="page-btn" disabled={page === 1} onClick={() => setPage(p => p-1)}>
            <IcChevL /> Prev
          </button>
          <span className="page-indicator">Page {page}</span>
          <button className="page-btn" disabled={!hasMore} onClick={() => setPage(p => p+1)}>
            Next <IcChevR />
          </button>
        </div>
      )}

      {/* ── Modals ──────────────────────────────── */}
      {formModal !== null && (
        <SaleFormModal
          sale={formModal.id ? formModal : null}
          onClose={() => setFormModal(null)}
          onSaved={() => load(page, selectedDate)}
        />
      )}
      {delModal && (
        <DeleteConfirm
          sale={delModal}
          onConfirm={handleDelete}
          onCancel={() => setDelModal(null)}
          loading={deleting}
        />
      )}

    </div>
  );
}
