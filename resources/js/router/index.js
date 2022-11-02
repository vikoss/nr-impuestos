import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import ('./../views/Home.vue'),
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import ('./../views/Login.vue'),
  },
  {
    path: '/certificaciones',
    name: 'TaxIndex',
    component: () => import ('./../views/Taxes/Index.vue'),
  },
  {
    path: '/certificaciones/:tax',
    name: 'TaxShow',
    component: () => import ('./../views/Taxes/Show.vue'),
  },
  {
    path: '/importar-excel',
    name: 'TaxCreate',
    component: () => import ('./../views/Taxes/Create.vue'),
  },
  /*
    Rutas publicas para visualizar certificacion
  */
  // Ruta legacy
  {
    path: '/ver/:uuid/:type',
    name: 'TaxValidityLegacy',
    component: () => import ('./../views/Taxes/Customer/Validity.vue'),
  },
  // Ruta nueva
  {
    path: '/:type/:uuid',
    name: 'TaxValidity',
    component: () => import ('./../views/Taxes/Customer/Validity.vue'),
  },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router