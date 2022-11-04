<template>
  <header-base />
  <main class="px-6 sm:px-16 py-12">
    <redirect-to-back :route="app.goToHome" />
    <title-bar
      title="Impuestos"
      subtitle="Genera el codigo QR."
      style="margin-bottom: 1.2rem;"
    />
    <search-bar
      id="search-bar"
      v-model="app.expedienteNumber"
      placeholder="Buscar por número de expediente"
      class="rounded-md"
      @search="app.fetchTaxes"
    />
    <div v-show="!app.taxes.data.length">
      <div class="flex flex-col items-center mt-20">
        <folder-not-found-svg />
        <p class="text-lg sm:text-xl opacity-70 mt-6">
          No se encontraron resultados.
        </p>
      </div>
    </div>
    <div v-show="!!app.taxes.data.length && !app.loading">
      <table-base
        :headers="[
          { name: 'EXP', label: 'Expediente' },
          { name: 'CLAVE_Y_VALOR_CATASTRAL', label: 'Clave y Valor Catastral' },
          { name: 'NO_ADEUDO_PREDIAL', label: 'Predial' },
          { name: 'APORTACIONES_MEJORAS', label: 'Aportacion Mejoras' },
          { name: 'NOMBRE', label: 'Nombre' },
          { name: 'created_in_year', label: 'AÑO' },
        ]"
        :data="app.taxes.data"
        :action="app.goToTaxDetails"
      />
      <pagination-base
        :currentPage="app.taxes.current_page"
        :offset="6"
        :lastPage="app.taxes.last_page"
        @pageChanged="app.fetchTaxes"
        class="float-right mt-5"
      />
    </div>
    <loading v-show="app.loading" />
  </main>
</template>

<script>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { getTaxes } from './../../api/taxes'
import HeaderBase from './../../components/HeaderBase.vue'
import TableBase from './../../components/TableBase.vue'
import Loading from './../../components/LoadingBalls.vue'
import PaginationBase from './../../components/PaginationBase.vue'
import RedirectToBack from './../../components/RedirectToBack.vue'
import TitleBar from './../../components/TitleBar.vue'
import ButtonBase from './../../components/ButtonBase.vue'
import { mapTaxesData } from './../../helpers/mappers'
import SearchBar from '../../components/SearchBar.vue'
import FolderNotFoundSvg from './../../components/svg/FolderNotFound.vue'

export default {
  components: {
    HeaderBase,
    TableBase,
    Loading,
    PaginationBase,
    RedirectToBack,
    TitleBar,
    ButtonBase,
    SearchBar,
    FolderNotFoundSvg,
  },
  setup() {
    const router = useRouter()
    const app = reactive({
      taxes: {
        data: [],
        current_page: 1,
        last_page: 1,
      },
      expedienteNumber: '',
      loading: false,
      fetchTaxes: async (page = 1) => {
        app.loading = true
        app.taxes = await getTaxes({ expediente: app.expedienteNumber, page })
        app.taxes.data = mapTaxesData(app.taxes.data)
        app.loading = false
      },
      goToTaxDetails: (tax) => router.push({ name: 'TaxShow', params: { tax }}),
      goToHome: () => router.push({ name: 'Home' }),
    })

    app.fetchTaxes()


    return { app }
  },
}
</script>