<script setup>
import { onMounted } from 'vue'
import { useMoodEntriesStore } from '../stores/moodEntries'

const moodEntries = useMoodEntriesStore()

function preview(note) {
  if (!note) return 'No note'

  return note.length > 90 ? `${note.slice(0, 90)}...` : note
}

function hasWeather(entry) {
  return [
    entry.temperature,
    entry.humidity,
    entry.pressure,
    entry.wind_speed,
    entry.weather_condition,
  ].some((value) => value !== null && value !== undefined && value !== '')
}

function hasValue(value) {
  return value !== null && value !== undefined && value !== ''
}

function weatherSummary(entry) {
  return [
    hasValue(entry.temperature) ? `${entry.temperature}C` : null,
    hasValue(entry.humidity) ? `${entry.humidity}% humidity` : null,
    hasValue(entry.pressure) ? `${entry.pressure} hPa` : null,
    hasValue(entry.wind_speed) ? `${entry.wind_speed} wind` : null,
    entry.weather_condition,
  ].filter(Boolean).join(' - ')
}

async function deleteEntry(id) {
  await moodEntries.deleteEntry(id)
}

onMounted(() => moodEntries.fetchEntries())
</script>

<template>
  <section>
    <div class="page-header">
      <h1>Mood Entries</h1>
      <RouterLink class="button-link" to="/mood-entries/new">New entry</RouterLink>
    </div>

    <p v-if="moodEntries.errors" class="error">{{ moodEntries.errors }}</p>
    <p v-if="moodEntries.loading">Loading entries...</p>

    <div v-else-if="moodEntries.entries.length" class="list">
      <article v-for="entry in moodEntries.entries" :key="entry.id" class="panel entry-row">
        <div>
          <strong>{{ entry.date }}</strong>
          <p>{{ entry.emotion }} - {{ entry.mood_score }}/10</p>
          <p v-if="hasWeather(entry)" class="muted">{{ weatherSummary(entry) }}</p>
          <p>{{ preview(entry.note) }}</p>
        </div>

        <div class="entry-actions">
          <RouterLink :to="`/mood-entries/${entry.id}`">Edit</RouterLink>
          <button type="button" @click="deleteEntry(entry.id)">Delete</button>
        </div>
      </article>
    </div>

    <div v-else class="panel">
      <p>No mood entries yet.</p>
    </div>
  </section>
</template>
