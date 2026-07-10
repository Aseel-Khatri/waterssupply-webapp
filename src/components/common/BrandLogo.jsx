// ============================================================
// WATER SUPPLY ADMIN — Brand Logo Component
// Renders logo from /logo.svg with fallback to water drop SVG
//
// FILES TO PLACE:
//   public/logo.svg      — Your brand logo (shown in sidebar, login, etc.)
//   public/logo-white.svg — White version for dark backgrounds
//   public/favicon.ico    — Browser tab icon (16x16 / 32x32)
//   public/favicon.svg    — Modern SVG favicon
//   index.html            — Update <link rel="icon"> to point to your favicon
// ============================================================

import { useState } from 'react';

// Fallback water drop icon
const DropIcon = ({ size = 22, color = 'white' }) => (
  <svg width={size} height={size} viewBox="0 0 24 24" fill={color}>
    <path d="M12 2C12 2 5 9.5 5 14a7 7 0 0 0 14 0c0-4.5-7-12-7-12z"/>
  </svg>
);

export function BrandIcon({ size = 22, variant = 'white' }) {
  const [imgError, setImgError] = useState(false);
  const src = variant === 'white' ? '/logo-white.png' : '/logo.png';

  if (imgError) return <DropIcon size={size} color={variant === 'white' ? 'white' : 'currentColor'} />;

  return (
    <img
      src={src}
      alt="Water Supply"
      width={size}
      height={size}
      onError={() => setImgError(true)}
      style={{ objectFit: 'contain' }}
    />
  );
}

export function BrandLogoFull({ variant = 'dark' }) {
  return (
    <div className="brand-logo-full">
      <div className={`brand-logo-icon ${variant}`}>
        <BrandIcon size={22} variant={variant === 'dark' ? 'color' : 'white'} />
      </div>
      <div className={`brand-logo-text ${variant}`}>
        Water Supply
        <span>Management System</span>
      </div>
    </div>
  );
}
