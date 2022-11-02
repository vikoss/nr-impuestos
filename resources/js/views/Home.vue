<template>
  <header-base />
  <main class="px-6 sm:px-16 py-12">
    <div class="grid gap-3 grid-cols-1 sm:grid-cols-2">
      <div class="col-span-1">
        <card-base
          label="Generar codigo QR"
          description="Consulta de certificaciones y generacion de codigo QR."
          :redirect-to="{ name: 'TaxIndex' }"
        />
      </div>
      <div class="col-span-1">
        <card-base
          label="Importar certificaciones."
          description="Importar certificaciones desde un archivo excel."
          :redirect-to="{ name: 'TaxCreate' }"
        />
      </div>
    </div>
  </main>
</template>

<script>
import HeaderBase from './../components/HeaderBase.vue'
import CardBase from './../components/CardBase.vue'
export default {
  components: { HeaderBase, CardBase }
}
</script>