<template>
  <button
    type="button"
    class="bg-wine text-white block h-11 w-full font-bold text-base rounded-md cursor-pointer
      disabled:cursor-not-allowed disabled:bg-gray-200 disabled:text-gray disabled:border-gray-200 border-wine border-solid border-3"
  >
    <svg
      v-show="loading"
      class="h-5 w-5 animate-spin mx-auto"
      :class="[classColorLoading]"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle
        class="opacity-25"
        cx="12"
        cy="12"
        r="10"
        stroke="currentColor"
        stroke-width="4"
      />
      <path
        class="opacity-75"
        fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
      />
    </svg>
    <span v-show="!loading">
      {{ label }}
    </span>
  </button>
</template>

<script>
export default {
  name: 'ButtonBase',
  props: {
    label: {
      type: String,
      default: '',
    },
    loading: {
      type: Boolean,
      default: false,
    },
    classColorLoading: {
      type: String,
      default: 'text-white',
    },
  },
}
</script>

<style scoped>
.border-3 {
  border-width: 3px;
}
</style>