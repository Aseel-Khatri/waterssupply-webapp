// ============================================================
// WATER SUPPLY ADMIN — App Router
// ============================================================

import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './hooks/useAuth';
import LoginPage from './pages/auth/Login';
import './styles/global.css';

// ── Auth Guard ────────────────────────────────────────────
function PrivateRoute({ children }) {
  const { isAuthenticated } = useAuth();
  return isAuthenticated ? children : <Navigate to="/login" replace />;
}

// ── Dashboard placeholder (to be built) ──────────────────
function Dashboard() {
  const { user, signOut } = useAuth();
  return (
    <div style={{ padding: 40 }}>
      <h2>Dashboard — coming soon</h2>
      <p>Logged in as: {user?.user_name}</p>
      <button onClick={signOut}>Logout</button>
    </div>
  );
}

// ── Root App ──────────────────────────────────────────────
export default function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <Routes>
          <Route path="/login" element={<LoginPage />} />
          <Route
            path="/dashboard"
            element={
              <PrivateRoute>
                <Dashboard />
              </PrivateRoute>
            }
          />
          <Route path="*" element={<Navigate to="/login" replace />} />
        </Routes>
      </BrowserRouter>
    </AuthProvider>
  );
}
