import { defineStore } from 'pinia'
import { fetchCurrentWeather } from '../api/weather'

export const useWeatherStore = defineStore('weather', {
  state: () => ({
    current: null,
    loading: false,
  }),
  actions: {
    async fetchCurrent() {
      this.loading = true
      try {
        const { data } = await fetchCurrentWeather()
        this.current = data
      } finally {
        this.loading = false
      }
    },
  },
})
