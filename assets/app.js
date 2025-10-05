// Loading indicator
function showLoading() { document.getElementById('loading').style.display='flex'; }
function hideLoading() { document.getElementById('loading').style.display='none'; }

// Toast notification
function showToast(msg) {
  var t=document.getElementById('toast');
  t.textContent=msg;t.className='show';
  setTimeout(()=>{t.className='';},3000);
}

// Dark mode toggle
function toggleDark() {
  document.body.classList.toggle('dark');
  localStorage.setItem('dark',document.body.classList.contains('dark'));
}
window.onload=function(){
  if(localStorage.getItem('dark')==='true')document.body.classList.add('dark');
}

// Sidebar dropdown
function toggleDropdown(el) {
  el.classList.toggle('open');
}

// AJAX polling for notifications
function pollNotifications() {
  fetch('notifications_api.php')
    .then(r=>r.json())
    .then(data=>{
      if(data.unread>0) {
        document.getElementById('bell').innerHTML='🔔<span style="color:red;font-weight:bold;">'+data.unread+'</span>';
      }
      setTimeout(pollNotifications,10000);
    });
}
// เรียกใช้ใน header ถ้ามี bell
if(document.getElementById('bell')) pollNotifications();
