import api from './axios'

export async function fetchCurrentWeather({ lat, lon }) {
  const response = await api.get('/weather/current', {
    params: { lat, lon },
  })

  return response.data.data
}
