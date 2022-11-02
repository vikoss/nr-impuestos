<template>
  <header-base />
  <main class="px-6 sm:px-16 py-12">
    <redirect-to-back :route="app.goToTaxIndex" />
    <title-bar
      title="Genera QR"
      subtitle="Consulta la información del vehículo. De ser necesario puedes cargar documentación."
    />
    <div class="grid sm:grid-cols-2 gap-y-5 gap-x-8">
      <input-base
        id="expediente"
        v-model="app.tax.EXP"
        label="Número de expediente"
        disabled
      />
      <input-base
        id="name"
        v-model="app.tax.NOMBRE"
        label="Nombre"
        disabled
      />
      <input-base
        id="clave_y_valor_catastral"
        v-model="app.tax.CLAVE_Y_VALOR_CATASTRAL"
        label="Clave y valor catastral"
        disabled
      />
      <input-base
        id="no_adeudo_predial"
        v-model="app.tax.NO_ADEUDO_PREDIAL"
        label="No adeudo predial"
        :disabled="true"
      />
      <input-base
        id="aportacion_mejoras"
        v-model="app.tax.APORTACIONES_MEJORAS"
        label="Aportacion a mejoras"
        disabled
      />
      <input-base
        id="clave_cat"
        v-model="app.tax.CLAVE_CAT"
        label="Clave catastral"
        disabled
      />
      <input-base
        id="vigencia"
        v-model="app.tax.VIGENCIA"
        label="Vigencia"
        disabled
      />
      <input-base
        id="fecha_emision"
        v-model="app.tax.FECHA_EMISION"
        label="Fecha emision"
        disabled
      />
    </div>

    <div class="flex flex-col gap-3 mt-7 sm:mt-10 sm:px-6 sm:flex-row">
      <button-base
        @click="app.generateQrCodeTaxByType({ taxType: 'CV' })"
        label="Generar QR Clave y Valor Catastral"
        :disabled="app.tax.CLAVE_Y_VALOR_CATASTRAL === 'S/N'"
      />
      <button-base
        @click="app.generateQrCodeTaxByType({ taxType: 'PP' })"
        label="Generar QR Pago Predial"
        :disabled="app.tax.NO_ADEUDO_PREDIAL === 'S/N'"
      />
      <button-base
        @click="app.generateQrCodeTaxByType({ taxType: 'AM' })"
        label="Generar QR Aportaciones a mejoras"
        :disabled="app.tax.APORTACIONES_MEJORAS === 'S/N'"
      />
    </div>
    <modal-show-pdf
      :show="app.modals.showPDF"
      :url="app.urlQrCodePDF"
      :closed="() => (app.modals.showPDF = false)"
    />
  </main>
</template>

<script>
import { reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getTax, destroyTax } from '../../api/taxes'
import { generateQrCodePDF } from './../../api/templates'
import HeaderBase from '../../components/HeaderBase.vue'
import RedirectToBack from '../../components/RedirectToBack.vue'
import InputBase from '../../components/InputBase.vue'
import ButtonBase from '../../components/ButtonBase.vue'
import TitleBar from '../../components/TitleBar.vue'
import Loading from '../../components/LoadingBalls.vue'
import ModalShowPdf from './../../components/ModalShowPDF.vue'
import { validateTaxNumberMap } from './../../helpers/mappers'

export default {
  components: {
    HeaderBase,
    RedirectToBack,
    TitleBar,
    InputBase,
    ButtonBase,
    Loading,
    ModalShowPdf,
  },
  setup() {
    const router = useRouter()
    const route = useRoute()
    const app = reactive({
      tax: {},
      loading: false,
      urlQrCodePDF: '',
      modals: {
        showPDF: false,
        confirm: false,
      },
      fetchTax: async () => {
        app.loading = true
        const tax = await getTax(route.params.tax)
        app.tax = validateTaxNumberMap(tax)
        app.loading = false
      },
      generateQrCodeTaxByType: async ({ taxType }) => {
        app.loading = true
        const data = await generateQrCodePDF({ taxId: app.tax.id, taxType })
        const blob = new Blob([data], { type: 'application/pdf' })
        app.urlQrCodePDF = window.URL.createObjectURL(blob)
        app.loading = false
        app.modals.showPDF = true
      },
      goToTaxIndex: () => router.push({ name: 'TaxIndex' }),
    })

    app.fetchTax()

    return { app }
  },
}
</script>