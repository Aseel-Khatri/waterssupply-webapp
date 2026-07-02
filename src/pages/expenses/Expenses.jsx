// ============================================================
// WATER SUPPLY ADMIN — Expenses
// POST /get_expenses    { from_date, to_date, page }
// POST /save_expense    { exp_name, price, datee? }
// POST /exp_edit        { exp_name, price, exp_id }
// POST /exp_delete      { exp_id }
// ============================================================

import { useState, useEffect, useCallback } from 'react';
import { getExpenses, saveExpense, editExpense, deleteExpense } from '../../services/api';
import './Expenses.css';

// ── Icons ─────────────────────────────────────────────────
const IcPlus    = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>;
const IcEdit    = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>;
const IcTrash   = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>;
const IcFilter  = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>;
const IcX       = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>;
const IcChevL   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="15 18 9 12 15 6"/></svg>;
const IcChevR   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="9 18 15 12 9 6"/></svg>;
const IcReceipt = () => <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16l3-2 2 2 2-2 2 2 2-2 3 2V4a2 2 0 0 0-2-2z"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="16" y2="14"/></svg>;

// ── Helpers ───────────────────────────────────────────────
const toApiDate = (d) =>
  `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;

const fmtDate = (str) => {
  if (!str) return '—';
  const d = new Date(str);
  return isNaN(d) ? str : d.toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' });
};

// First day of current month
const firstOfMonth = () => {
  const d = new Date(); d.setDate(1); return d;
};

// ── Expense Form Modal ────────────────────────────────────
function ExpenseFormModal({ expense, onClose, onSaved }) {
  const isEdit = !!expense;
  const [name,    setName]    = useState(expense?.exp_name ?? expense?.name ?? '');
  const [price,   setPrice]   = useState(expense?.price?.toString() ?? '');
  const [date,    setDate]    = useState('');
  const [errors,  setErrors]  = useState({});
  const [apiErr,  setApiErr]  = useState('');
  const [loading, setLoading] = useState(false);

  const validate = () => {
    const e = {};
    if (!name.trim())                         e.name  = 'Expense name is required';
    if (!price.trim() || isNaN(Number(price)))e.price = 'Enter a valid amount';
    return e;
  };

  const handleSubmit = async (ev) => {
    ev.preventDefault();
    const errs = validate();
    setErrors(errs);
    if (Object.keys(errs).length) return;

    setLoading(true);
    try {
      if (isEdit) {
        await editExpense({ exp_id: String(expense.id), exp_name: name.trim(), price });
      } else {
        const payload = { exp_name: name.trim(), price };
        if (date) payload.datee = date;
        await saveExpense(payload);
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
    <div className="exp-backdrop" onClick={onClose}>
      <div className="exp-modal" onClick={e => e.stopPropagation()}>
        <div className="exp-modal-header">
          <div className="exp-modal-header-left">
            <div className="exp-modal-icon">{isEdit ? <IcEdit /> : <IcPlus />}</div>
            <div>
              <h3 className="exp-modal-title">{isEdit ? 'Edit Expense' : 'Add Expense'}</h3>
              {isEdit && <p className="exp-modal-sub">{fmtDate(expense.datee)}</p>}
            </div>
          </div>
          <button className="exp-close" onClick={onClose}><IcX /></button>
        </div>

        <form className="exp-modal-body" onSubmit={handleSubmit} noValidate>
          {apiErr && <div className="exp-api-error">{apiErr}</div>}

          <div className="form-group">
            <label className="form-label">Expense Name</label>
            <input
              type="text"
              className={`exp-text-input ${errors.name ? 'error' : ''}`}
              placeholder="e.g. Fuel, Maintenance…"
              value={name}
              onChange={e => { setName(e.target.value); setErrors(p=>({...p,name:''})); }}
              autoFocus
            />
            {errors.name && <span className="field-hint error-hint">{errors.name}</span>}
          </div>

          <div className="form-group">
            <label className="form-label">Amount (₨)</label>
            <div className="exp-amount-wrap">
              <span className="exp-rupee">₨</span>
              <input
                type="number"
                className={`exp-amount-input ${errors.price ? 'error' : ''}`}
                placeholder="0"
                value={price}
                onChange={e => { setPrice(e.target.value); setErrors(p=>({...p,price:''})); }}
                min="0"
              />
            </div>
            {errors.price && <span className="field-hint error-hint">{errors.price}</span>}
          </div>

          {/* Date only shown when adding — edit doesn't allow date change */}
          {!isEdit && (
            <div className="form-group">
              <label className="form-label">Date <span className="optional">(optional — defaults to today)</span></label>
              <input
                type="date"
                className="exp-text-input"
                value={date}
                onChange={e => setDate(e.target.value)}
              />
            </div>
          )}

          <div className="exp-modal-footer">
            <button type="button" className="btn-ghost" onClick={onClose}>Cancel</button>
            <button type="submit" className="btn-primary-sm" disabled={loading}>
              {loading
                ? <><span className="spinner-xs"/>{isEdit ? 'Saving…' : 'Adding…'}</>
                : isEdit ? 'Save Changes' : 'Add Expense'
              }
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

// ── Delete Confirm ────────────────────────────────────────
function DeleteConfirm({ expense, onConfirm, onCancel, loading }) {
  return (
    <div className="exp-backdrop" onClick={onCancel}>
      <div className="exp-modal confirm" onClick={e => e.stopPropagation()}>
        <div className="confirm-icon-wrap"><IcTrash /></div>
        <h3 className="confirm-title">Delete Expense?</h3>
        <p className="confirm-msg">
          Remove <strong>"{expense.exp_name || expense.name}"</strong> of{' '}
          <strong>₨ {Number(expense.price).toLocaleString()}</strong>?
          This cannot be undone.
        </p>
        <div className="exp-modal-footer">
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
export default function ExpensesPage() {
  const today = new Date();

  const [fromDate,    setFromDate]    = useState(toApiDate(firstOfMonth()));
  const [toDate,      setToDate]      = useState(toApiDate(today));
  const [page,        setPage]        = useState(1);
  const [expenses,    setExpenses]    = useState([]);
  const [totalAmount, setTotalAmount] = useState(0);
  const [hasMore,     setHasMore]     = useState(false);
  const [loading,     setLoading]     = useState(false);
  const [error,       setError]       = useState('');

  const [formModal,   setFormModal]   = useState(null);
  const [delModal,    setDelModal]    = useState(null);
  const [deleting,    setDeleting]    = useState(false);

  const load = useCallback(async (pg = page) => {
    setLoading(true);
    setError('');
    try {
      const res        = await getExpenses({ from_date: fromDate, to_date: toDate, page: String(pg) });
      const list       = Array.isArray(res?.data?.expenses) ? res.data.expenses : [];
      const total      = parseFloat(res?.data?.summary?.total_amount ?? 0) || 0;
      const more       = res?.data?.pagination?.has_more ?? false;
      setExpenses(list);
      setTotalAmount(total);
      setHasMore(more);
    } catch (e) {
      setError(e.message || 'Failed to load');
    } finally {
      setLoading(false);
    }
  }, [fromDate, toDate, page]);

  useEffect(() => { load(); }, [fromDate, toDate, page]);

  const applyFilter = () => { setPage(1); load(1); };
  const clearFilter = () => {
    setFromDate(toApiDate(firstOfMonth()));
    setToDate(toApiDate(today));
    setPage(1);
  };

  const handleDelete = async () => {
    setDeleting(true);
    try {
      await deleteExpense(String(delModal.id));
      setDelModal(null);
      load(page);
    } catch (e) { alert(e.message || 'Delete failed'); }
    finally { setDeleting(false); }
  };

  return (
    <div className="exp-page">

      {/* Header */}
      <div className="exp-header">
        <div>
          <h1 className="page-title">Expenses</h1>
          <p className="page-sub">Track and manage business expenses</p>
        </div>
        <button className="btn-add" onClick={() => setFormModal({})}>
          <IcPlus /> Add Expense
        </button>
      </div>

      {/* Filter bar + summary */}
      <div className="exp-filter-bar">
        <div className="exp-filter-left">
          <IcFilter />
          <div className="exp-date-range">
            <input
              type="date"
              className="exp-date-input"
              value={fromDate}
              onChange={e => setFromDate(e.target.value)}
            />
            <span className="exp-date-sep">→</span>
            <input
              type="date"
              className="exp-date-input"
              value={toDate}
              onChange={e => setToDate(e.target.value)}
            />
          </div>
          <button className="btn-apply" onClick={applyFilter}>Apply</button>
          <button className="btn-clear-filter" onClick={clearFilter} title="Reset to this month">↺</button>
        </div>

        <div className="exp-summary">
          <span className="exp-summary-label">Total</span>
          <span className="exp-summary-value">₨ {totalAmount.toLocaleString()}</span>
        </div>
      </div>

      {/* List */}
      {loading ? (
        <div className="exp-state"><div className="spinner-ring"/><span>Loading…</span></div>
      ) : error ? (
        <div className="exp-state error">
          <span>{error}</span>
          <button className="btn-retry" onClick={() => load()}>Retry</button>
        </div>
      ) : expenses.length === 0 ? (
        <div className="exp-state">
          <IcReceipt />
          <span>No expenses found for this period</span>
        </div>
      ) : (
        <div className="exp-list">
          {expenses.map((exp, i) => (
            <div className="exp-card" key={exp.id ?? i}>
              <div className="exp-card-left">
                <div className="exp-card-num">{i + 1}</div>
                <div className="exp-card-info">
                  <span className="exp-card-name">{exp.exp_name || exp.name}</span>
                  <span className="exp-card-date">{fmtDate(exp.datee)}</span>
                </div>
              </div>
              <div className="exp-card-right">
                <span className="exp-card-amount">₨ {Number(exp.price).toLocaleString()}</span>
                <div className="exp-card-actions">
                  <button className="exp-action-btn edit"   onClick={() => setFormModal(exp)} title="Edit"><IcEdit /></button>
                  <button className="exp-action-btn delete" onClick={() => setDelModal(exp)}  title="Delete"><IcTrash /></button>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Pagination */}
      {!loading && expenses.length > 0 && (
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

      {/* Modals */}
      {formModal !== null && (
        <ExpenseFormModal
          expense={formModal.id ? formModal : null}
          onClose={() => setFormModal(null)}
          onSaved={() => load(page)}
        />
      )}
      {delModal && (
        <DeleteConfirm
          expense={delModal}
          onConfirm={handleDelete}
          onCancel={() => setDelModal(null)}
          loading={deleting}
        />
      )}

    </div>
  );
}
