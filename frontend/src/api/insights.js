import api from './axios'

export async function fetchInsights(range = 'all_time') {
  const response = await api.get('/insights', {
    params: { range },
  })

  return response.data
}
