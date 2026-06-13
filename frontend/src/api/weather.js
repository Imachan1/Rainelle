import api from './axios'

export function fetchCurrentWeather() {
  return api.get('/weather/current')
}
