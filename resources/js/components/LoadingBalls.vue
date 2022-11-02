<template>
  <div>
    <div
      class="transition-opacity ease-in-out duration-300 pointer-events-auto
        fixed w-full h-full top-0 left-0
        flex items-center justify-center"
    >
      <div class="absolute w-full h-full opacity-30" style="background-color: currentColor;" />
      <div class="z-50">
        <div class="w-60 h-40 flex flex-wrap items-end justify-between">
          <div class="bg-wine ball"></div>
          <div class="bg-wine ball"></div>
          <div class="bg-wine ball"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
}
</script>

<style scoped>
.ball {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  animation: bounce .5s alternate infinite;
}
.ball:nth-child(2) {
  animation-delay: .16s;
}
.ball:nth-child(3) {
  animation-delay: .32s;
}
@keyframes bounce {
  from {
    transform: scaleX(1.25);
  }
  to {
    transform: translateY(-100px) scale(1);
  }
}
</style>