import { defineStore } from 'pinia'
import * as authApi from '../api/auth'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('auth_token'),
    loading: false,
  }),
  actions: {
    setSession({ user, token }) {
      this.user = user
      this.token = token
      localStorage.setItem('auth_token', token)
    },
    async login(payload) {
      this.loading = true
      try {
        const { data } = await authApi.login(payload)
        this.setSession(data)
      } finally {
        this.loading = false
      }
    },
    async register(payload) {
      this.loading = true
      try {
        const { data } = await authApi.register(payload)
        this.setSession(data)
      } finally {
        this.loading = false
      }
    },
    async fetchUser() {
      if (!this.token) return

      const { data } = await authApi.fetchMe()
      this.user = data
    },
    async logout() {
      if (this.token) {
        await authApi.logout()
      }

      this.user = null
      this.token = null
      localStorage.removeItem('auth_token')
    },
  },
})
