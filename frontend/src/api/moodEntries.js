import api from './axios'

export function fetchMoodEntries() {
  return api.get('/mood-entries')
}

export function fetchMoodEntry(id) {
  return api.get(`/mood-entries/${id}`)
}

export function createMoodEntry(payload) {
  return api.post('/mood-entries', payload)
}

export function updateMoodEntry(id, payload) {
  return api.put(`/mood-entries/${id}`, payload)
}

export function deleteMoodEntry(id) {
  return api.delete(`/mood-entries/${id}`)
}
