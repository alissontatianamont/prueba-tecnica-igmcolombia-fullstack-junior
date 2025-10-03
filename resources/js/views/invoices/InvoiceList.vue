<template>
  <AppLayout>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Facturas</h1>
        <p class="mt-2 text-sm text-gray-700">Lista de todas las facturas del sistema</p>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <router-link
          v-if="hasPermission('create invoices')"
          to="/invoices/create"
          class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
          <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Nueva Factura
        </router-link>
      </div>
    </div>

    <!-- Filtros -->
    <div class="mt-6 bg-white p-4 rounded-lg shadow">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Número de Factura</label>
          <input
            v-model="filters.inv_number"
            type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            placeholder="Buscar por número"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Cliente</label>
          <input
            v-model="filters.client_name"
            type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            placeholder="Buscar por cliente"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Estado</label>
          <select
            v-model="filters.inv_status"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          >
            <option value="">Todos los estados</option>
            <option value="pending">Pendiente</option>
            <option value="paid">Pagada</option>
            <option value="overdue">Vencida</option>
          </select>
        </div>
        <div class="flex items-end space-x-2">
          <button
            @click="applyFilters"
            class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
          >
            Filtrar
          </button>
          <button
            @click="clearFilters"
            class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Limpiar
          </button>
        </div>
      </div>
    </div>

    <!-- Lista de facturas -->
    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-md">
      <div v-if="isLoading" class="text-center py-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
        <p class="mt-2 text-sm text-gray-600">Cargando facturas...</p>
      </div>

      <div v-else-if="invoices.length === 0" class="text-center py-8">
        <div class="mx-auto h-12 w-12 text-gray-400">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay facturas</h3>
        <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva factura.</p>
        <div v-if="hasPermission('create invoices')" class="mt-6">
          <router-link
            to="/invoices/create"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
          >
            Crear Factura
          </router-link>
        </div>
      </div>

      <ul v-else class="divide-y divide-gray-200">
        <li v-for="invoice in invoices" :key="invoice?.id || Math.random()" class="px-6 py-4">
          <div v-if="invoice" class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-10 w-10">
                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                  <span class="text-sm font-medium text-indigo-700">
                    {{ invoice.inv_number ? invoice.inv_number.substring(0, 2).toUpperCase() : 'FA' }}
                  </span>
                </div>
              </div>
              <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">
                  {{ invoice.inv_number || `Factura #${invoice.id}` }}
                </div>
                <div class="text-sm text-gray-500">
                  Cliente: {{ getClientName(invoice.client) }}
                </div>
                <div class="text-sm text-gray-500">
                  Fecha: {{ formatDate(invoice.inv_issue_date) }} 
                  | Vence: {{ formatDate(invoice.inv_due_date) }}
                </div>
                <div class="text-sm font-medium text-gray-900">
                  Total: ${{ formatCurrency(invoice.inv_total_amount) }}
                </div>
                <div class="mt-1">
                  <span :class="getStatusClass(invoice.inv_status)">
                    {{ getStatusLabel(invoice.inv_status) }}
                  </span>
                </div>
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <div class="flex space-x-2">
                <button
                  v-if="hasPermission('view invoices') && invoice.id"
                  @click="downloadInvoiceFile(invoice.id)"
                  class="text-blue-600 hover:text-blue-900 text-sm"
                  title="Descargar PDF"
                >
                  PDF
                </button>
                <router-link
                  v-if="hasPermission('edit invoices') && invoice.id && invoice.is_editable"
                  :to="`/invoices/${invoice.id}/edit`"
                  class="text-indigo-600 hover:text-indigo-900 text-sm"
                >
                  Editar
                </router-link>
                <span
                  v-if="hasPermission('edit invoices') && invoice.id && !invoice.is_editable"
                  class="text-gray-400 text-sm cursor-not-allowed"
                  title="No se puede editar - Fecha de vencimiento pasada"
                >
                  Editar
                </span>
                <button
                  v-if="hasPermission('delete invoices') && invoice.id && invoice.is_editable"
                  @click="confirmDelete(invoice)"
                  class="text-red-600 hover:text-red-900 text-sm"
                >
                  Eliminar
                </button>
                <span
                  v-if="hasPermission('delete invoices') && invoice.id && !invoice.is_editable"
                  class="text-gray-400 text-sm cursor-not-allowed"
                  title="No se puede eliminar - Fecha de vencimiento pasada"
                >
                  Eliminar
                </span>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <!-- Paginación -->
    <div v-if="pagination && pagination.last_page > 1" class="mt-6 flex items-center justify-between">
      <div class="flex-1 flex justify-between sm:hidden">
        <button
          @click="goToPage(pagination.current_page - 1)"
          :disabled="pagination.current_page <= 1"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
        >
          Anterior
        </button>
        <button
          @click="goToPage(pagination.current_page + 1)"
          :disabled="pagination.current_page >= pagination.last_page"
          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
        >
          Siguiente
        </button>
      </div>
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-700">
            Mostrando
            <span class="font-medium">{{ ((pagination.current_page - 1) * pagination.per_page) + 1 }}</span>
            a
            <span class="font-medium">{{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }}</span>
            de
            <span class="font-medium">{{ pagination.total }}</span>
            resultados
          </p>
        </div>
        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
            <button
              @click="goToPage(pagination.current_page - 1)"
              :disabled="pagination.current_page <= 1"
              class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
            >
              &lt;
            </button>
            
            <button
              v-for="page in Math.min(pagination.last_page, 5)"
              :key="page"
              @click="goToPage(page)"
              :class="[
                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                page === pagination.current_page
                  ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                  : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
              ]"
            >
              {{ page }}
            </button>
            
            <button
              @click="goToPage(pagination.current_page + 1)"
              :disabled="pagination.current_page >= pagination.last_page"
              class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
            >
              &gt;
            </button>
          </nav>
        </div>
      </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.316 15.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
          </div>
          <div class="mt-2 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Eliminar Factura</h3>
            <p class="text-sm text-gray-500 mt-2">
              ¿Estas seguro de que deseas eliminar la factura "{{
                invoiceToDelete?.inv_number || `#${invoiceToDelete?.id}`
              }}"?
              Esta accion no se puede deshacer.
            </p>
          </div>
          <div class="flex justify-center space-x-4 px-4 py-3">
            <button
              @click="showDeleteModal = false"
              class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400"
            >
              Cancelar
            </button>
            <button
              @click="deleteInvoiceConfirmed"
              class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700"
            >
              Eliminar
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { ref, reactive, onMounted } from 'vue';
import { useInvoices } from '@/composables/useInvoices';
import { useAuth } from '@/composables/useAuth';
import AppLayout from '@/components/layouts/AppLayout.vue';

export default {
  name: 'InvoiceList',
  components: {
    AppLayout
  },
  setup() {
    const { fetchInvoices, deleteInvoice, getInvoiceFileUrl, invoices, isLoading, pagination } = useInvoices();
    const { hasPermission } = useAuth();
    
    const filters = reactive({
      inv_number: '',
      client_name: '',
      inv_status: ''
    });

    const showDeleteModal = ref(false);
    const invoiceToDelete = ref(null);

    const loadInvoices = (params = {}) => {
      fetchInvoices({
        page: 1,
        per_page: 10,
        ...params
      });
    };

    const applyFilters = () => {
      const params = {};
      if (filters.inv_number) params.inv_number = filters.inv_number;
      if (filters.client_name) params.client_name = filters.client_name;
      if (filters.inv_status) params.inv_status = filters.inv_status;
      
      loadInvoices({
        ...params
      });
    };

    const clearFilters = () => {
      filters.inv_number = '';
      filters.client_name = '';
      filters.inv_status = '';
      
      loadInvoices();
    };

    const goToPage = (page) => {
      if (page >= 1 && page <= (pagination.value?.last_page || 1)) {
        loadInvoices({
          page,
          ...filters
        });
      }
    };

    const confirmDelete = (invoice) => {
      if (invoice && invoice.id) {
        invoiceToDelete.value = invoice;
        showDeleteModal.value = true;
      }
    };

    const deleteInvoiceConfirmed = async () => {
      if (invoiceToDelete.value) {
        const result = await deleteInvoice(invoiceToDelete.value.id);
        if (result.success) {
          showDeleteModal.value = false;
          invoiceToDelete.value = null;
          loadInvoices();
        }
      }
    };

    const downloadInvoiceFile = async (invoiceId) => {
      try {
        console.log('Intentando obtener URL del archivo para factura:', invoiceId);
        const result = await getInvoiceFileUrl(invoiceId);
        console.log('Resultado de getInvoiceFileUrl:', result);
        
        // Extraer la URL del archivo de la respuesta
        let fileUrl = null;
        
        if (result && result.data && result.data.file_url) {
          fileUrl = result.data.file_url;
        } else if (result && result.file_url) {
          fileUrl = result.file_url;
        }
        
        if (fileUrl) {
          console.log('URL encontrada:', fileUrl);
          console.log('Abriendo URL:', fileUrl);
          window.open(fileUrl, '_blank');
        } else {
          console.error('No se encontró URL en la respuesta:', result);
          alert('No se pudo obtener el enlace del archivo PDF');
        }
      } catch (error) {
        console.error('Error al descargar archivo:', error);
        alert('Error al obtener el archivo PDF: ' + error.message);
      }
    };

    const getClientName = (client) => {
      if (!client) return 'Cliente no asignado';
      return `${client.cli_first_name || ''} ${client.cli_middle_name || ''} ${client.cli_last_name || ''} ${client.cli_second_last_name || ''}`.trim() || 'Sin nombre';
    };

    const formatDate = (date) => {
      if (!date) return '';
      return new Date(date).toLocaleDateString('es-CO');
    };

    const formatCurrency = (amount) => {
      if (!amount) return '0.00';
      return parseFloat(amount).toLocaleString('es-CO', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    };

    const getStatusClass = (status) => {
      const baseClass = 'inline-flex px-2 text-xs font-semibold rounded-full';
      switch (status) {
        case 'paid':
          return `${baseClass} bg-green-100 text-green-800`;
        case 'pending':
          return `${baseClass} bg-yellow-100 text-yellow-800`;
        case 'overdue':
          return `${baseClass} bg-red-100 text-red-800`;
        default:
          return `${baseClass} bg-blue-100 text-blue-800`;
      }
    };

    const getStatusLabel = (status) => {
      const labels = {
        'pending': 'Pendiente',
        'paid': 'Pagada',
        'overdue': 'Vencida'
      };
      return labels[status] || 'Desconocido';
    };

    onMounted(() => {
      loadInvoices();
    });

    return {
      invoices,
      isLoading,
      pagination,
      filters,
      showDeleteModal,
      invoiceToDelete,
      hasPermission,
      applyFilters,
      clearFilters,
      goToPage,
      confirmDelete,
      deleteInvoiceConfirmed,
      downloadInvoiceFile,
      getClientName,
      formatDate,
      formatCurrency,
      getStatusClass,
      getStatusLabel
    };
  }
};
</script>