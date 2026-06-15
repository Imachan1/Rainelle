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
        <RouterView />
      </section>
    </section>
  </main>
</template>
