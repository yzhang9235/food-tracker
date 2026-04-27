<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FoodTracker — Sign In</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="styles.css" />
  <style>
    .auth-page {
      min-height: 100vh;
      display: grid;
      grid-template-columns: 1fr 1fr;
    }

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

    .field input.error { border-color: var(--rust); }

    .field-error {
      font-size: 0.78rem;
      color: var(--rust);
      display: none;
    }

    .field-error.visible { display: block; }

    .field-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .forgot-link {
      font-size: 0.8rem;
      color: var(--muted);
      text-decoration: none;
      transition: color .2s;
    }

    .forgot-link:hover { color: var(--sage-dk); }

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

    @media (max-width: 768px) {
      .auth-page { grid-template-columns: 1fr; }
      .auth-left { display: none; }
      .auth-right { padding: 5rem 1.5rem 3rem; align-items: flex-start; }
    }
  </style>
</head>
<body>

  <div class="auth-page">

    <div class="auth-left">
      <a class="auth-logo" href="index.php">Food<span>Tracker</span></a>

      <div class="auth-left-content">
        <h2>Welcome <em>back</em>.</h2>
        <p>Sign in to check your inventory, see what's expiring soon, and get recipe ideas from what you already have.</p>
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

    <div class="auth-right">
      <div class="auth-form-wrap">
        <h1>Sign in</h1>
        <p class="sub">Don't have an account? <a href="register.php">Sign up free</a></p>

        <div class="field">
          <label for="email">Email</label>
          <input type="email" id="email" placeholder="you@example.com" autocomplete="email" />
          <span class="field-error" id="err-email"></span>
        </div>

        <div class="field">
          <div class="field-row">
            <label for="password">Password</label>
          </div>
          <input type="password" id="password" placeholder="Your password" autocomplete="current-password" />
          <span class="field-error" id="err-password"></span>
        </div>

        <button class="btn-full" id="login-btn">Sign in</button>

        <div class="form-status" id="form-status"></div>
      </div>
    </div>

  </div>

  <script>
    const API = './backend';

    const emailEl = document.getElementById('email');
    const passwordEl = document.getElementById('password');
    const errEmail = document.getElementById('err-email');
    const errPassword = document.getElementById('err-password');
    const status = document.getElementById('form-status');
    const btn    = document.getElementById('login-btn');

    function showError(el, errEl, msg) {
      el.classList.add('error');
      errEl.textContent = msg;
      errEl.classList.add('visible');
    }

    function clearError(el, errEl) {
      el.classList.remove('error');
      errEl.classList.remove('visible');
    }

    function clearAll() {
      clearError(emailEl, errEmail);
      clearError(passwordEl, errPassword);
      status.className = 'form-status';
      status.textContent = '';
    }

    function validate() {
      let ok = true;
      if (!emailEl.value.trim()) {
        showError(emailEl, errEmail, 'Email is required.'); ok = false;
      }
      if (!passwordEl.value) {
        showError(passwordEl, errPassword, 'Password is required.'); ok = false;
      }
      return ok;
    }

    btn.addEventListener('click', async () => {
      clearAll();
      if (!validate()) return;

      btn.disabled = true;
      btn.textContent = 'Signing in…';

      const form = new FormData();
      form.append('email', emailEl.value.trim());
      form.append('password', passwordEl.value);

      try {
        const res  = await fetch(`${API}/login.php`, { method: 'POST', body: form, credentials: 'include' });
        const data = await res.json();

        if (data.success) {
          status.className = 'form-status success';
          status.textContent = '✓ Signed in! Redirecting…';
          setTimeout(() => { window.location.href = 'inventory.php'; }, 1000);
        } else {
          status.className = 'form-status error';
          status.textContent = data.message || 'Incorrect username or password.';
          btn.disabled = false;
          btn.textContent = 'Sign in';
        }
      } catch (err) {
        status.className = 'form-status error';
        status.textContent = 'Could not connect to the server.';
        btn.disabled = false;
        btn.textContent = 'Sign in';
      }
    });

    emailEl.addEventListener('input', () => clearError(emailEl, errEmail));
    passwordEl.addEventListener('input', () => clearError(passwordEl, errPassword));

    document.addEventListener('keydown', e => {
      if (e.key === 'Enter') btn.click();
    });
  </script>
</body>
</html>