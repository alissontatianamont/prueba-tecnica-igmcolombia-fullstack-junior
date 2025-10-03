import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

import Login from '@/views/auth/Login.vue';
import Dashboard from '@/views/Dashboard.vue';
import InvoiceList from '@/views/invoices/InvoiceList.vue';
import InvoiceCreate from '@/views/invoices/InvoiceCreate.vue';
import InvoiceEdit from '@/views/invoices/InvoiceEdit.vue';
import InvoiceDetail from '@/views/invoices/InvoiceDetail.vue';
import ClientList from '@/views/clients/ClientList.vue';
import ClientCreate from '@/views/clients/ClientCreate.vue';
import ClientEdit from '@/views/clients/ClientEdit.vue';
import UserList from '@/views/users/UserList.vue';
import UserCreate from '@/views/users/UserCreate.vue';
import UserEdit from '@/views/users/UserEdit.vue';

const routes = [
  {
    path: '/',
    redirect: '/dashboard'
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { guest: true }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/invoices',
    name: 'invoices',
    component: InvoiceList,
    meta: { requiresAuth: true, permission: 'view invoices' }
  },
  {
    path: '/invoices/create',
    name: 'invoice-create',
    component: InvoiceCreate,
    meta: { requiresAuth: true, permission: 'create invoices' }
  },
  {
    path: '/invoices/:id',
    name: 'invoice-detail',
    component: InvoiceDetail,
    meta: { requiresAuth: true, permission: 'view invoices' }
  },
  {
    path: '/invoices/:id/edit',
    name: 'invoice-edit',
    component: InvoiceEdit,
    meta: { requiresAuth: true, permission: 'edit invoices' }
  },
  {
    path: '/clients',
    name: 'clients',
    component: ClientList,
    meta: { requiresAuth: true, permission: 'view clients' }
  },
  {
    path: '/clients/create',
    name: 'client-create',
    component: ClientCreate,
    meta: { requiresAuth: true, permission: 'create clients' }
  },
  {
    path: '/clients/:id/edit',
    name: 'client-edit',
    component: ClientEdit,
    meta: { requiresAuth: true, permission: 'edit clients' }
  },
  {
    path: '/users',
    name: 'users',
    component: UserList,
    meta: { requiresAuth: true, permission: 'view users' }
  },
  {
    path: '/users/create',
    name: 'user-create',
    component: UserCreate,
    meta: { requiresAuth: true, permission: 'create users' }
  },
  {
    path: '/users/:id/edit',
    name: 'user-edit',
    component: UserEdit,
    meta: { requiresAuth: true, permission: 'edit users' }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login');
  } else if (to.meta.guest && authStore.isAuthenticated) {
    next('/dashboard');
  } else if (to.meta.permission && !authStore.hasPermission(to.meta.permission)) {
    next('/dashboard');
  } else {
    next();
  }
});

export default router;