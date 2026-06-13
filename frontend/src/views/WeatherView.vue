<script setup>
import { onMounted } from 'vue'
import { useWeatherStore } from '../stores/weather'

const weather = useWeatherStore()

onMounted(() => weather.fetchCurrent())
</script>

<template>
  <section>
    <h1>Weather</h1>
    <div class="panel">
      <p>{{ weather.current?.message || 'Weather data is not connected yet.' }}</p>
    </div>
  </section>
</template>
