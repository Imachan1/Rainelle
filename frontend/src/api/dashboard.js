import api from './axios'

export async function fetchDashboard() {
  const response = await api.get('/dashboard')

  return response.data
}
