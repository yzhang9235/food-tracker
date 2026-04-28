<?php require_once 'backend/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FoodTracker — My Inventory</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="styles.css" />

  <style>
    .inv-page {
      min-height: 100vh;
      padding: 6rem 5% 4rem;
      max-width: 1200px;
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
      margin: 0;
    }

    .page-header-left h1 em {
      font-style: italic;
      color: var(--sage);
    }

    .header-actions {
      display: flex;
      gap: 0.75rem;
      align-items: center;
      flex-wrap: wrap;
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

    .btn-logout:hover {
      border-color: var(--rust);
      color: var(--rust);
    }

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

    .camera-wrap {
      display: none;
      background: #fff;
      border-radius: 20px;
      padding: 1.2rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 24px rgba(42,34,25,0.07);
    }

    .camera-wrap.open { display: block; }

    #camera-video {
      width: 100%;
      max-width: 520px;
      border-radius: 14px;
      display: block;
      margin: 0 auto 1rem;
      background: #000;
    }

    .camera-actions {
      display: flex;
      gap: 0.75rem;
      justify-content: center;
      flex-wrap: wrap;
    }

    #scan-result {
      margin-top: 0.9rem;
      text-align: center;
      color: var(--muted);
      font-size: 0.9rem;
    }

    .add-form {
      display: none;
      background: #fff;
      border-radius: 20px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 24px rgba(42,34,25,0.07);
    }

    .add-form.open { display: block; }

    .form-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--text);
      margin-bottom: 1.5rem;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.4rem;
    }

    .form-group label {
      font-size: 0.78rem;
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
      transition: border-color .2s;
      outline: none;
    }

    .form-group input:focus,
    .form-group select:focus {
      border-color: var(--sage);
    }

    .form-actions {
      display: flex;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    .form-msg {
      margin-top: 0.8rem;
      font-size: 0.85rem;
      color: var(--sage-dk);
    }

    .toolbar {
      display: flex;
      gap: 0.9rem;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 1.2rem;
    }

    .search-wrap {
      flex: 1;
      min-width: 240px;
    }

    .search-wrap input,
    .filter-select {
      width: 100%;
      padding: 0.8rem 0.95rem;
      border: 1.5px solid var(--warm);
      border-radius: 12px;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.92rem;
      color: var(--text);
      background: #fff;
      outline: none;
    }

    .filter-select {
      width: auto;
      min-width: 180px;
    }

    .inv-table-wrap {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 24px rgba(42,34,25,0.07);
      overflow: hidden;
    }

    .inv-loading {
      padding: 2.5rem;
      text-align: center;
      color: var(--muted);
      font-size: 0.9rem;
    }

    .inv-empty {
      padding: 3rem;
      text-align: center;
      color: var(--muted);
      font-size: 0.95rem;
    }

    .inv-table {
      width: 100%;
      border-collapse: collapse;
    }

    .inv-table thead {
      background: var(--warm);
    }

    .inv-table th {
      padding: 1rem 1.2rem;
      text-align: left;
      font-size: 0.72rem;
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

    .inv-table tbody tr:hover {
      background: rgba(245,240,232,0.6);
    }

    .cat-badge {
      display: inline-block;
      padding: 0.2rem 0.7rem;
      border-radius: 100px;
      font-size: 0.75rem;
      font-weight: 500;
      background: var(--warm);
      color: var(--sage-dk);
      text-transform: capitalize;
    }

    .row-actions {
      display: flex;
      gap: 0.5rem;
    }

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

    .edit-input,
    .edit-select {
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

    .expiry-soon {
      color: var(--rust);
      font-weight: 500;
    }

    @media (max-width: 768px) {
      .inv-page { padding-top: 5rem; }
      nav .nav-links { display: none; }

      .inv-table th:nth-child(4),
      .inv-table td:nth-child(4) {
        display: none;
      }
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
        <button class="btn btn-secondary" id="open-camera-btn">Scan food</button>
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
        <div class="stat-label">Next expiring</div>
        <div class="stat-value rust" id="stat-recent" style="font-size:1rem;padding-top:0.5rem;">-</div>
      </div>
    </div>

    <div class="camera-wrap" id="camera-wrap">
      <video id="camera-video" autoplay playsinline></video>
      <canvas id="camera-canvas" style="display:none;"></canvas>
      <div class="camera-actions">
        <button class="btn btn-primary" id="capture-btn">Capture</button>
        <button class="btn btn-secondary" id="close-camera-btn">Cancel</button>
      </div>
      <p id="scan-result"></p>
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
            <option value="seafood">Seafood</option>
            <option value="pantry">Pantry</option>
            <option value="frozen">Frozen</option>
            <option value="other">Other</option>
          </select>
        </div>

        <div class="form-group">
          <label for="item-quantity">Quantity</label>
          <input type="number" id="item-quantity" min="1" step="1" placeholder="e.g. 2" />
        </div>

        <div class="form-group">
          <label for="item-unit">Unit</label>
          <select id="item-unit">
            <option value="">Select unit</option>
            <option value="oz">oz</option>
            <option value="lb">lb</option>
            <option value="g">g</option>
            <option value="kg">kg</option>
            <option value="ml">ml</option>
            <option value="l">l</option>
            <option value="pcs">pcs</option>
            <option value="bag">bag</option>
            <option value="box">box</option>
            <option value="bottle">bottle</option>
            <option value="can">can</option>
            <option value="jar">jar</option>
            <option value="pack">pack</option>
          </select>
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
        <option value="seafood">Seafood</option>
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
            <th>Unit</th>
            <th>Expiration Date</th>
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
    let cameraStream = null;

    const addFormEl = document.getElementById('add-form');
    const formMsg = document.getElementById('form-msg');

    document.getElementById('add-item-btn').addEventListener('click', () => {
      addFormEl.classList.add('open');
      formMsg.textContent = '';
    });

    document.getElementById('cancel-item').addEventListener('click', () => {
      clearForm();
      addFormEl.classList.remove('open');
      formMsg.textContent = '';
    });

    document.getElementById('open-camera-btn').addEventListener('click', async () => {
      const wrap = document.getElementById('camera-wrap');
      wrap.classList.add('open');
      document.getElementById('scan-result').textContent = '';

      try {
        cameraStream = await navigator.mediaDevices.getUserMedia({
          video: { facingMode: 'environment' }
        });
        document.getElementById('camera-video').srcObject = cameraStream;
      } catch (err) {
        document.getElementById('scan-result').textContent = 'Camera access denied or unavailable.';
      }
    });

    document.getElementById('close-camera-btn').addEventListener('click', stopCamera);

    document.getElementById('capture-btn').addEventListener('click', async () => {
      const video = document.getElementById('camera-video');
      const canvas = document.getElementById('camera-canvas');

      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext('2d').drawImage(video, 0, 0);

      const imageData = canvas.toDataURL('image/jpeg');
      stopCamera();

      const scanResult = document.getElementById('scan-result');
      addFormEl.classList.add('open');
      scanResult.textContent = 'Image captured. Identifying food...';

      try {
        const res = await fetch('api/claude_api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ image: imageData })
        });

        const data = await res.json();

        if (!data.success) {
          scanResult.textContent = data.error || 'Could not identify the food.';
          return;
        }

        document.getElementById('item-name').value = data.name || '';
        document.getElementById('item-expiry').value = data.expiration_date || '';
        document.getElementById('item-quantity').value = '';

        const categorySelect = document.getElementById('item-category');
        const allowed = ['produce', 'dairy', 'meat', 'seafood', 'pantry', 'frozen', 'other'];
        const apiCategory = (data.category || 'other').toLowerCase();
        categorySelect.value = allowed.includes(apiCategory) ? apiCategory : 'other';

        if (data.unit) {
          document.getElementById('item-unit').value = data.unit;
        }

        scanResult.textContent = 'Food identified. Please review the form before saving.';
      } catch (err) {
        scanResult.textContent = 'Could not process the image.';
      }
    });

    function stopCamera() {
      if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
      }
      document.getElementById('camera-wrap').classList.remove('open');
    }

    function clearForm() {
      document.getElementById('item-name').value = '';
      document.getElementById('item-category').value = 'produce';
      document.getElementById('item-quantity').value = '';
      document.getElementById('item-unit').value = '';
      document.getElementById('item-expiry').value = '';
    }

    function formatDate(dateStr) {
      if (!dateStr) return '';
      return dateStr;
    }

    function daysUntil(dateStr) {
      if (!dateStr) return null;
      const today = new Date();
      today.setHours(0,0,0,0);
      const target = new Date(dateStr);
      target.setHours(0,0,0,0);
      return Math.round((target - today) / (1000 * 60 * 60 * 24));
    }

    function getFilteredItems() {
      const query = document.getElementById('search-input').value.trim().toLowerCase();
      const cat = document.getElementById('filter-cat').value;

      return allItems.filter(item => {
        const matchesSearch =
          !query ||
          (item.item_name || '').toLowerCase().includes(query) ||
          (item.category || '').toLowerCase().includes(query) ||
          (item.unit || '').toLowerCase().includes(query);

        const matchesCat = !cat || item.category === cat;
        return matchesSearch && matchesCat;
      });
    }

    function updateStats(items) {
      document.getElementById('stat-total').textContent = items.length;

      const categories = new Set(
        items.map(item => item.category).filter(Boolean)
      );
      document.getElementById('stat-cats').textContent = categories.size;

      const withExpiry = items.filter(item => item.expiration_date);
      if (!withExpiry.length) {
        document.getElementById('stat-recent').textContent = '-';
      } else {
        const nextExpiring = [...withExpiry].sort((a, b) => {
          return new Date(a.expiration_date) - new Date(b.expiration_date);
        })[0];
        document.getElementById('stat-recent').textContent = nextExpiring.item_name || '-';
      }
    }

    function renderInventory(items) {
      const loading = document.getElementById('inv-loading');
      const empty = document.getElementById('inv-empty');
      const table = document.getElementById('inv-table');
      const body = document.getElementById('inv-body');

      loading.style.display = 'none';
      body.innerHTML = '';

      if (!items.length) {
        table.style.display = 'none';
        empty.style.display = 'block';
        updateStats(items);
        return;
      }

      table.style.display = 'table';
      empty.style.display = 'none';

      items.forEach(item => {
        const tr = document.createElement('tr');
        const expiryDays = daysUntil(item.expiration_date);
        let expiryClass = '';
        if (expiryDays !== null) {
            if (expiryDays < 0) {
                expiryClass = 'expired-date';
            } else if (expiryDays <= 3) {
                expiryClass = 'expiry-soon';
            }
        }

        tr.innerHTML = `
          <td>${escapeHtml(item.item_name || '')}</td>
          <td><span class="cat-badge">${escapeHtml(item.category || 'other')}</span></td>
          <td>${item.quantity ?? ''}</td>
          <td>${escapeHtml(item.unit || '')}</td>
          <td class="${expiryClass}">${formatDate(item.expiration_date || '')}</td>
          <td>
            <div class="row-actions">
              <button class="btn-icon edit" data-id="${item.item_id}">Edit</button>
              <button class="btn-icon delete" data-id="${item.item_id}">Delete</button>
            </div>
          </td>
        `;

        body.appendChild(tr);
      });

      updateStats(items);
      attachRowActions();
    }

    function escapeHtml(str) {
      return String(str)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#39;');
    }

    async function loadInventory() {
      const loading = document.getElementById('inv-loading');
      const table = document.getElementById('inv-table');
      const empty = document.getElementById('inv-empty');

      loading.style.display = 'block';
      table.style.display = 'none';
      empty.style.display = 'none';

      try {
        const res = await fetch(`${API}/food_get.php`, {
          credentials: 'include'
        });

        const data = await res.json();
        allItems = Array.isArray(data) ? data : [];
        renderInventory(getFilteredItems());
      } catch (err) {
        loading.textContent = 'Could not load inventory.';
      }
    }

    document.getElementById('submit-item').addEventListener('click', async () => {
      const itemName = document.getElementById('item-name').value.trim();
      const category = document.getElementById('item-category').value;
      const quantityRaw = document.getElementById('item-quantity').value;
      const unit = document.getElementById('item-unit').value;
      const expirationDate = document.getElementById('item-expiry').value;

      formMsg.textContent = '';

      if (!itemName) {
        formMsg.textContent = 'Item name is required.';
        return;
      }

      if (!quantityRaw) {
        formMsg.textContent = 'Quantity is required.';
        return;
      }

      const quantity = parseInt(quantityRaw, 10);
      if (Number.isNaN(quantity) || quantity < 1) {
        formMsg.textContent = 'Quantity must be a positive integer.';
        return;
      }

      const form = new FormData();
      form.append('item_name', itemName);
      form.append('category', category);
      form.append('quantity', quantity);
      form.append('unit', unit);
      form.append('expiration_date', expirationDate);

      try {
        const res = await fetch(`${API}/food_add.php`, {
          method: 'POST',
          body: form,
          credentials: 'include'
        });

        const data = await res.json();

        if (data.success) {
          formMsg.textContent = 'Food added.';
          clearForm();
          addFormEl.classList.remove('open');
          loadInventory();
        } else {
          formMsg.textContent = data.message || 'Could not add item.';
        }
      } catch (err) {
        formMsg.textContent = 'Server error.';
      }
    });

    function attachRowActions() {
      document.querySelectorAll('.delete').forEach(btn => {
        btn.addEventListener('click', async () => {
          const itemId = btn.dataset.id;
          if (!confirm('Delete this item?')) return;

          const form = new FormData();
          form.append('item_id', itemId);

          try {
            const res = await fetch(`${API}/food_delete.php`, {
              method: 'POST',
              body: form,
              credentials: 'include'
            });

            await res.text();
            loadInventory();
          } catch (err) {
            alert('Could not delete item.');
          }
        });
      });

      document.querySelectorAll('.edit').forEach(btn => {
        btn.addEventListener('click', () => {
          const itemId = btn.dataset.id;
          const item = allItems.find(x => String(x.item_id) === String(itemId));
          if (!item) return;

          const row = btn.closest('tr');
          row.innerHTML = `
            <td><input class="edit-input" id="edit-name-${itemId}" value="${escapeHtml(item.item_name || '')}"></td>
            <td>
              <select class="edit-select" id="edit-category-${itemId}">
                ${['produce','dairy','meat','seafood','pantry','frozen','other'].map(cat =>
                  `<option value="${cat}" ${item.category === cat ? 'selected' : ''}>${cat.charAt(0).toUpperCase() + cat.slice(1)}</option>`
                ).join('')}
              </select>
            </td>
            <td><input class="edit-input" type="number" min="1" step="1" id="edit-quantity-${itemId}" value="${item.quantity ?? ''}"></td>
            <td>
              <select class="edit-select" id="edit-unit-${itemId}">
                ${['','pcs','bag','box','bottle','can','jar','pack','lb','oz','g','kg','ml','l'].map(unit =>
                  `<option value="${unit}" ${item.unit === unit ? 'selected' : ''}>${unit || 'Select unit'}</option>`
                ).join('')}
              </select>
            </td>
            <td><input class="edit-input" type="date" id="edit-expiry-${itemId}" value="${item.expiration_date || ''}"></td>
            <td>
              <div class="row-actions">
                <button class="btn-icon edit save-edit" data-id="${itemId}">Save</button>
                <button class="btn-icon cancel-edit" data-id="${itemId}">Cancel</button>
              </div>
            </td>
          `;

          row.querySelector('.cancel-edit').addEventListener('click', () => {
            renderInventory(getFilteredItems());
          });

          row.querySelector('.save-edit').addEventListener('click', async () => {
            const name = document.getElementById(`edit-name-${itemId}`).value.trim();
            const category = document.getElementById(`edit-category-${itemId}`).value;
            const quantityRaw = document.getElementById(`edit-quantity-${itemId}`).value;
            const unit = document.getElementById(`edit-unit-${itemId}`).value;
            const expirationDate = document.getElementById(`edit-expiry-${itemId}`).value;

            const quantity = parseInt(quantityRaw, 10);
            if (!name) {
              alert('Item name is required.');
              return;
            }
            if (Number.isNaN(quantity) || quantity < 1) {
              alert('Quantity must be a positive integer.');
              return;
            }

            const form = new FormData();
            form.append('item_id', itemId);
            form.append('item_name', name);
            form.append('category', category);
            form.append('quantity', quantity);
            form.append('unit', unit);
            form.append('expiration_date', expirationDate);

            try {
              const res = await fetch(`${API}/food_update.php`, {
                method: 'POST',
                body: form,
                credentials: 'include'
              });

              const data = await res.json();

              if (data.success) {
                loadInventory();
              } else {
                alert(data.message || 'Could not update item.');
              }
            } catch (err) {
              alert('Server error.');
            }
          });
        });
      });
    }

    document.getElementById('search-input').addEventListener('input', () => {
      renderInventory(getFilteredItems());
    });

    document.getElementById('filter-cat').addEventListener('change', () => {
      renderInventory(getFilteredItems());
    });

    loadInventory();
  </script>
</body>
</html>
