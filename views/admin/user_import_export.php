<section class="card" style="max-width:700px;margin:32px auto;">
  <h2>Import/Export Users</h2>
  <form method="post" action="?a=import_users" enctype="multipart/form-data" style="margin-bottom:24px;">
    <label>Import Users (CSV): <input type="file" name="csv" accept=".csv" required></label>
    <button class="btn primary" type="submit">Import</button>
  </form>
  <form method="get" action="?a=export_users">
    <button class="btn" type="submit">Export Users (CSV)</button>
  </form>
</section>
