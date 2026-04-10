// ============================================================
// WATER SUPPLY ADMIN — App Router
// ============================================================

import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './hooks/useAuth';

// Layout
import AppLayout from './components/layout/AppLayout';

// Auth pages
import LoginPage          from './pages/auth/Login';
import RegisterPage       from './pages/auth/Register';
import ForgotPasswordPage from './pages/auth/ForgotPassword';
import OTPVerifyPage      from './pages/auth/OTPVerify';
import ResetPasswordPage  from './pages/auth/ResetPassword';

// App pages
import DashboardPage   from './pages/dashboard/Dashboard';
import DeliveriesPage      from './pages/deliveries/Deliveries';
import CustomerRegisterPage from './pages/customers/CustomerRegister';
import CustomersListPage    from './pages/customers/CustomersList';
import CustomerDetailPage   from './pages/customers/CustomerDetail';
import DeliveryBoysPage     from './pages/delivery-boys/DeliveryBoys';
import CounterSalesPage     from './pages/counter-sales/CounterSales';
import ExpensesPage         from './pages/expenses/Expenses';
import SupportPage          from './pages/support/Support';
import EmailVerificationPage from './pages/profile/EmailVerification';
import SubscriptionPage      from './pages/subscription/Subscription';

import './styles/global.css';

// ── Guards ────────────────────────────────────────────────
function PrivateRoute({ children }) {
  const { isAuthenticated } = useAuth();
  return isAuthenticated ? children : <Navigate to="/login" replace />;
}

// Admin-only guard — delivery boys only see deliveries
function AdminRoute({ children }) {
  const { user } = useAuth();
  if (!user) return <Navigate to="/login" replace />;
  return user.userTypeId === 1 ? children : <Navigate to="/deliveries" replace />;
}

// ── Root App ──────────────────────────────────────────────
export default function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <Routes>

          {/* ── Public (auth) routes ──────────────────── */}
          <Route path="/login"           element={<LoginPage />} />
          <Route path="/register"        element={<RegisterPage />} />
          <Route path="/forgot-password" element={<ForgotPasswordPage />} />
          <Route path="/verify-otp"      element={<OTPVerifyPage />} />
          <Route path="/reset-password"  element={<ResetPasswordPage />} />

          {/* ── Protected routes (inside layout shell) ── */}
          <Route
            element={
              <PrivateRoute>
                <AppLayout />
              </PrivateRoute>
            }
          >
            <Route
              path="/dashboard"
              element={
                <AdminRoute>
                  <DashboardPage />
                </AdminRoute>
              }
            />
            <Route path="/deliveries" element={<DeliveriesPage />} />
            <Route path="/customers/register" element={<AdminRoute><CustomerRegisterPage /></AdminRoute>} />
            <Route path="/customers/active"   element={<AdminRoute><CustomersListPage /></AdminRoute>} />
            <Route path="/customers/inactive" element={<AdminRoute><CustomersListPage /></AdminRoute>} />
            <Route path="/customers/:id"        element={<AdminRoute><CustomerDetailPage /></AdminRoute>} />
            <Route path="/delivery-boys"         element={<AdminRoute><DeliveryBoysPage /></AdminRoute>} />
            <Route path="/delivery-boys/add"     element={<AdminRoute><DeliveryBoysPage /></AdminRoute>} />
            <Route path="/counter-sale"          element={<AdminRoute><CounterSalesPage /></AdminRoute>} />
            <Route path="/counter-sales"         element={<AdminRoute><CounterSalesPage /></AdminRoute>} />
            <Route path="/expenses"              element={<AdminRoute><ExpensesPage /></AdminRoute>} />
            <Route path="/support"               element={<SupportPage />} />
            <Route path="/verify-email"          element={<EmailVerificationPage />} />
            <Route path="/subscription"          element={<SubscriptionPage />} />
            {/* Other pages to be added here */}
          </Route>

          {/* ── Catch-all ─────────────────────────────── */}
          <Route path="*" element={<Navigate to="/login" replace />} />

        </Routes>
      </BrowserRouter>
    </AuthProvider>
  );
}
