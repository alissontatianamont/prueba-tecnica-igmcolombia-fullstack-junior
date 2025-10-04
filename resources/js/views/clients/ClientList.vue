<template>
  <AppLayout>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Clientes</h1>
        <p class="mt-2 text-sm text-gray-700">Gestión de clientes del sistema</p>
      </div>
      <div v-if="hasPermission('create clients')" class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <router-link
          to="/clients/create"
          class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
        >
          Agregar Cliente
        </router-link>
      </div>
    </div>

    <!-- Filtros -->
    <div class="mt-6 bg-white p-4 rounded-lg shadow">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Número de Documento</label>
          <input
            v-model="filters.cli_document_number"
            type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            placeholder="Buscar por documento"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input
            v-model="filters.cli_email"
            type="email"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            placeholder="Buscar por email"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Estado</label>
          <select
            v-model="filters.cli_status"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          >
            <option value="">Todos los estados</option>
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
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

    <!-- Lista de clientes -->
    <div class="mt-6 bg-white shadow sm:rounded-md">
      <div v-if="isLoading" class="text-center py-8">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
        <p class="mt-2 text-sm text-gray-600">Cargando clientes...</p>
      </div>

      <div v-else-if="clients.length === 0" class="text-center py-8">
        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
          <path d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A9.971 9.971 0 0124 24c4.21 0 7.863 2.613 9.288 6.286M30 14a6 6 0 11-12 0 6 6 0 0112 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay clientes</h3>
        <p class="mt-1 text-sm text-gray-500">Comienza agregando tu primer cliente al sistema.</p>
        <div v-if="hasPermission('create clients')" class="mt-6">
          <router-link
            to="/clients/create"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
          >
            Agregar Cliente
          </router-link>
        </div>
      </div>

      <ul v-else class="divide-y divide-gray-200">
        <li v-for="client in clients" :key="client?.id || Math.random()" class="px-6 py-4">
          <div v-if="client" class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-10 w-10">
                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                  <span class="text-sm font-medium text-indigo-700">
                    {{ client.cli_first_name ? client.cli_first_name.charAt(0).toUpperCase() : 'C' }}
                  </span>
                </div>
              </div>
              <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">
                  {{ 
                    client.cli_first_name 
                      ? `${client.cli_first_name} ${client.cli_middle_name || ''} ${client.cli_last_name || ''} ${client.cli_second_last_name || ''}`.trim()
                      : `Cliente #${client.id}` 
                  }}
                </div>
                <div class="text-sm text-gray-500">{{ client.cli_email || 'Sin email' }}</div>
                <div v-if="client.cli_phone" class="text-sm text-gray-500">{{ client.cli_phone }}</div>
                <div v-if="client.cli_document_number" class="text-sm text-gray-500">
                  {{ client.cli_document_type || 'Doc' }}: {{ client.cli_document_number }}
                </div>
                <div class="mt-1">
                  <span :class="[
                    'inline-flex px-2 text-xs font-semibold rounded-full',
                    parseInt(client.cli_status) === 1 
                      ? 'bg-green-100 text-green-800' 
                      : 'bg-red-100 text-red-800'
                  ]">
                    {{ parseInt(client.cli_status) === 1 ? 'Activo' : 'Inactivo' }}
                  </span>
                </div>
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <div class="flex space-x-2">
                <router-link
                  v-if="hasPermission('edit clients') && client.id"
                  :to="`/clients/${client.id}/edit`"
                  class="text-indigo-600 hover:text-indigo-900 text-sm"
                >
                  Editar
                </router-link>
                <button
                  v-if="hasPermission('edit clients') && client.id && parseInt(client.cli_status) === 1"
                  @click="changeStatus(client.id, 'deactivate')"
                  class="text-orange-600 hover:text-orange-900 text-sm"
                >
                  Desactivar
                </button>
                <button
                  v-if="hasPermission('edit clients') && client.id && parseInt(client.cli_status) === 0"
                  @click="changeStatus(client.id, 'activate')"
                  class="text-green-600 hover:text-green-900 text-sm"
                >
                  Activar
                </button>
                <button
                  v-if="hasPermission('delete clients') && client.id"
                  @click="confirmDelete(client)"
                  class="text-red-600 hover:text-red-900 text-sm"
                >
                  Eliminar
                </button>
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
              ‹
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
              ›
            </button>
          </nav>
        </div>
      </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <h3 class="text-lg font-medium text-gray-900">Confirmar eliminación</h3>
          <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-500">
              ¿Estas seguro de que quieres eliminar el cliente "{{ 
                clientToDelete?.cli_first_name 
                  ? `${clientToDelete.cli_first_name} ${clientToDelete.cli_last_name || ''}`.trim()
                  : `Cliente #${clientToDelete?.id}` 
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
              @click="deleteClientConfirmed"
              class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700"
            >
              Eliminar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de error informativo para clientes -->
    <div v-if="showErrorModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center mb-4">
          <svg class="h-6 w-6 text-yellow-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
          </svg>
          <h3 class="text-lg font-medium text-gray-900">Cliente desactivado</h3>
        </div>
        <p class="text-sm text-gray-500 mb-6">
          Este cliente tiene facturas relacionadas y no puede ser eliminado. Ha sido desactivado en su lugar.
        </p>
        <div class="flex justify-end">
          <button
            @click="showErrorModal = false"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700"
          >
            Entendido
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { ref, reactive, onMounted } from 'vue';
import { useClients } from '@/composables/useClients';
import { useAuth } from '@/composables/useAuth';
import AppLayout from '@/components/layouts/AppLayout.vue';

export default {
  name: 'ClientList',
  components: {
    AppLayout
  },
  setup() {
    const { fetchClients, deleteClient, activateClient, deactivateClient, clients, isLoading, pagination } = useClients();
    const { hasPermission } = useAuth();
    
    const filters = reactive({
      cli_document_number: '',
      cli_email: '',
      cli_status: ''
    });

    const showDeleteModal = ref(false);
    const showErrorModal = ref(false);
    const clientToDelete = ref(null);

    const loadClients = (params = {}) => {
      fetchClients({
        page: 1,
        per_page: 10,
        ...params
      });
    };

    const applyFilters = () => {
      const params = {};
      if (filters.cli_document_number) params.cli_document_number = filters.cli_document_number;
      if (filters.cli_email) params.cli_email = filters.cli_email;
      if (filters.cli_status !== '') params.cli_status = filters.cli_status;
      
      loadClients({
        ...params
      });
    };

    const clearFilters = () => {
      filters.cli_document_number = '';
      filters.cli_email = '';
      filters.cli_status = '';
      
      loadClients();
    };

    const goToPage = (page) => {
      if (page >= 1 && page <= (pagination.value?.last_page || 1)) {
        loadClients({
          page,
          ...filters
        });
      }
    };

    const confirmDelete = (client) => {
      if (client && client.id) {
        clientToDelete.value = client;
        showDeleteModal.value = true;
      }
    };

    const deleteClientConfirmed = async () => {
      if (clientToDelete.value) {
        const result = await deleteClient(clientToDelete.value.id);
        if (result.success) {
          showDeleteModal.value = false;
          clientToDelete.value = null;
          loadClients();
        } else {
          // Verificar si es el error específico de cliente con facturas
          if (result.errors && result.errors.general && 
              result.errors.general[0] && 
              result.errors.general[0].includes('deactivated instead of deleted')) {
            showDeleteModal.value = false;
            showErrorModal.value = true;
            // Refrescar la lista para mostrar el cliente desactivado
            loadClients();
          }
        }
      }
    };

    const changeStatus = async (clientId, action) => {
      let result;
      if (action === 'activate') {
        result = await activateClient(clientId);
      } else if (action === 'deactivate') {
        result = await deactivateClient(clientId);
      }
      
      if (result && result.success) {
        loadClients(); // Recargar la lista para mostrar el estado actualizado
      }
    };

    onMounted(() => {
      loadClients();
    });

    return {
      clients,
      isLoading,
      pagination,
      filters,
      showDeleteModal,
      showErrorModal,
      clientToDelete,
      hasPermission,
      applyFilters,
      clearFilters,
      goToPage,
      confirmDelete,
      deleteClientConfirmed,
      changeStatus
    };
  }
};
</script>