<template>
  <svg id="delete_outline_black_24dp_2_" data-name="delete_outline_black_24dp (2)" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
    <path id="Trazado_11651" data-name="Trazado 11651" d="M0,0H24V24H0Z" fill="none"/>
    <path id="Trazado_11652" data-name="Trazado 11652" d="M6,19a2.006,2.006,0,0,0,2,2h8a2.006,2.006,0,0,0,2-2V7H6ZM8,9h8V19H8Zm7.5-5-1-1h-5l-1,1H5V6H19V4Z" :fill="hexColor"/>
  </svg>
</template>

<script>
export default {
  name: 'DeleteItemSVG',
  props: {
    hexColor: {
      type: String,
      default: '#54191b',
    },
  },
}
</script>
