import { defineStore } from 'pinia'
import * as authApi from '../api/auth'

const TOKEN_KEY = 'auth_token'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem(TOKEN_KEY),
    loading: false,
    initialized: false,
  }),
  getters: {
    isAuthenticated: (state) => Boolean(state.token),
  },
  actions: {
    setSession({ user, token }) {
      this.user = user
      this.token = token
      localStorage.setItem(TOKEN_KEY, token)
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
      if (!this.token) {
        this.initialized = true
        return
      }

      try {
        const { data } = await authApi.fetchMe()
        this.user = data.user
      } catch {
        this.clearSession()
      } finally {
        this.initialized = true
      }
    },
    async logout() {
      try {
        if (this.token) {
          await authApi.logout()
        }
      } finally {
        this.clearSession()
      }
    },
    clearSession() {
      this.user = null
      this.token = null
      localStorage.removeItem(TOKEN_KEY)
    },
  },
})
