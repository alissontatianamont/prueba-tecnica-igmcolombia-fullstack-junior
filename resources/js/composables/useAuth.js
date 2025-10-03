import { computed } from 'vue';
import { useAuthStore } from '@/stores/auth';

export function useAuth() {
  const authStore = useAuthStore();

  const user = computed(() => authStore.currentUser);
  const isAuthenticated = computed(() => authStore.isAuthenticated);
  const isLoading = computed(() => authStore.isLoading);
  const errors = computed(() => authStore.errors);

  const login = (credentials) => authStore.login(credentials);
  const logout = () => authStore.logout();
  const clearErrors = () => authStore.clearErrors();

  const hasRole = (role) => authStore.hasRole(role);
  const hasPermission = (permission) => authStore.hasPermission(permission);

  return {
    user,
    isAuthenticated,
    isLoading,
    errors,
    login,
    logout,
    clearErrors,
    hasRole,
    hasPermission
  };
}