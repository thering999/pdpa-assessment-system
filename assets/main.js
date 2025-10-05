// Loading Indicator
function showLoading() {
  document.getElementById('loading-spinner').style.display = 'flex';
}
function hideLoading() {
  document.getElementById('loading-spinner').style.display = 'none';
}
// Toast Notification
function showToast(msg, success=true) {
  var t = document.getElementById('toast');
  t.innerText = msg;
  t.style.background = success ? '#3498db' : '#e74c3c';
  t.classList.add('show');
  setTimeout(()=>t.classList.remove('show'), 3000);
}
// Dark Mode Toggle
function toggleDark() {
  document.body.classList.toggle('dark');
  localStorage.setItem('dark', document.body.classList.contains('dark'));
}
window.onload = function() {
  if(localStorage.getItem('dark')==='true') document.body.classList.add('dark');
}
