// ============================================================
// WATER SUPPLY ADMIN — Brand Logo
// Renders the full brand logo from public/logo.png on auth pages,
// with a water-drop + text fallback if the image is missing.
// ============================================================

import { useState } from 'react';

// App is served under a base path (/web-app-new/), so public assets
// must be prefixed with it
const BASE = import.meta.env.BASE_URL;

// Fallback water drop icon
const DropIcon = ({ size = 22, color = 'white' }) => (
  <svg width={size} height={size} viewBox="0 0 24 24" fill={color}>
    <path d="M12 2C12 2 5 9.5 5 14a7 7 0 0 0 14 0c0-4.5-7-12-7-12z"/>
  </svg>
);

export function AuthLogo() {
  const [imgError, setImgError] = useState(false);

  if (imgError) {
    return (
      <>
        <div className="login-logo-icon">
          <DropIcon size={22} />
        </div>
        <div className="login-logo-name">Water Supply<span>Management System</span></div>
      </>
    );
  }

  return (
    <img
      src={`${BASE}logo.png`}
      alt="Water Supply Soft"
      className="auth-logo-img"
      onError={() => setImgError(true)}
    />
  );
}
