import { defineStore } from 'pinia'
import { getApiErrorMessage } from '../api/errors'
import { fetchCurrentWeather } from '../api/weather'

export const useWeatherStore = defineStore('weather', {
  state: () => ({
    current: null,
    loading: false,
    error: null,
  }),
  actions: {
    async fetchCurrent(coords) {
      this.loading = true
      this.error = null
      try {
        this.current = await fetchCurrentWeather(coords)
      } catch (error) {
        this.error = getApiErrorMessage(error, 'Unable to load weather.')
        throw error
      } finally {
        this.loading = false
      }
    },
  },
})
