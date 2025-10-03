import { computed } from 'vue';
import { useClientStore } from '@/stores/clients';

export function useClients() {
  const clientStore = useClientStore();

  const clients = computed(() => (clientStore.clients || []).filter(client => client && client.id));
  const currentClient = computed(() => clientStore.currentClient);
  const isLoading = computed(() => clientStore.isLoading);
  const errors = computed(() => clientStore.errors || {});
  const pagination = computed(() => clientStore.pagination || {
    current_page: 1,
    per_page: 10,
    total: 0,
    last_page: 1
  });

  const fetchClients = (params) => clientStore.fetchClients(params);
  const createClient = (clientData) => clientStore.createClient(clientData);
  const updateClient = (id, clientData) => clientStore.updateClient(id, clientData);
  const deleteClient = (id) => clientStore.deleteClient(id);
  const fetchClient = (id) => clientStore.fetchClient(id);
  const activateClient = (id) => clientStore.activateClient(id);
  const deactivateClient = (id) => clientStore.deactivateClient(id);
  const clearErrors = () => clientStore.clearErrors();
  const getClientById = (id) => clientStore.getClientById(id);

  return {
    clients,
    currentClient,
    isLoading,
    errors,
    pagination,
    fetchClients,
    createClient,
    updateClient,
    deleteClient,
    fetchClient,
    activateClient,
    deactivateClient,
    clearErrors,
    getClientById
  };
}