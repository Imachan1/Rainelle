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
      <RouterLink class="auth-switch__item" to="/login">sign in</RouterLink>
      <RouterLink class="auth-switch__item" to="/register">sign up</RouterLink>
    </nav>

    <div class="auth-form__fields">
      <label class="auth-field">
        <svg class="auth-field__icon auth-field__icon--mail" aria-hidden="true" viewBox="0 0 24 24">
          <path d="M4.75 6.75h14.5v10.5H4.75z" />
          <path d="m5.25 7.25 6.75 5.5 6.75-5.5" />
        </svg>
        <input v-model="form.email" type="email" autocomplete="email" placeholder="email" aria-label="Email" required />
      </label>
      <label class="auth-field">
        <svg class="auth-field__icon auth-field__icon--lock" aria-hidden="true" viewBox="0 0 24 24">
          <path d="M7.5 10.5h9v8h-9z" />
          <path d="M9 10.5V8a3 3 0 0 1 6 0v2.5" />
        </svg>
        <input
          v-model="form.password"
          type="password"
          autocomplete="current-password"
          placeholder="password"
          aria-label="Password"
          required
        />
        <svg class="auth-field__icon auth-field__icon--eye" aria-hidden="true" viewBox="0 0 24 24">
          <path d="M3.75 12s3-5 8.25-5 8.25 5 8.25 5-3 5-8.25 5-8.25-5-8.25-5z" />
          <path d="M12 9.75a2.25 2.25 0 1 1 0 4.5 2.25 2.25 0 0 1 0-4.5z" />
        </svg>
      </label>
    </div>

    <RouterLink class="auth-forgot" to="/login">forgot password?</RouterLink>

    <p v-if="error" class="auth-error">{{ error }}</p>

    <button class="auth-submit" type="submit" :disabled="auth.loading">
      <span>{{ auth.loading ? 'signing in...' : 'sign in' }}</span>
      <span class="auth-submit__arrow" aria-hidden="true">
        <svg viewBox="0 0 24 24">
          <path d="M5 12h13" />
          <path d="m13 6 6 6-6 6" />
        </svg>
      </span>
    </button>

    <div class="auth-divider">
      <span>or</span>
    </div>

    <div class="auth-socials" aria-label="Social sign in placeholders">
      <button class="auth-social" type="button" disabled>
        <span>G</span>
        continue with google
      </button>
      <button class="auth-social" type="button" disabled>
        <span class="auth-social__facebook">f</span>
        continue with facebook
      </button>
    </div>

    <p class="auth-terms">
      by continuing, you agree to our<br />
      <span>Terms of Use</span> and <span>Privacy Policy</span>
    </p>
  </form>
</template>
