import api from './axios'

function getResponseData(response) {
  return response.data.data
}

export async function fetchMoodEntries() {
  return getResponseData(await api.get('/mood-entries'))
}

export async function fetchMoodEntry(id) {
  return getResponseData(await api.get(`/mood-entries/${id}`))
}

export async function createMoodEntry(payload) {
  return getResponseData(await api.post('/mood-entries', payload))
}

export async function updateMoodEntry(id, payload) {
  return getResponseData(await api.put(`/mood-entries/${id}`, payload))
}

export async function deleteMoodEntry(id) {
  await api.delete(`/mood-entries/${id}`)
}
