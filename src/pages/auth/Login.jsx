// ============================================================
// WATER SUPPLY ADMIN — Login Page
// ============================================================

import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth } from '../../hooks/useAuth';
import { login } from '../../services/api';
import { AuthLogo } from '../../components/common/BrandLogo';
import './Login.css';

// ── Icons ─────────────────────────────────────────────────
const IconUser  = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>;
const IconLock  = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>;
const IconEye   = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>;
const IconEyeOff= () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>;
const IconCheck = () => <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12"/></svg>;
const IconAlert = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{flexShrink:0,marginTop:'2px'}}><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>;
const IconArrow = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>;
const IconAdmin = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>;
const IconBike  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="5.5" cy="17.5" r="3.5"/><circle cx="18.5" cy="17.5" r="3.5"/><path d="M15 6a1 1 0 0 0-1-1h-2"/><path d="M8.5 17.5 L12 10 L16 14 L18.5 17.5"/><path d="M12 10 L14 6"/></svg>;
const IconExpired = () => <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>;

// ── Component ─────────────────────────────────────────────
export default function LoginPage() {
  const navigate   = useNavigate();
  const { signIn } = useAuth();

  const [identifier,  setIdentifier]  = useState('');
  const [countryCode, setCountryCode] = useState('+92');
  const [password,    setPassword]    = useState('');
  const [showPass,    setShowPass]    = useState(false);
  const [remember,    setRemember]    = useState(false);
  const [userType,    setUserType]    = useState(1);   // 1=Admin, 2=Delivery Boy
  const [loading,     setLoading]     = useState(false);
  const [error,       setError]       = useState('');
  const [expired,     setExpired]     = useState(false);
  const [fieldErrors, setFieldErrors] = useState({});

  const validate = () => {
    const errs = {};
    if (!identifier.trim()) errs.identifier = true;
    if (!password.trim())   errs.password   = true;
    return errs;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setExpired(false);

    const errs = validate();
    setFieldErrors(errs);
    if (Object.keys(errs).length) return;

    // Admin logs in with phone number — backend matches the full number
    // with country code (mobile app sends it the same way)
    const loginIdentifier = userType === 1
      ? `${countryCode}${identifier.trim().replace(/^0+/, '')}`
      : identifier.trim();

    setLoading(true);
    try {
      const user = await login({ identifier: loginIdentifier, password, userType });

      // Remember me → localStorage (survives browser restart);
      // otherwise sessionStorage (cleared when the tab closes)
      signIn(user, user.token, remember);
      navigate('/dashboard');
    } catch (err) {
      if (err.code === 'ACCOUNT_EXPIRED') {
        setExpired(true);
      } else {
        setError(err.message || 'Invalid credentials. Please try again.');
      }
    } finally {
      setLoading(false);
    }
  };

  const clearFieldError = (field) => {
    if (fieldErrors[field]) setFieldErrors(prev => ({ ...prev, [field]: false }));
    if (error) setError('');
  };

  // Label for the identifier field changes by role
  const identifierLabel       = userType === 1 ? 'Phone Number'   : 'Username / Number';
  const identifierPlaceholder = userType === 1 ? 'Enter phone number' : 'Enter username';

  // ── Expired Dialog ───────────────────────────────────────
  if (expired) {
    return (
      <div className="login-root">
        <div className="login-panel" style={{flex:1, maxWidth:480, margin:'0 auto'}}>
          <div className="login-logo">
            <AuthLogo />
          </div>

          <div className="expired-card">
            <div className="expired-icon"><IconExpired /></div>
            <h2 className="expired-title">Account Expired</h2>
            <p className="expired-desc">
              Your subscription has expired. Please renew your plan to continue using the system.
            </p>
            <button className="btn-login" onClick={() => setExpired(false)}>
              Back to Login
            </button>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="login-root">

      {/* ── Left: Form Panel ───────────────────────── */}
      <div className="login-panel">
        <div className="login-logo">
          <AuthLogo />
        </div>

        <h1 className="login-heading">Welcome back</h1>
        <p className="login-subheading">Sign in to your admin account to continue</p>

        <form className="login-form" onSubmit={handleSubmit} noValidate>

          {/* Error Alert */}
          {error && (
            <div className="alert-error">
              <IconAlert />
              <span>{error}</span>
            </div>
          )}

          {/* Role Toggle */}
          <div className="form-group">
            <label className="form-label">Login As</label>
            <div className="role-toggle">
              <button
                type="button"
                className={`role-option ${userType === 1 ? 'active' : ''}`}
                onClick={() => { setUserType(1); setIdentifier(''); setError(''); }}
              >
                <IconAdmin /> Admin
              </button>
              <button
                type="button"
                className={`role-option ${userType === 2 ? 'active' : ''}`}
                onClick={() => { setUserType(2); setIdentifier(''); setError(''); }}
              >
                <IconBike /> Delivery Boy
              </button>
            </div>
          </div>

          {/* Identifier — phone (with country code) or username depending on role */}
          <div className="form-group">
            <label htmlFor="identifier" className="form-label">{identifierLabel}</label>
            {userType === 1 ? (
              <div className="phone-input-row">
                <select
                  className="country-code-select"
                  value={countryCode}
                  onChange={e => setCountryCode(e.target.value)}
                  aria-label="Country code"
                >
                  <option value="+92">🇵🇰 +92</option>
                  <option value="+91">🇮🇳 +91</option>
                  <option value="+971">🇦🇪 +971</option>
                  <option value="+966">🇸🇦 +966</option>
                  <option value="+1">🇺🇸 +1</option>
                  <option value="+44">🇬🇧 +44</option>
                </select>
                <div className="input-wrapper phone-input-wrapper">
                  <span className="input-icon"><IconUser /></span>
                  <input
                    id="identifier"
                    type="tel"
                    className={`form-input ${fieldErrors.identifier ? 'error' : ''}`}
                    placeholder="3001234567"
                    value={identifier}
                    onChange={e => { setIdentifier(e.target.value.replace(/\D/g, '')); clearFieldError('identifier'); }}
                    autoComplete="tel"
                    autoFocus
                  />
                </div>
              </div>
            ) : (
              <div className="input-wrapper">
                <span className="input-icon"><IconUser /></span>
                <input
                  id="identifier"
                  type="text"
                  className={`form-input ${fieldErrors.identifier ? 'error' : ''}`}
                  placeholder={identifierPlaceholder}
                  value={identifier}
                  onChange={e => { setIdentifier(e.target.value); clearFieldError('identifier'); }}
                  autoComplete="username"
                  autoFocus
                />
              </div>
            )}
          </div>

          {/* Password */}
          <div className="form-group">
            <label htmlFor="password" className="form-label">Password</label>
            <div className="input-wrapper">
              <span className="input-icon"><IconLock /></span>
              <input
                id="password"
                type={showPass ? 'text' : 'password'}
                className={`form-input ${fieldErrors.password ? 'error' : ''}`}
                placeholder="Enter your password"
                value={password}
                onChange={e => { setPassword(e.target.value); clearFieldError('password'); }}
                autoComplete="current-password"
              />
              <span className="input-suffix">
                <button
                  type="button"
                  className="toggle-password"
                  onClick={() => setShowPass(v => !v)}
                  aria-label={showPass ? 'Hide password' : 'Show password'}
                >
                  {showPass ? <IconEyeOff /> : <IconEye />}
                </button>
              </span>
            </div>
          </div>

          {/* Remember + Forgot */}
          <div className="form-row">
            <label className="checkbox-label">
              <input type="checkbox" checked={remember} onChange={e => setRemember(e.target.checked)} />
              <span className="custom-checkbox"><IconCheck /></span>
              Remember me
            </label>
            <Link to="/forgot-password" className="forgot-link">Forgot password?</Link>
          </div>

          {/* Submit */}
          <button type="submit" className="btn-login" disabled={loading}>
            {loading
              ? <><span className="spinner" /> Signing in…</>
              : <>Sign In <IconArrow /></>
            }
          </button>

          <p className="register-prompt">
            Don't have an account? <Link to="/register">Create one</Link>
          </p>

        </form>
      </div>

      {/* ── Right: Visual Panel ─────────────────────── */}
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
          <h2 className="visual-title">Water Supply<br />Management</h2>
          <p className="visual-desc">
            Monitor deliveries, manage customers,<br />
            track counter sales, and generate<br />
            reports — all in one place.
          </p>
          <div className="visual-stats">
            <div className="stat-item">
              <div className="stat-value">Live</div>
              <div className="stat-label">Deliveries</div>
            </div>
            <div className="stat-divider" />
            <div className="stat-item">
              <div className="stat-value">360°</div>
              <div className="stat-label">Dashboard</div>
            </div>
            <div className="stat-divider" />
            <div className="stat-item">
              <div className="stat-value">Fast</div>
              <div className="stat-label">Reports</div>
            </div>
          </div>
        </div>
      </div>

    </div>
  );
}
