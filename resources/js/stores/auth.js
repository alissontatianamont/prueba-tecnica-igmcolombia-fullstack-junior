import { defineStore } from 'pinia';
import api from '@/services/api';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    isLoading: false,
    errors: {}
  }),

  getters: {

    isAuthenticated: (state) => !!state.token,
    

    currentUser: (state) => state.user,
    
    hasPermission: (state) => (permission) => {
      if (!state.user || !state.user.roles) return false;
      return state.user.roles.some(role => 
        role.permissions && role.permissions.some(perm => perm.name === permission)
      );
    },
    
    hasRole: (state) => (role) => {
      if (!state.user || !state.user.roles) return false;
      return state.user.roles.some(userRole => userRole.name === role);
    }
  },


  actions: {

    async login(credentials) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await api.post('/auth/login', credentials);
        const { token, user } = response.data.data;
        
        this.token = token;
        this.user = user;
        localStorage.setItem('token', token);
        
        return { success: true };
      } catch (error) {
        this.errors = error.response?.data?.errors || { 
          general: [error.response?.data?.message || 'Error de inicio de sesión'] 
        };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async logout() {
      try {
        await api.post('/auth/logout');
      } catch (error) {
        console.error('Error al hacer logout:', error);
      } finally {
        this.token = null;
        this.user = null;
        localStorage.removeItem('token');
      }
    },

    async fetchUser() {
      if (!this.token) return;
      
      try {
        const response = await api.get('/auth/me');
        this.user = response.data.data.user;
      } catch (error) {
        console.error('Error al obtener usuario:', error);
        this.logout();
      }
    },

    clearErrors() {
      this.errors = {};
    }
  }
});