import { defineStore } from 'pinia';
import api from '@/services/api';

export const useInvoiceStore = defineStore('invoices', {
  state: () => ({
    invoices: [],
    currentInvoice: null,
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
    getInvoiceById: (state) => (id) => {
      return state.invoices.find(invoice => invoice && invoice.id === parseInt(id));
    }
  },

  actions: {
    async fetchInvoices(params = {}) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await api.get('/invoices', { params });
        
        const invoicesData = response.data.data || response.data || [];
        this.invoices = invoicesData.filter(invoice => invoice && invoice.id);
        
        this.pagination = response.data.pagination || {
          current_page: 1,
          per_page: 10,
          total: 0,
          last_page: 1
        };
        return { success: true };
      } catch (error) {
        this.errors = { general: [error.response?.data?.message || 'Error al cargar facturas'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async createInvoice(invoiceData) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await api.post('/invoices', invoiceData);
        if (response.data.data) {
          this.invoices.unshift(response.data.data);
        }
        return { success: true, data: response.data.data };
      } catch (error) {
        this.errors = error.response?.data?.errors || { general: [error.response?.data?.message || 'Error al crear factura'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async updateInvoice(id, invoiceData) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await api.patch(`/invoices/${id}`, invoiceData);
        const index = this.invoices.findIndex(invoice => invoice.id === parseInt(id));
        if (index !== -1) {
          this.invoices[index] = response.data.data;
        }
        return { success: true, data: response.data.data };
      } catch (error) {
        this.errors = error.response?.data?.errors || { general: [error.response?.data?.message || 'Error al actualizar factura'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async deleteInvoice(id) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        await api.delete(`/invoices/${id}`);
        this.invoices = this.invoices.filter(invoice => invoice.id !== parseInt(id));
        return { success: true };
      } catch (error) {
        this.errors = { general: [error.response?.data?.message || 'Error al eliminar factura'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async fetchInvoice(id) {
      this.isLoading = true;
      this.errors = {};
      
      try {
        const response = await api.get(`/invoices/${id}`);
        this.currentInvoice = response.data.data;
        return { success: true, data: response.data.data };
      } catch (error) {
        this.errors = { general: [error.response?.data?.message || 'Error al cargar factura'] };
        return { success: false, errors: this.errors };
      } finally {
        this.isLoading = false;
      }
    },

    async getInvoiceFileUrl(id) {
      try {
        const response = await api.get(`/invoices/${id}/file`);
        
        // Devolver directamente los datos del backend
        return response.data;
      } catch (error) {
        console.error('Error en getInvoiceFileUrl:', error);
        console.error('Respuesta de error:', error.response?.data);
        throw error;
      }
    },

    // Función auxiliar para obtener clientes activos
    async fetchActiveClients() {
      try {
        const response = await api.get('/clients', { params: { cli_status: 1, paginate: false } });
        return { success: true, data: response.data.data || [] };
      } catch (error) {
        return { success: false, errors: { general: ['Error al cargar clientes'] } };
      }
    },

    // Función auxiliar para obtener productos con stock
    async fetchAvailableProducts() {
      try {
        const response = await api.get('/products', { params: { pro_status: 1, has_stock: true, paginate: false } });
        return { success: true, data: response.data.data || [] };
      } catch (error) {
        return { success: false, errors: { general: ['Error al cargar productos'] } };
      }
    },

    clearErrors() {
      this.errors = {};
    }
  }
});