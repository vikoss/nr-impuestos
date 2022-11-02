<template>
  <modal-base :show="show" @closed="closed">
    <div class="py-12 pb-8">
      <div class="flex flex-col items-center">
        <success-svg />
        <h5 class="text-base mb-14 mt-12 text-center" :style="'max-width: 587px; min-width: ' + minWidth ">
          {{ message }}
        </h5>
      </div>
      <div class="flex justify-center">
        <button-base
          class="max-w-xs mr-2 text-wine border-solid border-wine"
          style="background-color: #ffffff;"
          label="No"
          @click="closed"
        />
        <button-base
          class="max-w-xs ml-2"
          label="Si"
          @click="action"
        />
      </div>
    </div>
  </modal-base>
</template>

<script>
import ModalBase from './ModalBase.vue'
import ButtonBase from './ButtonBase.vue'
import SuccessSvg from '../svg/Success.vue'
export default {
  components: {
    ModalBase,
    ButtonBase,
    SuccessSvg,
  },
  props: {
    show: {
      type: Boolean,
      default: false
    },
    message: {
      type: String,
      default: '',
    },
    closed: {
      type: Function,
      default: () => {},
    },
    minWidth: {
      type: String,
      default: 'auto',
    },
    action: {
      type: Function,
      default: () => ({}),
    },
  },
  emits: ['closed'],
  setup() {
    const close = () => this.$emit('closed')
    return { close }
  },
}
</script>