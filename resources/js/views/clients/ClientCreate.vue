<template>
  <AppLayout>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Crear Cliente</h1>
        <p class="mt-2 text-sm text-gray-700">Agregar un nuevo cliente al sistema</p>
      </div>
    </div>

    <div class="mt-6 bg-white shadow sm:rounded-lg">
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

            <div class="sm:col-span-2">
              <label class="block text-sm font-medium text-gray-700">Estado</label>
              <select
                v-model="form.cli_status"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.cli_status }"
              >
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
              </select>
              <p v-if="errors.cli_status" class="mt-1 text-sm text-red-600">{{ errors.cli_status[0] }}</p>
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
              {{ isLoading ? 'Creando...' : 'Crear Cliente' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useClients } from '@/composables/useClients';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/components/layouts/AppLayout.vue';

export default {
  name: 'ClientCreate',
  components: {
    AppLayout
  },
  setup() {
    const router = useRouter();
    const { createClient, isLoading } = useClients();
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
      cli_address: '',
      cli_status: '1'
    });

    const errors = ref({});

    const handleSubmit = async () => {
      errors.value = {};
      
      const result = await createClient(form);
      
      if (result.success) {
        showSuccess('Cliente creado', 'El cliente se ha creado exitosamente');
        router.push('/clients');
      } else {
        errors.value = result.errors;
        
        // Mostrar notificación específica para documento duplicado
        if (result.errors?.cli_document_number) {
          showError(
            'Error al crear cliente',
            result.errors.cli_document_number[0]
          );
        } else {
          showError(
            'Error al crear cliente',
            'Verifique los datos e intente nuevamente'
          );
        }
      }
    };

    return {
      form,
      errors,
      isLoading,
      handleSubmit
    };
  }
};
</script>