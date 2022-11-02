<template>
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
    <defs>
    </defs>
    <g transform="translate(24 24) rotate(180)">
      <path style="fill:none;" d="M0,0H24V24H0Z"/>
      <path :style="`fill: ${hexColor}`" d="M22,12,18,8v3H3v2H18v3Z"/>
    </g>
  </svg>
</template>

<script>
export default {
  name: 'ArrowLeft',
  props: {
    hexColor: {
      type: String,
      default: '#54191b',
    },
  },
}
</script>