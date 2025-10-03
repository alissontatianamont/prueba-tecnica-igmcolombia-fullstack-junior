import { defineStore } from 'pinia';
import api from '@/services/api';

export const useUserStore = defineStore('users', {
  state: () => ({
    users: [],
    currentUser: null,
    isLoading: false,
    errors: {},
    pagination: {
      current_page: 1,
      per_page: 10,
      total: 0,
      last_page: 1
    }
  }),

  getters: {
    getUserById: (state) => (id) => {
      return state.users.find(user => user && user.id === parseInt(id));
    }
  },

  actions: {
    async fetchUsers(params = {}) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await api.get('/users', { params });
        this.users = (response.data.data || []).filter(user => user && user.id);
        this.pagination = response.data.meta || {
          current_page: 1,
          per_page: 10,
          total: 0,
          last_page: 1
        };
        return { success: true };
      } catch (error) {
        this.errors = { general: [error.response?.data?.message || 'Error al cargar usuarios'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async createUser(userData) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const dataToSend = {
          name: userData.name,
          email: userData.email,
          password: userData.password,
          password_confirmation: userData.password_confirmation,
          user_rol: userData.role
        };
        
        const response = await api.post('/users', dataToSend);
        if (response.data.data) {
          this.users.unshift(response.data.data);
        }
        return { success: true, data: response.data.data };
      } catch (error) {
        this.errors = error.response?.data?.errors || { general: [error.response?.data?.message || 'Error al crear usuario'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async updateUser(id, userData) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const dataToSend = {};
        
        if (userData.name) dataToSend.name = userData.name;
        if (userData.email) dataToSend.email = userData.email;
        if (userData.password) {
          dataToSend.password = userData.password;
          dataToSend.password_confirmation = userData.password_confirmation;
        }
        if (userData.role) dataToSend.user_rol = userData.role;
        
        const response = await api.patch(`/users/${id}`, dataToSend);
        const index = this.users.findIndex(user => user.id === parseInt(id));
        if (index !== -1) {
          this.users[index] = response.data.data;
        }
        return { success: true, data: response.data.data };
      } catch (error) {
        this.errors = error.response?.data?.errors || { general: [error.response?.data?.message || 'Error al actualizar usuario'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async deleteUser(id) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        await api.delete(`/users/${id}`);
        this.users = this.users.filter(user => user.id !== parseInt(id));
        return { success: true };
      } catch (error) {
        this.errors = { general: [error.response?.data?.message || 'Error al eliminar usuario'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async fetchUser(id) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await api.get(`/users/${id}`);
        this.currentUser = response.data.data;
        return { success: true, data: response.data.data };
      } catch (error) {
        this.errors = { general: [error.response?.data?.message || 'Error al cargar usuario'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    clearErrors() {
      this.errors = {};
    }
  }
});