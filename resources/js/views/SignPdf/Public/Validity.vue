<template>
  <header-customer />
  <main class="px-6 sm:px-16 py-12">
    <title-bar
      :title="`Tu Empresa ${app.date.getFullYear()}`"
      subtitle="Validez de la Firma"
    />
    <div class="grid sm:grid-cols-2 gap-y-5 gap-x-8">
      <input-base
        id="provider"
        v-model="app.signature.provider.name"
        label="Proveedor"
        disabled
      />
      <input-base
        id="legal_representative"
        v-model="app.signature.provider.legal_representative"
        label="Representante Legal"
        disabled
      />
      <input-base
        id="folio"
        v-model="app.signature.provider.folio"
        label="Folio"
        disabled
      />
      <input-base
        id="rfc"
        v-model="app.signature.provider.rfc"
        label="RFC"
        disabled
      />
      <input-base
        id="date"
        :value="app.currentDateFormat()"
        label="Fecha"
        disabled
      />
      <input-base
        id="contract_number"
        v-model="app.signature.provider.contract_number"
        label="Número de Contrato"
        disabled
      />
    </div>
  </main>
</template>

<script>
import HeaderCustomer from './../../../components/HeaderCustomer.vue'
import TitleBar from './../../../components/TitleBar.vue'
import InputBase from './../../../components/InputBase.vue'
import { getPdfSignatureByUuid } from './../../../api/signPdf'
import { reactive } from 'vue'
import { useRoute } from 'vue-router'


export default {
  name: 'Validity',
  components: { HeaderCustomer, TitleBar, InputBase },
  setup() {
    const app = reactive({
      loading: false,
      signature: {
        provider: {
          name: '',
          legal_representative: '',
          folio: '',
          rfc: '',
          date: '',
          contract_number: '',
        },
      },
      route: useRoute(),
      date: new Date(),
      getSignature: async () => {
        app.loading = true
        app.signature = await getPdfSignatureByUuid(app.route.params.uuid)
        app.loading = false
      },
      currentDateFormat: () => {
        const options = { year: 'numeric', month: 'long', day: 'numeric' }
        return app.date.toLocaleDateString('es-MX', options)
      },
    })

    app.getSignature()

    return { app }
  },

}
</script>
