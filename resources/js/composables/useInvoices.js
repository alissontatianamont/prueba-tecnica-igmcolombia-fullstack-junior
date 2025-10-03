import { computed } from 'vue';
import { useInvoiceStore } from '@/stores/invoices';

export function useInvoices() {
  const invoiceStore = useInvoiceStore();

  const invoices = computed(() => (invoiceStore.invoices || []).filter(invoice => invoice && invoice.id));
  const currentInvoice = computed(() => invoiceStore.currentInvoice);
  const isLoading = computed(() => invoiceStore.isLoading);
  const errors = computed(() => invoiceStore.errors || {});
  const pagination = computed(() => invoiceStore.pagination || {
    current_page: 1,
    per_page: 10,
    total: 0,
    last_page: 1
  });

  const fetchInvoices = (params) => invoiceStore.fetchInvoices(params);
  const createInvoice = (invoiceData) => invoiceStore.createInvoice(invoiceData);
  const updateInvoice = (id, invoiceData) => invoiceStore.updateInvoice(id, invoiceData);
  const deleteInvoice = (id) => invoiceStore.deleteInvoice(id);
  const fetchInvoice = (id) => invoiceStore.fetchInvoice(id);
  const getInvoiceFileUrl = (id) => invoiceStore.getInvoiceFileUrl(id);
  const fetchActiveClients = () => invoiceStore.fetchActiveClients();
  const fetchAvailableProducts = () => invoiceStore.fetchAvailableProducts();
  const clearErrors = () => invoiceStore.clearErrors();
  const getInvoiceById = (id) => invoiceStore.getInvoiceById(id);

  return {
    invoices,
    currentInvoice,
    isLoading,
    errors,
    pagination,
    fetchInvoices,
    createInvoice,
    updateInvoice,
    deleteInvoice,
    fetchInvoice,
    getInvoiceFileUrl,
    fetchActiveClients,
    fetchAvailableProducts,
    clearErrors,
    getInvoiceById
  };
}