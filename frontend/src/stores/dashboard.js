import { defineStore } from 'pinia'
import { fetchDashboard } from '../api/dashboard'
import { getApiErrorMessage } from '../api/errors'

export const useDashboardStore = defineStore('dashboard', {
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
        this.summary = await fetchDashboard()
      } catch (error) {
        this.error = getApiErrorMessage(error, 'Unable to load dashboard.')
      } finally {
        this.loading = false
      }
    },
  },
})
