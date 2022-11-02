<template>
  <div class="grid grid-cols-3 py-6 px-5 rounded-lg h-full" style="box-shadow: 0px 0px 15px #00000029;">
    <div class="col-span-2 flex items-center">
      <document-svg />
      <h5 class="ml-3">{{ label }}</h5>
    </div>
    <div>
      <router-link :to="redirectTo" class="border border-wine rounded-lg block text-center text-wine">
        Ir
      </router-link>
    </div>
    <div class="col-span-2 mt-4">
      <p>{{ description }}</p>
    </div>
  </div>
</template>

<script>
import DocumentSvg from './svg/Document.vue'
export default {
  name: 'CardBase',
  components: { DocumentSvg },
  props: {
    label: {
      type: String,
      default: '',
    },
    description: {
      type: String,
      default: '',
    },
    redirectTo: {
      type: Object,
      default: () => ({}),
    },
  },
}
</script>