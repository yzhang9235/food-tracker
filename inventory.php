<?php require_once 'backend/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FoodTracker — My Inventory</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <style>
    .inv-page {
      min-height: 100vh;
      padding: 6rem 5% 4rem;
      max-width: 1100px;
      margin: 0 auto;
    }

    .page-header {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1rem;
      margin-bottom: 2.5rem;
    }

    .page-header-left .greeting {
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: var(--rust);
      font-weight: 500;
      margin-bottom: 0.3rem;
    }

    .page-header-left h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2rem, 4vw, 2.8rem);
      color: var(--text);
      line-height: 1.1;
      opacity: 1;
      animation: none;
    }

    .page-header-left h1 em {
      font-style: italic;
      color: var(--sage);
    }

    .header-actions {
      display: flex;
      gap: 0.75rem;
      align-items: center;
    }

    .btn-logout {
      font-size: 0.85rem;
      color: var(--muted);
      text-decoration: none;
      padding: 0.5rem 1rem;
      border: 1.5px solid var(--warm);
      border-radius: 100px;
      transition: border-color .2s, color .2s;
    }

    .btn-logout:hover { border-color: var(--rust); color: var(--rust); }

    .stats-bar {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: #fff;
      border-radius: 16px;
      padding: 1.2rem 1.4rem;
      box-shadow: 0 2px 16px rgba(42,34,25,0.06);
    }

    .stat-label {
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--muted);
      margin-bottom: 0.3rem;
    }

    .stat-value {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      color: var(--text);
      line-height: 1;
    }

    .stat-value.rust { color: var(--rust); }
    .stat-value.sage { color: var(--sage-dk); }

    .add-form {
      background: #fff;
      border-radius: 20px;
      padding: 2rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 24px rgba(42,34,25,0.07);
      display: none;
    }

    .add-form.open { display: block; }

    .form-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--text);
      margin-bottom: 1.4rem;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 1rem;
      margin-bottom: 1.4rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.4rem;
    }

    .form-group label {
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--muted);
      font-weight: 500;
    }

    .form-group input,
    .form-group select {
      padding: 0.65rem 0.9rem;
      border: 1.5px solid var(--warm);
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.9rem;
      color: var(--text);
      background: var(--cream);
      outline: none;
      transition: border-color .2s;
    }

    .form-group input:focus,
    .form-group select:focus { border-color: var(--sage); }

    .form-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }

    .form-msg {
      margin-top: 0.8rem;
      font-size: 0.85rem;
      min-height: 1.2em;
    }

    .form-msg.ok  { color: var(--sage-dk); }
    .form-msg.err { color: var(--rust); }

    .toolbar {
      display: flex;
      gap: 0.75rem;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 1.2rem;
    }

    .search-wrap {
      position: relative;
      flex: 1;
      min-width: 200px;
    }

    .search-wrap input {
      width: 100%;
      padding: 0.6rem 0.9rem;
      border: 1.5px solid var(--warm);
      border-radius: 100px;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.88rem;
      background: #fff;
      outline: none;
      transition: border-color .2s;
      color: var(--text);
    }

    .search-wrap input:focus { border-color: var(--sage); }

    .filter-select {
      padding: 0.6rem 1rem;
      border: 1.5px solid var(--warm);
      border-radius: 100px;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.85rem;
      background: #fff;
      color: var(--text);
      outline: none;
      cursor: pointer;
      transition: border-color .2s;
    }

    .filter-select:focus { border-color: var(--sage); }

    .inv-table-wrap {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 24px rgba(42,34,25,0.07);
      overflow: hidden;
    }

    .inv-loading, .inv-empty {
      padding: 3rem;
      text-align: center;
      color: var(--muted);
      font-size: 0.95rem;
    }

    .inv-table {
      width: 100%;
      border-collapse: collapse;
    }

    .inv-table thead { background: var(--warm); }

    .inv-table th {
      padding: 1rem 1.2rem;
      text-align: left;
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: var(--muted);
      font-weight: 500;
      white-space: nowrap;
    }

    .inv-table td {
      padding: 1rem 1.2rem;
      font-size: 0.9rem;
      color: var(--text);
      border-bottom: 1px solid var(--warm);
      vertical-align: middle;
    }

    .inv-table tbody tr:last-child td { border-bottom: none; }
    .inv-table tbody tr { transition: background .15s; }
    .inv-table tbody tr:hover { background: rgba(245,240,232,0.5); }

    .cat-badge {
      display: inline-block;
      padding: 0.2rem 0.7rem;
      border-radius: 100px;
      font-size: 0.74rem;
      font-weight: 500;
      background: var(--warm);
      color: var(--sage-dk);
      text-transform: capitalize;
    }

    .row-actions { display: flex; gap: 0.5rem; }

    .btn-icon {
      background: none;
      border: 1.5px solid var(--warm);
      border-radius: 8px;
      padding: 0.3rem 0.65rem;
      font-size: 0.78rem;
      cursor: pointer;
      color: var(--muted);
      font-family: 'DM Sans', sans-serif;
      transition: border-color .2s, color .2s;
      white-space: nowrap;
    }

    .btn-icon:hover { border-color: var(--rust); color: var(--rust); }
    .btn-icon.edit:hover { border-color: var(--sage); color: var(--sage-dk); }

    .edit-input {
      padding: 0.35rem 0.6rem;
      border: 1.5px solid var(--sage);
      border-radius: 8px;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.88rem;
      color: var(--text);
      background: var(--cream);
      outline: none;
      width: 100%;
    }

    @media (max-width: 768px) {
      .inv-page { padding-top: 5rem; }
      .inv-table th:nth-child(4),
      .inv-table td:nth-child(4) { display: none; }
      nav .nav-links { display: none; }
    }
  </style>
</head>
<body>

  <nav>
    <a class="nav-logo" href="index.php">Food<span>Tracker</span></a>
    <div class="nav-links">
      <a href="inventory.php">My Inventory</a>
      <a href="recipes.php">Recipes</a>
      <a href="backend/logout.php" class="btn btn-nav">Sign out</a>
    </div>
  </nav>

  <div class="inv-page">

    <div class="page-header">
      <div class="page-header-left">
        <div class="greeting">My Kitchen</div>
        <h1>Your <em>inventory</em></h1>
      </div>
      <div class="header-actions">
        <button class="btn btn-primary" id="add-item-btn">+ Add item</button>
        <a href="backend/logout.php" class="btn-logout">Sign out</a>
      </div>
    </div>

    <div class="stats-bar">
      <div class="stat-card">
        <div class="stat-label">Total items</div>
        <div class="stat-value sage" id="stat-total">-</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Categories</div>
        <div class="stat-value" id="stat-cats">-</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Most recent</div>
        <div class="stat-value rust" id="stat-recent" style="font-size:1rem;padding-top:0.5rem;">-</div>
      </div>
    </div>

    <div class="add-form" id="add-form">
      <div class="form-title">Add a new item</div>
      <div class="form-grid">
        <div class="form-group">
          <label for="item-name">Item name</label>
          <input type="text" id="item-name" placeholder="e.g. Spinach" />
        </div>
        <div class="form-group">
          <label for="item-category">Category</label>
          <select id="item-category">
            <option value="produce">Produce</option>
            <option value="dairy">Dairy</option>
            <option value="meat">Meat</option>
            <option value="pantry">Pantry</option>
            <option value="frozen">Frozen</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="form-group">
          <label for="item-quantity">Quantity</label>
          <input type="text" id="item-quantity" placeholder="e.g. 1 bag" />
        </div>
        <div class="form-group">
          <label for="item-expiry">Expiration date</label>
          <input type="date" id="item-expiry" />
        </div>
      </div>
      <div class="form-actions">
        <button class="btn btn-primary" id="submit-item">Save item</button>
        <button class="btn btn-secondary" id="cancel-item">Cancel</button>
      </div>
      <p class="form-msg" id="form-msg"></p>
    </div>

    <div class="toolbar">
      <div class="search-wrap">
        <input type="text" id="search-input" placeholder="Search items..." />
      </div>
      <select class="filter-select" id="filter-cat">
        <option value="">All categories</option>
        <option value="produce">Produce</option>
        <option value="dairy">Dairy</option>
        <option value="meat">Meat</option>
        <option value="pantry">Pantry</option>
        <option value="frozen">Frozen</option>
        <option value="other">Other</option>
      </select>
    </div>

    <div class="inv-table-wrap">
      <div class="inv-loading" id="inv-loading">Loading your inventory...</div>
      <p class="inv-empty" id="inv-empty" style="display:none;">No items yet - add something above!</p>
      <table class="inv-table" id="inv-table" style="display:none;">
        <thead>
          <tr>
            <th>Item</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Date added</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="inv-body"></tbody>
      </table>
    </div>

  </div>

  <script>
    const API = 'backend';
    let allItems = [];

    async function loadInventory() {
      try {
        const res  = await fetch(`${API}/food_get.php`, { credentials: 'include' });
        const data = await res.json();
        allItems = Array.isArray(data) ? data : [];
        updateStats(allItems);
        renderTable(allItems);
      } catch (err) {
        document.getElementById('inv-loading').textContent = 'Could not load inventory.';
      }
    }

    function updateStats(items) {
      document.getElementById('stat-total').textContent = items.length;
      const cats = new Set(items.map(i => i.category).filter(Boolean));
      document.getElementById('stat-cats').textContent = cats.size || 0;
      if (items.length) {
        const latest = items.reduce((a, b) =>
          new Date(a.date_added) > new Date(b.date_added) ? a : b
        );
        document.getElementById('stat-recent').textContent = latest.item_name;
      } else {
        document.getElementById('stat-recent').textContent = '-';
      }
    }

    function renderTable(items) {
      const loading = document.getElementById('inv-loading');
      const table   = document.getElementById('inv-table');
      const empty   = document.getElementById('inv-empty');
      const tbody   = document.getElementById('inv-body');

      loading.style.display = 'none';

      if (!items || items.length === 0) {
        table.style.display = 'none';
        empty.style.display = 'block';
        return;
      }

      empty.style.display = 'none';
      table.style.display = 'table';

      tbody.innerHTML = items.map(item => `
        <tr data-id="${item.item_id}" id="row-${item.item_id}">
          <td>${escHtml(item.item_name)}</td>
          <td><span class="cat-badge">${escHtml(item.category || '-')}</span></td>
          <td>${escHtml(item.quantity || '-')}</td>
          <td>${item.date_added ? new Date(item.date_added).toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'}) : '-'}</td>
          <td>
            <div class="row-actions">
              <button class="btn-icon edit" onclick="startEdit(${item.item_id})">Edit</button>
              <button class="btn-icon" onclick="deleteItem(${item.item_id})">Delete</button>
            </div>
          </td>
        </tr>
      `).join('');
    }

    function applyFilter() {
      const q   = document.getElementById('search-input').value.trim().toLowerCase();
      const cat = document.getElementById('filter-cat').value;
      const filtered = allItems.filter(item => {
        const matchQ   = !q   || item.item_name.toLowerCase().includes(q);
        const matchCat = !cat || item.category === cat;
        return matchQ && matchCat;
      });
      renderTable(filtered);
    }

    document.getElementById('search-input').addEventListener('input', applyFilter);
    document.getElementById('filter-cat').addEventListener('change', applyFilter);

    document.getElementById('add-item-btn').addEventListener('click', () => {
      document.getElementById('add-form').classList.toggle('open');
    });

    document.getElementById('cancel-item').addEventListener('click', () => {
      document.getElementById('add-form').classList.remove('open');
      clearFormMsg();
    });

    document.getElementById('submit-item').addEventListener('click', async () => {
      const name     = document.getElementById('item-name').value.trim();
      const category = document.getElementById('item-category').value;
      const quantity = document.getElementById('item-quantity').value.trim();
      const expiry   = document.getElementById('item-expiry').value;

      if (!name) { setFormMsg('Please enter an item name.', 'err'); return; }

      const form = new FormData();
      form.append('item_name', name);
      form.append('category', category);
      form.append('quantity', quantity);
      form.append('expiration_date', expiry);

      try {
        const res  = await fetch(`${API}/food_add.php`, { method: 'POST', body: form, credentials: 'include' });
        const data = await res.json();
        if (data.success) {
          setFormMsg('Item added!', 'ok');
          document.getElementById('item-name').value     = '';
          document.getElementById('item-quantity').value = '';
          document.getElementById('item-expiry').value   = '';
          await loadInventory();
          setTimeout(() => {
            document.getElementById('add-form').classList.remove('open');
            clearFormMsg();
          }, 900);
        } else {
          setFormMsg(data.message || 'Something went wrong.', 'err');
        }
      } catch (err) {
        setFormMsg('Could not connect to server.', 'err');
      }
    });

    async function deleteItem(id) {
      if (!confirm('Remove this item from your inventory?')) return;
      const form = new FormData();
      form.append('item_id', id);
      try {
        await fetch(`${API}/food_delete.php`, { method: 'POST', body: form, credentials: 'include' });
        await loadInventory();
      } catch (err) {
        alert('Could not delete item.');
      }
    }

    function startEdit(id) {
      const row  = document.getElementById(`row-${id}`);
      const item = allItems.find(i => i.item_id == id);
      if (!item) return;

      row.innerHTML = `
        <td><input class="edit-input" id="edit-name-${id}" value="${escHtml(item.item_name)}" /></td>
        <td>
          <select class="edit-input" id="edit-cat-${id}">
            ${['produce','dairy','meat','pantry','frozen','other'].map(c =>
              `<option value="${c}" ${item.category === c ? 'selected' : ''}>${c.charAt(0).toUpperCase() + c.slice(1)}</option>`
            ).join('')}
          </select>
        </td>
        <td><input class="edit-input" id="edit-qty-${id}" value="${escHtml(item.quantity || '')}" /></td>
        <td>${item.date_added ? new Date(item.date_added).toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'}) : '-'}</td>
        <td>
          <div class="row-actions">
            <button class="btn-icon edit" onclick="saveEdit(${id})">Save</button>
            <button class="btn-icon" onclick="loadInventory()">Cancel</button>
          </div>
        </td>
      `;
    }

    async function saveEdit(id) {
      const name = document.getElementById(`edit-name-${id}`).value.trim();
      const cat  = document.getElementById(`edit-cat-${id}`).value;
      const qty  = document.getElementById(`edit-qty-${id}`).value.trim();

      if (!name) { alert('Item name cannot be empty.'); return; }

      const form = new FormData();
      form.append('item_id', id);
      form.append('item_name', name);
      form.append('category', cat);
      form.append('quantity', qty);

      try {
        const res  = await fetch(`${API}/food_update.php`, { method: 'POST', body: form, credentials: 'include' });
        const data = await res.json();
        if (data.success) {
          await loadInventory();
        } else {
          alert(data.message || 'Could not update item.');
        }
      } catch (err) {
        alert('Could not connect to server.');
      }
    }

    function setFormMsg(msg, type) {
      const el = document.getElementById('form-msg');
      el.textContent = msg;
      el.className = `form-msg ${type}`;
    }

    function clearFormMsg() {
      const el = document.getElementById('form-msg');
      el.textContent = '';
      el.className = 'form-msg';
    }

    function escHtml(str) {
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    }

    loadInventory();
  </script>
</body>
</html>