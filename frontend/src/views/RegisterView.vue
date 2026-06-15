<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { getApiErrorMessage } from '../api/errors'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const auth = useAuthStore()
const error = ref('')
const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

async function submit() {
  error.value = ''

  try {
    await auth.register(form)
    router.push({ name: 'dashboard' })
  } catch (err) {
    error.value = getApiErrorMessage(err, 'Unable to create an account.')
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
      <p>Start softly</p>
      <h2>Create your account</h2>
    </header>

    <div class="auth-form__fields">
      <label class="auth-field">
        <span>Name</span>
        <input v-model="form.name" type="text" autocomplete="name" required />
      </label>
      <label class="auth-field">
        <span>Email</span>
        <input v-model="form.email" type="email" autocomplete="email" required />
      </label>
      <label class="auth-field">
        <span>Password</span>
        <input v-model="form.password" type="password" autocomplete="new-password" minlength="8" required />
      </label>
      <label class="auth-field">
        <span>Confirm password</span>
        <input
          v-model="form.password_confirmation"
          type="password"
          autocomplete="new-password"
          minlength="8"
          required
        />
      </label>
    </div>

    <p v-if="error" class="auth-error">{{ error }}</p>

    <button class="auth-submit" type="submit" :disabled="auth.loading">
      {{ auth.loading ? 'Creating account...' : 'Create account' }}
    </button>

    <div class="auth-divider">
      <span>or</span>
    </div>

    <div class="auth-socials" aria-label="Social sign up placeholders">
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
      Already registered?
      <RouterLink to="/login">Sign in</RouterLink>
    </p>
  </form>
</template>
