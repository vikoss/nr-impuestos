import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { isAuthenticated } from './helpers/localstorage'


router.beforeEach((to, from, next) => {
  if (to.name === 'TaxValidityLegacy') return next()
  if (to.name === 'TaxValidity') return next()
  if (to.name !== 'Login' && !isAuthenticated()) next({ name: 'Login' })
  else next()
})

createApp(App)
  .use(router)
  .mount('#app')