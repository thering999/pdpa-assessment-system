// Chart.js CDN loader for dashboard
(function(){
  if (window.Chart) return;
  var s = document.createElement('script');
  s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
  s.onload = function() { window.dispatchEvent(new Event('chartjs:ready')); };
  document.head.appendChild(s);
})();
