<script setup>
import { computed, onMounted } from 'vue'
import { useDashboardStore } from '../stores/dashboard'

const dashboard = useDashboardStore()
const summary = computed(() => dashboard.summary)

function hasWeather(entry) {
  if (!entry) return false

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

onMounted(() => dashboard.fetchSummary())
</script>

<template>
  <section>
    <div class="page-header">
      <h1>Dashboard</h1>
      <RouterLink class="button-link" to="/mood-entries/new">New entry</RouterLink>
    </div>

    <p v-if="dashboard.error" class="error">{{ dashboard.error }}</p>
    <p v-if="dashboard.loading">Loading dashboard...</p>

    <div v-else>
      <div class="summary-grid">
        <div class="panel">
          <span>Total entries</span>
          <strong>{{ summary?.total_entries ?? 0 }}</strong>
        </div>

        <div class="panel">
          <span>Average mood</span>
          <strong>{{ summary?.average_mood ?? 0 }}/10</strong>
        </div>
      </div>

      <div class="panel">
        <h2>Today</h2>
        <template v-if="summary?.today_entry">
          <p>{{ summary.today_entry.emotion }} - {{ summary.today_entry.mood_score }}/10</p>
          <p v-if="hasWeather(summary.today_entry)" class="muted">
            {{ weatherSummary(summary.today_entry) }}
          </p>
        </template>
        <p v-else>No mood entry for today yet.</p>
      </div>

      <div class="panel">
        <h2>Latest entries</h2>
        <div v-if="summary?.latest_entries?.length" class="list">
          <RouterLink
            v-for="entry in summary.latest_entries"
            :key="entry.id"
            :to="`/mood-entries/${entry.id}`"
            class="entry-summary"
          >
            <strong>{{ entry.date }}</strong>
            <span>
              {{ entry.emotion }} - {{ entry.mood_score }}/10
              <small v-if="hasWeather(entry)">{{ weatherSummary(entry) }}</small>
            </span>
          </RouterLink>
        </div>
        <p v-else>No entries yet.</p>
      </div>
    </div>
  </section>
</template>
