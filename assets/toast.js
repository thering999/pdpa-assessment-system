// Minimal toast.js for notification
function showToast(msg, type) {
  type = type || 'info';
  let color = '#2196f3';
  if(type==='success') color = '#4caf50';
  if(type==='error') color = '#f44336';
  let toast = document.createElement('div');
  toast.textContent = msg;
  toast.style.position = 'fixed';
  toast.style.bottom = '32px';
  toast.style.left = '50%';
  toast.style.transform = 'translateX(-50%)';
  toast.style.background = color;
  toast.style.color = '#fff';
  toast.style.padding = '14px 32px';
  toast.style.borderRadius = '8px';
  toast.style.fontSize = '1.1em';
  toast.style.boxShadow = '0 2px 12px #0005';
  toast.style.zIndex = 9999;
  toast.style.opacity = 0.97;
  document.body.appendChild(toast);
  setTimeout(()=>{ toast.style.opacity=0; setTimeout(()=>toast.remove(), 400); }, 1200);
}
