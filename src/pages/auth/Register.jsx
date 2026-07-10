// ============================================================
// WATER SUPPLY ADMIN — Register Page
// ============================================================

import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { register } from '../../services/api';
import './Register.css';

const IconUser    = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>;
const IconMail    = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,12 2,6"/></svg>;
const IconLock    = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>;
const IconPhone   = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 6 6l.97-.97a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>;
const IconBuilding= () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>;
const IconMapPin  = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>;
const IconEye     = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>;
const IconEyeOff  = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>;
const IconArrow   = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>;
const IconAlert   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{flexShrink:0,marginTop:'2px'}}><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>;
const IconCheck   = () => <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12"/></svg>;

export default function RegisterPage() {
  const navigate = useNavigate();

  const [form, setForm] = useState({
    fullName:       '',
    email:          '',
    password:       '',
    companyName:    '',
    countryCode:    '+92',
    phoneNumber:    '',
    companyAddress: '',
  });
  const [showPass,    setShowPass]    = useState(false);
  const [loading,     setLoading]     = useState(false);
  const [error,       setError]       = useState('');
  const [fieldErrors, setFieldErrors] = useState({});
  const [success,     setSuccess]     = useState(false);

  const set = (field) => (e) => {
    setForm(prev => ({ ...prev, [field]: e.target.value }));
    setFieldErrors(prev => ({ ...prev, [field]: '' }));
    setError('');
  };

  const validate = () => {
    const errs = {};
    if (!form.fullName.trim())       errs.fullName       = 'Full name is required';
    if (!form.email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email))
                                     errs.email          = 'Valid email is required';
    if (form.password.length < 6)    errs.password       = 'Minimum 6 characters';
    if (!form.companyName.trim())    errs.companyName    = 'Company name is required';
    if (!form.phoneNumber.trim())    errs.phoneNumber    = 'Phone number is required';
    else if (!/^\d{7,12}$/.test(form.phoneNumber.replace(/^0/, '')))
                                     errs.phoneNumber    = 'Enter a valid phone number';
    if (!form.companyAddress.trim()) errs.companyAddress = 'Address is required';
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
      await register({
        username:       form.fullName,
        email:          form.email,
        password:       form.password,
        companyName:    form.companyName,
        phoneNumber:    `${form.countryCode}${form.phoneNumber.replace(/^0/, '')}`,
        address:        form.companyAddress,
      });
      setSuccess(true);
    } catch (err) {
      setError(err.message || 'Registration failed. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  // ── Success Screen ───────────────────────────────────────
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
          <h1 className="auth-card-heading">Account Created!</h1>
          <p className="auth-card-sub">Thanks for signing up. Your account has been created successfully. You can now sign in.</p>
          <button className="btn-login" style={{marginTop:'var(--space-6)'}} onClick={() => navigate('/login')}>
            Go to Login <IconArrow />
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="register-root">

      {/* ── Left: Visual Panel ─────────────────────── */}
      <div className="login-visual">
        <div className="orb orb-1" />
        <div className="orb orb-2" />
        <div className="orb orb-3" />
        <div className="visual-content">
          <div className="visual-icon-wrap">
            <svg width="52" height="52" viewBox="0 0 24 24" fill="rgba(255,255,255,0.9)">
              <path d="M12 2C12 2 5 9.5 5 14a7 7 0 0 0 14 0c0-4.5-7-12-7-12z"/>
            </svg>
          </div>
          <h2 className="visual-title">Get Started<br />Today</h2>
          <p className="visual-desc">
            Set up your water supply business<br />
            in minutes. Manage deliveries,<br />
            customers, and reports — all in one place.
          </p>
          <div className="visual-stats">
            <div className="stat-item"><div className="stat-value">Fast</div><div className="stat-label">Setup</div></div>
            <div className="stat-divider" />
            <div className="stat-item"><div className="stat-value">Safe</div><div className="stat-label">& Secure</div></div>
            <div className="stat-divider" />
            <div className="stat-item"><div className="stat-value">24/7</div><div className="stat-label">Access</div></div>
          </div>
        </div>
      </div>

      {/* ── Right: Form Panel ──────────────────────── */}
      <div className="login-panel register-panel">
        <div className="login-logo">
          <div className="login-logo-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0 0 14 0c0-4.5-7-12-7-12z"/></svg>
          </div>
          <div className="login-logo-name">Water Supply<span>Management System</span></div>
        </div>

        <h1 className="login-heading">Create Account</h1>
        <p className="login-subheading">Fill in your details to get started</p>

        <form className="login-form" onSubmit={handleSubmit} noValidate>

          {error && (
            <div className="alert-error">
              <IconAlert />
              <span>{error}</span>
            </div>
          )}

          {/* Full Name — full width */}
          <div className="form-group">
            <label className="form-label">Full Name</label>
            <div className="input-wrapper">
              <span className="input-icon"><IconUser /></span>
              <input type="text" className={`form-input ${fieldErrors.fullName ? 'error' : ''}`}
                placeholder="Your full name" value={form.fullName} onChange={set('fullName')} autoFocus />
            </div>
            {fieldErrors.fullName && <span className="field-hint error-hint">{fieldErrors.fullName}</span>}
          </div>

          {/* Phone Number — full width, country code gets room to breathe */}
          <div className="form-group">
            <label className="form-label">Phone Number</label>
            <div className="phone-input-row">
              <select
                className="country-code-select"
                value={form.countryCode}
                onChange={e => setForm(prev => ({ ...prev, countryCode: e.target.value }))}
              >
                <option value="+92">🇵🇰 +92</option>
                <option value="+91">🇮🇳 +91</option>
                <option value="+971">🇦🇪 +971</option>
                <option value="+966">🇸🇦 +966</option>
                <option value="+1">🇺🇸 +1</option>
                <option value="+44">🇬🇧 +44</option>
              </select>
              <div className="input-wrapper phone-input-wrapper">
                <span className="input-icon"><IconPhone /></span>
                <input type="tel" className={`form-input ${fieldErrors.phoneNumber ? 'error' : ''}`}
                  placeholder="3001234567" value={form.phoneNumber}
                  onChange={e => { setForm(prev => ({ ...prev, phoneNumber: e.target.value.replace(/\D/g,'') })); setFieldErrors(prev => ({ ...prev, phoneNumber: '' })); setError(''); }} />
              </div>
            </div>
            {fieldErrors.phoneNumber && <span className="field-hint error-hint">{fieldErrors.phoneNumber}</span>}
          </div>

          {/* Email */}
          <div className="form-group">
            <label className="form-label">Email Address</label>
            <div className="input-wrapper">
              <span className="input-icon"><IconMail /></span>
              <input type="email" className={`form-input ${fieldErrors.email ? 'error' : ''}`}
                placeholder="your@email.com" value={form.email} onChange={set('email')} autoComplete="email" />
            </div>
            {fieldErrors.email && <span className="field-hint error-hint">{fieldErrors.email}</span>}
          </div>

          {/* Password */}
          <div className="form-group">
            <label className="form-label">Password</label>
            <div className="input-wrapper">
              <span className="input-icon"><IconLock /></span>
              <input
                type={showPass ? 'text' : 'password'}
                className={`form-input ${fieldErrors.password ? 'error' : ''}`}
                placeholder="Minimum 6 characters"
                value={form.password}
                onChange={set('password')}
                autoComplete="new-password"
              />
              <span className="input-suffix">
                <button type="button" className="toggle-password" onClick={() => setShowPass(v => !v)}>
                  {showPass ? <IconEyeOff /> : <IconEye />}
                </button>
              </span>
            </div>
            {fieldErrors.password && <span className="field-hint error-hint">{fieldErrors.password}</span>}
          </div>

          {/* Company Name */}
          <div className="form-group">
            <label className="form-label">Company Name</label>
            <div className="input-wrapper">
              <span className="input-icon"><IconBuilding /></span>
              <input type="text" className={`form-input ${fieldErrors.companyName ? 'error' : ''}`}
                placeholder="Your water supply company" value={form.companyName} onChange={set('companyName')} />
            </div>
            {fieldErrors.companyName && <span className="field-hint error-hint">{fieldErrors.companyName}</span>}
          </div>

          {/* Company Address */}
          <div className="form-group">
            <label className="form-label">Company Address</label>
            <div className="input-wrapper">
              <span className="input-icon"><IconMapPin /></span>
              <input type="text" className={`form-input ${fieldErrors.companyAddress ? 'error' : ''}`}
                placeholder="Street, City" value={form.companyAddress} onChange={set('companyAddress')} />
            </div>
            {fieldErrors.companyAddress && <span className="field-hint error-hint">{fieldErrors.companyAddress}</span>}
          </div>

          <button type="submit" className="btn-login" disabled={loading}>
            {loading
              ? <><span className="spinner" /> Creating Account…</>
              : <>Create Account <IconArrow /></>
            }
          </button>

          <p className="register-privacy-note">
            By creating an account, you agree to our{' '}
            <a href="https://watersupply-soft.com/Privacy-Policy.html" target="_blank" rel="noopener noreferrer">
              Privacy Policy
            </a>
          </p>

          <p className="register-signin-link">
            Already have an account? <Link to="/login">Sign in</Link>
          </p>

        </form>
      </div>

    </div>
  );
}
