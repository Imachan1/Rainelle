import api from './axios'

export function fetchDashboard() {
  return api.get('/dashboard')
}
