// ============================================================
// WATER SUPPLY ADMIN — Water Plant
// GET  /all_plant
// POST /add_plant    { name, number, price, address, type }
// POST /edit_plant   { name, number, price, address, type, id }
// POST /delete_plant { id }
// POST /plant_order  { user_id, plant_id, empty_rec, refil_rec, am_rec }
// ============================================================

import { useState, useEffect } from 'react';
import {
  getAllPlants, addPlant, editPlant, deletePlant, submitPlantOrder
} from '../../services/api';
import './Plant.css';

// ── Icons ─────────────────────────────────────────────────
const IcPlus    = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>;
const IcEdit    = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>;
const IcTrash   = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>;
const IcTruck   = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>;
const IcMapPin  = () => <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>;
const IcPhone   = () => <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 6 6l.97-.97a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>;
const IcX       = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>;
const IcDrop    = () => <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0 0 14 0c0-4.5-7-12-7-12z"/></svg>;

// ── Helpers ───────────────────────────────────────────────
const COLORS = ['#3B82F6','#10B981','#8B5CF6','#F59E0B','#EF4444','#06B6D4'];
function avatarColor(n) {
  let h = 0;
  for (let i = 0; i < (n||'').length; i++) h = n.charCodeAt(i) + ((h << 5) - h);
  return COLORS[Math.abs(h) % COLORS.length];
}

const SUPPLY_TYPES = [
  { value: '1', label: 'Can' },
  { value: '2', label: 'Bottle' },
];

// ── Plant Form Modal ──────────────────────────────────────
function PlantFormModal({ plant, onClose, onSaved }) {
  const isEdit = !!plant;
  const [form, setForm] = useState({
    name:    plant?.name    ?? '',
    number:  plant?.number  ?? '',
    price:   plant?.price?.toString() ?? '',
    address: plant?.address ?? '',
    type:    plant?.type?.toString() ?? '1',
  });
  const [errors,  setErrors]  = useState({});
  const [apiErr,  setApiErr]  = useState('');
  const [loading, setLoading] = useState(false);

  const set = (field) => (e) => {
    setForm(p => ({ ...p, [field]: e.target.value }));
    setErrors(p => ({ ...p, [field]: '' }));
    setApiErr('');
  };

  const validate = () => {
    const e = {};
    if (!form.name.trim())    e.name    = 'Required';
    if (!form.price.trim() || isNaN(Number(form.price))) e.price = 'Enter valid amount';
    if (!form.address.trim()) e.address = 'Required';
    return e;
  };

  const handleSubmit = async (ev) => {
    ev.preventDefault();
    const errs = validate();
    setErrors(errs);
    if (Object.keys(errs).length) return;
    setLoading(true);
    try {
      const payload = {
        name:    form.name.trim(),
        number:  form.number.trim(),
        price:   form.price,
        address: form.address.trim(),
        type:    form.type,
      };
      if (isEdit) {
        payload.id = String(plant.id);
        await editPlant(payload);
      } else {
        await addPlant(payload);
      }
      onSaved();
      onClose();
    } catch (e) {
      setApiErr(e.message || 'Operation failed');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="pl-backdrop" onClick={onClose}>
      <div className="pl-modal" onClick={e => e.stopPropagation()}>
        <div className="pl-modal-header">
          <div className="pl-modal-header-left">
            <div className="pl-modal-icon"><IcDrop /></div>
            <div>
              <h3 className="pl-modal-title">{isEdit ? 'Edit Plant' : 'Add Plant'}</h3>
              <p className="pl-modal-sub">Water supply plant details</p>
            </div>
          </div>
          <button className="pl-close" onClick={onClose}><IcX /></button>
        </div>

        <form className="pl-modal-body" onSubmit={handleSubmit} noValidate>
          {apiErr && <div className="pl-api-error">{apiErr}</div>}

          <div className="pl-grid-2">
            <div className="form-group">
              <label className="form-label">Plant Name <span className="req">*</span></label>
              <input type="text" className={`pl-input ${errors.name ? 'error' : ''}`}
                placeholder="e.g. Main Plant" value={form.name} onChange={set('name')} autoFocus />
              {errors.name && <span className="field-hint error-hint">{errors.name}</span>}
            </div>
            <div className="form-group">
              <label className="form-label">Phone Number</label>
              <input type="tel" className="pl-input"
                placeholder="e.g. 03001234567" value={form.number} onChange={set('number')} />
            </div>
          </div>

          <div className="pl-grid-2">
            <div className="form-group">
              <label className="form-label">Price <span className="req">*</span></label>
              <input type="number" className={`pl-input ${errors.price ? 'error' : ''}`}
                placeholder="0" value={form.price} onChange={set('price')} min="0" />
              {errors.price && <span className="field-hint error-hint">{errors.price}</span>}
            </div>
            <div className="form-group">
              <label className="form-label">Type</label>
              <div className="pl-type-row">
                {SUPPLY_TYPES.map(t => (
                  <button key={t.value} type="button"
                    className={`pl-type-btn ${form.type === t.value ? 'active' : ''}`}
                    onClick={() => setForm(p => ({ ...p, type: t.value }))}>
                    {t.label}
                  </button>
                ))}
              </div>
            </div>
          </div>

          <div className="form-group">
            <label className="form-label">Address <span className="req">*</span></label>
            <input type="text" className={`pl-input ${errors.address ? 'error' : ''}`}
              placeholder="Plant location" value={form.address} onChange={set('address')} />
            {errors.address && <span className="field-hint error-hint">{errors.address}</span>}
          </div>

          <div className="pl-modal-footer">
            <button type="button" className="btn-ghost" onClick={onClose}>Cancel</button>
            <button type="submit" className="btn-primary-sm" disabled={loading}>
              {loading
                ? <><span className="spinner-xs"/>{isEdit ? 'Saving…' : 'Adding…'}</>
                : isEdit ? 'Save Changes' : 'Add Plant'
              }
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

// ── Plant Order Modal ─────────────────────────────────────
function PlantOrderModal({ plant, onClose, onSaved }) {
  const [form, setForm] = useState({
    empty_rec: '',
    refil_rec: '',
    am_rec:    '',
  });
  const [errors,  setErrors]  = useState({});
  const [apiErr,  setApiErr]  = useState('');
  const [loading, setLoading] = useState(false);

  const set = (field) => (e) => {
    setForm(p => ({ ...p, [field]: e.target.value }));
    setErrors(p => ({ ...p, [field]: '' }));
    setApiErr('');
  };

  const validate = () => {
    const e = {};
    if (!form.refil_rec.trim() || isNaN(Number(form.refil_rec))) e.refil_rec = 'Required';
    if (!form.empty_rec.trim() || isNaN(Number(form.empty_rec))) e.empty_rec = 'Required';
    return e;
  };

  const handleSubmit = async (ev) => {
    ev.preventDefault();
    const errs = validate();
    setErrors(errs);
    if (Object.keys(errs).length) return;
    setLoading(true);
    try {
      await submitPlantOrder({
        plant_id:  String(plant.id),
        empty_rec: form.empty_rec,
        refil_rec: form.refil_rec,
        am_rec:    form.am_rec || '0',
      });
      onSaved();
      onClose();
    } catch (e) {
      setApiErr(e.message || 'Order failed');
    } finally {
      setLoading(false);
    }
  };

  const color = avatarColor(plant.name);

  return (
    <div className="pl-backdrop" onClick={onClose}>
      <div className="pl-modal" onClick={e => e.stopPropagation()}>
        <div className="pl-modal-header">
          <div className="pl-modal-header-left">
            <div className="pl-order-avatar" style={{ background: color }}>
              {plant.name[0].toUpperCase()}
            </div>
            <div>
              <h3 className="pl-modal-title">Plant Delivery</h3>
              <p className="pl-modal-sub">{plant.name}</p>
            </div>
          </div>
          <button className="pl-close" onClick={onClose}><IcX /></button>
        </div>

        {/* Balances strip */}
        <div className="pl-order-balances">
          <div className="pl-bal-item">
            <span className="pl-bal-label">Bottle Balance</span>
            <span className="pl-bal-value">{plant.blnc_b ?? 0}</span>
          </div>
          <div className="pl-bal-divider" />
          <div className="pl-bal-item">
            <span className="pl-bal-label">Amount Balance</span>
            <span className={`pl-bal-value ${Number(plant.am_blnc) < 0 ? 'negative' : ''}`}>
              ₨ {Number(plant.am_blnc ?? 0).toLocaleString()}
            </span>
          </div>
          <div className="pl-bal-divider" />
          <div className="pl-bal-item">
            <span className="pl-bal-label">Price</span>
            <span className="pl-bal-value">₨ {Number(plant.price ?? 0).toLocaleString()}</span>
          </div>
        </div>

        <form className="pl-modal-body" onSubmit={handleSubmit} noValidate>
          {apiErr && <div className="pl-api-error">{apiErr}</div>}

          <div className="pl-grid-2">
            <div className="form-group">
              <label className="form-label">Empty Received <span className="req">*</span></label>
              <input type="number" className={`pl-input ${errors.empty_rec ? 'error' : ''}`}
                placeholder="0" value={form.empty_rec} onChange={set('empty_rec')} min="0" />
              {errors.empty_rec && <span className="field-hint error-hint">{errors.empty_rec}</span>}
            </div>
            <div className="form-group">
              <label className="form-label">Refill Received <span className="req">*</span></label>
              <input type="number" className={`pl-input ${errors.refil_rec ? 'error' : ''}`}
                placeholder="0" value={form.refil_rec} onChange={set('refil_rec')} autoFocus min="0" />
              {errors.refil_rec && <span className="field-hint error-hint">{errors.refil_rec}</span>}
            </div>
          </div>

          <div className="form-group">
            <label className="form-label">Amount Received</label>
            <div className="pl-rupee-wrap">
              <span className="pl-rupee">₨</span>
              <input type="number" className="pl-input pl-rupee-input"
                placeholder="0" value={form.am_rec} onChange={set('am_rec')} min="0" />
            </div>
          </div>

          <div className="pl-modal-footer">
            <button type="button" className="btn-ghost" onClick={onClose}>Cancel</button>
            <button type="submit" className="btn-primary-sm" disabled={loading}>
              {loading ? <><span className="spinner-xs"/>Submitting…</> : 'Submit Order'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

// ── Delete Confirm ────────────────────────────────────────
function DeleteConfirm({ plant, onConfirm, onCancel, loading }) {
  return (
    <div className="pl-backdrop" onClick={onCancel}>
      <div className="pl-modal confirm" onClick={e => e.stopPropagation()}>
        <div className="confirm-icon-wrap"><IcTrash /></div>
        <h3 className="confirm-title">Delete Plant?</h3>
        <p className="confirm-msg">Remove <strong>"{plant.name}"</strong>? This cannot be undone.</p>
        <div className="pl-modal-footer">
          <button className="btn-ghost" onClick={onCancel}>Cancel</button>
          <button className="btn-danger-sm" onClick={onConfirm} disabled={loading}>
            {loading ? <><span className="spinner-xs"/>Deleting…</> : 'Delete'}
          </button>
        </div>
      </div>
    </div>
  );
}

// ── Plant Card ────────────────────────────────────────────
function PlantCard({ plant, onEdit, onOrder, onDelete }) {
  const color = avatarColor(plant.name);
  return (
    <div className="pl-card">
      <div className="pl-card-top">
        <div className="pl-avatar" style={{ background: color }}>
          {plant.name[0]?.toUpperCase()}
        </div>
        <div className="pl-card-info">
          <span className="pl-card-name">{plant.name}</span>
          <div className="pl-card-meta">
            {plant.address && <span><IcMapPin />{plant.address}</span>}
            {plant.number  && <span><IcPhone />{plant.number}</span>}
          </div>
        </div>
        <div className="pl-card-actions">
          <button className="pl-action-btn order"  onClick={() => onOrder(plant)}  title="Record delivery"><IcTruck /></button>
          <button className="pl-action-btn edit"   onClick={() => onEdit(plant)}   title="Edit"><IcEdit /></button>
          <button className="pl-action-btn delete" onClick={() => onDelete(plant)} title="Delete"><IcTrash /></button>
        </div>
      </div>

      <div className="pl-card-balances">
        <div className="pl-stat">
          <span className="pl-stat-label">Price</span>
          <span className="pl-stat-value">₨ {Number(plant.price ?? 0).toLocaleString()}</span>
        </div>
        <div className="pl-stat-divider"/>
        <div className="pl-stat">
          <span className="pl-stat-label">Bottle Bal.</span>
          <span className="pl-stat-value">{plant.blnc_b ?? 0}</span>
        </div>
        <div className="pl-stat-divider"/>
        <div className="pl-stat">
          <span className="pl-stat-label">Amount Bal.</span>
          <span className={`pl-stat-value ${Number(plant.am_blnc) < 0 ? 'negative' : ''}`}>
            ₨ {Number(plant.am_blnc ?? 0).toLocaleString()}
          </span>
        </div>
        <div className="pl-stat-divider"/>
        <div className="pl-stat">
          <span className="pl-stat-label">Type</span>
          <span className={`pl-type-badge ${plant.type == 1 ? 'can' : plant.type == 2 ? 'bottle' : 'other'}`}>
            {plant.type == 1 ? 'Can' : plant.type == 2 ? 'Bottle' : 'Other'}
          </span>
        </div>
      </div>
    </div>
  );
}

// ── Main Page ─────────────────────────────────────────────
export default function PlantPage() {
  const [plants,     setPlants]     = useState([]);
  const [loading,    setLoading]    = useState(true);
  const [error,      setError]      = useState('');
  const [formModal,  setFormModal]  = useState(null); // null | {} (add) | plant (edit)
  const [orderModal, setOrderModal] = useState(null); // plant
  const [delModal,   setDelModal]   = useState(null); // plant
  const [deleting,   setDeleting]   = useState(false);

  const load = () => {
    setLoading(true);
    getAllPlants()
      .then(res => {
        const list = Array.isArray(res?.data) ? res.data
                   : Array.isArray(res?.data?.plants) ? res.data.plants
                   : [];
        setPlants(list);
      })
      .catch(e => setError(e.message || 'Failed to load'))
      .finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, []);

  const handleDelete = async () => {
    setDeleting(true);
    try {
      await deletePlant(String(delModal.id));
      setDelModal(null);
      load();
    } catch (e) { alert(e.message || 'Delete failed'); }
    finally { setDeleting(false); }
  };

  return (
    <div className="pl-page">

      {/* Header */}
      <div className="pl-header">
        <div>
          <h1 className="page-title">Water Plant</h1>
          <p className="page-sub">Manage plant deliveries and balances</p>
        </div>
        <button className="btn-add" onClick={() => setFormModal({})}>
          <IcPlus /> Add Plant
        </button>
      </div>

      {/* List */}
      {loading ? (
        <div className="pl-state"><div className="spinner-ring"/><span>Loading…</span></div>
      ) : error ? (
        <div className="pl-state error"><span>{error}</span><button className="btn-retry" onClick={load}>Retry</button></div>
      ) : plants.length === 0 ? (
        <div className="pl-state">
          <IcDrop />
          <span>No plants added yet</span>
        </div>
      ) : (
        <div className="pl-list">
          {plants.map(plant => (
            <PlantCard
              key={plant.id}
              plant={plant}
              onEdit={p  => setFormModal(p)}
              onOrder={p => setOrderModal(p)}
              onDelete={p=> setDelModal(p)}
            />
          ))}
        </div>
      )}

      {/* Modals */}
      {formModal !== null && (
        <PlantFormModal
          plant={formModal.id ? formModal : null}
          onClose={() => setFormModal(null)}
          onSaved={load}
        />
      )}
      {orderModal && (
        <PlantOrderModal
          plant={orderModal}
          onClose={() => setOrderModal(null)}
          onSaved={load}
        />
      )}
      {delModal && (
        <DeleteConfirm
          plant={delModal}
          onConfirm={handleDelete}
          onCancel={() => setDelModal(null)}
          loading={deleting}
        />
      )}

    </div>
  );
}
