<template>
  <AppLayout>
    <div class="bg-white overflow-hidden shadow rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
          Bienvenido al Sistema de Facturación
        </h3>
        <p class="text-sm text-gray-600 mb-4">
          Hola <b>{{ authStore.currentUser?.name }}</b>, bienvenido a esta prueba técnica. Has iniciado sesión correctamente.
        </p>
        <div class="mt-4">
          <p class="text-sm text-gray-500">
            Desde aquí podrás acceder a todas las funcionalidades del sistema:
          </p>
          <ul class="mt-2 text-sm text-gray-500 list-disc list-inside">
            <li>Gestionar facturas</li>
            <li>Administrar clientes</li>
            <li v-if="authStore.hasPermission('view users')">  Gestionar usuarios</li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { useAuthStore } from '@/stores/auth';
import AppLayout from '@/components/layouts/AppLayout.vue';

export default {
  name: 'Dashboard',
  components: {
    AppLayout
  },
  setup() {
    const authStore = useAuthStore();

    return {
      authStore
    };
  }
}
</script>