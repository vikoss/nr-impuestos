<template>
  <header-base />
  <main class="px-6 sm:px-16 py-12">
    <redirect-to-back :route="app.goToHome" />
    <title-bar
      title="Firma"
      subtitle="Firma digital de contrato"
    />

    <div class="max-w-4xl mx-auto" v-if="!pdfBase64">
      <div class="border-dashed border-2 border-wine pt-11 pb-6 px-5 sm:px-20">
        <button-base
          :disabled="app.loading"
          label="Agregar archivo"
          @click="app.loadFile"
        />
        <input type="file" name="file" id="inputFile" accept=".pdf" hidden @change="app.onChange">
        <p class="text-base text-center text-gray mt-5">
          Solo se admiten archivos de tipo
          <strong>
            PDF
          </strong>
        </p>
      </div>
      <div v-show="app.file" class="rounded-lg py-3 px-5 flex items-center gap-6 mt-6 border border-gray-100 shadow-lg">
        <div>
          <document-svg class="w-7 h-auto" />
        </div>
        <div class="w-full flex flex-col gap-1">
          <div>
            <p>{{ app.file?.name }}</p>
          </div>
          <div class="bg-gray-200 rounded">
            <div class="bg-wine h-2 rounded" :style="{ width: app.dataOnUploadProgress.percentage + '%' }"></div>
          </div>
          <div class="flex justify-between">
            <div class="hidden sm:block">
              <p class="text-gray text-xs sm:text-sm">
                {{ app.dataOnUploadProgress.loaded }} KB de {{ app.dataOnUploadProgress.total || app.file?.size }} KB
              </p>
            </div>
            <div class="text-gray">
              {{ app.dataOnUploadProgress.percentage }} % completado
            </div>
          </div>
        </div>
      </div>
    </div>

    <PdfViewer
      v-if="pdfBase64"
      id="signed-viewer"
      label="Contrato Firmado (Base64)"
      :src="pdfBase64"
      containerHeight="70vh"
      :initialScale="0.9"
      class="mt-6"
    />

    <button-base
      class="mt-7 max-w-xs mx-auto text-wine border-solid border-wine"
      style="background-color: #ffffff;"
      classColorLoading="text-wine"
      label="Firmar"
      :disabled="app.disabledButton"
      v-show="!app.disabledButton"
      :loading="app.loading"
      @click="app.uploadFileWithTaxes"
    />
    <modal-success
      :show="app.modal"
      :action="app.closeModalSuccess"
      :closed="app.closeModalSuccess"
      message="Firma exitosa"
    />
  </main>
</template>

<script>
import { computed, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import HeaderBase from '../../components/HeaderBase.vue'
import ButtonBase from '../../components/ButtonBase.vue'
import RedirectToBack from '../../components/RedirectToBack.vue'
import TitleBar from '../../components/TitleBar.vue'
import DocumentSvg from '../../components/svg/Document.vue'
import { signPdf } from './../../api/signPdf'
import ModalSuccess from './../../components/ModalSuccess.vue'
import PdfViewer from './../../components/PdfViewer.vue'

export default {
  components: {
    PdfViewer,
    ButtonBase,
    HeaderBase,
    RedirectToBack,
    TitleBar,
    DocumentSvg,
    ModalSuccess,
  },
  setup() {
    const router = useRouter()
    const route = useRoute()
    const pdfBase64String = ref(null)
    const pdfBase64 = computed(() => {
      return pdfBase64String.value
        ? `data:application/pdf;base64,${pdfBase64String.value}`
        : false
    })
    const app = reactive({
      dataOnUploadProgress: {
        loaded: 0,
        total: 0,
        percentage: 0,
      },
      file: null,
      modal: false,
      loading: false,
      disabledButton: computed(() => !app.file),
      goToHome: () => router.push({ name: 'Home' }),
      closeModalSuccess: () => {
        app.modal = false
      },
      onChange: () => {
        if (!app.inputFile().files?.[0]) return
        if (app.inputFile().files[0].type !== 'application/pdf') {
          app.inputFile().value = ''
          return
        }
        app.file = app.inputFile().files?.[0]
      },
      uploadFileWithTaxes: async () => {
        app.loading = true
        const data = await signPdf(app.file, app.onUploadProgress)
        pdfBase64String.value = data.pdf_base64
        app.loading = false
        //app.modal = true
      },
      onUploadProgress: (progressEvent) => {
        app.dataOnUploadProgress.total = progressEvent.total
        app.dataOnUploadProgress.loaded = progressEvent.loaded
        app.dataOnUploadProgress.percentage = Math.round((progressEvent.loaded * 100) / progressEvent.total)
      },
      inputFile: () => document.getElementById('inputFile'),
      loadFile: () => {
        app.inputFile().value = ''
        app.inputFile().click()
      },
    })

    return { app, pdfBase64 }
  },

}
</script>
