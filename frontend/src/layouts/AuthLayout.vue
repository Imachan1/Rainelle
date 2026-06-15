<template>
  <main class="auth-layout">
    <section class="auth-shell" aria-label="Rainelle authentication">
      <div class="auth-brand">
        <div class="auth-brand__mark">
          <span>R</span>
          <span>24</span>
        </div>
        <div class="auth-brand__content">
          <h1>Rainelle</h1>
          <p>your space, your creativity.</p>
        </div>
        <div class="auth-sculpture" aria-hidden="true">
          <span class="auth-sculpture__stone auth-sculpture__stone--top"></span>
          <span class="auth-sculpture__stone auth-sculpture__stone--base"></span>
        </div>
      </div>
      <div class="auth-language" aria-hidden="true">
        EN
        <span></span>
      </div>
      <section class="auth-card">
        <RouterView />
      </section>
    </section>
  </main>
</template>
