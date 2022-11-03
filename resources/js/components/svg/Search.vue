<template>
  <svg xmlns="http://www.w3.org/2000/svg" width="26.222" height="26.222" viewBox="0 0 26.222 26.222">
    <defs>
    </defs>
    <path :fill="hexColor" d="M9.74,0a9.752,9.752,0,0,1,9.74,9.74,9.951,9.951,0,0,1-2.31,6.368l.375.375H18.73l7.492,7.492-2.248,2.248L16.482,18.73V17.544l-.375-.375A9.791,9.791,0,0,1,0,9.74,9.752,9.752,0,0,1,9.74,0m0,3A6.743,6.743,0,1,0,16.482,9.74,6.715,6.715,0,0,0,9.74,3Z"/>
  </svg>
</template>

<script>
export default {
  name: 'DocumentSVG',
  props: {
    hexColor: {
      type: String,
      default: '#54191b',
    },
  },
}
</script>
