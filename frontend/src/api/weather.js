import api from './axios'

export async function fetchCurrentWeather(coords = {}) {
  const { lat, lon } = coords

  if (lat === undefined || lon === undefined) {
    throw new Error('Location coordinates are required to load weather.')
  }

  const response = await api.get('/weather/current', {
    params: { lat, lon },
  })

  return response.data.data
}
