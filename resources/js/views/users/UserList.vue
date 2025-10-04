<template>
  <AppLayout>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Usuarios</h1>
        <p class="mt-2 text-sm text-gray-700">Lista de usuarios del sistema</p>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <router-link
          v-if="hasPermission('create users')"
          to="/users/create"
          class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
        >
          Crear Usuario
        </router-link>
      </div>
    </div>

    <!-- Filtros -->
    <div class="mt-6 bg-white p-4 rounded-lg shadow">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Nombre</label>
          <input
            v-model="filters.name"
            type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            placeholder="Buscar por nombre"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input
            v-model="filters.email"
            type="email"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            placeholder="Buscar por email"
          />
        </div>
        <div class="flex items-end">
          <button
            @click="applyFilters"
            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
          >
            Filtrar
          </button>
        </div>
      </div>
    </div>

    <!-- Tabla -->
    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-md">
      <div v-if="isLoading" class="p-6 text-center">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
        <p class="mt-2 text-sm text-gray-600">Cargando usuarios...</p>
      </div>

      <div v-else-if="users.length === 0" class="p-6 text-center">
        <p class="text-gray-500">No se encontraron usuarios</p>
      </div>

      <ul v-else class="divide-y divide-gray-200">
        <li v-for="user in users" :key="user?.id || Math.random()" class="px-6 py-4">
          <div v-if="user" class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-10 w-10">
                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                  <span class="text-sm font-medium text-indigo-700">
                    {{ user.name?.charAt(0)?.toUpperCase() || '?' }}
                  </span>
                </div>
              </div>
              <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">{{ user.name || 'Sin nombre' }}</div>
                <div class="text-sm text-gray-500">{{ user.email || 'Sin email' }}</div>
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <span
                v-for="role in (user.roles || [])"
                :key="role?.id || Math.random()"
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
              >
                {{ formatRoleName(role?.name) }}
              </span>
              <div class="flex space-x-2">
                <router-link
                  v-if="hasPermission('edit users') && user.id"
                  :to="`/users/${user.id}/edit`"
                  class="text-indigo-600 hover:text-indigo-900 text-sm"
                >
                  Editar
                </router-link>
                <button
                  v-if="hasPermission('delete users') && user.id"
                  @click="confirmDelete(user)"
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
      <div class="text-sm text-gray-700">
        Mostrando {{ ((pagination.current_page - 1) * pagination.per_page) + 1 }} a 
        {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }} de 
        {{ pagination.total }} usuarios
      </div>
      <div class="flex space-x-2">
        <button
          :disabled="pagination.current_page === 1"
          @click="goToPage(pagination.current_page - 1)"
          class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-md disabled:opacity-50"
        >
          Anterior
        </button>
        <button
          :disabled="pagination.current_page === pagination.last_page"
          @click="goToPage(pagination.current_page + 1)"
          class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-md disabled:opacity-50"
        >
          Siguiente
        </button>
      </div>
    </div>

    <!-- Modal de confirmación -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar eliminación</h3>
        <p class="text-sm text-gray-500 mb-6">
          ¿Estas seguro de que quieres eliminar al usuario "{{ userToDelete?.name }}"? Esta accion no se puede deshacer.
        </p>
        <div class="flex justify-end space-x-3">
          <button
            @click="showDeleteModal = false"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
          >
            Cancelar
          </button>
          <button
            @click="deleteUserConfirmed"
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700"
          >
            Eliminar
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de error informativo -->
    <div v-if="showErrorModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center mb-4">
          <svg class="h-6 w-6 text-yellow-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
          </svg>
          <h3 class="text-lg font-medium text-gray-900">Usuario desactivado</h3>
        </div>
        <p class="text-sm text-gray-500 mb-6">
          Este usuario no puede ser eliminado, tiene facturas enlazadas.
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
import { useUsers } from '@/composables/useUsers';
import { useAuth } from '@/composables/useAuth';
import { formatRoleName } from '@/utils/roles';
import AppLayout from '@/components/layouts/AppLayout.vue';

export default {
  name: 'UserList',
  components: {
    AppLayout
  },
  setup() {
    const { users, isLoading, pagination, fetchUsers, deleteUser } = useUsers();
    const { hasPermission } = useAuth();
    
    const filters = reactive({
      name: '',
      email: ''
    });

    const showDeleteModal = ref(false);
    const showErrorModal = ref(false);
    const userToDelete = ref(null);

    const applyFilters = () => {
      fetchUsers({
        page: 1,
        ...filters
      });
    };

    const goToPage = (page) => {
      fetchUsers({
        page,
        ...filters
      });
    };

    const confirmDelete = (user) => {
      if (user && user.id) {
        userToDelete.value = user;
        showDeleteModal.value = true;
      }
    };

    const deleteUserConfirmed = async () => {
      if (userToDelete.value) {
        const result = await deleteUser(userToDelete.value.id);
        if (result.success) {
          showDeleteModal.value = false;
          userToDelete.value = null;
        } else {
          showDeleteModal.value = false;
          showErrorModal.value = true;
          fetchUsers();
        }
      }
    };

    onMounted(() => {
      fetchUsers();
    });

    return {
      users,
      isLoading,
      pagination,
      filters,
      showDeleteModal,
      showErrorModal,
      userToDelete,
      hasPermission,
      formatRoleName,
      applyFilters,
      goToPage,
      confirmDelete,
      deleteUserConfirmed
    };
  }
};
</script>