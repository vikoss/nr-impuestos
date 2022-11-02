<template>
  <div
    class="transition-opacity ease-in-out duration-300 fixed w-full h-full top-0 left-0 flex items-center justify-center"
    :class="`${show ? 'opacity-100 pointer-events-auto': 'opacity-0 pointer-events-none'}`"
  >
    <div class="absolute w-full h-full opacity-40" style="background-color: currentColor;" />
    <div class="bg-white w-11/12 mx-auto rounded z-50 overflow-y-auto" style="box-shadow: 0px 0px 6px #00000029;">
      <div class="py-4 px-6">
        <div class="text-right mb-2">
          <button class="text-right py-1 px-3" @click="close">
            x
          </button>
        </div>
        <slot />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    show: {
      type: Boolean,
      default: false
    },
  },
  emits: ['closed'],
  setup(ctx, { emit }) {
    const close = () => emit('closed')

    return { close }
  },
}
</script>
