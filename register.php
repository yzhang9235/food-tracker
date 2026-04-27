<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FoodTracker — Create Account</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="styles.css" />
  <style>
    .auth-page {
      min-height: 100vh;
      display: grid;
      grid-template-columns: 1fr 1fr;
    }

    /* ── Left panel ── */
    .auth-left {
      background: var(--sage-dk);
      padding: 3rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
      overflow: hidden;
    }

    .auth-left::before {
      content: '';
      position: absolute;
      width: 500px; height: 500px;
      bottom: -150px; left: -150px;
      background: radial-gradient(circle, rgba(107,143,113,0.5) 0%, transparent 65%);
      pointer-events: none;
    }

    .auth-left::after {
      content: '';
      position: absolute;
      width: 300px; height: 300px;
      top: -80px; right: -80px;
      background: radial-gradient(circle, rgba(168,201,171,0.2) 0%, transparent 65%);
      pointer-events: none;
    }

    .auth-logo {
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
      color: #fff;
      text-decoration: none;
      letter-spacing: -0.02em;
      position: relative; z-index: 1;
    }

    .auth-logo span { color: var(--rust-lt); font-style: italic; }

    .auth-left-content {
      position: relative; z-index: 1;
    }

    .auth-left-content h2 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2rem, 3vw, 2.8rem);
      color: #fff;
      line-height: 1.15;
      margin-bottom: 1.2rem;
    }

    .auth-left-content h2 em {
      font-style: italic;
      color: #a8c9ab;
    }

    .auth-left-content p {
      color: rgba(255,255,255,0.6);
      font-size: 0.95rem;
      line-height: 1.7;
      max-width: 340px;
    }

    .auth-perks {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      position: relative; z-index: 1;
    }

    .perk {
      display: flex;
      align-items: center;
      gap: 0.8rem;
      color: rgba(255,255,255,0.75);
      font-size: 0.88rem;
    }

    .perk-icon {
      width: 32px; height: 32px;
      background: rgba(255,255,255,0.1);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1rem;
      flex-shrink: 0;
    }

    /* ── Right panel ── */
    .auth-right {
      background: var(--cream);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 3rem 2rem;
    }

    .auth-form-wrap {
      width: 100%;
      max-width: 400px;
    }

    .auth-form-wrap h1 {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      color: var(--text);
      margin-bottom: 0.4rem;
      opacity: 1;
      animation: none;
    }

    .auth-form-wrap .sub {
      color: var(--muted);
      font-size: 0.9rem;
      margin-bottom: 2.2rem;
    }

    .auth-form-wrap .sub a {
      color: var(--sage-dk);
      text-decoration: none;
      font-weight: 500;
    }

    .auth-form-wrap .sub a:hover { text-decoration: underline; }

    .field {
      display: flex;
      flex-direction: column;
      gap: 0.4rem;
      margin-bottom: 1.1rem;
    }

    .field label {
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--muted);
      font-weight: 500;
    }

    .field input {
      padding: 0.75rem 1rem;
      border: 1.5px solid var(--warm);
      border-radius: 12px;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.95rem;
      color: var(--text);
      background: #fff;
      transition: border-color .2s, box-shadow .2s;
      outline: none;
    }

    .field input:focus {
      border-color: var(--sage);
      box-shadow: 0 0 0 3px rgba(107,143,113,0.12);
    }

    .field input.error {
      border-color: var(--rust);
    }

    .field-error {
      font-size: 0.78rem;
      color: var(--rust);
      display: none;
    }

    .field-error.visible { display: block; }

    .btn-full {
      width: 100%;
      padding: 0.9rem;
      border: none;
      border-radius: 12px;
      background: var(--rust);
      color: #fff;
      font-family: 'DM Sans', sans-serif;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: background .2s, transform .15s, box-shadow .2s;
      box-shadow: 0 4px 20px rgba(196,96,58,0.3);
      margin-top: 0.5rem;
    }

    .btn-full:hover {
      background: var(--sage-dk);
      transform: translateY(-1px);
      box-shadow: 0 6px 24px rgba(68,92,72,0.25);
    }

    .btn-full:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .form-status {
      margin-top: 1rem;
      padding: 0.75rem 1rem;
      border-radius: 10px;
      font-size: 0.88rem;
      display: none;
    }

    .form-status.success {
      display: block;
      background: rgba(107,143,113,0.12);
      color: var(--sage-dk);
      border: 1px solid rgba(107,143,113,0.3);
    }

    .form-status.error {
      display: block;
      background: rgba(196,96,58,0.1);
      color: var(--rust);
      border: 1px solid rgba(196,96,58,0.25);
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin: 1.5rem 0;
      color: var(--muted);
      font-size: 0.8rem;
    }

    .divider::before, .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--warm);
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
      .auth-page { grid-template-columns: 1fr; }
      .auth-left { display: none; }
      .auth-right { padding: 5rem 1.5rem 3rem; align-items: flex-start; }
    }
  </style>
</head>
<body>

  <div class="auth-page">

    <!-- Left decorative panel -->
    <div class="auth-left">
      <a class="auth-logo" href="index.php">Food<span>Tracker</span></a>

      <div class="auth-left-content">
        <h2>Waste <em>less</em>,<br>cook more.</h2>
        <p>Track what's in your fridge, get alerts before things expire, and discover recipes from what you already have.</p>
      </div>

      <div class="auth-perks">
        <div class="perk">
          <div class="perk-icon"></div>
          Track expiration dates automatically
        </div>
        <div class="perk">
          <div class="perk-icon"></div>
          Get recipe ideas from your inventory
        </div>
        <div class="perk">
          <div class="perk-icon"></div>
          Manage your fridge & pantry in one place
        </div>
      </div>
    </div>

    <!-- Right form panel -->
    <div class="auth-right">
      <div class="auth-form-wrap">
        <h1>Create account</h1>
        <p class="sub">Already have one? <a href="login.php">Sign in</a></p>

        <div class="field">
          <label for="username">Username</label>
          <input type="text" id="username" placeholder="e.g. elaine123" autocomplete="username" />
          <span class="field-error" id="err-username"></span>
        </div>

        <div class="field">
          <label for="email">Email</label>
          <input type="email" id="email" placeholder="you@example.com" autocomplete="email" />
          <span class="field-error" id="err-email"></span>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <input type="password" id="password" placeholder="At least 8 characters" autocomplete="new-password" />
          <span class="field-error" id="err-password"></span>
        </div>

        <div class="field">
          <label for="confirm-password">Confirm password</label>
          <input type="password" id="confirm-password" placeholder="Repeat your password" autocomplete="new-password" />
          <span class="field-error" id="err-confirm"></span>
        </div>

        <button class="btn-full" id="register-btn">Create account</button>

        <div class="form-status" id="form-status"></div>
      </div>
    </div>

  </div>

  <script>
    const API = './backend';

    const fields = {
      username: document.getElementById('username'),
      email:    document.getElementById('email'),
      password: document.getElementById('password'),
      confirm:  document.getElementById('confirm-password'),
    };

    const errors = {
      username: document.getElementById('err-username'),
      email:    document.getElementById('err-email'),
      password: document.getElementById('err-password'),
      confirm:  document.getElementById('err-confirm'),
    };

    const status = document.getElementById('form-status');
    const btn    = document.getElementById('register-btn');

    function showError(field, msg) {
      fields[field].classList.add('error');
      errors[field].textContent = msg;
      errors[field].classList.add('visible');
    }

    function clearError(field) {
      fields[field].classList.remove('error');
      errors[field].classList.remove('visible');
    }

    function clearAll() {
      Object.keys(fields).forEach(clearError);
      status.className = 'form-status';
      status.textContent = '';
    }

    function validate() {
      let ok = true;

      if (!fields.username.value.trim()) {
        showError('username', 'Username is required.'); ok = false;
      } else { clearError('username'); }

      const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRe.test(fields.email.value.trim())) {
        showError('email', 'Enter a valid email address.'); ok = false;
      } else { clearError('email'); }

      if (fields.password.value.length < 8) {
        showError('password', 'Password must be at least 8 characters.'); ok = false;
      } else { clearError('password'); }

      if (fields.confirm.value !== fields.password.value) {
        showError('confirm', 'Passwords do not match.'); ok = false;
      } else { clearError('confirm'); }

      return ok;
    }

    btn.addEventListener('click', async () => {
      clearAll();
      if (!validate()) return;

      btn.disabled = true;
      btn.textContent = 'Creating account…';

      const form = new FormData();
      form.append('username', fields.username.value.trim());
      form.append('email',    fields.email.value.trim());
      form.append('password', fields.password.value);

      try {
        const res  = await fetch(`${API}/register.php`, { method: 'POST', body: form, credentials: 'include' });
        const data = await res.json();

        if (data.success) {
          status.className = 'form-status success';
          status.textContent = '✓ Account created! Redirecting to login…';
          setTimeout(() => { window.location.href = 'login.php'; }, 1500);
        } else {
          status.className = 'form-status error';
          status.textContent = data.message || 'Something went wrong. Please try again.';
          btn.disabled = false;
          btn.textContent = 'Create account';
        }
      } catch (err) {
        status.className = 'form-status error';
        status.textContent = 'Could not connect to the server.';
        btn.disabled = false;
        btn.textContent = 'Create account';
      }
    });

    // Clear errors on input
    Object.keys(fields).forEach(key => {
      fields[key].addEventListener('input', () => clearError(key));
    });

    // Allow Enter key to submit
    document.addEventListener('keydown', e => {
      if (e.key === 'Enter') btn.click();
    });
  </script>
</body>
</html>