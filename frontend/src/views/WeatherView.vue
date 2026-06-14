<script setup>
import { ref } from 'vue'
import { useWeatherStore } from '../stores/weather'

const weather = useWeatherStore()
const lastCoords = ref(null)

function getCurrentPosition() {
  return new Promise((resolve, reject) => {
    if (!navigator.geolocation) {
      reject(new Error('Geolocation is not supported by this browser.'))
      return
    }

    navigator.geolocation.getCurrentPosition(resolve, reject)
  })
}

async function useCurrentLocation() {
  weather.error = null

  try {
    const position = await getCurrentPosition()
    lastCoords.value = {
      lat: position.coords.latitude,
      lon: position.coords.longitude,
    }
    await weather.fetchCurrent(lastCoords.value)
  } catch (error) {
    if (!weather.error) {
      weather.error = error.message || 'Unable to get your location.'
    }
  }
}

async function refreshWeather() {
  if (!lastCoords.value) {
    weather.error = 'Use current location before refreshing weather.'
    return
  }

  await weather.fetchCurrent(lastCoords.value)
}
</script>

<template>
  <section>
    <div class="page-header">
      <h1>Weather</h1>
      <div class="entry-actions">
        <button type="button" :disabled="weather.loading" @click="useCurrentLocation">
          Use current location
        </button>
        <button type="button" :disabled="weather.loading" @click="refreshWeather">
          Refresh
        </button>
      </div>
    </div>

    <p v-if="weather.error" class="error">{{ weather.error }}</p>
    <p v-if="weather.loading">Loading weather...</p>

    <div v-if="weather.current && !weather.loading" class="panel weather-grid">
      <div>
        <span>Temperature</span>
        <strong>{{ weather.current.temperature }}C</strong>
      </div>
      <div>
        <span>Humidity</span>
        <strong>{{ weather.current.humidity }}%</strong>
      </div>
      <div>
        <span>Pressure</span>
        <strong>{{ weather.current.pressure }} hPa</strong>
      </div>
      <div>
        <span>Wind speed</span>
        <strong>{{ weather.current.wind_speed }} m/s</strong>
      </div>
      <div>
        <span>Condition</span>
        <strong>{{ weather.current.weather_condition }}</strong>
      </div>
    </div>

    <div v-else-if="!weather.loading" class="panel">
      <p>Use your current location to load weather.</p>
    </div>
  </section>
</template>
