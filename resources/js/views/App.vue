<template>
  <div id="app" class="min-h-screen bg-gray-100">
    <router-view />
  </div>
</template>

<script>
import { useAuthStore } from '@/stores/auth';

export default {
  name: 'App',
  async created() {
    const authStore = useAuthStore();
    if (authStore.token) {
      await authStore.fetchUser();
    }
  }
}
</script>

<style>
/* Estilos globales adicionales si es necesario */
</style>