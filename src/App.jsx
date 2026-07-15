// ============================================================
// WATER SUPPLY ADMIN — App Router
// ============================================================

import { lazy, Suspense } from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './hooks/useAuth';

// Layout + Login stay in the main bundle (most common cold entry);
// every other page is code-split into its own chunk so the initial
// download stays small.
import AppLayout from './components/layout/AppLayout';
import LoginPage from './pages/auth/Login';

// Auth pages
const RegisterPage       = lazy(() => import('./pages/auth/Register'));
const ForgotPasswordPage = lazy(() => import('./pages/auth/ForgotPassword'));
const OTPVerifyPage      = lazy(() => import('./pages/auth/OTPVerify'));
const ResetPasswordPage  = lazy(() => import('./pages/auth/ResetPassword'));

// App pages
const DashboardPage         = lazy(() => import('./pages/dashboard/Dashboard'));
const DeliveriesPage        = lazy(() => import('./pages/deliveries/Deliveries'));
const CustomerRegisterPage  = lazy(() => import('./pages/customers/CustomerRegister'));
const CustomersListPage     = lazy(() => import('./pages/customers/CustomersList'));
const CustomerDetailPage    = lazy(() => import('./pages/customers/CustomerDetail'));
const DeliveryBoysPage      = lazy(() => import('./pages/delivery-boys/DeliveryBoys'));
const CounterSalesPage      = lazy(() => import('./pages/counter-sales/CounterSales'));
const ExpensesPage          = lazy(() => import('./pages/expenses/Expenses'));
const SupportPage           = lazy(() => import('./pages/support/Support'));
const EmailVerificationPage = lazy(() => import('./pages/profile/EmailVerification'));
const PlantPage             = lazy(() => import('./pages/plant/Plant'));
const SubscriptionPage      = lazy(() => import('./pages/subscription/Subscription'));
const ProfilePage           = lazy(() => import('./pages/profile/Profile'));

import './styles/global.css';

// Shown while a lazy page chunk downloads
function PageLoader() {
  return (
    <div style={{ display:'flex', alignItems:'center', justifyContent:'center', minHeight:'50vh' }}>
      <div className="page-loader-spinner" />
    </div>
  );
}

// ── Guards ────────────────────────────────────────────────
function PrivateRoute({ children }) {
  const { isAuthenticated } = useAuth();
  return isAuthenticated ? children : <Navigate to="/login" replace />;
}

function AdminRoute({ children }) {
  const { user } = useAuth();
  if (!user) return <Navigate to="/login" replace />;
  return user.userTypeId === 1 ? children : <Navigate to="/deliveries" replace />;
}

function CatchAll() {
  const { isAuthenticated } = useAuth();
  return <Navigate to={isAuthenticated ? '/dashboard' : '/login'} replace />;
}
export default function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <Suspense fallback={<PageLoader />}>
        <Routes>

          {/* ── Public routes ─────────────────────────── */}
          <Route path="/login"           element={<LoginPage />} />
          <Route path="/register"        element={<RegisterPage />} />
          <Route path="/forgot-password" element={<ForgotPasswordPage />} />
          <Route path="/verify-otp"      element={<OTPVerifyPage />} />
          <Route path="/reset-password"  element={<ResetPasswordPage />} />

          {/* ── Protected routes ──────────────────────── */}
          <Route element={<PrivateRoute><AppLayout /></PrivateRoute>}>

            {/* Admin only */}
            <Route path="/dashboard"          element={<AdminRoute><DashboardPage /></AdminRoute>} />
            <Route path="/customers/register" element={<AdminRoute><CustomerRegisterPage /></AdminRoute>} />
            <Route path="/customers/edit/:id" element={<AdminRoute><CustomerRegisterPage /></AdminRoute>} />
            <Route path="/customers/active"   element={<AdminRoute><CustomersListPage /></AdminRoute>} />
            <Route path="/customers/inactive" element={<AdminRoute><CustomersListPage /></AdminRoute>} />
            <Route path="/customers/:id"      element={<AdminRoute><CustomerDetailPage /></AdminRoute>} />
            <Route path="/delivery-boys"      element={<AdminRoute><DeliveryBoysPage /></AdminRoute>} />
            <Route path="/delivery-boys/add"  element={<AdminRoute><DeliveryBoysPage /></AdminRoute>} />
            <Route path="/counter-sale"       element={<AdminRoute><CounterSalesPage /></AdminRoute>} />
            <Route path="/counter-sales"      element={<AdminRoute><CounterSalesPage /></AdminRoute>} />
            <Route path="/plant"             element={<AdminRoute><PlantPage /></AdminRoute>} />
            <Route path="/expenses"           element={<AdminRoute><ExpensesPage /></AdminRoute>} />

            {/* All users */}
            <Route path="/deliveries"   element={<DeliveriesPage />} />
            <Route path="/support"      element={<SupportPage />} />
            <Route path="/subscription" element={<SubscriptionPage />} />
            <Route path="/profile"      element={<ProfilePage />} />
            <Route path="/verify-email" element={<EmailVerificationPage />} />

          </Route>

          <Route path="*" element={<CatchAll />} />

        </Routes>
        </Suspense>
      </BrowserRouter>
    </AuthProvider>
  );
}
