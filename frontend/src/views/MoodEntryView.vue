<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getApiErrorMessage } from '../api/errors'
import { fetchCurrentWeather } from '../api/weather'
import { useMoodEntriesStore } from '../stores/moodEntries'

const route = useRoute()
const router = useRouter()
const moodEntries = useMoodEntriesStore()
const isEditing = computed(() => route.params.id !== 'new')
const weatherLoading = ref(false)
const weatherError = ref('')
const form = reactive({
  date: new Date().toISOString().slice(0, 10),
  mood_score: 5,
  emotion: '',
  note: '',
  temperature: '',
  humidity: '',
  pressure: '',
  wind_speed: '',
  weather_condition: '',
})

function getCurrentPosition() {
  return new Promise((resolve, reject) => {
    if (!navigator.geolocation) {
      reject(new Error('Geolocation is not supported by this browser.'))
      return
    }

    navigator.geolocation.getCurrentPosition(resolve, reject)
  })
}

onMounted(async () => {
  if (!isEditing.value) return

  const entry = await moodEntries.fetchEntry(route.params.id)
  form.date = entry.date
  form.mood_score = entry.mood_score
  form.emotion = entry.emotion
  form.note = entry.note || ''
  form.temperature = entry.temperature ?? ''
  form.humidity = entry.humidity ?? ''
  form.pressure = entry.pressure ?? ''
  form.wind_speed = entry.wind_speed ?? ''
  form.weather_condition = entry.weather_condition || ''
})

async function fillWeatherFromLocation() {
  weatherLoading.value = true
  weatherError.value = ''

  try {
    const position = await getCurrentPosition()
    const weather = await fetchCurrentWeather({
      lat: position.coords.latitude,
      lon: position.coords.longitude,
    })

    form.temperature = weather.temperature ?? ''
    form.humidity = weather.humidity ?? ''
    form.pressure = weather.pressure ?? ''
    form.wind_speed = weather.wind_speed ?? ''
    form.weather_condition = weather.weather_condition || ''
  } catch (error) {
    weatherError.value = getApiErrorMessage(error, error.message || 'Unable to load weather.')
  } finally {
    weatherLoading.value = false
  }
}

async function submit() {
  if (isEditing.value) {
    await moodEntries.updateEntry(route.params.id, { ...form })
  } else {
    await moodEntries.createEntry({ ...form })
  }

  router.push({ name: 'mood-entries' })
}
</script>

<template>
  <section>
    <h1>{{ isEditing ? 'Edit Mood Entry' : 'New Mood Entry' }}</h1>

    <form class="form panel" @submit.prevent="submit">
      <label>
        Date
        <input v-model="form.date" type="date" required />
      </label>

      <label>
        Mood score
        <input v-model.number="form.mood_score" type="number" min="1" max="10" required />
      </label>

      <label>
        Emotion
        <input v-model="form.emotion" type="text" maxlength="80" required />
      </label>

      <label>
        Note
        <textarea v-model="form.note" rows="4" maxlength="2000" />
      </label>

      <div class="weather-header">
        <h2>Weather</h2>
        <button type="button" :disabled="weatherLoading" @click="fillWeatherFromLocation">
          {{ weatherLoading ? 'Loading weather...' : 'Use current location weather' }}
        </button>
      </div>

      <p v-if="weatherError" class="error">{{ weatherError }}</p>

      <div class="form-grid">
        <label>
          Temperature
          <input v-model.number="form.temperature" type="number" step="0.1" />
        </label>

        <label>
          Humidity
          <input v-model.number="form.humidity" type="number" min="0" max="100" />
        </label>

        <label>
          Pressure
          <input v-model.number="form.pressure" type="number" min="800" max="1200" />
        </label>

        <label>
          Wind speed
          <input v-model.number="form.wind_speed" type="number" min="0" step="0.1" />
        </label>
      </div>

      <label>
        Weather condition
        <input v-model="form.weather_condition" type="text" maxlength="80" />
      </label>

      <p v-if="moodEntries.errors" class="error">{{ moodEntries.errors }}</p>

      <div class="form-actions">
        <button type="submit" :disabled="moodEntries.loading">
          {{ moodEntries.loading ? 'Saving...' : 'Save entry' }}
        </button>
        <RouterLink to="/mood-entries">Cancel</RouterLink>
      </div>
    </form>
  </section>
</template>
