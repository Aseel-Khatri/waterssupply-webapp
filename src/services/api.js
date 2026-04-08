// ============================================================
// WATER SUPPLY ADMIN — API Service
// Base URL: https://watersupply-soft.com/staging/api/
// Field names mirror Flutter UserModel.fromJson exactly
// ============================================================

const BASE_URL = 'https://watersupply-soft.com/staging/api';

// ── Core request wrapper ──────────────────────────────────
async function request(endpoint, options = {}) {
  const token = localStorage.getItem('auth_token') || sessionStorage.getItem('auth_token');

  const config = {
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...options.headers,
    },
    ...options,
  };

  const res  = await fetch(`${BASE_URL}/${endpoint}`, config);
  const data = await res.json();

  if (!res.ok) {
    const message = data?.message || `Error ${res.status}`;
    throw new Error(message);
  }

  return data;
}

// ── User Model mapper ─────────────────────────────────────
// Maps raw API JSON → consistent JS object
// Mirrors Flutter UserModel.fromJson field-for-field
export function mapUser(raw) {
  return {
    id:               raw.id,
    userTypeId:       raw.type,              // 1 = Admin, 2 = Delivery Boy
    userName:         raw.user_name,
    email:            raw.email,
    coName:           raw.com_name,
    phoneNo:          raw.phone_number,
    deliveryBoyLimit: raw.delivery_boy_limit ?? 1,
    expiryDate:       raw.end_datee,
    isAddFree:        raw.is_add_free ?? 0,
    isEmailVerified:  raw.is_email_verified ?? false,
    token:            raw.token,
  };
}

// ── Auth ──────────────────────────────────────────────────

/**
 * Login
 * POST /login
 * Body: { phone_number, password, type }
 *
 * type 1 = Admin    → identifier is phone number
 * type 2 = Delivery Boy → identifier is user_name/number
 *
 * Backend error responses come as data.data strings:
 *   "Invalid"                  → wrong credentials
 *   "Your Account is expired"  → expired account
 */
export async function login({ identifier, password, userType }) {
  const res = await request('login', {
    method: 'POST',
    body: JSON.stringify({
      phone_number: identifier,
      password:     password,
      type:         String(userType),
    }),
  });

  const payload = res.data ?? res;

  if (payload === 'Invalid' || payload?.message === 'Invalid') {
    throw new Error('Invalid phone number or password');
  }
  if (payload === 'Your Account is expired') {
    const err = new Error('Your account has expired. Please renew your subscription.');
    err.code  = 'ACCOUNT_EXPIRED';
    throw err;
  }

  return mapUser(payload);
}

// ── Support ───────────────────────────────────────────────

export async function getSupport() {
  return request('get_support', { method: 'GET'});
}

// ── Expenses ──────────────────────────────────────────────

export async function getExpenses({ from_date, to_date, page }) {
  return request('get_expenses', {
    method: 'POST',
    body: JSON.stringify({ from_date, to_date, page }),
  });
}

export async function saveExpense({ exp_name, price, datee }) {
  const body = { exp_name, price };
  if (datee) body.datee = datee;
  return request('save_expense', { method: 'POST', body: JSON.stringify(body) });
}

export async function editExpense({ exp_id, exp_name, price }) {
  return request('exp_edit', {
    method: 'POST',
    body: JSON.stringify({ exp_id, exp_name, price }),
  });
}

export async function deleteExpense(exp_id) {
  return request('exp_delete', {
    method: 'POST',
    body: JSON.stringify({ exp_id }),
  });
}

// ── Counter Sales ─────────────────────────────────────────

export async function getCounterSales({ page, date }) {
  return request('get_counter_sale', {
    method: 'POST',
    body: JSON.stringify({ page, date }),
  });
}

export async function addCounterSale({ amount }) {
  return request('add_counter_sale', {
    method: 'POST',
    body: JSON.stringify({ amount }),
  });
}

export async function updateCounterSale({ amount, sale_id, date_, new_sale_amount, old_sale_amount }) {
  return request('update_counter_sale', {
    method: 'POST',
    body: JSON.stringify({ amount, sale_id, date_, new_sale_amount, old_sale_amount }),
  });
}

export async function deleteCounterSale({ sale_id, date_, sale_amount }) {
  return request('delete_counter_sale', {
    method: 'POST',
    body: JSON.stringify({ sale_id, date_, sale_amount }),
  });
}

// ── Delivery Boys ─────────────────────────────────────────

export async function getDeliveryBoys() {
  return request('get_delivery_boy', { method: 'GET' });
}

export async function addDeliveryBoy({ username, password }) {
  return request('add_delivery_boy', {
    method: 'POST',
    body: JSON.stringify({ username, password }),
  });
}

export async function editDeliveryBoy({ username, password, id }) {
  return request('edit_delivery_boy', {
    method: 'POST',
    body: JSON.stringify({ username, password, id }),
  });
}

export async function deleteDeliveryBoy(id) {
  return request('delete_delivery_boy', {
    method: 'POST',
    body: JSON.stringify({ id }),
  });
}

// ── Transactions ──────────────────────────────────────────

/**
 * Edit a transaction row
 * POST /customer_detail_edit
 */
export async function updateTransaction(formData) {
  return request('customer_detail_edit', {
    method: 'POST',
    body: JSON.stringify(formData),
  });
}

// ── Customers ─────────────────────────────────────────────

/**
 * Customer detail
 * POST /customer_detail
 * Returns personal_details + filling_data
 */
export async function getCustomerDetail(customerId) {
  return request('customer_detail', {
    method: 'POST',
    body: JSON.stringify({ customer_id: String(customerId) }),
  });
}

/**
 * Get customers list
 * POST /get_customers
 * status: 0=active, 1=inactive
 */
export async function getCustomers({ status, page, sort, keyword }) {
  const body = { status: String(status), page: String(page), limit: '25', sort };
  if (keyword) body.keyword = keyword;
  return request('get_customers', { method: 'POST', body: JSON.stringify(body) });
}

/**
 * Toggle customer active/inactive
 * POST /update_customer_status
 * status: 'active' | 'inactive'
 */
export async function toggleCustomerStatus(customerId, currentStatus) {
  const newStatus = currentStatus === 0 ? 'inactive' : 'active';
  return request('update_customer_status', {
    method: 'POST',
    body: JSON.stringify({ customer_id: customerId, status: newStatus }),
  });
}

/**
 * Update delivery days
 * POST /edit_day_of_giving
 */
export async function updateDeliveryDays(customerId, days) {
  return request('edit_day_of_giving', {
    method: 'POST',
    body: JSON.stringify({ customer_id: customerId, days_of_giving: days }),
  });
}

/**
 * Save (register or update) a customer
 * POST /save_customer
 * For new:  include amount_blnc, bottle_blnc
 * For edit: include customer_id, is_bottle instead
 */
export async function saveCustomer(formData) {
  return request('save_customer', {
    method: 'POST',
    body: JSON.stringify(formData),
  });
}

// ── Dashboard ─────────────────────────────────────────────

/**
 * Dashboard stats
 * POST /dashboard_stats  (adjust endpoint if different)
 * Returns counts: todayDeliveries, activeCustomers, inactiveCustomers,
 *   deliveryBoys, counterSales
 */
export async function getDashboardStats() {
  return request('dashboard_stats', { method: 'POST', body: JSON.stringify({}) });
}

// ── Deliveries ────────────────────────────────────────────

/**
 * Get deliveries
 * POST /deliveries
 * Body: { filter, page, sort, keyword? }
 * filter: 'today' | 'all'
 */
export async function getDeliveries({ filter, page, sort, keyword }) {
  const body = { filter, page: String(page), sort };
  if (keyword) body.keyword = keyword;
  return request('deliveries', {
    method: 'POST',
    body: JSON.stringify(body),
  });
}

/**
 * Register
 * POST /register
 * Body: { username, email, password, company_name, phone_number, address, is_app_register }
 */
export async function register({ username, email, password, companyName, phoneNumber, address }) {
  return request('register', {
    method: 'POST',
    body: JSON.stringify({
      username,
      email,
      password,
      company_name:    companyName,
      phone_number:    phoneNumber,
      address,
      is_app_register: 1,
    }),
  });
}

/**
 * Logout
 * POST /logout  (requires auth token)
 */
export async function logout() {
  return request('logout', { method: 'POST' });
}

/**
 * Forgot password — triggers OTP email
 * POST /forgot_password
 * Body: { email }
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
 * Body: { email, otp, purpose }
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
 * Body: { email, otp, password, password_confirmation }
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
