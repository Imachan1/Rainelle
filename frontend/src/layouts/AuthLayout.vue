<template>
  <main class="auth-layout">
    <section class="auth-shell" aria-label="Rainelle authentication">
      <div class="auth-brand">
        <div class="auth-brand__mark">
          <span>R</span>
          <span>24</span>
        </div>
        <div class="auth-brand__content">
          <p class="auth-brand__eyebrow">Mood-led weather journaling</p>
          <h1>Rainelle</h1>
          <p>Track the shape of your days with quiet, clear emotional context.</p>
        </div>
        <div class="auth-sculpture" aria-hidden="true">
          <span class="auth-sculpture__stone auth-sculpture__stone--top"></span>
          <span class="auth-sculpture__stone auth-sculpture__stone--base"></span>
        </div>
      </div>
      <section class="auth-card">
        <RouterView />
      </section>
    </section>
  </main>
</template>
