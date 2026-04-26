const revealEls = document.querySelectorAll('.reveal');
const io = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
      io.unobserve(e.target);
    }
  });
}, { threshold: 0.12 });
revealEls.forEach(el => io.observe(el));

const API = '../backend';

async function loadInventory() {
  const loading = document.getElementById('inv-loading');
  const table   = document.getElementById('inv-table');
  const empty   = document.getElementById('inv-empty');
  const tbody   = document.getElementById('inv-body');

  if (!tbody) return; 

  try {
    const res  = await fetch(`${API}/food_get.php`, { credentials: 'include' });
    const data = await res.json();

    loading.style.display = 'none';

    if (!data || data.length === 0) {
      empty.style.display = 'block';
      return;
    }

    table.style.display = 'table';
    tbody.innerHTML = data.map(item => `
      <tr data-id="${item.item_id}">
        <td>${item.item_name}</td>
        <td><span class="cat-badge">${item.category || '—'}</span></td>
        <td>${item.quantity || '—'}</td>
        <td>${item.date_added ? new Date(item.date_added).toLocaleDateString() : '—'}</td>
        <td>
          <div class="inv-actions">
            <button class="btn-icon" onclick="deleteItem(${item.item_id})">🗑 Delete</button>
          </div>
        </td>
      </tr>
    `).join('');
  } catch (err) {
    loading.textContent = 'Could not load inventory.';
    console.error(err);
  }
}

async function deleteItem(id) {
  if (!confirm('Remove this item?')) return;
  try {
    const form = new FormData();
    form.append('item_id', id);
    await fetch(`${API}/food_delete.php`, { method: 'POST', body: form, credentials: 'include' });
    loadInventory();
  } catch (err) {
    console.error(err);
  }
}

const addBtn    = document.getElementById('add-item-btn');
const addForm   = document.getElementById('add-form');
const cancelBtn = document.getElementById('cancel-item');
const submitBtn = document.getElementById('submit-item');
const formMsg   = document.getElementById('form-msg');



//for claude ai
const openBtn = document.getElementById("open-camera-btn");
const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const captureBtn = document.getElementById("capture-btn");

let stream;
openBtn.addEventListener("click", async () => {
  try {
    stream = await navigator.mediaDevices.getUserMedia({ video: true });
    video.srcObject = stream;
    video.style.display = "block";
  } catch (err) {
    alert("Camera access denied");
  }
});

document.getElementById("capture-btn").addEventListener("click", () => {
  console.log("capture clicked");
  const ctx = canvas.getContext("2d");
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;
  ctx.drawImage(video, 0, 0);
  const imageData = canvas.toDataURL("image/png");
  sendToBackend(imageData);
});

//close camera
if (stream) {
  stream.getTracks().forEach(track => track.stop());
}
video.style.display = "none";

function sendToBackend(imageData) {
  fetch("/api/claude_api.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      image: imageData
    })
  })
  // .then(res => res.json())
  .then(async res => {
    const text = await res.text();
    console.log("RAW:", text);
    try {
      const data = JSON.parse(text);
      console.log("Claude result:", data);
    } catch (e) {
      console.error("NOT JSON:", text);
    }
  })

  .then(data => {
    console.log("Claude result:", data);
  })
  .catch(err => {
    console.error(err);
  });
}





if (addBtn) {
  addBtn.addEventListener('click', () => {
    addForm.style.display = addForm.style.display === 'none' ? 'block' : 'none';
  });
}

if (cancelBtn) {
  cancelBtn.addEventListener('click', () => {
    addForm.style.display = 'none';
    formMsg.textContent = '';
  });
}

if (submitBtn) {
  submitBtn.addEventListener('click', async () => {
    const name     = document.getElementById('item-name').value.trim();
    const category = document.getElementById('item-category').value;
    const quantity = document.getElementById('item-quantity').value.trim();
    const expiry   = document.getElementById('item-expiry').value;

    if (!name) { formMsg.textContent = 'Please enter an item name.'; return; }

    const form = new FormData();
    form.append('item_name', name);
    form.append('category', category);
    form.append('quantity', quantity);
    form.append('expiration_date', expiry);

    try {
      const res = await fetch(`${API}/food_add.php`, { method: 'POST', body: form, credentials: 'include' });
      const data = await res.json();
      if (data.success) {
        formMsg.textContent = '✓ Item added!';
        document.getElementById('item-name').value = '';
        document.getElementById('item-quantity').value = '';
        document.getElementById('item-expiry').value = '';
        loadInventory();
        setTimeout(() => { addForm.style.display = 'none'; formMsg.textContent = ''; }, 1000);
      } else {
        formMsg.textContent = data.message || 'Something went wrong.';
      }
    } catch (err) {
      formMsg.textContent = 'Could not connect to server.';
      console.error(err);
    }
  });
}

loadInventory();