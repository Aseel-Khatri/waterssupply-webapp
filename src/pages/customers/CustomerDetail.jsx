// ============================================================
// WATER SUPPLY ADMIN — Customer Detail
// POST /customer_detail  { customer_id }
// ============================================================

import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { getCustomerDetail } from '../../services/api';
import TransactionFormModal from './TransactionFormModal';
import './CustomerDetail.css';

// ── Icons ─────────────────────────────────────────────────
const IcBack    = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="15 18 9 12 15 6"/></svg>;
const IcEdit    = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>;
const IcPrint   = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>;
const IcPhone   = () => <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 6 6l.97-.97a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>;
const IcMapPin  = () => <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>;
const IcCalendar= () => <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>;
const IcTruck2  = () => <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>;
const IcFilter  = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>;

// ── Helpers ───────────────────────────────────────────────
function parseAddressData(raw) {
  if (!raw) return { text: '—', coords: null };
  try {
    const p = typeof raw === 'string' ? JSON.parse(raw) : raw;
    if (p && typeof p === 'object') {
      return {
        text: p.address || `${p.lat}, ${p.lng}`,
        coords: p.type === 'latlng' ? { lat: p.lat, lng: p.lng } : null,
      };
    }
  } catch {}
  return { text: raw, coords: null };
}
function fmt(v) {
  const n = Number(v);
  return isNaN(n) ? (v ?? '—') : n.toLocaleString();
}
function fmtDate(str) {
  if (!str) return '—';
  const d = new Date(str);
  return isNaN(d) ? str : d.toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' });
}
const COLORS = ['#3B82F6','#10B981','#8B5CF6','#F59E0B','#EF4444','#06B6D4'];
function avatarColor(n) {
  let h = 0;
  for (let i = 0; i < (n||'').length; i++) h = n.charCodeAt(i) + ((h << 5) - h);
  return COLORS[Math.abs(h) % COLORS.length];
}

// ── Page ──────────────────────────────────────────────────
export default function CustomerDetailPage() {
  const { id }   = useParams();
  const navigate = useNavigate();

  const [details,      setDetails]      = useState(null);
  const [transactions, setTransactions] = useState([]);
  const [loading,      setLoading]      = useState(true);
  const [error,        setError]        = useState('');
  const [fromDate,     setFromDate]     = useState('');
  const [toDate,       setToDate]       = useState('');
  const [editTx,       setEditTx]       = useState(null);

  const load = () => {
    setLoading(true);
    getCustomerDetail(id)
      .then(res => {
        setDetails(res?.data?.personal_details?.[0] ?? null);
        setTransactions(res?.data?.filling_data ?? []);
      })
      .catch(e => setError(e.message || 'Failed to load'))
      .finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, [id]);

  const filtered = transactions.filter(t => {
    const d = new Date(t.datee);
    if (fromDate && d < new Date(fromDate)) return false;
    if (toDate   && d > new Date(toDate + 'T23:59:59')) return false;
    return true;
  });

  const totalAmount   = filtered.reduce((s, t) => s + Number(t.amount    ?? 0), 0);
  const totalReceived = filtered.reduce((s, t) => s + Number(t.amount_rec ?? 0), 0);
  const lastBalance   = filtered.length ? filtered[0].amount_blnc : 0;

  const handlePrint = () => {
    if (!filtered.length) return;
    const name = details ? `${details.first_name} ${details.last_name}`.trim() : 'Customer';
    const w = window.open('', '_blank');
    w.document.write(`<!DOCTYPE html><html><head><title>${name} — Report</title>
    <style>
      body{font-family:Arial,sans-serif;padding:24px;font-size:13px;color:#111}
      h2{margin:0 0 4px;font-size:18px}.meta{color:#666;font-size:12px;margin-bottom:16px}
      table{width:100%;border-collapse:collapse;margin-top:16px}
      th{background:#0D6EFD;color:#fff;padding:8px 10px;text-align:left;font-size:12px}
      td{padding:7px 10px;border-bottom:1px solid #eee;font-size:12px}
      tr:nth-child(even) td{background:#f9f9f9}
      .totals{margin-top:16px;display:flex;gap:32px}
      .tot-label{font-size:11px;color:#888}.tot-val{font-size:15px;font-weight:bold}
      .neg{color:#DC2626}.pos{color:#059669}
    </style></head><body>
    <h2>${name}</h2>
    <div class="meta">${details?.number ? `Phone: ${details.number} &nbsp;|&nbsp;` : ''}${fromDate||toDate ? `Period: ${fromDate||'—'} to ${toDate||'—'}` : 'All time'}</div>
    <table><thead><tr><th>Date</th><th>Filled</th><th>Empty</th><th>Bottle Bal.</th><th>Amount</th><th>Received</th><th>Balance</th></tr></thead>
    <tbody>${filtered.map(t=>`<tr>
      <td>${fmtDate(t.datee)}</td><td>${t.filled_deliver}</td><td>${t.empty_recieved}</td>
      <td>${t.bottle_blnc}</td><td>₨ ${fmt(t.amount)}</td><td>₨ ${fmt(t.amount_rec)}</td>
      <td class="${Number(t.amount_blnc)<0?'neg':'pos'}">₨ ${fmt(t.amount_blnc)}</td>
    </tr>`).join('')}</tbody></table>
    <div class="totals">
      <div><div class="tot-label">Total Amount</div><div class="tot-val">₨ ${fmt(totalAmount)}</div></div>
      <div><div class="tot-label">Total Received</div><div class="tot-val">₨ ${fmt(totalReceived)}</div></div>
      <div><div class="tot-label">Balance</div><div class="tot-val ${Number(lastBalance)<0?'neg':'pos'}">₨ ${fmt(lastBalance)}</div></div>
    </div>
    <script>window.onload=()=>window.print()<\/script></body></html>`);
    w.document.close();
  };

  // ── Loading / Error ───────────────────────────────────
  if (loading) return <div className="cd-state"><div className="spinner-ring"/><span>Loading…</span></div>;
  if (error)   return <div className="cd-state error"><span>{error}</span><button className="btn-retry" onClick={load}>Retry</button></div>;
  if (!details)return <div className="cd-state"><span>Customer not found.</span></div>;

  const name  = `${details.first_name} ${details.last_name}`.trim();
  const color = avatarColor(name);

  return (
    <div className="cd-page">

      {/* Top bar */}
      <div className="cd-topbar">
        <button className="cr-back-btn" onClick={() => navigate(-1)}><IcBack /></button>
        <div className="cd-topbar-actions">
          <button className="btn-outline-sm" onClick={() => navigate(`/customers/edit/${id}`, { state:{ customer: details } })}>
            <IcEdit /> Edit
          </button>
          <button className="btn-outline-sm" onClick={handlePrint} disabled={!filtered.length}>
            <IcPrint /> Print Report
          </button>
        </div>
      </div>

      {/* Profile card */}
      <div className="cd-profile-card">
        <div className="cd-avatar" style={{ background: color }}>{name[0]?.toUpperCase()}</div>
        <div className="cd-profile-info">
          <h1 className="cd-name">{name}</h1>
          <div className="cd-profile-meta">
            {details.number  && <span><IcPhone />  {details.number}</span>}
            {details.address && (() => {
              const { text, coords } = parseAddressData(details.address);
              return (
                <span className="cd-address-wrap">
                  <span className="cd-address-row"><IcMapPin /> {text}</span>
                  {coords && (
                    <a
                      className="cd-maps-link"
                      href={`https://www.google.com/maps?q=${coords.lat},${coords.lng}`}
                      target="_blank" rel="noopener noreferrer"
                    >
                      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                      Open in Google Maps
                      <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    </a>
                  )}
                </span>
              );
            })()}
            {details.rdatee  && <span><IcCalendar /> Since {details.rdatee}</span>}
            {(() => {
              const boyName = details.delivery_boy?.user_name ?? details.delivery_boy?.username ?? (typeof details.delivery_boy === 'string' ? details.delivery_boy : null);
              return boyName
                ? <span><IcTruck2 /> {boyName}</span>
                : <span className="cd-no-boy"><IcTruck2 /> No delivery boy assigned</span>;
            })()}
          </div>
        </div>
        <div className="cd-profile-badges">
          <span className={`badge-type ${details.type == 1 ? 'can' : details.type == 2 ? 'bottle' : 'other'}`}>
            {details.type == 1 ? 'Can' : details.type == 2 ? 'Bottle' : 'Other'}
          </span>
          <span className={`badge-status-pill ${details.status == 0 ? 'active' : 'inactive'}`}>
            {details.status == 0 ? 'Active' : 'Inactive'}
          </span>
        </div>
      </div>

      {/* Summary strip */}
      <div className="cd-summary-strip">
        {[
          { label: 'Price / Delivery', value: `₨ ${fmt(details.price)}` },
          { label: 'Deposit',          value: `₨ ${fmt(details.deposit)}` },
          { label: 'Total Billed',     value: `₨ ${fmt(totalAmount)}` },
          { label: 'Total Received',   value: `₨ ${fmt(totalReceived)}` },
          { label: 'Balance',          value: `₨ ${fmt(lastBalance)}`, negative: Number(lastBalance) < 0 },
        ].map(item => (
          <div className="cd-summary-item" key={item.label}>
            <span className="cd-summary-label">{item.label}</span>
            <span className={`cd-summary-value ${item.negative ? 'negative' : ''}`}>{item.value}</span>
          </div>
        ))}
      </div>

      {/* Transactions */}
      <div className="cd-section">
        <div className="cd-section-head">
          <h2 className="cd-section-title">Transaction History</h2>
          <div className="cd-date-filter">
            <IcFilter />
            <input type="date" className="date-input" value={fromDate} onChange={e => setFromDate(e.target.value)} />
            <span className="date-sep">–</span>
            <input type="date" className="date-input" value={toDate}   onChange={e => setToDate(e.target.value)} />
            {(fromDate || toDate) && (
              <button className="date-clear" onClick={() => { setFromDate(''); setToDate(''); }}>✕</button>
            )}
          </div>
        </div>

        {filtered.length === 0 ? (
          <div className="cd-state" style={{padding:'40px 0'}}>
            <span>No transactions found for this range.</span>
          </div>
        ) : (
          <div className="cd-table-wrap">
            <table className="cd-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th className="text-right">Filled</th>
                  <th className="text-right">Empty</th>
                  <th className="text-right">Bottle Bal.</th>
                  <th className="text-right">Amount</th>
                  <th className="text-right">Received</th>
                  <th className="text-right">Balance</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                {filtered.map(t => (
                  <tr key={t.detail_id}>
                    <td className="td-date">{fmtDate(t.datee)}</td>
                    <td className="text-right">{t.filled_deliver}</td>
                    <td className="text-right">{t.empty_recieved}</td>
                    <td className="text-right">{t.bottle_blnc}</td>
                    <td className="text-right">₨ {fmt(t.amount)}</td>
                    <td className="text-right td-received">₨ {fmt(t.amount_rec)}</td>
                    <td className={`text-right td-balance ${Number(t.amount_blnc) < 0 ? 'negative' : 'positive'}`}>
                      ₨ {fmt(t.amount_blnc)}
                    </td>
                    <td className="td-action">
                      <button className="tx-edit-btn" onClick={() => setEditTx(t)} title="Edit transaction">
                        <IcEdit />
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
              <tfoot>
                <tr className="tfoot-row">
                  <td colSpan={4} className="tfoot-label">Totals</td>
                  <td className="text-right tfoot-val">₨ {fmt(totalAmount)}</td>
                  <td className="text-right tfoot-val">₨ {fmt(totalReceived)}</td>
                  <td className={`text-right tfoot-val ${Number(lastBalance) < 0 ? 'negative' : 'positive'}`}>
                    ₨ {fmt(lastBalance)}
                  </td>
                  <td />
                </tr>
              </tfoot>
            </table>
          </div>
        )}
      </div>

      {/* Edit transaction modal */}
      {editTx && (
        <TransactionFormModal
          transaction={editTx}
          customerId={id}
          onClose={() => setEditTx(null)}
          onSaved={() => { setEditTx(null); load(); }}
        />
      )}

    </div>
  );
}
