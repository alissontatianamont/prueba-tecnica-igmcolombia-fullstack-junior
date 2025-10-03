<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
              <h1 class="text-xl font-bold text-gray-900">
                Sistema de Facturación
              </h1>
            </div>
            
            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
              <router-link 
                to="/dashboard" 
                class="nav-link"
                :class="{ 'nav-link-active': $route.name === 'dashboard' }"
              >
                Dashboard
              </router-link>
              <router-link 
                v-if="authStore.hasPermission('view invoices')"
                to="/invoices" 
                class="nav-link"
                :class="{ 'nav-link-active': $route.name?.includes('invoice') }"
              >
                Facturas
              </router-link>
              <router-link 
                v-if="authStore.hasPermission('view clients')"
                to="/clients" 
                class="nav-link"
                :class="{ 'nav-link-active': $route.name?.includes('client') }"
              >
                Clientes
              </router-link>
              <router-link 
                v-if="authStore.hasPermission('view users')" 
                to="/users" 
                class="nav-link"
                :class="{ 'nav-link-active': $route.name?.includes('user') }"
              >
                Usuarios
              </router-link>
            </div>
          </div>
          
          <!-- User Menu -->
          <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
              <span class="text-sm text-gray-700">
                {{ authStore.currentUser?.name }}
              </span>
              <button 
                @click="logout"
                class="text-sm text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out"
              >
                Cerrar Sesión
              </button>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <slot />
      </div>
    </main>

    <!-- Toast Container -->
    <div class="fixed top-4 right-4 z-50 space-y-4">
      <Toast
        v-for="toast in toasts"
        :key="toast.id"
        :type="toast.type"
        :title="toast.title"
        :message="toast.message"
        :duration="toast.duration"
        :auto-close="toast.autoClose"
        @close="removeToast(toast.id)"
      />
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import { useToast } from '@/composables/useToast';
import Toast from '@/components/Toast.vue';

export default {
  name: 'AppLayout',
  components: {
    Toast
  },
  setup() {
    const authStore = useAuthStore();
    const router = useRouter();
    const { toasts, removeToast } = useToast();

    const logout = async () => {
      await authStore.logout();
      router.push('/login');
    };

    return {
      authStore,
      logout,
      toasts,
      removeToast
    };
  }
}
</script>

<style scoped>
.nav-link {
  @apply inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition duration-150 ease-in-out;
}

.nav-link-active {
  @apply border-indigo-500 text-gray-900;
}
</style>