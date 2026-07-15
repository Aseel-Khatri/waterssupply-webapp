// ============================================================
// WATER SUPPLY ADMIN — Forgot Password (Step 1: Enter Email)
// ============================================================

import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { forgotPassword } from '../../services/api';
import { AuthLogo } from '../../components/common/BrandLogo';
import './ForgotPassword.css';

const IconMail  = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,12 2,6"/></svg>;
const IconArrow = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>;
const IconBack  = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="15 18 9 12 15 6"/></svg>;
const IconAlert = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{flexShrink:0,marginTop:'2px'}}><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>;

export default function ForgotPasswordPage() {
  const navigate      = useNavigate();
  const [email,       setEmail]   = useState('');
  const [loading,     setLoading] = useState(false);
  const [error,       setError]   = useState('');
  const [fieldError,  setFieldError] = useState(false);

  const validate = () => {
    if (!email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      setFieldError(true);
      return false;
    }
    return true;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    if (!validate()) return;

    setLoading(true);
    try {
      await forgotPassword(email.trim());
      // Pass email to OTP page via state
      navigate('/verify-otp', { state: { email: email.trim(), purpose: 'forgot_password' } });
    } catch (err) {
      setError(err.message || 'Failed to send OTP. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="auth-centered-root">
      <div className="auth-card">

        <div className="auth-card-logo">
          <AuthLogo />
        </div>

        <div className="auth-card-icon-wrap">
          <IconMail />
        </div>

        <h1 className="auth-card-heading">Forgot Password?</h1>
        <p className="auth-card-sub">Enter your registered email address and we'll send you a verification code.</p>

        <form className="auth-form" onSubmit={handleSubmit} noValidate>

          {error && (
            <div className="alert-error">
              <IconAlert />
              <span>{error}</span>
            </div>
          )}

          <div className="form-group">
            <label htmlFor="email" className="form-label">Email Address</label>
            <div className="input-wrapper">
              <span className="input-icon"><IconMail /></span>
              <input
                id="email"
                type="email"
                className={`form-input ${fieldError ? 'error' : ''}`}
                placeholder="Enter your email address"
                value={email}
                onChange={e => { setEmail(e.target.value); setFieldError(false); setError(''); }}
                autoComplete="email"
                autoFocus
              />
            </div>
            {fieldError && <span className="field-hint">Please enter a valid email address</span>}
          </div>

          <button type="submit" className="btn-login" disabled={loading}>
            {loading
              ? <><span className="spinner" /> Sending OTP…</>
              : <>Send Verification Code <IconArrow /></>
            }
          </button>

        </form>

        <Link to="/login" className="auth-back-link">
          <IconBack /> Back to Login
        </Link>

      </div>
    </div>
  );
}
