<script setup>
import { onMounted, reactive } from 'vue'
import { useMoodEntriesStore } from '../stores/moodEntries'

const moodEntries = useMoodEntriesStore()
const form = reactive({
  entry_date: new Date().toISOString().slice(0, 10),
  mood: '',
  intensity: 5,
  notes: '',
})

async function submit() {
  await moodEntries.createEntry({ ...form })
  form.mood = ''
  form.intensity = 5
  form.notes = ''
}

onMounted(() => moodEntries.fetchEntries())
</script>

<template>
  <section>
    <h1>Mood Entries</h1>

    <form class="form panel" @submit.prevent="submit">
      <label>
        Date
        <input v-model="form.entry_date" type="date" required />
      </label>
      <label>
        Mood
        <input v-model="form.mood" type="text" required />
      </label>
      <label>
        Intensity
        <input v-model.number="form.intensity" type="number" min="1" max="10" required />
      </label>
      <label>
        Notes
        <textarea v-model="form.notes" rows="3" />
      </label>
      <button type="submit">Save entry</button>
    </form>

    <div class="list">
      <RouterLink
        v-for="entry in moodEntries.entries"
        :key="entry.id"
        class="panel"
        :to="`/mood-entries/${entry.id}`"
      >
        <strong>{{ entry.mood }}</strong>
        <span>{{ entry.entry_date }} - {{ entry.intensity }}/10</span>
      </RouterLink>
    </div>
  </section>
</template>
