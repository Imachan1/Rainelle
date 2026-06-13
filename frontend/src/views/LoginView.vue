<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
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
  } catch {
    error.value = 'Unable to sign in with those credentials.'
  }
}
</script>

<template>
  <form class="form" @submit.prevent="submit">
    <h2>Log in</h2>
    <label>
      Email
      <input v-model="form.email" type="email" required />
    </label>
    <label>
      Password
      <input v-model="form.password" type="password" required />
    </label>
    <p v-if="error" class="error">{{ error }}</p>
    <button type="submit">Log in</button>
    <RouterLink to="/register">Create an account</RouterLink>
  </form>
</template>
