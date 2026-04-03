// ============================================================
// WATER SUPPLY ADMIN — Customer Register / Edit
// POST save_customer
// ============================================================

import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { saveCustomer } from '../../services/api';
import './CustomerRegister.css';

// ── Icons ─────────────────────────────────────────────────
const IcUser   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>;
const IcPhone  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 6 6l.97-.97a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>;
const IcMapPin = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>;
const IcTag    = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>;
const IcCalendar=() => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>;
const IcWallet = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4z"/></svg>;
const IcCheck  = () => <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12"/></svg>;
const IcAlert  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{flexShrink:0}}><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>;
const IcArrow  = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>;
const IcBack   = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="15 18 9 12 15 6"/></svg>;
const IcCheckCircle = () => <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>;

const WEEK_DAYS = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

const SUPPLY_TYPES = [
  { value: '1', label: 'Can',    desc: 'Water can delivery' },
  { value: '2', label: 'Bottle', desc: 'Bottle delivery' },
];

const today = () => {
  const d = new Date();
  return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
};

export default function CustomerRegisterPage() {
  const navigate = useNavigate();

  const [form, setForm] = useState({
    firstName:     '',
    lastName:      '',
    phone:         '',
    address:       '',
    price:         '',
    deposit:       '',
    amountBalance: '',
    bottleBalance: '',
    deliveryBoyId: '',
    other:         '',
    date:          today(),
  });
  const [supplyType,    setSupplyType]    = useState('');
  const [selectedDays,  setSelectedDays]  = useState([]);
  const [loading,       setLoading]       = useState(false);
  const [error,         setError]         = useState('');
  const [fieldErrors,   setFieldErrors]   = useState({});
  const [success,       setSuccess]       = useState(false);

  const set = (field) => (e) => {
    setForm(p => ({ ...p, [field]: e.target.value }));
    setFieldErrors(p => ({ ...p, [field]: '' }));
    setError('');
  };

  const toggleDay = (day) => {
    setSelectedDays(prev =>
      prev.includes(day) ? prev.filter(d => d !== day) : [...prev, day]
    );
    setError('');
  };

  const validate = () => {
    const e = {};
    if (!form.firstName.trim()) e.firstName = 'Required';
    if (!form.phone.trim())     e.phone     = 'Required';
    if (!form.address.trim())   e.address   = 'Required';
    if (!form.price.trim())     e.price     = 'Required';
    if (!supplyType)            e.supplyType = 'Please select a supply type';
    if (selectedDays.length === 0) e.days   = 'Select at least one delivery day';
    return e;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    const errs = validate();
    setFieldErrors(errs);
    if (Object.keys(errs).length) return;

    setLoading(true);
    try {
      await saveCustomer({
        first_name:       form.firstName,
        last_name:        form.lastName,
        number:           form.phone,
        address:          form.address,
        type:             supplyType,
        price:            form.price,
        date_:            form.date,
        days_of_giving:   selectedDays,
        deposit:          form.deposit || '0',
        delivery_boy_id:  form.deliveryBoyId || '0',
        other:            form.other,
        amount_blnc:      form.amountBalance,
        bottle_blnc:      form.bottleBalance,
      });
      setSuccess(true);
    } catch (err) {
      setError(err.message || 'Failed to register customer. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  // ── Success ──────────────────────────────────────────────
  if (success) {
    return (
      <div className="cr-success">
        <div className="cr-success-card">
          <div className="cr-success-icon"><IcCheckCircle /></div>
          <h2>Customer Registered!</h2>
          <p>The customer has been successfully added to the system.</p>
          <div className="cr-success-actions">
            <button className="btn-primary" onClick={() => { setSuccess(false); setForm({ firstName:'',lastName:'',phone:'',address:'',price:'',deposit:'',amountBalance:'',bottleBalance:'',deliveryBoyId:'',other:'',date:today()}); setSupplyType(''); setSelectedDays([]); }}>
              Register Another
            </button>
            <button className="btn-outline" onClick={() => navigate('/customers/active')}>
              View Customers
            </button>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="cr-page">

      {/* ── Page header ────────────────────────────── */}
      <div className="cr-page-header">
        <button className="cr-back-btn" onClick={() => navigate(-1)}><IcBack /></button>
        <div>
          <h1 className="page-title">Register Customer</h1>
          <p className="page-sub">Fill in the details to add a new customer</p>
        </div>
      </div>

      <form className="cr-form" onSubmit={handleSubmit} noValidate>

        {error && (
          <div className="cr-alert-error">
            <IcAlert /><span>{error}</span>
          </div>
        )}

        {/* ── Personal Info ──────────────────────── */}
        <div className="cr-section">
          <div className="cr-section-header">
            <div className="cr-section-dot" style={{'--dot':'#3B82F6'}} />
            <h2 className="cr-section-title">Personal Information</h2>
          </div>
          <div className="cr-grid-2">
            <div className="form-group">
              <label className="form-label">First Name <span className="req">*</span></label>
              <div className="input-wrapper">
                <span className="input-icon"><IcUser /></span>
                <input type="text" className={`form-input ${fieldErrors.firstName ? 'error':''}`}
                  placeholder="First name" value={form.firstName} onChange={set('firstName')} autoFocus />
              </div>
              {fieldErrors.firstName && <span className="field-hint error-hint">{fieldErrors.firstName}</span>}
            </div>
            <div className="form-group">
              <label className="form-label">Last Name</label>
              <div className="input-wrapper">
                <span className="input-icon"><IcUser /></span>
                <input type="text" className="form-input"
                  placeholder="Last name (optional)" value={form.lastName} onChange={set('lastName')} />
              </div>
            </div>
            <div className="form-group">
              <label className="form-label">Phone Number <span className="req">*</span></label>
              <div className="input-wrapper">
                <span className="input-icon"><IcPhone /></span>
                <input type="tel" className={`form-input ${fieldErrors.phone ? 'error':''}`}
                  placeholder="e.g. 03001234567" value={form.phone} onChange={set('phone')} />
              </div>
              {fieldErrors.phone && <span className="field-hint error-hint">{fieldErrors.phone}</span>}
            </div>
            <div className="form-group">
              <label className="form-label">Start Date <span className="req">*</span></label>
              <div className="input-wrapper">
                <span className="input-icon"><IcCalendar /></span>
                <input type="date" className="form-input"
                  value={form.date} onChange={set('date')} />
              </div>
            </div>
          </div>

          <div className="form-group">
            <label className="form-label">Address <span className="req">*</span></label>
            <div className="input-wrapper">
              <span className="input-icon" style={{top:14,alignItems:'flex-start'}}><IcMapPin /></span>
              <textarea className={`form-input form-textarea ${fieldErrors.address ? 'error':''}`}
                placeholder="Street, Area, City" value={form.address} onChange={set('address')} rows={2} />
            </div>
            {fieldErrors.address && <span className="field-hint error-hint">{fieldErrors.address}</span>}
          </div>
        </div>

        {/* ── Supply Type ────────────────────────── */}
        <div className="cr-section">
          <div className="cr-section-header">
            <div className="cr-section-dot" style={{'--dot':'#10B981'}} />
            <h2 className="cr-section-title">Supply Type <span className="req">*</span></h2>
          </div>
          <div className="supply-type-row">
            {SUPPLY_TYPES.map(t => (
              <button
                key={t.value}
                type="button"
                className={`supply-type-card ${supplyType === t.value ? 'active' : ''} ${fieldErrors.supplyType ? 'error-border':''}`}
                onClick={() => { setSupplyType(t.value); setFieldErrors(p=>({...p,supplyType:''})); }}
              >
                <span className="supply-type-label">{t.label}</span>
                <span className="supply-type-desc">{t.desc}</span>
                {supplyType === t.value && <span className="supply-type-check"><IcCheck /></span>}
              </button>
            ))}
          </div>
          {fieldErrors.supplyType && <span className="field-hint error-hint">{fieldErrors.supplyType}</span>}
        </div>

        {/* ── Delivery Days ──────────────────────── */}
        <div className="cr-section">
          <div className="cr-section-header">
            <div className="cr-section-dot" style={{'--dot':'#8B5CF6'}} />
            <h2 className="cr-section-title">Delivery Days <span className="req">*</span></h2>
          </div>
          <div className="days-grid">
            {WEEK_DAYS.map(day => (
              <button
                key={day}
                type="button"
                className={`day-chip ${selectedDays.includes(day) ? 'active' : ''} ${fieldErrors.days ? 'error-border':''}`}
                onClick={() => toggleDay(day)}
              >
                {selectedDays.includes(day) && <span className="day-check"><IcCheck /></span>}
                {day.slice(0, 3)}
              </button>
            ))}
          </div>
          {fieldErrors.days && <span className="field-hint error-hint">{fieldErrors.days}</span>}
        </div>

        {/* ── Pricing & Balance ──────────────────── */}
        <div className="cr-section">
          <div className="cr-section-header">
            <div className="cr-section-dot" style={{'--dot':'#F59E0B'}} />
            <h2 className="cr-section-title">Pricing & Balance</h2>
          </div>
          <div className="cr-grid-2">
            <div className="form-group">
              <label className="form-label">Price per Delivery <span className="req">*</span></label>
              <div className="input-wrapper">
                <span className="input-icon"><IcTag /></span>
                <input type="number" className={`form-input ${fieldErrors.price ? 'error':''}`}
                  placeholder="0" value={form.price} onChange={set('price')} min="0" />
              </div>
              {fieldErrors.price && <span className="field-hint error-hint">{fieldErrors.price}</span>}
            </div>
            <div className="form-group">
              <label className="form-label">Deposit</label>
              <div className="input-wrapper">
                <span className="input-icon"><IcWallet /></span>
                <input type="number" className="form-input"
                  placeholder="0" value={form.deposit} onChange={set('deposit')} min="0" />
              </div>
            </div>
            <div className="form-group">
              <label className="form-label">Amount Balance</label>
              <div className="input-wrapper">
                <span className="input-icon"><IcWallet /></span>
                <input type="number" className="form-input"
                  placeholder="0" value={form.amountBalance} onChange={set('amountBalance')} />
              </div>
            </div>
            <div className="form-group">
              <label className="form-label">Bottle Balance</label>
              <div className="input-wrapper">
                <span className="input-icon"><IcTag /></span>
                <input type="number" className="form-input"
                  placeholder="0" value={form.bottleBalance} onChange={set('bottleBalance')} />
              </div>
            </div>
          </div>
        </div>

        {/* ── Other ──────────────────────────────── */}
        <div className="cr-section">
          <div className="cr-section-header">
            <div className="cr-section-dot" style={{'--dot':'#6B7280'}} />
            <h2 className="cr-section-title">Additional Info</h2>
          </div>
          <div className="form-group">
            <label className="form-label">Notes / Other</label>
            <div className="input-wrapper">
              <textarea className="form-input form-textarea"
                placeholder="Any extra notes…" value={form.other} onChange={set('other')} rows={3} />
            </div>
          </div>
        </div>

        {/* ── Submit ─────────────────────────────── */}
        <div className="cr-submit-row">
          <button type="button" className="btn-outline" onClick={() => navigate(-1)}>Cancel</button>
          <button type="submit" className="btn-primary" disabled={loading}>
            {loading
              ? <><span className="spinner" /> Registering…</>
              : <>Register Customer <IcArrow /></>
            }
          </button>
        </div>

      </form>
    </div>
  );
}
