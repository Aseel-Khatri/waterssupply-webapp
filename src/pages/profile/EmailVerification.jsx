// ============================================================
// WATER SUPPLY ADMIN — Email Verification / Change
// Flow A (no email):        update_email → OTP → mark verified
// Flow B (has email, changing): update_email → OTP → mark verified
// Flow C (has email, just verify): forgot_password → OTP → mark verified
// ============================================================

import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../hooks/useAuth';
import { updateEmail, forgotPassword } from '../../services/api';
import './EmailVerification.css';

const IcMail  = () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,12 2,6"/></svg>;
const IcBack  = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="15 18 9 12 15 6"/></svg>;
const IcArrow = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>;
const IcAlert = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{flexShrink:0}}><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>;
const IcEdit  = () => <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>;

export default function EmailVerificationPage() {
  const navigate         = useNavigate();
  const { user, signIn } = useAuth();

  const hasEmail   = !!(user?.email?.trim());
  const isVerified = !!user?.isEmailVerified;

  // If verified, start in "change" mode (show input); otherwise show current email chip
  const [changing, setChanging] = useState(!hasEmail); // true = show input field
  const [email,    setEmail]    = useState(user?.email ?? '');
  const [loading,  setLoading]  = useState(false);
  const [error,    setError]    = useState('');

  const needsInput = !hasEmail || changing;

  // Determine if the submitted email is different from current
  const emailChanged = email.trim().toLowerCase() !== (user?.email ?? '').toLowerCase();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    const targetEmail = needsInput ? email.trim() : user.email;

    // Validate if input is shown
    if (needsInput) {
      if (!targetEmail || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(targetEmail)) {
        setError('Please enter a valid email address');
        return;
      }
    }

    setLoading(true);
    try {
      if (needsInput) {
        // Always call update_email when input is shown (new email or change)
        await updateEmail(targetEmail);
        // Update auth context: new email, isEmailVerified = false
        const token = localStorage.getItem('auth_token') || sessionStorage.getItem('auth_token');
        signIn(
          { ...user, email: targetEmail, isEmailVerified: false },
          token,
          !!localStorage.getItem('auth_token')
        );
        // Send OTP to the new email
        await forgotPassword(targetEmail);
        navigate('/verify-otp', { state: { email: targetEmail, purpose: 'email_verification' } });
      } else {
        // Email exists and not changing — just send OTP to verify
        await forgotPassword(targetEmail);
        navigate('/verify-otp', { state: { email: targetEmail, purpose: 'email_verification' } });
      }
    } catch (err) {
      setError(err.message || 'Something went wrong. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  // Title / subtitle logic
  const title = !hasEmail
    ? 'Enter your email'
    : changing
    ? 'Change email'
    : isVerified
    ? 'Email verified'
    : 'Verify your email';

  const subtitle = !hasEmail
    ? 'Add an email address to enable password recovery and notifications'
    : changing
    ? 'Enter a new email address. A verification code will be sent to it.'
    : isVerified
    ? `Your email ${user.email} is verified. You can change it below.`
    : `We'll send a verification code to ${user.email}`;

  const btnLabel = needsInput
    ? (loading ? 'Saving…' : 'Save & Send Code')
    : (loading ? 'Sending Code…' : 'Send Verification Code');

  return (
    <div className="ev-page">
      <div className="ev-card">

        <button className="ev-back" onClick={() => navigate(-1)}><IcBack /></button>

        <div className={`ev-icon-wrap ${isVerified && !changing ? 'verified' : ''}`}>
          <IcMail />
        </div>

        <h2 className="ev-title">{title}</h2>
        <p className="ev-sub">{subtitle}</p>

        <form className="ev-form" onSubmit={handleSubmit} noValidate>

          {error && (
            <div className="ev-error"><IcAlert /><span>{error}</span></div>
          )}

          {/* Show input when no email, or when user clicked "Change" */}
          {needsInput ? (
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
          ) : (
            /* Show current email chip with a "Change" button */
            <div className="ev-email-chip">
              <IcMail />
              <span className="ev-chip-email">{user.email}</span>
              <button
                type="button"
                className="ev-change-btn"
                onClick={() => { setChanging(true); setError(''); }}
              >
                <IcEdit /> Change
              </button>
            </div>
          )}

          <button type="submit" className="ev-btn" disabled={loading}>
            {loading
              ? <><span className="ev-spinner" /> {btnLabel}</>
              : <>{btnLabel} <IcArrow /></>
            }
          </button>

        </form>

      </div>
    </div>
  );
}
