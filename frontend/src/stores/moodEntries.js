import { defineStore } from 'pinia'
import * as moodEntriesApi from '../api/moodEntries'

export const useMoodEntriesStore = defineStore('moodEntries', {
  state: () => ({
    entries: [],
    loading: false,
  }),
  actions: {
    async fetchEntries() {
      this.loading = true
      try {
        const { data } = await moodEntriesApi.fetchMoodEntries()
        this.entries = data
      } finally {
        this.loading = false
      }
    },
    async createEntry(payload) {
      const { data } = await moodEntriesApi.createMoodEntry(payload)
      this.entries.unshift(data)
      return data
    },
  },
})
