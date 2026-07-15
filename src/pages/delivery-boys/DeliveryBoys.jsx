// ============================================================
// WATER SUPPLY ADMIN — Delivery Boys
// List: GET /get_delivery_boy
// Add:  POST /add_delivery_boy   { username, password }
// Edit: POST /edit_delivery_boy  { username, password, id }
// Del:  POST /delete_delivery_boy { id }
// ============================================================

import { useState, useEffect } from 'react';
import {
  getDeliveryBoys, addDeliveryBoy, editDeliveryBoy, deleteDeliveryBoy
} from '../../services/api';
import './DeliveryBoys.css';

// ── Icons ─────────────────────────────────────────────────
const IcPlus   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>;
const IcEdit   = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>;
const IcTrash  = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>;
const IcEye    = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>;
const IcEyeOff = () => <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>;
const IcX      = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>;
const IcUser   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>;
const IcLock   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>;

const COLORS = ['#3B82F6','#10B981','#8B5CF6','#F59E0B','#EF4444','#06B6D4'];
function avatarColor(n) {
  let h = 0;
  for (let i = 0; i < (n||'').length; i++) h = n.charCodeAt(i) + ((h << 5) - h);
  return COLORS[Math.abs(h) % COLORS.length];
}

// ── Add/Edit Modal ────────────────────────────────────────
function BoyFormModal({ boy, onClose, onSaved }) {
  const isEdit = !!boy;
  const [username, setUsername] = useState(boy?.user_name ?? '');
  const [password, setPassword] = useState(boy?.pass      ?? '');
  const [showPass, setShowPass] = useState(false);
  const [errors,   setErrors]   = useState({});
  const [apiError, setApiError] = useState('');
  const [loading,  setLoading]  = useState(false);

  const validate = () => {
    const e = {};
    if (!username.trim())        e.username = 'Username is required';
    else if (username.includes(' ')) e.username = 'No spaces allowed';
    if (!password.trim())        e.password = 'Password is required';
    return e;
  };

  const handleSubmit = async (ev) => {
    ev.preventDefault();
    const errs = validate();
    setErrors(errs);
    if (Object.keys(errs).length) return;
    setLoading(true);
    try {
      const payload = { username: username.trim(), password };
      if (isEdit) payload.id = String(boy.id);
      isEdit ? await editDeliveryBoy(payload) : await addDeliveryBoy(payload);
      onSaved();
      onClose();
    } catch (e) {
      setApiError(e.message || 'Operation failed');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="db-backdrop" onClick={onClose}>
      <div className="db-modal" onClick={e => e.stopPropagation()}>
        <div className="db-modal-header">
          <div className="db-modal-header-left">
            <div className="db-modal-icon">
              {isEdit ? <IcEdit /> : <IcUser />}
            </div>
            <div>
              <h3 className="db-modal-title">{isEdit ? 'Update Delivery Boy' : 'Add Delivery Boy'}</h3>
              <p className="db-modal-sub">Enter credentials</p>
            </div>
          </div>
          <button className="db-close" onClick={onClose}><IcX /></button>
        </div>

        <form className="db-modal-body" onSubmit={handleSubmit} noValidate>
          {apiError && <div className="db-api-error">{apiError}</div>}

          <div className="form-group">
            <label className="form-label">Username</label>
            <div className="input-wrapper">
              <span className="input-icon"><IcUser /></span>
              <input
                type="text"
                className={`form-input ${errors.username ? 'error' : ''}`}
                placeholder="Enter username (no spaces)"
                value={username}
                onChange={e => { setUsername(e.target.value); setErrors(p=>({...p,username:''})); }}
                autoFocus
              />
            </div>
            {errors.username && <span className="field-hint error-hint">{errors.username}</span>}
          </div>

          <div className="form-group">
            <label className="form-label">Password</label>
            <div className="input-wrapper">
              <span className="input-icon"><IcLock /></span>
              <input
                type={showPass ? 'text' : 'password'}
                className={`form-input ${errors.password ? 'error' : ''}`}
                placeholder="Enter password"
                value={password}
                onChange={e => { setPassword(e.target.value); setErrors(p=>({...p,password:''})); }}
              />
              <span className="input-suffix">
                <button type="button" className="toggle-password" onClick={() => setShowPass(v=>!v)}>
                  {showPass ? <IcEyeOff /> : <IcEye />}
                </button>
              </span>
            </div>
            {errors.password && <span className="field-hint error-hint">{errors.password}</span>}
          </div>

          <div className="db-modal-footer">
            <button type="button" className="btn-ghost" onClick={onClose}>Cancel</button>
            <button type="submit" className="btn-primary-sm" disabled={loading}>
              {loading
                ? <><span className="spinner-xs"/>{isEdit ? 'Updating…' : 'Adding…'}</>
                : isEdit ? 'Update' : 'Add Boy'
              }
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

// ── Confirm Delete ────────────────────────────────────────
function DeleteConfirm({ boy, onConfirm, onCancel, loading }) {
  return (
    <div className="db-backdrop" onClick={onCancel}>
      <div className="db-modal confirm" onClick={e => e.stopPropagation()}>
        <div className="confirm-icon-wrap">
          <IcTrash />
        </div>
        <h3 className="confirm-title">Delete Delivery Boy?</h3>
        <p className="confirm-msg">Remove <strong>"{boy.user_name}"</strong>? This cannot be undone.</p>
        <div className="db-modal-footer">
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
export default function DeliveryBoysPage() {
  const [boys,     setBoys]     = useState([]);
  const [loading,  setLoading]  = useState(true);
  const [error,    setError]    = useState('');
  const [formModal,setFormModal]= useState(null); // null | {} (add) | boy object (edit)
  const [delModal, setDelModal] = useState(null); // boy object
  const [deleting, setDeleting] = useState(false);

  const load = () => {
    setLoading(true);
    getDeliveryBoys()
      .then(res => {
        const list = Array.isArray(res?.data) ? res.data: [];
        setBoys(list);
      })
      .catch(e => setError(e.message || 'Failed to load'))
      .finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, []);

  const handleDelete = async () => {
    setDeleting(true);
    try {
      await deleteDeliveryBoy(String(delModal.id));
      setDelModal(null);
      load();
    } catch (e) {
      alert(e.message || 'Delete failed');
    } finally {
      setDeleting(false);
    }
  };

  return (
    <div className="db-page">

      {/* Header */}
      <div className="db-header">
        <div>
          <h1 className="page-title">Delivery Boys</h1>
          <p className="page-sub">Manage your delivery staff</p>
        </div>
        <button className="btn-add" onClick={() => setFormModal({})}>
          <IcPlus /> Add Delivery Boy
        </button>
      </div>

      {/* List */}
      {loading ? (
        <div className="db-state"><div className="spinner-ring"/><span>Loading…</span></div>
      ) : error ? (
        <div className="db-state error"><span>{error}</span><button className="btn-retry" onClick={load}>Retry</button></div>
      ) : boys.length === 0 ? (
        <div className="db-state">
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" style={{color:'var(--color-text-muted)'}}><circle cx="5.5" cy="17.5" r="3.5"/><circle cx="18.5" cy="17.5" r="3.5"/><path d="M15 6a1 1 0 0 0-1-1h-2"/><path d="M8.5 17.5L12 10l4 4 2.5 3.5"/><path d="M12 10l2-4"/></svg>
          <span>No delivery boys added yet</span>
        </div>
      ) : (
        <div className="db-list">
          {boys.map(boy => {
            const color = avatarColor(boy.user_name);
            return (
              <div className="db-card" key={boy.id}>
                <div className="db-card-left">
                  {/* Avatar with online dot */}
                  <div className="db-avatar-wrap">
                    <div className="db-avatar" style={{ background: color }}>
                      {(boy.user_name || '?')[0].toUpperCase()}
                    </div>
                    <span className="db-online-dot" />
                  </div>

                  <div className="db-card-info">
                    <span className="db-card-name">{boy.user_name}</span>
                    <span className="db-card-pass">
                      <IcLock />
                      ••••••
                    </span>
                  </div>
                </div>

                <div className="db-card-actions">
                  <button className="db-action-btn edit" onClick={() => setFormModal(boy)} title="Edit">
                    <IcEdit />
                  </button>
                  <button className="db-action-btn delete" onClick={() => setDelModal(boy)} title="Delete">
                    <IcTrash />
                  </button>
                </div>
              </div>
            );
          })}
        </div>
      )}

      {/* Modals */}
      {formModal !== null && (
        <BoyFormModal
          boy={formModal.id ? formModal : null}
          onClose={() => setFormModal(null)}
          onSaved={load}
        />
      )}
      {delModal && (
        <DeleteConfirm
          boy={delModal}
          onConfirm={handleDelete}
          onCancel={() => setDelModal(null)}
          loading={deleting}
        />
      )}

    </div>
  );
}
