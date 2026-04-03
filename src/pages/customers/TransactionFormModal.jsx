// ============================================================
// WATER SUPPLY ADMIN — Transaction Edit Modal
// POST /customer_detail_edit
// Mirrors Flutter TransactionForm fields exactly
// ============================================================

import { useState } from 'react';
import { updateTransaction } from '../../services/api';
import './TransactionFormModal.css';

const IcX = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>;

function Field({ label, value, onChange, required, error }) {
  return (
    <div className="tf-field">
      <label className="tf-label">{label}{required && <span className="req"> *</span>}</label>
      <input
        type="number"
        className={`tf-input ${error ? 'error' : ''}`}
        value={value}
        onChange={e => onChange(e.target.value)}
      />
      {error && <span className="tf-hint">{error}</span>}
    </div>
  );
}

export default function TransactionFormModal({ transaction, customerId, onClose, onSaved }) {
  const [form, setForm] = useState({
    filled:        String(transaction.filled_deliver ?? 0),
    empty:         String(transaction.empty_recieved ?? 0),
    amount:        String(transaction.amount         ?? 0),
    bottleBlnc:    String(transaction.bottle_blnc    ?? 0),
    amountRec:     String(transaction.amount_rec     ?? 0),
    amountBlnc:    String(transaction.amount_blnc    ?? 0),
    totalAmount:   String(transaction.total_amount   ?? 0),
  });
  const [errors,  setErrors]  = useState({});
  const [loading, setLoading] = useState(false);
  const [apiError,setApiError]= useState('');

  const set = (field) => (val) => {
    setForm(p => ({ ...p, [field]: val }));
    setErrors(p => ({ ...p, [field]: '' }));
    setApiError('');
  };

  const validate = () => {
    const e = {};
    ['filled','amount','bottleBlnc','amountRec','amountBlnc','totalAmount'].forEach(f => {
      if (form[f].trim() === '') e[f] = 'Required';
      else if (isNaN(Number(form[f]))) e[f] = 'Must be a number';
    });
    return e;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const errs = validate();
    setErrors(errs);
    if (Object.keys(errs).length) return;

    setLoading(true);
    try {
      await updateTransaction({
        customer_id:      String(customerId),
        detail_id:        String(transaction.detail_id),
        date_:            String(transaction.datee),
        filled_delivered: form.filled,
        empty_rec:        form.empty,
        amount:           form.amount,
        bottle_blnc:      form.bottleBlnc,
        amount_rec:       form.amountRec,
        amount_blnc:      form.amountBlnc,
        total_amount:     form.totalAmount,
      });
      onSaved();
      onClose();
    } catch (err) {
      setApiError(err.message || 'Update failed. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  function fmtDate(str) {
    if (!str) return '';
    const d = new Date(str);
    return isNaN(d) ? str : d.toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' });
  }

  return (
    <div className="tf-backdrop" onClick={onClose}>
      <div className="tf-modal" onClick={e => e.stopPropagation()}>

        {/* Header */}
        <div className="tf-header">
          <div className="tf-header-info">
            <div className="tf-header-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </div>
            <div>
              <h3 className="tf-title">Edit Transaction</h3>
              <p className="tf-sub">Date: {fmtDate(transaction.datee)}</p>
            </div>
          </div>
          <button className="tf-close" onClick={onClose}><IcX /></button>
        </div>

        <form className="tf-body" onSubmit={handleSubmit} noValidate>
          {apiError && <div className="tf-api-error">{apiError}</div>}

          <p className="tf-section-label">Delivery Information</p>
          <div className="tf-row-2">
            <Field label="Filled Delivered" value={form.filled}     onChange={set('filled')}     required error={errors.filled} />
            <Field label="Empty Received"   value={form.empty}      onChange={set('empty')}                error={errors.empty} />
          </div>
          <Field label="Bottle Balance"     value={form.bottleBlnc} onChange={set('bottleBlnc')} required error={errors.bottleBlnc} />

          <p className="tf-section-label" style={{marginTop:20}}>Payment Information</p>
          <div className="tf-row-2">
            <Field label="Amount"           value={form.amount}     onChange={set('amount')}     required error={errors.amount} />
            <Field label="Amount Received"  value={form.amountRec}  onChange={set('amountRec')}  required error={errors.amountRec} />
          </div>
          <div className="tf-row-2">
            <Field label="Amount Balance"   value={form.amountBlnc} onChange={set('amountBlnc')} required error={errors.amountBlnc} />
            <Field label="Total Amount"     value={form.totalAmount}onChange={set('totalAmount')}required error={errors.totalAmount} />
          </div>

          <div className="tf-footer">
            <button type="button" className="btn-ghost" onClick={onClose}>Cancel</button>
            <button type="submit" className="btn-primary-sm" disabled={loading}>
              {loading ? <><span className="spinner-xs"/>Updating…</> : 'Update Transaction'}
            </button>
          </div>
        </form>

      </div>
    </div>
  );
}
