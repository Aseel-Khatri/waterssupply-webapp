// ============================================================
// WATER SUPPLY ADMIN — Email Verification
// Flow A (no email): update_email → OTP verify → mark verified
// Flow B (has email, not verified): forgot_password (sends OTP) → OTP verify → mark verified
// ============================================================

import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../hooks/useAuth';
import { updateEmail, forgotPassword } from '../../services/api';
import './EmailVerification.css';

const IcMail    = () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,12 2,6"/></svg>;
const IcBack    = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="15 18 9 12 15 6"/></svg>;
const IcArrow   = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>;
const IcAlert   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{flexShrink:0}}><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>;
const IcCheck   = () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12"/></svg>;

export default function EmailVerificationPage() {
  const navigate     = useNavigate();
  const { user, signIn } = useAuth();

  const hasEmail     = !!(user?.email?.trim());
  const isVerified   = user?.isEmailVerified;

  const [email,   setEmail]   = useState(user?.email ?? '');
  const [loading, setLoading] = useState(false);
  const [error,   setError]   = useState('');

  // Already verified — nothing to do
  if (isVerified) {
    return (
      <div className="ev-page">
        <div className="ev-card verified">
          <div className="ev-icon-wrap verified"><IcCheck /></div>
          <h2 className="ev-title">Email Verified</h2>
          <p className="ev-sub">{user.email}</p>
          <button className="ev-back-link" onClick={() => navigate(-1)}><IcBack /> Back</button>
        </div>
      </div>
    );
  }

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    if (!hasEmail) {
      // Flow A: save new email first, then send OTP
      if (!email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        setError('Please enter a valid email address');
        return;
      }
      setLoading(true);
      try {
        await updateEmail(email.trim());
        // Update local user with new email, isEmailVerified = false
        const updated = { ...user, email: email.trim(), isEmailVerified: false };
        signIn(updated, localStorage.getItem('auth_token') || sessionStorage.getItem('auth_token'),
          !!localStorage.getItem('auth_token'));
        // Now send OTP
        await forgotPassword(email.trim());
        navigate('/verify-otp', { state: { email: email.trim(), purpose: 'email_verification' } });
      } catch (err) {
        setError(err.message || 'Failed to save email. Please try again.');
      } finally {
        setLoading(false);
      }
    } else {
      // Flow B: email exists, just send OTP
      setLoading(true);
      try {
        await forgotPassword(user.email);
        navigate('/verify-otp', { state: { email: user.email, purpose: 'email_verification' } });
      } catch (err) {
        setError(err.message || 'Failed to send OTP. Please try again.');
      } finally {
        setLoading(false);
      }
    }
  };

  return (
    <div className="ev-page">
      <div className="ev-card">

        <button className="ev-back" onClick={() => navigate(-1)}><IcBack /></button>

        <div className="ev-icon-wrap"><IcMail /></div>

        <h2 className="ev-title">
          {hasEmail ? 'Verify your email' : 'Enter your email'}
        </h2>
        <p className="ev-sub">
          {hasEmail
            ? `We'll send a verification code to ${user.email}`
            : "Add an email address to enable password recovery and notifications"
          }
        </p>

        <form className="ev-form" onSubmit={handleSubmit} noValidate>
          {error && (
            <div className="ev-error"><IcAlert /><span>{error}</span></div>
          )}

          {!hasEmail && (
            <div className="ev-input-wrap">
              <span className="ev-input-icon"><IcMail /></span>
              <input
                type="email"
                className="ev-input"
                placeholder="your@email.com"
                value={email}
                onChange={e => { setEmail(e.target.value); setError(''); }}
                autoFocus
                autoComplete="email"
              />
            </div>
          )}

          {hasEmail && (
            <div className="ev-email-chip">
              <IcMail />
              <span>{user.email}</span>
            </div>
          )}

          <button type="submit" className="ev-btn" disabled={loading}>
            {loading
              ? <><span className="ev-spinner"/> {hasEmail ? 'Sending OTP…' : 'Saving…'}</>
              : <>{hasEmail ? 'Send Verification Code' : 'Continue'} <IcArrow /></>
            }
          </button>
        </form>

      </div>
    </div>
  );
}
