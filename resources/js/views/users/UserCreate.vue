<template>
  <AppLayout>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Crear Usuario</h1>
        <p class="mt-2 text-sm text-gray-700">Agregar un nuevo usuario al sistema</p>
      </div>
    </div>

    <div class="mt-6 bg-white shadow sm:rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <form @submit.prevent="handleSubmit">
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
              <label class="block text-sm font-medium text-gray-700">Nombre</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.name }"
              />
              <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input
                v-model="form.email"
                type="email"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.email }"
              />
              <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Contraseña</label>
              <input
                v-model="form.password"
                type="password"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.password }"
              />
              <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password[0] }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
              <input
                v-model="form.password_confirmation"
                type="password"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div class="sm:col-span-2">
              <label class="block text-sm font-medium text-gray-700">Rol</label>
              <select
                v-model="form.role"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.role }"
              >
                <option value="">Seleccionar rol</option>
                <option value="admin">Administrador</option>
                <option value="salesman">Vendedor</option>
              </select>
              <p v-if="errors.role" class="mt-1 text-sm text-red-600">{{ errors.role[0] }}</p>
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
              to="/users"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Cancelar
            </router-link>
            <button
              type="submit"
              :disabled="isLoading"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
            >
              {{ isLoading ? 'Creando...' : 'Crear Usuario' }}
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
import { useUsers } from '@/composables/useUsers';
import AppLayout from '@/components/layouts/AppLayout.vue';

export default {
  name: 'UserCreate',
  components: {
    AppLayout
  },
  setup() {
    const router = useRouter();
    const { createUser, isLoading } = useUsers();
    
    const form = reactive({
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      role: ''
    });

    const errors = ref({});

    const handleSubmit = async () => {
      errors.value = {};
      
      const result = await createUser(form);
      
      if (result.success) {
        router.push('/users');
      } else {
        errors.value = result.errors;
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