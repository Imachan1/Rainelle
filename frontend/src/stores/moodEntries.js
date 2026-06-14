import { defineStore } from 'pinia'
import { getApiErrorMessage } from '../api/errors'
import * as moodEntriesApi from '../api/moodEntries'

export const useMoodEntriesStore = defineStore('moodEntries', {
  state: () => ({
    entries: [],
    currentEntry: null,
    loading: false,
    errors: null,
  }),
  actions: {
    setError(error, fallback) {
      this.errors = getApiErrorMessage(error, fallback)
    },
    async fetchEntries() {
      this.loading = true
      this.errors = null
      try {
        this.entries = await moodEntriesApi.fetchMoodEntries()
      } catch (error) {
        this.setError(error, 'Unable to load mood entries.')
      } finally {
        this.loading = false
      }
    },
    async fetchEntry(id) {
      this.loading = true
      this.errors = null
      try {
        this.currentEntry = await moodEntriesApi.fetchMoodEntry(id)
        return this.currentEntry
      } catch (error) {
        this.setError(error, 'Unable to load mood entry.')
        throw error
      } finally {
        this.loading = false
      }
    },
    async createEntry(payload) {
      this.loading = true
      this.errors = null
      try {
        const entry = await moodEntriesApi.createMoodEntry(payload)
        this.entries.unshift(entry)
        return entry
      } catch (error) {
        this.setError(error, 'Unable to create mood entry.')
        throw error
      } finally {
        this.loading = false
      }
    },
    async updateEntry(id, payload) {
      this.loading = true
      this.errors = null
      try {
        const entry = await moodEntriesApi.updateMoodEntry(id, payload)
        this.currentEntry = entry
        this.entries = this.entries.map((item) => (item.id === entry.id ? entry : item))
        return entry
      } catch (error) {
        this.setError(error, 'Unable to update mood entry.')
        throw error
      } finally {
        this.loading = false
      }
    },
    async deleteEntry(id) {
      this.loading = true
      this.errors = null
      try {
        await moodEntriesApi.deleteMoodEntry(id)
        this.entries = this.entries.filter((entry) => entry.id !== id)
        if (this.currentEntry?.id === id) {
          this.currentEntry = null
        }
      } catch (error) {
        this.setError(error, 'Unable to delete mood entry.')
        throw error
      } finally {
        this.loading = false
      }
    },
  },
})
