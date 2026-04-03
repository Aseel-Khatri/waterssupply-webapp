// ============================================================
// WATER SUPPLY ADMIN — Reset Password (Step 3)
// ============================================================

import { useState, useEffect } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import { resetPassword } from '../../services/api';
import './ForgotPassword.css';

const IconLock  = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>;
const IconEye   = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>;
const IconEyeOff= () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>;
const IconArrow = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>;
const IconAlert = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{flexShrink:0,marginTop:'2px'}}><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>;
const IconCheck = () => <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12"/></svg>;

export default function ResetPasswordPage() {
  const navigate  = useNavigate();
  const location  = useLocation();
  const email     = location.state?.email || '';
  const otp       = location.state?.otp   || '';

  const [password,    setPassword]    = useState('');
  const [confirm,     setConfirm]     = useState('');
  const [showPass,    setShowPass]    = useState(false);
  const [showConfirm, setShowConfirm] = useState(false);
  const [loading,     setLoading]     = useState(false);
  const [error,       setError]       = useState('');
  const [fieldErrors, setFieldErrors] = useState({});
  const [success,     setSuccess]     = useState(false);

  useEffect(() => {
    if (!email || !otp) navigate('/forgot-password', { replace: true });
  }, [email, otp, navigate]);

  // Password strength
  const strength = (() => {
    if (!password) return 0;
    let s = 0;
    if (password.length >= 8)        s++;
    if (/[A-Z]/.test(password))      s++;
    if (/[0-9]/.test(password))      s++;
    if (/[^A-Za-z0-9]/.test(password)) s++;
    return s;
  })();
  const strengthLabel = ['', 'Weak', 'Fair', 'Good', 'Strong'][strength];
  const strengthClass = ['', 'weak', 'fair', 'good', 'strong'][strength];

  const validate = () => {
    const errs = {};
    if (password.length < 8)  errs.password = 'Minimum 8 characters';
    if (password !== confirm)  errs.confirm  = 'Passwords do not match';
    return errs;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    const errs = validate();
    setFieldErrors(errs);
    if (Object.keys(errs).length) return;

    setLoading(true);
    try {
      await resetPassword({ email, otp, password, passwordConfirmation: confirm });
      setSuccess(true);
    } catch (err) {
      setError(err.message || 'Failed to reset password. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  if (success) {
    return (
      <div className="auth-centered-root">
        <div className="auth-card">
          <div className="auth-card-logo">
            <div className="login-logo-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0 0 14 0c0-4.5-7-12-7-12z"/></svg>
            </div>
            <div className="login-logo-name">Water Supply<span>Management System</span></div>
          </div>
          <div className="auth-card-icon-wrap success-icon"><IconCheck /></div>
          <h1 className="auth-card-heading">Password Reset!</h1>
          <p className="auth-card-sub">Your password has been updated successfully. You can now sign in with your new password.</p>
          <button className="btn-login" style={{marginTop:'var(--space-6)'}} onClick={() => navigate('/login')}>
            Back to Login <IconArrow />
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="auth-centered-root">
      <div className="auth-card">

        <div className="auth-card-logo">
          <div className="login-logo-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0 0 14 0c0-4.5-7-12-7-12z"/></svg>
          </div>
          <div className="login-logo-name">Water Supply<span>Management System</span></div>
        </div>

        <div className="auth-card-icon-wrap">
          <IconLock />
        </div>

        <h1 className="auth-card-heading">Set New Password</h1>
        <p className="auth-card-sub">Your new password must be at least 8 characters long.</p>

        <form className="auth-form" onSubmit={handleSubmit} noValidate>

          {error && (
            <div className="alert-error">
              <IconAlert />
              <span>{error}</span>
            </div>
          )}

          {/* New Password */}
          <div className="form-group">
            <label htmlFor="password" className="form-label">New Password</label>
            <div className="input-wrapper">
              <span className="input-icon"><IconLock /></span>
              <input
                id="password"
                type={showPass ? 'text' : 'password'}
                className={`form-input ${fieldErrors.password ? 'error' : ''}`}
                placeholder="Enter new password"
                value={password}
                onChange={e => { setPassword(e.target.value); setFieldErrors(p=>({...p,password:''})); }}
                autoFocus
              />
              <span className="input-suffix">
                <button type="button" className="toggle-password" onClick={() => setShowPass(v=>!v)}>
                  {showPass ? <IconEyeOff /> : <IconEye />}
                </button>
              </span>
            </div>
            {fieldErrors.password && <span className="field-hint error-hint">{fieldErrors.password}</span>}

            {/* Strength bar */}
            {password && (
              <div className="strength-bar-wrap">
                <div className="strength-bar">
                  {[1,2,3,4].map(i => (
                    <div key={i} className={`strength-seg ${i <= strength ? strengthClass : ''}`} />
                  ))}
                </div>
                <span className={`strength-label ${strengthClass}`}>{strengthLabel}</span>
              </div>
            )}
          </div>

          {/* Confirm Password */}
          <div className="form-group">
            <label htmlFor="confirm" className="form-label">Confirm Password</label>
            <div className="input-wrapper">
              <span className="input-icon"><IconLock /></span>
              <input
                id="confirm"
                type={showConfirm ? 'text' : 'password'}
                className={`form-input ${fieldErrors.confirm ? 'error' : ''}`}
                placeholder="Confirm new password"
                value={confirm}
                onChange={e => { setConfirm(e.target.value); setFieldErrors(p=>({...p,confirm:''})); }}
              />
              <span className="input-suffix">
                <button type="button" className="toggle-password" onClick={() => setShowConfirm(v=>!v)}>
                  {showConfirm ? <IconEyeOff /> : <IconEye />}
                </button>
              </span>
            </div>
            {fieldErrors.confirm && <span className="field-hint error-hint">{fieldErrors.confirm}</span>}
          </div>

          <button type="submit" className="btn-login" disabled={loading}>
            {loading
              ? <><span className="spinner" /> Resetting…</>
              : <>Reset Password <IconArrow /></>
            }
          </button>

        </form>

      </div>
    </div>
  );
}
