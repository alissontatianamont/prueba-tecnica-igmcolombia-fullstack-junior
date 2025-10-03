<template>
  <AppLayout>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Crear Factura</h1>
        <p class="mt-2 text-sm text-gray-700">Crear una nueva factura para el cliente</p>
      </div>
    </div>

    <div class="mt-6 bg-white shadow sm:rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <form @submit.prevent="handleSubmit">
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <!-- Sección Cliente -->
            <div class="sm:col-span-2">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Cliente</h3>
              
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Cliente</label>
                  <select
                    v-model="form.inv_client_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': errors.inv_client_id }"
                    @change="clientChanged"
                  >
                    <option value="">Seleccionar cliente existente</option>
                    <option v-for="client in activeClients" :key="client.id" :value="client.id">
                      {{ getClientName(client) }} - {{ client.cli_email }}
                    </option>
                    <option value="new">+ Crear nuevo cliente</option>
                  </select>
                  <p v-if="errors.inv_client_id" class="mt-1 text-sm text-red-600">{{ errors.inv_client_id[0] }}</p>
                </div>

                <div v-if="showNewClientForm">
                  <button
                    type="button"
                    @click="toggleNewClientForm"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                  >
                    {{ showNewClientForm ? 'Cancelar' : 'Nuevo Cliente' }}
                  </button>
                </div>
              </div>
            </div>

            <!-- Formulario de nuevo cliente (condicional) -->
            <div v-if="showNewClientForm" class="sm:col-span-2 border-t pt-6">
              <h4 class="text-md font-medium text-gray-900 mb-4">Datos del Nuevo Cliente</h4>
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Primer Nombre</label>
                  <input
                    v-model="newClient.cli_first_name"
                    type="text"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': errors.cli_first_name }"
                  />
                  <p v-if="errors.cli_first_name" class="mt-1 text-sm text-red-600">{{ errors.cli_first_name[0] }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Primer Apellido</label>
                  <input
                    v-model="newClient.cli_last_name"
                    type="text"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': errors.cli_last_name }"
                  />
                  <p v-if="errors.cli_last_name" class="mt-1 text-sm text-red-600">{{ errors.cli_last_name[0] }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Email</label>
                  <input
                    v-model="newClient.cli_email"
                    type="email"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': errors.cli_email }"
                  />
                  <p v-if="errors.cli_email" class="mt-1 text-sm text-red-600">{{ errors.cli_email[0] }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Tipo de Documento</label>
                  <select
                    v-model="newClient.cli_document_type"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': errors.cli_document_type }"
                  >
                    <option value="">Seleccionar tipo</option>
                    <option value="CC">Cédula de Ciudadanía</option>
                    <option value="CE">Cédula de Extranjería</option>
                    <option value="TI">Tarjeta de Identidad</option>
                    <option value="PP">Pasaporte</option>
                    <option value="NIT">NIT</option>
                  </select>
                  <p v-if="errors.cli_document_type" class="mt-1 text-sm text-red-600">{{ errors.cli_document_type[0] }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Número de Documento</label>
                  <input
                    v-model="newClient.cli_document_number"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': errors.cli_document_number }"
                  />
                  <p v-if="errors.cli_document_number" class="mt-1 text-sm text-red-600">{{ errors.cli_document_number[0] }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                  <input
                    v-model="newClient.cli_phone"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': errors.cli_phone }"
                  />
                  <p v-if="errors.cli_phone" class="mt-1 text-sm text-red-600">{{ errors.cli_phone[0] }}</p>
                </div>
              </div>
            </div>

            <!-- Información de la factura -->
            <div class="sm:col-span-2 border-t pt-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Información de la Factura</h3>
              
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
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
                No hay productos agregados. Haz clic en "Agregar Producto" para comenzar.
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
              :disabled="isLoading || form.items.length === 0"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
            >
              {{ isLoading ? 'Creando...' : 'Crear Factura' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useInvoices } from '@/composables/useInvoices';
import { useClients } from '@/composables/useClients';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/components/layouts/AppLayout.vue';

export default {
  name: 'InvoiceCreate',
  components: {
    AppLayout
  },
  setup() {
    const router = useRouter();
    const { createInvoice, fetchActiveClients, fetchAvailableProducts, isLoading } = useInvoices();
    const { createClient } = useClients();
    const { showError, showSuccess } = useToast();
    
    const form = reactive({
      inv_client_id: '',
      inv_description: '',
      inv_notes: '',
      inv_issue_date: new Date().toISOString().split('T')[0],
      inv_due_date: '',
      inv_status: 'pending',
      inv_iva_percentage: 19,
      items: []
    });

    const newClient = reactive({
      cli_first_name: '',
      cli_last_name: '',
      cli_email: '',
      cli_document_type: '',
      cli_document_number: '',
      cli_phone: '',
      cli_status: 1
    });

    const activeClients = ref([]);
    const availableProducts = ref([]);
    const showNewClientForm = ref(false);
    const errors = ref({});

    const subtotal = computed(() => {
      return form.items.reduce((sum, item) => sum + (item.total || 0), 0);
    });

    const ivaAmount = computed(() => {
      return subtotal.value * (form.inv_iva_percentage / 100);
    });

    const total = computed(() => {
      return subtotal.value + ivaAmount.value;
    });

    const loadActiveClients = async () => {
      const result = await fetchActiveClients();
      if (result.success) {
        activeClients.value = result.data;
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

    const clientChanged = () => {
      if (form.inv_client_id === 'new') {
        showNewClientForm.value = true;
        form.inv_client_id = '';
      } else {
        showNewClientForm.value = false;
      }
    };

    const toggleNewClientForm = () => {
      showNewClientForm.value = !showNewClientForm.value;
      if (!showNewClientForm.value) {
        Object.keys(newClient).forEach(key => {
          newClient[key] = key === 'cli_status' ? 1 : '';
        });
      }
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

    const handleSubmit = async () => {
      errors.value = {};
      
      // Crear cliente nuevo si es necesario
      let clientId = form.inv_client_id;
      if (showNewClientForm.value && newClient.cli_first_name) {
        const clientResult = await createClient(newClient);
        if (clientResult.success) {
          clientId = clientResult.data.id;
        } else {
          // Mostrar errores específicos del cliente
          errors.value = {
            ...clientResult.errors,
            general: ['Error al crear el cliente. Verifique los datos e intente nuevamente.']
          };
          
          // Mostrar notificación de error para documento duplicado
          if (clientResult.errors?.cli_document_number) {
            showError(
              'Error al crear cliente',
              clientResult.errors.cli_document_number[0]
            );
          } else {
            showError(
              'Error al crear cliente',
              'Verifique los datos e intente nuevamente'
            );
          }
          return;
        }
      }

      const invoiceData = {
        inv_client_id: clientId,
        inv_description: form.inv_description,
        inv_notes: form.inv_notes,
        inv_issue_date: form.inv_issue_date,
        inv_due_date: form.inv_due_date,
        inv_status: form.inv_status,
        inv_iva_percentage: form.inv_iva_percentage,
        inv_total_amount: total.value,
        items: form.items.filter(item => item.ii_product_id && item.ii_quantity > 0)
      };
      
      const result = await createInvoice(invoiceData);
      
      if (result.success) {
        router.push('/invoices');
      } else {
        errors.value = result.errors;
      }
    };

    onMounted(() => {
      loadActiveClients();
      loadAvailableProducts();
      
      // Establecer fecha de vencimiento por defecto (30 días)
      const dueDate = new Date();
      dueDate.setDate(dueDate.getDate() + 30);
      form.inv_due_date = dueDate.toISOString().split('T')[0];
    });

    return {
      form,
      newClient,
      activeClients,
      availableProducts,
      showNewClientForm,
      errors,
      isLoading,
      subtotal,
      ivaAmount,
      total,
      getClientName,
      formatCurrency,
      clientChanged,
      toggleNewClientForm,
      addProduct,
      removeProduct,
      productChanged,
      calculateItemTotal,
      getMaxQuantity,
      handleSubmit
    };
  }
};
</script>