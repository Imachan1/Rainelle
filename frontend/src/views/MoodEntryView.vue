<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { fetchMoodEntry } from '../api/moodEntries'

const route = useRoute()
const entry = ref(null)

onMounted(async () => {
  const { data } = await fetchMoodEntry(route.params.id)
  entry.value = data
})
</script>

<template>
  <section>
    <h1>Mood Entry</h1>
    <div v-if="entry" class="panel">
      <h2>{{ entry.mood }}</h2>
      <p>{{ entry.entry_date }} - {{ entry.intensity }}/10</p>
      <p>{{ entry.notes || 'No notes saved.' }}</p>
    </div>
  </section>
</template>
