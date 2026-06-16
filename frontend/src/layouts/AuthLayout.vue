<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const authSwitchClass = computed(() => (route.name === 'register' ? 'auth-switch--register' : 'auth-switch--login'))
</script>

<template>
  <main class="auth-layout">
    <section class="auth-shell" aria-label="Rainelle authentication">
      <div class="auth-brand">
        <div class="auth-brand__content">
          <h1>rainelle</h1>
          <p>your space, your creativity.</p>
        </div>
      </div>
      <div class="auth-sculpture" aria-hidden="true"></div>
      <section class="auth-card">
        <nav class="auth-switch" :class="authSwitchClass" aria-label="Authentication options">
          <RouterLink class="auth-switch__item" to="/login">sign in</RouterLink>
          <RouterLink class="auth-switch__item" to="/register">sign up</RouterLink>
        </nav>
        <RouterView />
      </section>
    </section>
  </main>
</template>
