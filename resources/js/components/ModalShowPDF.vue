<template>
  <modal-base :show="show" @closed="closed">
    <div class="max-w-xl lg:max-w-4xl mx-auto">
      <div class="pt-full relative overflow-hidden w-full">
        <embed
          class="absolute top-0 left-0 bottom-0 right-0 w-full h-full"
          :src="url"
          type="application/pdf"
          height="100%"
          width="100%"
        >
      </div>
    </div>
  </modal-base>
</template>

<script>
import ModalBase from './ModalBase.vue'
import ButtonBase from './ButtonBase.vue'
export default {
  components: {
    ModalBase,
    ButtonBase,
  },
  props: {
    show: {
      type: Boolean,
      default: false
    },
    closed: {
      type: Function,
      default: () => {},
    },
    url: {
      type: String,
      default: '',
    },
  },
}
</script>

<style scoped>
.pt-full {
  padding-top: 100%;
}
</style>
