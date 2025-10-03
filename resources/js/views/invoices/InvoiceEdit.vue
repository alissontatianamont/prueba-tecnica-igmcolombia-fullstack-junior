<template>
  <AppLayout>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Editar Factura</h1>
        <p class="mt-2 text-sm text-gray-700">Modificar información de la factura</p>
      </div>
    </div>

    <div v-if="isLoading && !form.inv_description" class="mt-6 text-center">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
      <p class="mt-2 text-sm text-gray-600">Cargando factura...</p>
    </div>

    <div v-else class="mt-6 bg-white shadow sm:rounded-lg">
      <div v-if="!isInvoiceEditable" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm text-yellow-700">
              <strong>Advertencia:</strong> Esta factura no puede ser editada porque su fecha de vencimiento ya ha pasado.
            </p>
          </div>
        </div>
      </div>
      
      <div class="px-4 py-5 sm:p-6">
        <form @submit.prevent="handleSubmit">
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Cliente</h3>
              
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Cliente</label>
                  <input
                    :value="currentClient ? `${getClientName(currentClient)} - ${currentClient.cli_email}` : 'Cliente no encontrado'"
                    type="text"
                    readonly
                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm"
                  />
                  <p class="mt-1 text-xs text-gray-500">El cliente no se puede modificar al editar una factura existente</p>
                </div>
              </div>
            </div>

            <!-- Información de la factura -->
            <div class="sm:col-span-2 border-t pt-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Información de la Factura</h3>
              
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Número de Factura</label>
                  <input
                    :value="currentInvoice?.inv_number || 'Generado automáticamente'"
                    type="text"
                    readonly
                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Fecha de Emisión</label>
                  <input
                    v-model="form.inv_issue_date"
                    type="date"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': errors.inv_issue_date }"
                  />
                  <p v-if="errors.inv_issue_date" class="mt-1 text-sm text-red-600">{{ errors.inv_issue_date[0] }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Fecha de Vencimiento</label>
                  <input
                    v-model="form.inv_due_date"
                    type="date"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': errors.inv_due_date }"
                  />
                  <p v-if="errors.inv_due_date" class="mt-1 text-sm text-red-600">{{ errors.inv_due_date[0] }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Estado</label>
                  <select
                    v-model="form.inv_status"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  >
                    <option value="pending">Pendiente</option>
                    <option value="paid">Pagada</option>
                    <option value="overdue">Vencida</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">IVA (%)</label>
                  <input
                    v-model.number="form.inv_iva_percentage"
                    type="number"
                    step="0.01"
                    min="0"
                    max="100"
                    @input="recalculateTotal"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>

                <div class="sm:col-span-3">
                  <label class="block text-sm font-medium text-gray-700">Descripción</label>
                  <textarea
                    v-model="form.inv_description"
                    rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Descripción de la factura"
                  ></textarea>
                </div>

                <div class="sm:col-span-3">
                  <label class="block text-sm font-medium text-gray-700">Notas</label>
                  <textarea
                    v-model="form.inv_notes"
                    rows="2"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Notas adicionales"
                  ></textarea>
                </div>
              </div>
            </div>

            <!-- Productos -->
            <div class="sm:col-span-2 border-t pt-6">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Productos</h3>
                <button
                  type="button"
                  @click="addProduct"
                  class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                >
                  + Agregar Producto
                </button>
              </div>

              <div v-if="form.items.length === 0" class="text-center py-4 text-gray-500">
                No hay productos agregados.
              </div>

              <div v-else class="space-y-4">
                <div
                  v-for="(item, index) in form.items"
                  :key="index"
                  class="grid grid-cols-1 gap-4 sm:grid-cols-6 p-4 border rounded-lg"
                >
                  <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Producto</label>
                    <select
                      v-model="item.ii_product_id"
                      @change="productChanged(index)"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      required
                    >
                      <option value="">Seleccionar producto</option>
                      <option v-for="product in availableProducts" :key="product.id" :value="product.id">
                        {{ product.pro_name }} - Stock: {{ product.pro_stock }} - ${{ formatCurrency(product.pro_unique_price) }}
                      </option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                    <input
                      v-model.number="item.ii_quantity"
                      type="number"
                      min="1"
                      :max="getMaxQuantity(item.ii_product_id)"
                      @input="calculateItemTotal(index)"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      required
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Precio Unit.</label>
                    <input
                      v-model.number="item.ii_unit_price"
                      type="number"
                      step="0.01"
                      @input="calculateItemTotal(index)"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      required
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Total</label>
                    <input
                      :value="formatCurrency(item.total || 0)"
                      type="text"
                      readonly
                      class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm"
                    />
                  </div>

                  <div class="flex items-end">
                    <button
                      type="button"
                      @click="removeProduct(index)"
                      class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200"
                    >
                      Eliminar
                    </button>
                  </div>
                </div>
              </div>

              <!-- Totales -->
              <div v-if="form.items.length > 0" class="mt-6 border-t pt-4">
                <div class="flex justify-end">
                  <div class="w-64 space-y-2">
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Subtotal:</span>
                      <span class="text-sm font-medium">${{ formatCurrency(subtotal) }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">IVA ({{ form.inv_iva_percentage }}%):</span>
                      <span class="text-sm font-medium">${{ formatCurrency(ivaAmount) }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                      <span class="text-base font-medium">Total:</span>
                      <span class="text-base font-bold">${{ formatCurrency(total) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-if="errors.general" class="mt-6 rounded-md bg-red-50 p-4">
            <div class="text-sm text-red-700">
              <ul class="list-disc pl-5 space-y-1">
                <li v-for="error in errors.general" :key="error">{{ error }}</li>
              </ul>
            </div>
          </div>

          <div class="mt-6 flex justify-end space-x-3">
            <router-link
              to="/invoices"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Cancelar
            </router-link>
            <button
              type="submit"
              :disabled="isLoading || !isInvoiceEditable"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white disabled:opacity-50"
              :class="isInvoiceEditable ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-400 cursor-not-allowed'"
            >
              {{ isLoading ? 'Actualizando...' : (isInvoiceEditable ? 'Actualizar Factura' : 'No Editable') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useInvoices } from '@/composables/useInvoices';
import AppLayout from '@/components/layouts/AppLayout.vue';

export default {
  name: 'InvoiceEdit',
  components: {
    AppLayout
  },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const { updateInvoice, fetchInvoice, fetchAvailableProducts, isLoading, currentInvoice } = useInvoices();
    
    const form = reactive({
      inv_client_id: '',
      inv_description: '',
      inv_notes: '',
      inv_issue_date: '',
      inv_due_date: '',
      inv_status: 'pending',
      inv_iva_percentage: 19,
      items: []
    });

    const availableProducts = ref([]);
    const errors = ref({});
    const isInvoiceEditable = ref(true);
    const currentClient = ref(null);

    const subtotal = computed(() => {
      return form.items.reduce((sum, item) => sum + (item.total || 0), 0);
    });

    const ivaAmount = computed(() => {
      return subtotal.value * (form.inv_iva_percentage / 100);
    });

    const total = computed(() => {
      return subtotal.value + ivaAmount.value;
    });

    // Función para formatear fechas para inputs tipo date
    const formatDateForInput = (dateString) => {
      if (!dateString) return '';
      
      // Crear objeto Date y obtener formato YYYY-MM-DD
      const date = new Date(dateString);
      if (isNaN(date.getTime())) return '';
      
      // Obtener año, mes y día (ajustando por zona horaria local)
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      
      return `${year}-${month}-${day}`;
    };

    const loadInvoice = async () => {
      const result = await fetchInvoice(route.params.id);
      if (result.success) {
        const invoice = result.data;
        
        // Establecer si la factura es editable
        isInvoiceEditable.value = invoice.is_editable !== false;
        
        // Cargar información del cliente
        currentClient.value = invoice.client || null;
        
        form.inv_client_id = invoice.inv_client_id || '';
        form.inv_description = invoice.inv_description || '';
        form.inv_notes = invoice.inv_notes || '';
        // Formatear fechas para inputs de tipo date (YYYY-MM-DD)
        form.inv_issue_date = invoice.inv_issue_date ? formatDateForInput(invoice.inv_issue_date) : '';
        form.inv_due_date = invoice.inv_due_date ? formatDateForInput(invoice.inv_due_date) : '';
        
        form.inv_status = invoice.inv_status || 'pending';
        form.inv_iva_percentage = parseFloat(invoice.inv_iva_percentage) || 19;
        form.items = invoice.invoice_items?.map(item => ({
          id: item.id,
          ii_product_id: item.ii_product_id,
          ii_quantity: parseInt(item.ii_quantity),
          ii_unit_price: parseFloat(item.ii_unit_price),
          total: parseFloat(item.ii_quantity) * parseFloat(item.ii_unit_price)
        })) || [];
      }
    };

    const loadAvailableProducts = async () => {
      const result = await fetchAvailableProducts();
      if (result.success) {
        availableProducts.value = result.data;
      }
    };

    const getClientName = (client) => {
      return `${client.cli_first_name || ''} ${client.cli_middle_name || ''} ${client.cli_last_name || ''} ${client.cli_second_last_name || ''}`.trim();
    };

    const formatCurrency = (amount) => {
      if (!amount) return '0.00';
      return parseFloat(amount).toLocaleString('es-CO', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    };

    const addProduct = () => {
      form.items.push({
        ii_product_id: '',
        ii_quantity: 1,
        ii_unit_price: 0,
        total: 0
      });
    };

    const removeProduct = (index) => {
      form.items.splice(index, 1);
    };

    const productChanged = (index) => {
      const product = availableProducts.value.find(p => p.id === form.items[index].ii_product_id);
      if (product) {
        form.items[index].ii_unit_price = parseFloat(product.pro_unique_price);
        calculateItemTotal(index);
      }
    };

    const calculateItemTotal = (index) => {
      const item = form.items[index];
      item.total = (item.ii_quantity || 0) * (item.ii_unit_price || 0);
    };

    const getMaxQuantity = (productId) => {
      const product = availableProducts.value.find(p => p.id === productId);
      return product ? product.pro_stock : 0;
    };

    const recalculateTotal = () => {
      // El total se recalcula automáticamente por computed
    };

    const handleSubmit = async () => {
      errors.value = {};
      
      // Verificar si la factura es editable antes de enviar
      if (!isInvoiceEditable.value) {
        errors.value = { 
          general: ['Esta factura no puede ser editada porque su fecha de vencimiento ya ha pasado'] 
        };
        return;
      }
      
      const updateData = {
        inv_client_id: form.inv_client_id,
        inv_description: form.inv_description,
        inv_notes: form.inv_notes,
        inv_issue_date: form.inv_issue_date,
        inv_due_date: form.inv_due_date,
        inv_status: form.inv_status,
        inv_iva_percentage: form.inv_iva_percentage,
        inv_total_amount: total.value,
        items: form.items.filter(item => item.ii_product_id && item.ii_quantity > 0)
      };
      
      const result = await updateInvoice(route.params.id, updateData);
      
      if (result.success) {
        router.push('/invoices');
      } else {
        errors.value = result.errors;
      }
    };

    onMounted(() => {
      loadInvoice();
      loadAvailableProducts();
    });

    return {
      form,
      availableProducts,
      errors,
      isLoading,
      currentInvoice,
      isInvoiceEditable,
      currentClient,
      subtotal,
      ivaAmount,
      total,
      getClientName,
      formatCurrency,
      addProduct,
      removeProduct,
      productChanged,
      calculateItemTotal,
      getMaxQuantity,
      recalculateTotal,
      handleSubmit
    };
  }
};
</script>