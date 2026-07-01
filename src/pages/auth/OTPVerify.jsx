// ============================================================
// WATER SUPPLY ADMIN — OTP Verification (Step 2)
// ============================================================

import { useState, useRef, useEffect } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { verifyOtp, forgotPassword } from '../../services/api';
import { useAuth } from '../../hooks/useAuth';
import './ForgotPassword.css';

const IconArrow  = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>;
const IconBack   = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="15 18 9 12 15 6"/></svg>;
const IconAlert  = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{flexShrink:0,marginTop:'2px'}}><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>;

const OTP_LENGTH = 6;

export default function OTPVerifyPage() {
  const navigate     = useNavigate();
  const location     = useLocation();
  const { user, signIn } = useAuth();
  const email        = location.state?.email   || '';
  const purpose      = location.state?.purpose || 'forgot_password';

  const [digits,    setDigits]    = useState(Array(OTP_LENGTH).fill(''));
  const [loading,   setLoading]   = useState(false);
  const [error,     setError]     = useState('');
  const [resending, setResending] = useState(false);
  const [resendMsg, setResendMsg] = useState('');
  const [cooldown,  setCooldown]  = useState(0); // seconds
  const inputs = useRef([]);

  // Redirect if no email in state
  useEffect(() => {
    if (!email) navigate('/forgot-password', { replace: true });
  }, [email, navigate]);

  // Cooldown countdown
  useEffect(() => {
    if (cooldown <= 0) return;
    const t = setTimeout(() => setCooldown(c => c - 1), 1000);
    return () => clearTimeout(t);
  }, [cooldown]);

  const handleResend = async () => {
    setResendMsg('');
    setError('');
    setResending(true);
    try {
      await forgotPassword(email);
      setResendMsg('A new code has been sent to your email.');
      setCooldown(60);
      setDigits(Array(OTP_LENGTH).fill(''));
      inputs.current[0]?.focus();
    } catch (err) {
      setError(err.message || 'Failed to resend code. Please try again.');
    } finally {
      setResending(false);
    }
  };

  const handleChange = (idx, val) => {
    if (!/^\d?$/.test(val)) return;         // digits only
    const next = [...digits];
    next[idx] = val;
    setDigits(next);
    setError('');
    if (val && idx < OTP_LENGTH - 1) inputs.current[idx + 1]?.focus();
  };

  const handleKeyDown = (idx, e) => {
    if (e.key === 'Backspace' && !digits[idx] && idx > 0) {
      inputs.current[idx - 1]?.focus();
    }
  };

  const handlePaste = (e) => {
    const pasted = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, OTP_LENGTH);
    if (!pasted) return;
    const next = [...digits];
    pasted.split('').forEach((ch, i) => { next[i] = ch; });
    setDigits(next);
    inputs.current[Math.min(pasted.length, OTP_LENGTH - 1)]?.focus();
    e.preventDefault();
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const otp = digits.join('');
    if (otp.length < OTP_LENGTH) { setError('Please enter the complete 6-digit code'); return; }

    setLoading(true);
    try {
      await verifyOtp({ email, otp, purpose });
      if (purpose === 'email_verification') {
        // Mark user as email verified in auth context
        const token = localStorage.getItem('auth_token') || sessionStorage.getItem('auth_token');
        signIn({ ...user, isEmailVerified: true }, token, !!localStorage.getItem('auth_token'));
        navigate('/dashboard', { replace: true });
      } else {
        navigate('/reset-password', { state: { email, otp } });
      }
    } catch (err) {
      setError(err.message || 'Invalid or expired code. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  const maskedEmail = email.replace(/(.{2}).+(@.+)/, '$1***$2');

  return (
    <div className="auth-centered-root">
      <div className="auth-card">

        <div className="auth-card-logo">
          <div className="login-logo-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0 0 14 0c0-4.5-7-12-7-12z"/></svg>
          </div>
          <div className="login-logo-name">Water Supply<span>Management System</span></div>
        </div>

        <div className="auth-card-icon-wrap otp-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>
          </svg>
        </div>

        <h1 className="auth-card-heading">Check your email</h1>
        <p className="auth-card-sub">
          We sent a 6-digit code to <strong>{maskedEmail}</strong>.<br/>Enter it below to continue.
        </p>

        <form className="auth-form" onSubmit={handleSubmit} noValidate>

          {error && (
            <div className="alert-error">
              <IconAlert />
              <span>{error}</span>
            </div>
          )}

          <div className="otp-row" onPaste={handlePaste}>
            {digits.map((d, i) => (
              <input
                key={i}
                ref={el => inputs.current[i] = el}
                type="text"
                inputMode="numeric"
                maxLength={1}
                className={`otp-input ${error ? 'error' : ''} ${d ? 'filled' : ''}`}
                value={d}
                onChange={e => handleChange(i, e.target.value)}
                onKeyDown={e => handleKeyDown(i, e)}
                autoFocus={i === 0}
              />
            ))}
          </div>

          <button type="submit" className="btn-login" disabled={loading}>
            {loading
              ? <><span className="spinner" /> Verifying…</>
              : <>Verify Code <IconArrow /></>
            }
          </button>

          {/* Resend OTP */}
          <div className="resend-row">
            {resendMsg && <p className="resend-success">{resendMsg}</p>}
            {cooldown > 0
              ? <p className="resend-cooldown">Resend code in <strong>{cooldown}s</strong></p>
              : (
                <button type="button" className="resend-btn" onClick={handleResend} disabled={resending}>
                  {resending ? <><span className="spinner spinner-sm" /> Sending…</> : 'Resend Code'}
                </button>
              )
            }
          </div>

        </form>

        <Link to="/forgot-password" className="auth-back-link">
          <IconBack /> Back
        </Link>

      </div>
    </div>
  );
}
