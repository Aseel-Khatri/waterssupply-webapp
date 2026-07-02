// ============================================================
// WATER SUPPLY ADMIN — Map Picker Modal
// Uses Leaflet (free) + Nominatim reverse geocoding (free)
// Returns: { type: 'latlng', lat, lng, address }
// ============================================================

import { useState, useEffect, useRef } from 'react';
import './MapPickerModal.css';

const IcX        = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>;
const IcLocate   = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M12 2v4m0 12v4m10-10h-4M6 12H2"/><circle cx="12" cy="12" r="4"/></svg>;
const IcMapPin   = () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>;

// Nominatim reverse geocode (free, no API key needed)
async function reverseGeocode(lat, lng) {
  try {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1&accept-language=en`;
    const res = await fetch(url, {
      headers: { 'User-Agent': 'WaterSupplyApp', 'Accept-Language': 'en' },
    });
    if (!res.ok) return 'Address not found';
    const data = await res.json();
    const a = data.address || {};
    const parts = [a.road, a.suburb, a.city, a.state, a.postcode].filter(Boolean);
    return parts.length ? parts.join(', ') : data.display_name || 'Unknown address';
  } catch {
    return 'Failed to get address';
  }
}

export default function MapPickerModal({ initialLat, initialLng, onClose, onConfirm }) {
  const mapRef      = useRef(null);
  const mapInstance  = useRef(null);
  const markerRef   = useRef(null);
  const [ready,     setReady]     = useState(false);
  const [pos,       setPos]       = useState({ lat: initialLat || 24.8607, lng: initialLng || 67.0011 });
  const [address,   setAddress]   = useState('');
  const [addrLoad,  setAddrLoad]  = useState(false);
  const [locating,  setLocating]  = useState(false);
  const [permError, setPermError] = useState('');

  // Load Leaflet CSS + JS dynamically (no npm install needed)
  useEffect(() => {
    if (window.L) { setReady(true); return; }

    const css = document.createElement('link');
    css.rel = 'stylesheet';
    css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
    document.head.appendChild(css);

    const js = document.createElement('script');
    js.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    js.onload = () => setReady(true);
    document.head.appendChild(js);
  }, []);

  // Get current location on mount if no initial coords
  useEffect(() => {
    if (!initialLat && !initialLng) {
      getCurrentLocation();
    }
  }, []);

  // Init map once Leaflet is ready
  useEffect(() => {
    if (!ready || !mapRef.current || mapInstance.current) return;

    const L = window.L;
    const map = L.map(mapRef.current).setView([pos.lat, pos.lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap',
      maxZoom: 19,
    }).addTo(map);

    const marker = L.marker([pos.lat, pos.lng], { draggable: true }).addTo(map);

    marker.on('dragend', () => {
      const { lat, lng } = marker.getLatLng();
      updatePosition(lat, lng);
    });

    map.on('click', (e) => {
      marker.setLatLng(e.latlng);
      updatePosition(e.latlng.lat, e.latlng.lng);
    });

    mapInstance.current = map;
    markerRef.current   = marker;

    // Fetch initial address
    fetchAddress(pos.lat, pos.lng);

    // Fix tile rendering issue in modals
    setTimeout(() => map.invalidateSize(), 200);

    return () => { map.remove(); mapInstance.current = null; };
  }, [ready]);

  const updatePosition = (lat, lng) => {
    setPos({ lat, lng });
    fetchAddress(lat, lng);
  };

  const fetchAddress = async (lat, lng) => {
    setAddrLoad(true);
    const addr = await reverseGeocode(lat, lng);
    setAddress(addr);
    setAddrLoad(false);
  };

  const getCurrentLocation = () => {
    if (!navigator.geolocation) {
      setPermError('Geolocation is not supported by your browser');
      return;
    }
    setLocating(true);
    setPermError('');
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        setPos({ lat, lng });
        if (mapInstance.current) {
          mapInstance.current.setView([lat, lng], 16);
          markerRef.current?.setLatLng([lat, lng]);
        }
        fetchAddress(lat, lng);
        setLocating(false);
      },
      (error) => {
        setLocating(false);
        switch (error.code) {
          case error.PERMISSION_DENIED:
            setPermError('Location access denied. Please enable it in browser settings.');
            break;
          case error.POSITION_UNAVAILABLE:
            setPermError('Location unavailable. Try again.');
            break;
          case error.TIMEOUT:
            setPermError('Location request timed out. Try again.');
            break;
          default:
            setPermError('Failed to get location.');
        }
      },
      { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
  };

  const handleConfirm = () => {
    onConfirm({
      type: 'latlng',
      lat: pos.lat,
      lng: pos.lng,
      address: address || `${pos.lat.toFixed(6)}, ${pos.lng.toFixed(6)}`,
    });
  };

  return (
    <div className="mp-backdrop" onClick={onClose}>
      <div className="mp-modal" onClick={e => e.stopPropagation()}>

        {/* Header */}
        <div className="mp-header">
          <div className="mp-header-left">
            <div className="mp-header-icon"><IcMapPin /></div>
            <div>
              <h3 className="mp-title">Select Location</h3>
              <p className="mp-sub">Tap on map or drag the marker</p>
            </div>
          </div>
          <button className="mp-close" onClick={onClose}><IcX /></button>
        </div>

        {/* Map */}
        <div className="mp-map-container">
          {!ready && (
            <div className="mp-loading">
              <div className="spinner-ring" />
              <span>Loading map…</span>
            </div>
          )}
          <div ref={mapRef} className="mp-map" />

          {/* My Location button */}
          <button
            className="mp-locate-btn"
            onClick={getCurrentLocation}
            disabled={locating}
            title="Go to my location"
          >
            {locating ? <div className="spinner-xs" /> : <IcLocate />}
          </button>
        </div>

        {/* Address bar + confirm */}
        <div className="mp-footer">
          {permError && <div className="mp-perm-error">{permError}</div>}

          <div className="mp-address-bar">
            <IcMapPin />
            <span className={`mp-address ${addrLoad ? 'loading' : ''}`}>
              {addrLoad ? 'Getting address…' : (address || 'Move the marker to set location')}
            </span>
          </div>

          <div className="mp-coords">
            <span>{pos.lat.toFixed(6)}, {pos.lng.toFixed(6)}</span>
          </div>

          <button className="mp-confirm-btn" onClick={handleConfirm} disabled={addrLoad}>
            Confirm Location
          </button>
        </div>

      </div>
    </div>
  );
}
