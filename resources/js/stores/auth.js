// Store auth — akan diisi oleh Dev A
import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    isAuthenticated: false,
  }),

  getters: {
    role: (state) => state.user?.role ?? null,
    isSuperadmin: (state) => state.user?.role === 'superadmin',
    isAdmin: (state) => state.user?.role === 'admin',
    isUser: (state) => state.user?.role === 'user',
  },

  actions: {
    // Placeholder — Dev A akan mengisi login/logout via API
    setUser(user) {
      this.user = user;
      this.isAuthenticated = !!user;
    },
    clearUser() {
      this.user = null;
      this.token = null;
      this.isAuthenticated = false;
    },
  },
});
