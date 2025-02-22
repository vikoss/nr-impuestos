<template>
  <header-customer />
  <main class="px-6 sm:px-16 py-12">
    <title-bar
      :title="`Tu Empresa ${app.date.getFullYear()}`"
      subtitle="Certificaciones vigentes"
    />
    <div class="grid sm:grid-cols-2 gap-y-5 gap-x-8">
      <input-base
        id="name"
        v-model="app.tax.NOMBRE"
        label="Nombre"
        disabled
      />
      <input-base
        id="certificate"
        v-model="app.tax.CERTIFICACION"
        label="Certificado"
        disabled
      />
      <input-base
        id="folio"
        v-model="app.tax.FOLIO"
        label="Folio"
        disabled
      />
      <input-base
        id="Vigencia"
        v-model="app.tax.VIGENCIA"
        label="Vigencia (Bimestre)"
        disabled
      />
    </div>
  </main>
</template>

<script>
import HeaderCustomer from './../../../components/HeaderCustomer.vue'
import TitleBar from './../../../components/TitleBar.vue'
import InputBase from './../../../components/InputBase.vue'
import { getTaxValidity } from './../../../api/taxes'
import { reactive } from 'vue'
import { useRoute } from 'vue-router'


export default {
  name: 'Validity',
  components: { HeaderCustomer, TitleBar, InputBase },
  setup() {
    const app = reactive({
      loading: false,
      tax: {},
      route: useRoute(),
      date: new Date(),
      getTax: async () => {
        app.loading = true
        app.tax = await getTaxValidity({ uuid: app.route.params.uuid, type: app.route.params.type })
        app.loading = false
      },
    })

    app.getTax()

    return { app }
  },

}
</script>

<style>

</style>