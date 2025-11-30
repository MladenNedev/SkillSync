<script setup>
defineOptions({
  name: 'LoginPage',
})
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { login, fetchCurrentUser } from '../api/auth'

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
    <form class="login-form" @submit.prevent="handleSubmit">
      <h1>SkillSync Login</h1>

      <div class="field">
        <label>Email</label>
        <input v-model="email" type="email" autocomplete="email" />
      </div>

      <div class="field">
        <label>Password</label>
        <input v-model="password" type="password" autocomplete="current-password" />
      </div>

      <p v-if="error" class="error">
        {{ error }}
      </p>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Logging in...' : 'Login' }}
      </button>
    </form>
  </div>
</template>
