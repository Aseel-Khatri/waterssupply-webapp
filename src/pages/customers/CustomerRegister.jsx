// ============================================================
// WATER SUPPLY ADMIN — Customer Register / Edit
// POST save_customer
// Edit: /customers/edit/:id with customer data in location.state
// ============================================================

import { useState, useEffect } from 'react';
import { useNavigate, useParams, useLocation } from 'react-router-dom';
import { saveCustomer, getDeliveryBoys } from '../../services/api';
import MapPickerModal from './MapPickerModal';
import './CustomerRegister.css';

// ── Icons ─────────────────────────────────────────────────
const IcUser   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>;
const IcPhone  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 6 6l.97-.97a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>;
const IcMapPin = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>;
const IcTag    = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>;
const IcCalendar=() => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>;
const IcWallet = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4z"/></svg>;
const IcTruck  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>;
const IcCheck  = () => <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12"/></svg>;
const IcAlert  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{flexShrink:0}}><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>;
const IcArrow  = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>;
const IcBack   = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="15 18 9 12 15 6"/></svg>;
const IcCheckCircle = () => <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>;

// Short day names matching mobile app
const WEEK_DAYS = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

const SUPPLY_TYPES = [
  { value: '1', label: 'Can',    desc: 'Water can delivery' },
  { value: '2', label: 'Bottle', desc: 'Bottle delivery' },
  { value: '3', label: 'Other',  desc: 'Other supply type' },
];

const todayStr = () => {
  const d = new Date();
  return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
};

// Default start date is tomorrow (not today) — starting a customer's
// delivery cycle "today" was silently creating a same-day delivery
// record, which then didn't surface correctly on the Today Delivery list.
const tomorrowStr = () => {
  const d = new Date();
  d.setDate(d.getDate() + 1);
  return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
};

// Parse address for pre-fill (could be JSON or plain text)
function parseAddressForEdit(raw) {
  if (!raw) return '';
  try {
    const p = typeof raw === 'string' ? JSON.parse(raw) : raw;
    if (p?.address) return p.address;
  } catch {}
  return raw;
}

export default function CustomerRegisterPage() {
  const navigate = useNavigate();
  const { id }   = useParams();
  const location = useLocation();

  // Edit mode: customer data from location.state or null
  const editData  = location.state?.customer ?? null;
  const isEdit    = !!editData;

  // Delivery boys list
  const [deliveryBoys, setDeliveryBoys] = useState([]);
  const [boysLoading,  setBoysLoading]  = useState(true);

  const [form, setForm] = useState({
    firstName:     editData?.first_name?.trim()  ?? '',
    lastName:      editData?.last_name?.trim()   ?? '',
    phone:         editData?.number?.trim()      ?? '',
    address:       parseAddressForEdit(editData?.address) ?? '',
    price:         editData?.price?.toString()    ?? '',
    deposit:       editData?.deposit?.toString()  ?? '',
    amountBalance: '',
    bottleBalance: '',
    deliveryBoyId: editData?.delivery_boy_id?.toString() ?? '0',
    other:         editData?.other ?? '',
    date:          editData?.datee ?? tomorrowStr(),
  });
  const [supplyType,    setSupplyType]    = useState(editData?.type?.toString() ?? '');
  const [selectedDays,  setSelectedDays]  = useState(() => {
    if (!editData?.days_of_giving) return [];
    if (Array.isArray(editData.days_of_giving)) return editData.days_of_giving;
    if (typeof editData.days_of_giving === 'string') return editData.days_of_giving.split(',').map(d => d.trim());
    return [];
  });
  const [loading,       setLoading]       = useState(false);
  const [error,         setError]         = useState('');
  const [fieldErrors,   setFieldErrors]   = useState({});
  const [success,       setSuccess]       = useState(false);
  const [mapOpen,       setMapOpen]       = useState(false);
  const [locationData,  setLocationData]  = useState(null); // {type:'latlng', lat, lng, address}

  // Fetch delivery boys on mount
  useEffect(() => {
    getDeliveryBoys()
      .then(res => {
        const list = Array.isArray(res?.data) ? res.data
                   : Array.isArray(res?.data?.delivery_boys) ? res.data.delivery_boys
                   : [];
        setDeliveryBoys(list);
        // If editing, match current delivery boy
        if (editData?.delivery_boy_id && list.length) {
          const found = list.find(b => b.id == editData.delivery_boy_id);
          if (found) setForm(p => ({ ...p, deliveryBoyId: String(found.id) }));
        }
      })
      .catch(() => {})
      .finally(() => setBoysLoading(false));
  }, []);

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
    if (!form.address.trim() && !locationData) e.address = 'Required';
    if (!form.price.trim())     e.price     = 'Required';
    if (!supplyType)            e.supplyType = 'Please select a supply type';
    if (supplyType === '3' && !form.other.trim()) e.other = 'Required when type is Other';
    if (!isEdit && selectedDays.length === 0) e.days = 'Select at least one delivery day';
    return e;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    const errs = validate();
    setFieldErrors(errs);
    if (Object.keys(errs).length) return;

    // Build address value — JSON if map location, plain text otherwise
    let addressValue;
    if (locationData && locationData.type === 'latlng') {
      addressValue = JSON.stringify({
        type: 'latlng',
        lat: locationData.lat,
        lng: locationData.lng,
        address: locationData.address,
      });
    } else {
      addressValue = form.address;
    }

    setLoading(true);
    try {
      const payload = {
        first_name:      form.firstName,
        last_name:       form.lastName,
        number:          form.phone,
        address:         addressValue,
        type:            supplyType,
        price:           form.price,
        date_:           form.date,
        days_of_giving:  selectedDays,
        deposit:         form.deposit || '0',
        delivery_boy_id: form.deliveryBoyId || '0',
        other:           form.other,
      };

      if (isEdit) {
        payload.customer_id = editData.id;
        payload.is_bottle   = supplyType;
      } else {
        payload.amount_blnc = form.amountBalance || '0';
        payload.bottle_blnc = form.bottleBalance || '0';
      }

      await saveCustomer(payload);
      setSuccess(true);
    } catch (err) {
      setError(err.message || `Failed to ${isEdit ? 'update' : 'register'} customer.`);
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
          <h2>{isEdit ? 'Customer Updated!' : 'Customer Registered!'}</h2>
          <p>The customer has been successfully {isEdit ? 'updated' : 'added'}.</p>
          <div className="cr-success-actions">
            {!isEdit && (
              <button className="btn-primary" onClick={() => {
                setSuccess(false);
                setForm({ firstName:'',lastName:'',phone:'',address:'',price:'',deposit:'',amountBalance:'',bottleBalance:'',deliveryBoyId:'0',other:'',date:tomorrowStr()});
                setSupplyType('');
                setSelectedDays([]);
              }}>
                Register Another
              </button>
            )}
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

      <div className="cr-page-header">
        <button className="cr-back-btn" onClick={() => navigate(-1)}><IcBack /></button>
        <div>
          <h1 className="page-title">{isEdit ? 'Edit Customer' : 'Register Customer'}</h1>
          <p className="page-sub">{isEdit ? 'Update customer details' : 'Fill in the details to add a new customer'}</p>
        </div>
      </div>

      <form className="cr-form" onSubmit={handleSubmit} noValidate>

        {error && (
          <div className="cr-alert-error"><IcAlert /><span>{error}</span></div>
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
              <label className="form-label">Start Date</label>
              <div className="input-wrapper">
                <span className="input-icon"><IcCalendar /></span>
                <input type="date" className="form-input"
                  value={form.date} onChange={set('date')} />
              </div>
              {!isEdit && <span className="field-hint">Deliveries begin from this date</span>}
            </div>
          </div>
          <div className="form-group">
            <label className="form-label">Address <span className="req">*</span></label>
            <div className="cr-address-wrap">
              <div className="input-wrapper">
                <span className="input-icon input-icon-top"><IcMapPin /></span>
                <textarea className={`form-input form-textarea ${fieldErrors.address ? 'error':''}`}
                  placeholder="Street, Area, City" value={form.address}
                  onChange={e => { set('address')(e); setLocationData(null); }} rows={2} />
              </div>
              <button type="button" className="cr-map-btn" onClick={() => setMapOpen(true)} title="Pick from map">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Map
              </button>
            </div>
            {locationData && (
              <span className="cr-location-chip">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Location set from map
              </span>
            )}
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

          {/* Other description — shown when type=3 */}
          {supplyType === '3' && (
            <div className="form-group" style={{marginTop:'var(--space-3)'}}>
              <label className="form-label">Specify Type <span className="req">*</span></label>
              <div className="input-wrapper">
                <span className="input-icon"><IcTag /></span>
                <input type="text" className={`form-input ${fieldErrors.other ? 'error':''}`}
                  placeholder="What type of supply?" value={form.other} onChange={set('other')} />
              </div>
              {fieldErrors.other && <span className="field-hint error-hint">{fieldErrors.other}</span>}
            </div>
          )}
        </div>

        {/* ── Delivery Days ──────────────────────── */}
        <div className="cr-section">
          <div className="cr-section-header">
            <div className="cr-section-dot" style={{'--dot':'#8B5CF6'}} />
            <h2 className="cr-section-title">Delivery Days {!isEdit && <span className="req">*</span>}</h2>
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
                {day}
              </button>
            ))}
          </div>
          {fieldErrors.days && <span className="field-hint error-hint">{fieldErrors.days}</span>}
        </div>

        {/* ── Delivery Boy + Pricing ─────────────── */}
        <div className="cr-section">
          <div className="cr-section-header">
            <div className="cr-section-dot" style={{'--dot':'#F59E0B'}} />
            <h2 className="cr-section-title">Pricing & Assignment</h2>
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
              <label className="form-label">Assign Delivery Boy</label>
              <div className="input-wrapper">
                <span className="input-icon"><IcTruck /></span>
                <select className="form-input form-select" value={form.deliveryBoyId} onChange={set('deliveryBoyId')}>
                  <option value="0">None (Unassigned)</option>
                  {deliveryBoys.map(boy => (
                    <option key={boy.id} value={String(boy.id)}>{boy.user_name}</option>
                  ))}
                </select>
              </div>
            </div>
            {supplyType !== '3' && (
              <div className="form-group">
                <label className="form-label">Notes</label>
                <div className="input-wrapper">
                  <span className="input-icon"><IcTag /></span>
                  <input type="text" className="form-input"
                    placeholder="Optional notes" value={form.other} onChange={set('other')} />
                </div>
              </div>
            )}
          </div>

          {/* Balance fields — only on create */}
          {!isEdit && (
            <div className="cr-grid-2" style={{marginTop:'var(--space-4)'}}>
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
          )}
        </div>

        {/* ── Submit ─────────────────────────────── */}
        <div className="cr-submit-row">
          <button type="button" className="btn-outline" onClick={() => navigate(-1)}>Cancel</button>
          <button type="submit" className="btn-primary" disabled={loading}>
            {loading
              ? <><span className="spinner" /> {isEdit ? 'Updating…' : 'Registering…'}</>
              : <>{isEdit ? 'Update Customer' : 'Register Customer'} <IcArrow /></>
            }
          </button>
        </div>

      </form>

      {/* Map picker modal */}
      {mapOpen && (
        <MapPickerModal
          initialLat={locationData?.lat}
          initialLng={locationData?.lng}
          onClose={() => setMapOpen(false)}
          onConfirm={(result) => {
            setLocationData(result);
            setForm(p => ({ ...p, address: result.address }));
            setFieldErrors(p => ({ ...p, address: '' }));
            setMapOpen(false);
          }}
        />
      )}

    </div>
  );
}
