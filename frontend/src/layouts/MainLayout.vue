<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const auth = useAuthStore()

async function handleLogout() {
  await auth.logout()
  router.push({ name: 'login' })
}
</script>

<template>
  <div class="app-layout">
    <aside class="sidebar">
      <RouterLink to="/">Rainelle</RouterLink>
      <nav>
        <RouterLink to="/">Dashboard</RouterLink>
        <RouterLink to="/mood-entries">Mood Entries</RouterLink>
        <RouterLink to="/weather">Weather</RouterLink>
        <RouterLink to="/insights">Insights</RouterLink>
      </nav>
      <button type="button" @click="handleLogout">Logout</button>
    </aside>

    <main class="page">
      <RouterView />
    </main>
  </div>
</template>
