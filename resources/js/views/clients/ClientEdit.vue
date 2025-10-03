<template>
  <AppLayout>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Editar Cliente</h1>
        <p class="mt-2 text-sm text-gray-700">Modificar información del cliente</p>
      </div>
    </div>

    <div v-if="isLoading && !form.name" class="mt-6 text-center">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
      <p class="mt-2 text-sm text-gray-600">Cargando cliente...</p>
    </div>

    <div v-else class="mt-6 bg-white shadow sm:rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <form @submit.prevent="handleSubmit">
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
              <label class="block text-sm font-medium text-gray-700">Primer Nombre</label>
              <input
                v-model="form.cli_first_name"
                type="text"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.cli_first_name }"
              />
              <p v-if="errors.cli_first_name" class="mt-1 text-sm text-red-600">{{ errors.cli_first_name[0] }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Segundo Nombre</label>
              <input
                v-model="form.cli_middle_name"
                type="text"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.cli_middle_name }"
              />
              <p v-if="errors.cli_middle_name" class="mt-1 text-sm text-red-600">{{ errors.cli_middle_name[0] }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Primer Apellido</label>
              <input
                v-model="form.cli_last_name"
                type="text"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.cli_last_name }"
              />
              <p v-if="errors.cli_last_name" class="mt-1 text-sm text-red-600">{{ errors.cli_last_name[0] }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Segundo Apellido</label>
              <input
                v-model="form.cli_second_last_name"
                type="text"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.cli_second_last_name }"
              />
              <p v-if="errors.cli_second_last_name" class="mt-1 text-sm text-red-600">{{ errors.cli_second_last_name[0] }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input
                v-model="form.cli_email"
                type="email"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.cli_email }"
              />
              <p v-if="errors.cli_email" class="mt-1 text-sm text-red-600">{{ errors.cli_email[0] }}</p>
              <p v-if="form.cli_email === originalEmail" class="mt-1 text-xs text-gray-500">
                Email actual - Solo cambia si es necesario
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Teléfono</label>
              <input
                v-model="form.cli_phone"
                type="text"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.cli_phone }"
              />
              <p v-if="errors.cli_phone" class="mt-1 text-sm text-red-600">{{ errors.cli_phone[0] }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Tipo de Documento</label>
              <select
                v-model="form.cli_document_type"
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
                v-model="form.cli_document_number"
                type="text"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.cli_document_number }"
              />
              <p v-if="errors.cli_document_number" class="mt-1 text-sm text-red-600">{{ errors.cli_document_number[0] }}</p>
            </div>

            <div class="sm:col-span-2">
              <label class="block text-sm font-medium text-gray-700">Dirección</label>
              <input
                v-model="form.cli_address"
                type="text"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.cli_address }"
              />
              <p v-if="errors.cli_address" class="mt-1 text-sm text-red-600">{{ errors.cli_address[0] }}</p>
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
              to="/clients"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Cancelar
            </router-link>
            <button
              type="submit"
              :disabled="isLoading"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
            >
              {{ isLoading ? 'Actualizando...' : 'Actualizar Cliente' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { ref, reactive, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useClients } from '@/composables/useClients';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/components/layouts/AppLayout.vue';

export default {
  name: 'ClientEdit',
  components: {
    AppLayout
  },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const { updateClient, fetchClient, isLoading } = useClients();
    const { showError, showSuccess } = useToast();
    
    const form = reactive({
      cli_first_name: '',
      cli_middle_name: '',
      cli_last_name: '',
      cli_second_last_name: '',
      cli_document_type: '',
      cli_document_number: '',
      cli_email: '',
      cli_phone: '',
      cli_address: ''
    });

    const originalEmail = ref('');
    const errors = ref({});

    const loadClient = async () => {
      const result = await fetchClient(route.params.id);
      if (result.success) {
        const client = result.data;
        form.cli_first_name = client.cli_first_name || '';
        form.cli_middle_name = client.cli_middle_name || '';
        form.cli_last_name = client.cli_last_name || '';
        form.cli_second_last_name = client.cli_second_last_name || '';
        form.cli_document_type = client.cli_document_type || '';
        form.cli_document_number = client.cli_document_number || '';
        form.cli_email = client.cli_email || '';
        form.cli_phone = client.cli_phone || '';
        form.cli_address = client.cli_address || '';
        originalEmail.value = client.cli_email || '';
      }
    };

    const handleSubmit = async () => {
      errors.value = {};
      
      const updateData = {
        cli_first_name: form.cli_first_name,
        cli_middle_name: form.cli_middle_name,
        cli_last_name: form.cli_last_name,
        cli_second_last_name: form.cli_second_last_name,
        cli_document_type: form.cli_document_type,
        cli_document_number: form.cli_document_number,
        cli_phone: form.cli_phone,
        cli_address: form.cli_address
      };

      // Solo incluir el email si ha cambiado
      if (form.cli_email !== originalEmail.value) {
        updateData.cli_email = form.cli_email;
      }
      
      const result = await updateClient(route.params.id, updateData);
      
      if (result.success) {
        showSuccess('Cliente actualizado', 'Los datos del cliente se han actualizado exitosamente');
        router.push('/clients');
      } else {
        errors.value = result.errors;
        
        // Mostrar notificación específica para documento duplicado
        if (result.errors?.cli_document_number) {
          showError(
            'Error al actualizar cliente',
            result.errors.cli_document_number[0]
          );
        } else {
          showError(
            'Error al actualizar cliente',
            'Verifique los datos e intente nuevamente'
          );
        }
      }
    };

    onMounted(() => {
      loadClient();
    });

    return {
      form,
      errors,
      isLoading,
      originalEmail,
      handleSubmit
    };
  }
};
</script>