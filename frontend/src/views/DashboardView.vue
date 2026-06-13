<script setup>
import { onMounted, ref } from 'vue'
import { fetchDashboard } from '../api/dashboard'

const dashboard = ref(null)

onMounted(async () => {
  const { data } = await fetchDashboard()
  dashboard.value = data
})
</script>

<template>
  <section>
    <h1>Dashboard</h1>
    <div class="panel">
      <p>Total entries: {{ dashboard?.mood_entries_count ?? 0 }}</p>
      <p>Latest entry: {{ dashboard?.latest_entry?.mood ?? 'No entries yet' }}</p>
    </div>
  </section>
</template>
