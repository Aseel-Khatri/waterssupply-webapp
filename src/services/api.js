// ============================================================
// WATER SUPPLY ADMIN — API Service
// Base URL: https://watersupply-soft.com/staging/api/
// ============================================================

const BASE_URL = 'https://watersupply-soft.com/staging/api';

/**
 * Core fetch wrapper — attaches token, handles errors uniformly
 */
async function request(endpoint, options = {}) {
  const token = localStorage.getItem('auth_token');

  const config = {
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...options.headers,
    },
    ...options,
  };

  const res = await fetch(`${BASE_URL}/${endpoint}`, config);
  const data = await res.json();

  if (!res.ok) {
    // Bubble backend message through
    const message = data?.message || `Error ${res.status}`;
    throw new Error(message);
  }

  return data;
}

// ── Auth ──────────────────────────────────────────────────

/**
 * Login
 * POST /login
 * Body: { user_name, password, user_type_id }
 * user_type_id: 1 = Admin, 2 = Delivery Boy
 */
export async function login({ username, password, userTypeId }) {
  return request('login', {
    method: 'POST',
    body: JSON.stringify({
      user_name:    username,
      password:     password,
      user_type_id: userTypeId,
    }),
  });
}

/**
 * Logout — invalidates Sanctum token
 * POST /logout  (authenticated)
 */
export async function logout() {
  return request('logout', { method: 'POST' });
}

/**
 * Forgot password — sends OTP to email
 * POST /forgot_password
 */
export async function forgotPassword(email) {
  return request('forgot_password', {
    method: 'POST',
    body: JSON.stringify({ email }),
  });
}

/**
 * Verify OTP
 * POST /verify_otp
 */
export async function verifyOtp({ email, otp, purpose = 'forgot_password' }) {
  return request('verify_otp', {
    method: 'POST',
    body: JSON.stringify({ email, otp, purpose }),
  });
}

/**
 * Reset password
 * POST /reset_password
 */
export async function resetPassword({ email, otp, password, passwordConfirmation }) {
  return request('reset_password', {
    method: 'POST',
    body: JSON.stringify({
      email,
      otp,
      password,
      password_confirmation: passwordConfirmation,
    }),
  });
}
