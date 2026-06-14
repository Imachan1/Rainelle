<script setup>
import { computed, onMounted } from 'vue'
import { useInsightsStore } from '../stores/insights'

const insights = useInsightsStore()
const summary = computed(() => insights.summary)
const moodByWeather = computed(() => Object.entries(summary.value?.mood_by_weather_condition || {}))

onMounted(() => insights.fetchSummary())
</script>

<template>
  <section>
    <h1>Insights</h1>

    <p v-if="insights.error" class="error">{{ insights.error }}</p>
    <p v-if="insights.loading">Loading insights...</p>

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

        <div class="panel">
          <span>Best mood</span>
          <strong>{{ summary?.best_mood ?? '-' }}</strong>
        </div>

        <div class="panel">
          <span>Worst mood</span>
          <strong>{{ summary?.worst_mood ?? '-' }}</strong>
        </div>

        <div class="panel">
          <span>Most common emotion</span>
          <strong>{{ summary?.most_common_emotion ?? '-' }}</strong>
        </div>

        <div class="panel">
          <span>Average recorded temperature</span>
          <strong>{{ summary?.average_temperature ?? 0 }}C</strong>
        </div>
      </div>

      <div class="panel">
        <h2>Mood by weather</h2>
        <div v-if="moodByWeather.length" class="list">
          <div v-for="[condition, average] in moodByWeather" :key="condition" class="entry-summary">
            <strong>{{ condition }}</strong>
            <span>{{ average }}/10</span>
          </div>
        </div>
        <p v-else>No weather-based mood data yet.</p>
      </div>
    </div>
  </section>
</template>
