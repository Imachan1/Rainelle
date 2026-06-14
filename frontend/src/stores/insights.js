import { defineStore } from 'pinia'
import { getApiErrorMessage } from '../api/errors'
import { fetchInsights } from '../api/insights'

export const useInsightsStore = defineStore('insights', {
  state: () => ({
    summary: null,
    loading: false,
    error: null,
  }),
  actions: {
    async fetchSummary() {
      this.loading = true
      this.error = null

      try {
        this.summary = await fetchInsights()
      } catch (error) {
        this.error = getApiErrorMessage(error, 'Unable to load insights.')
      } finally {
        this.loading = false
      }
    },
  },
})
