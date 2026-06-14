import api from './axios'

export async function fetchInsights() {
  const response = await api.get('/insights')

  return response.data
}
