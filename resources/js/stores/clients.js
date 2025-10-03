import { defineStore } from 'pinia';
import api from '@/services/api';

export const useClientStore = defineStore('clients', {
  state: () => ({
    clients: [],
    currentClient: null,
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
    getClientById: (state) => (id) => {
      return state.clients.find(client => client && client.id === parseInt(id));
    }
  },

  actions: {
    async fetchClients(params = {}) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await api.get('/clients', { params });
        
        // Manejar diferentes estructuras de respuesta del backend
        const clientsData = response.data.data || response.data || [];
        this.clients = clientsData.filter(client => client && client.id);
        
        this.pagination = response.data.pagination || {
          current_page: 1,
          per_page: 10,
          total: 0,
          last_page: 1
        };
        return { success: true };
      } catch (error) {
        this.errors = { general: [error.response?.data?.message || 'Error al cargar clientes'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async createClient(clientData) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await api.post('/clients', clientData);
        if (response.data.data) {
          this.clients.unshift(response.data.data);
        }
        return { success: true, data: response.data.data };
      } catch (error) {
        this.errors = error.response?.data?.errors || { general: [error.response?.data?.message || 'Error al crear cliente'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async updateClient(id, clientData) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await api.patch(`/clients/${id}`, clientData);
        const index = this.clients.findIndex(client => client.id === parseInt(id));
        if (index !== -1) {
          this.clients[index] = response.data.data;
        }
        return { success: true, data: response.data.data };
      } catch (error) {
        this.errors = error.response?.data?.errors || { general: [error.response?.data?.message || 'Error al actualizar cliente'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async deleteClient(id) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        await api.delete(`/clients/${id}`);
        this.clients = this.clients.filter(client => client.id !== parseInt(id));
        return { success: true };
      } catch (error) {
        this.errors = { general: [error.response?.data?.message || 'Error al eliminar cliente'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async fetchClient(id) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await api.get(`/clients/${id}`);
        this.currentClient = response.data.data;
        return { success: true, data: response.data.data };
      } catch (error) {
        this.errors = { general: [error.response?.data?.message || 'Error al cargar cliente'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async activateClient(id) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await api.patch(`/clients/${id}/activate`);
        const index = this.clients.findIndex(client => client.id === parseInt(id));
        if (index !== -1) {
          this.clients[index] = response.data.data;
        }
        return { success: true, data: response.data.data };
      } catch (error) {
        this.errors = { general: [error.response?.data?.message || 'Error al activar cliente'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async deactivateClient(id) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await api.patch(`/clients/${id}/deactivate`);
        const index = this.clients.findIndex(client => client.id === parseInt(id));
        if (index !== -1) {
          this.clients[index] = response.data.data;
        }
        return { success: true, data: response.data.data };
      } catch (error) {
        this.errors = { general: [error.response?.data?.message || 'Error al desactivar cliente'] };
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