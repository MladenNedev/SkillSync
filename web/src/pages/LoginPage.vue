<script setup>
defineOptions({
  name: 'LoginPage',
})
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { login, fetchCurrentUser } from '@/api/auth'

const router = useRouter()

const email = ref('demo@skillsync.test')
const password = ref('password')
const loading = ref(false)
const error = ref(null)

async function handleSubmit() {
  error.value = null
  loading.value = true

  try {
    await login(email.value, password.value)
    const user = await fetchCurrentUser()

    console.log('Logged in as:', user)

    // TODO later: store user in Pinia instead of just logging

    router.push({ name: 'dashboard' })
  } catch (e) {
    console.error(e)
    error.value = 'Login failed. Check your credentials or server.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="login-page">
    <div class="login-glow" aria-hidden="true"></div>
    <div class="login-shell">
      <section class="login-side">
        <div class="brand-pill">SkillSync</div>
        <h1>Welcome back</h1>
        <p>
          Pick up where you left off with a focused view of streaks, progress,
          and next up on your learning path.
        </p>
        <ul class="login-highlights">
          <li>Daily focus stats and streaks</li>
          <li>Course and challenge tracking</li>
          <li>Weekly study snapshots</li>
        </ul>
      </section>

      <form class="login-card" @submit.prevent="handleSubmit">
        <div class="card-header">
          <p class="eyebrow">Sign in</p>
          <h2>SkillSync account</h2>
        </div>

        <div class="field">
          <label for="login-email">Email</label>
          <input
            id="login-email"
            v-model="email"
            type="email"
            autocomplete="email"
            placeholder="you@skillsync.test"
            required
          />
        </div>

        <div class="field">
          <label for="login-password">Password</label>
          <input
            id="login-password"
            v-model="password"
            type="password"
            autocomplete="current-password"
            placeholder="••••••••"
            required
          />
        </div>

        <p v-if="error" class="error">
          {{ error }}
        </p>

        <button type="submit" class="primary-button" :disabled="loading">
          {{ loading ? 'Logging in...' : 'Login' }}
        </button>
      </form>
    </div>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700&family=Playfair+Display:wght@600&display=swap');

.login-page {
  min-height: 100vh;
  display: grid;
  place-items: center;
  padding: 48px 20px;
  background: radial-gradient(circle at top left, #fff2dc, #f6efe6 45%, #edf3f2 100%);
  position: relative;
  overflow: hidden;
  color: #202020;
}

.login-glow {
  position: absolute;
  inset: -20% 10% auto auto;
  width: 420px;
  height: 420px;
  background: radial-gradient(circle, rgba(0, 128, 123, 0.18), rgba(0, 128, 123, 0));
  filter: blur(8px);
  z-index: 0;
}

.login-shell {
  position: relative;
  z-index: 1;
  width: min(980px, 100%);
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 32px;
  padding: 28px;
  border-radius: 28px;
  background: rgba(255, 255, 255, 0.75);
  box-shadow: 0 30px 60px rgba(24, 38, 36, 0.12);
  backdrop-filter: blur(18px);
}

.login-side {
  display: grid;
  gap: 16px;
  padding: 14px 8px 14px 18px;
  font-family: 'Archivo', sans-serif;
}

.brand-pill {
  align-self: flex-start;
  padding: 6px 14px;
  border-radius: 999px;
  background: #132b2a;
  color: #fbead2;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  font-size: 0.72rem;
}

.login-side h1 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2.2rem, 3vw, 3rem);
  margin: 0;
  color: #132b2a;
}

.login-side p {
  margin: 0;
  font-size: 1.02rem;
  color: #3b4b49;
}

.login-highlights {
  margin: 6px 0 0;
  padding: 0;
  list-style: none;
  display: grid;
  gap: 10px;
  color: #2b3f3d;
}

.login-highlights li {
  padding-left: 22px;
  position: relative;
}

.login-highlights li::before {
  content: '';
  position: absolute;
  left: 0;
  top: 7px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #ff8a4c;
}

.login-card {
  background: #ffffff;
  border-radius: 20px;
  padding: 28px 26px 26px;
  display: grid;
  gap: 18px;
  box-shadow: 0 18px 40px rgba(16, 32, 30, 0.12);
  font-family: 'Archivo', sans-serif;
}

.card-header {
  display: grid;
  gap: 6px;
}

.eyebrow {
  margin: 0;
  font-size: 0.78rem;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  color: #ff8a4c;
  font-weight: 600;
}

.login-card h2 {
  margin: 0;
  font-size: 1.6rem;
  color: #162726;
}

.field {
  display: grid;
  gap: 8px;
}

.field label {
  font-size: 0.88rem;
  font-weight: 600;
  color: #2c3d3b;
}

.field input {
  border: 1px solid #d4e0dd;
  border-radius: 12px;
  padding: 12px 14px;
  font-size: 0.98rem;
  background: #f9fbfb;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.field input:focus {
  outline: none;
  border-color: #00807b;
  box-shadow: 0 0 0 3px rgba(0, 128, 123, 0.16);
}

.error {
  margin: 0;
  padding: 10px 12px;
  border-radius: 10px;
  background: #ffe9e1;
  color: #9f2f1c;
  font-size: 0.92rem;
}

.primary-button {
  border: none;
  border-radius: 12px;
  padding: 12px 16px;
  font-size: 1rem;
  font-weight: 600;
  background: linear-gradient(135deg, #0b5f5c, #129e98);
  color: #fff8f1;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
}

.primary-button:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 12px 24px rgba(11, 95, 92, 0.25);
}

.primary-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

@media (max-width: 820px) {
  .login-shell {
    grid-template-columns: 1fr;
  }

  .login-side {
    padding: 10px 8px 0;
  }
}
</style>
