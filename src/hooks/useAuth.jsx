// ============================================================
// WATER SUPPLY ADMIN — Auth Context
// Stores mapped UserModel (mirrors Flutter's SharedPreferences)
// ============================================================

import { createContext, useContext, useState, useCallback } from 'react';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(() => {
    try {
      // Check both storages — remember-me uses localStorage, session uses sessionStorage
      const stored = localStorage.getItem('auth_user') || sessionStorage.getItem('auth_user');
      return stored ? JSON.parse(stored) : null;
    } catch {
      return null;
    }
  });

  /**
   * signIn — called after successful login
   * @param {object}  userData  - mapped UserModel object
   * @param {string}  token     - Sanctum bearer token
   * @param {boolean} remember  - persist to localStorage vs sessionStorage
   */
  const signIn = useCallback((userData, token, remember = false) => {
    const storage = remember ? localStorage : sessionStorage;
    storage.setItem('auth_token', token);
    storage.setItem('auth_user', JSON.stringify(userData));
    setUser(userData);
  }, []);

  const signOut = useCallback(() => {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_user');
    sessionStorage.removeItem('auth_token');
    sessionStorage.removeItem('auth_user');
    setUser(null);
  }, []);

  return (
    <AuthContext.Provider value={{ user, signIn, signOut, isAuthenticated: !!user }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth must be used inside <AuthProvider>');
  return ctx;
}
