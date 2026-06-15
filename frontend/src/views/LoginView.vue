<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { getApiErrorMessage } from '../api/errors'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const auth = useAuthStore()
const error = ref('')
const form = reactive({
  email: '',
  password: '',
})

async function submit() {
  error.value = ''

  try {
    await auth.login(form)
    router.push({ name: 'dashboard' })
  } catch (err) {
    error.value = getApiErrorMessage(err, 'Unable to sign in with those credentials.')
  }
}
</script>

<template>
  <form class="auth-form" @submit.prevent="submit">
    <nav class="auth-switch" aria-label="Authentication options">
      <RouterLink class="auth-switch__item" to="/login">Sign in</RouterLink>
      <RouterLink class="auth-switch__item" to="/register">Sign up</RouterLink>
    </nav>

    <header class="auth-form__header">
      <p>Welcome back</p>
      <h2>Sign in to Rainelle</h2>
    </header>

    <div class="auth-form__fields">
      <label class="auth-field">
        <span>Email</span>
        <input v-model="form.email" type="email" autocomplete="email" required />
      </label>
      <label class="auth-field">
        <span>Password</span>
        <input v-model="form.password" type="password" autocomplete="current-password" required />
      </label>
    </div>

    <p v-if="error" class="auth-error">{{ error }}</p>

    <button class="auth-submit" type="submit" :disabled="auth.loading">
      {{ auth.loading ? 'Signing in...' : 'Sign in' }}
    </button>

    <div class="auth-divider">
      <span>or</span>
    </div>

    <div class="auth-socials" aria-label="Social sign in placeholders">
      <button class="auth-social" type="button" disabled>
        <span>G</span>
        Continue with Google
      </button>
      <button class="auth-social" type="button" disabled>
        <span>A</span>
        Continue with Apple
      </button>
    </div>

    <p class="auth-alt">
      New here?
      <RouterLink to="/register">Create an account</RouterLink>
    </p>
  </form>
</template>
