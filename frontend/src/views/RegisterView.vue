<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
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

function getErrorMessage(err) {
  if (err.response?.data?.message) {
    return err.response.data.message
  }

  if (err.request) {
    return 'Could not reach the API. Check that the backend server is running.'
  }

  return 'Unable to create an account.'
}

async function submit() {
  error.value = ''

  try {
    await auth.register(form)
    router.push({ name: 'dashboard' })
  } catch (err) {
    error.value = getErrorMessage(err)
  }
}
</script>

<template>
  <form class="form" @submit.prevent="submit">
    <h2>Register</h2>
    <label>
      Name
      <input v-model="form.name" type="text" required />
    </label>
    <label>
      Email
      <input v-model="form.email" type="email" required />
    </label>
    <label>
      Password
      <input v-model="form.password" type="password" minlength="8" required />
    </label>
    <label>
      Confirm password
      <input v-model="form.password_confirmation" type="password" minlength="8" required />
    </label>
    <p v-if="error" class="error">{{ error }}</p>
    <button type="submit" :disabled="auth.loading">
      {{ auth.loading ? 'Creating account...' : 'Register' }}
    </button>
    <RouterLink to="/login">Already have an account?</RouterLink>
  </form>
</template>
