<script setup>
import { computed, onMounted, ref } from 'vue'
import MoodByWeatherChart from '../components/charts/MoodByWeatherChart.vue'
import MoodTimelineChart from '../components/charts/MoodTimelineChart.vue'
import { useInsightsStore } from '../stores/insights'
import { useMoodEntriesStore } from '../stores/moodEntries'

const ranges = [
  { value: 'last_7_days', label: 'Last 7 days' },
  { value: 'last_30_days', label: 'Last 30 days' },
  { value: 'this_month', label: 'This month' },
  { value: 'all_time', label: 'All time' },
]

const insights = useInsightsStore()
const moodEntries = useMoodEntriesStore()
const selectedRange = ref('all_time')
const summary = computed(() => insights.summary)
const moodByWeather = computed(() => Object.entries(summary.value?.mood_by_weather_condition || {}))
const loading = computed(() => insights.loading || moodEntries.loading)

function loadInsights() {
  insights.fetchSummary(selectedRange.value)
  moodEntries.fetchEntries({ range: selectedRange.value })
}

function selectRange(range) {
  selectedRange.value = range
  loadInsights()
}

onMounted(loadInsights)
</script>

<template>
  <section>
    <h1>Insights</h1>

    <div class="filter-actions" aria-label="Insights date range">
      <button
        v-for="range in ranges"
        :key="range.value"
        type="button"
        :class="{ secondary: selectedRange !== range.value }"
        :disabled="loading && selectedRange === range.value"
        @click="selectRange(range.value)"
      >
        {{ range.label }}
      </button>
    </div>

    <p v-if="insights.error" class="error">{{ insights.error }}</p>
    <p v-if="loading">Loading insights...</p>

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
          <span>Current streak</span>
          <strong>{{ summary?.current_streak ?? 0 }} days</strong>
        </div>

        <div class="panel">
          <span>Longest streak</span>
          <strong>{{ summary?.longest_streak ?? 0 }} days</strong>
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
        <h2>Mood timeline</h2>
        <MoodTimelineChart v-if="moodEntries.entries.length" :entries="moodEntries.entries" />
        <p v-else>No mood entries to chart yet.</p>
      </div>

      <div class="panel">
        <h2>Mood by weather</h2>
        <template v-if="moodByWeather.length">
          <MoodByWeatherChart :items="moodByWeather" />
          <div class="list">
            <div v-for="[condition, average] in moodByWeather" :key="condition" class="entry-summary">
              <strong>{{ condition }}</strong>
              <span>{{ average }}/10</span>
            </div>
          </div>
        </template>
        <p v-else>No weather-based mood data yet.</p>
      </div>
    </div>
  </section>
</template>
