import { computed } from 'vue';
import { useUserStore } from '@/stores/users';

export function useUsers() {
  const userStore = useUserStore();

  const users = computed(() => (userStore.users || []).filter(user => user && user.id));
  const currentUser = computed(() => userStore.currentUser);
  const isLoading = computed(() => userStore.isLoading);
  const errors = computed(() => userStore.errors || {});
  const pagination = computed(() => userStore.pagination || {
    current_page: 1,
    per_page: 10,
    total: 0,
    last_page: 1
  });

  const fetchUsers = (params) => userStore.fetchUsers(params);
  const createUser = (userData) => userStore.createUser(userData);
  const updateUser = (id, userData) => userStore.updateUser(id, userData);
  const deleteUser = (id) => userStore.deleteUser(id);
  const fetchUser = (id) => userStore.fetchUser(id);
  const clearErrors = () => userStore.clearErrors();
  const getUserById = (id) => userStore.getUserById(id);

  return {
    users,
    currentUser,
    isLoading,
    errors,
    pagination,
    fetchUsers,
    createUser,
    updateUser,
    deleteUser,
    fetchUser,
    clearErrors,
    getUserById
  };
}