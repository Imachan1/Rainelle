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
        <span class="auth-field__icon auth-field__icon--mail" aria-hidden="true"></span>
        <input v-model="form.email" type="email" autocomplete="email" placeholder="email" aria-label="Email" required />
      </label>
      <label class="auth-field">
        <span class="auth-field__icon auth-field__icon--lock" aria-hidden="true"></span>
        <input
          v-model="form.password"
          type="password"
          autocomplete="current-password"
          placeholder="password"
          aria-label="Password"
          required
        />
        <span class="auth-field__icon auth-field__icon--eye" aria-hidden="true"></span>
      </label>
    </div>

    <RouterLink class="auth-forgot" to="/login">forgot password?</RouterLink>

    <p v-if="error" class="auth-error">{{ error }}</p>

    <button class="auth-submit" type="submit" :disabled="auth.loading">
      <span>{{ auth.loading ? 'signing in...' : 'sign in' }}</span>
      <span class="auth-submit__arrow" aria-hidden="true"></span>
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
        <span class="auth-social__apple"></span>
        continue with apple
      </button>
    </div>

    <p class="auth-terms">
      by continuing, you agree to our<br />
      <span>Terms of Use</span> and <span>Privacy Policy</span>
    </p>
  </form>
</template>
