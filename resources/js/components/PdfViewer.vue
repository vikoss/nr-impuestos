<template>
  <div class="flex flex-col w-full h-full text-[14px]">
    <div
      v-if="showToolbar && !error"
      class="flex flex-wrap gap-2 p-2 bg-gray-100 border-b border-gray-300 sticky top-0 z-20"
    >
      <button class="px-3 py-1 bg-wine text-white rounded disabled:opacity-40" @click="prevPage" :disabled="currentPage <= 1">←</button>
      <button class="px-3 py-1 bg-wine text-white rounded disabled:opacity-40" @click="nextPage" :disabled="currentPage >= numPages">→</button>
      <span class="px-2 py-1 font-semibold">Página {{ currentPage }} / {{ numPages }}</span>
      <button class="px-3 py-1 bg-wine text-white rounded disabled:opacity-40" @click="zoomOut" :disabled="scale <= minScale">-</button>
      <button class="px-3 py-1 bg-wine text-white rounded disabled:opacity-40" @click="zoomIn" :disabled="scale >= maxScale">+</button>
      <button class="px-3 py-1 bg-wine/80 text-white rounded" @click="resetZoom">100%</button>
      <button class="px-3 py-1 bg-wine/80 text-white rounded" @click="fitWidth">Ajustar ancho</button>
      <button v-if="downloadable" class="px-3 py-1 bg-green-600 text-white rounded" @click="downloadPdf">Descargar</button>
      <slot name="extra-controls"></slot>
    </div>

    <div v-if="error && nativeFallback" class="flex flex-col h-full">
      <div class="bg-yellow-100 text-yellow-800 p-2 text-xs border-b border-yellow-300">Fallback nativo activado</div>
      <embed v-if="src" :src="src" type="application/pdf" class="flex-1 w-full border-none h-max" />
    </div>

    <div
      v-else
      ref="scrollContainer"
      class="flex-1 overflow-y-auto bg-gray-200 py-4 px-2"
      @scroll.passive="onScroll"
    >
      <div v-if="loading" class="text-center text-gray-700 py-8 text-sm">Cargando PDF…</div>

      <div
        v-for="pageNum in renderedPages"
        :key="pageNum"
        class="mx-auto mb-4 shadow bg-white"
        :style="{ width: pageViewports[pageNum]?.width ? pageViewports[pageNum].width + 'px' : 'auto' }"
      >
        <canvas :ref="setCanvasRef(pageNum)" class="block w-full"></canvas>
      </div>

      <div v-if="!loading && renderedPages.length === 0" class="text-center text-gray-600 text-sm">
        No se pudieron renderizar páginas.
      </div>
    </div>
  </div>
</template>

<script>
import { markRaw } from 'vue'
import * as pdfjsLib from 'pdfjs-dist/legacy/build/pdf.mjs'

pdfjsLib.GlobalWorkerOptions.workerSrc = '/pdf.worker.legacy.min.js'

export default {
  name: 'PdfViewer',
  props: {
    src: { type: String, required: true },
    initialPage: { type: Number, default: 1 },
    initialScale: { type: Number, default: 1.1 },
    showToolbar: { type: Boolean, default: true },
    downloadable: { type: Boolean, default: true },
    nativeFallback: { type: Boolean, default: true },
    lazyOffset: { type: Number, default: 400 },
    minScale: { type: Number, default: 0.5 },
    maxScale: { type: Number, default: 2.5 },
    fitParentWidth: { type: Boolean, default: true }
  },
  data() {
    return {
      pdfDoc: null,
      numPages: 0,
      currentPage: this.initialPage,
      scale: this.initialScale,
      loading: true,
      error: null,
      renderedPages: [],
      canvasRefs: {},
      pageViewports: {},
      scrollTimeout: null,
      initialFitDone: false
    }
  },
  emits: ['loaded', 'page-change', 'error', 'scale-change'],
  methods: {
    setCanvasRef(pageNum) {
      return el => { if (el) this.canvasRefs[pageNum] = el }
    },
    async loadPdf() {
      this.loading = true
      this.error = null
      try {
        const task = pdfjsLib.getDocument({ url: this.src })
        this.pdfDoc = markRaw(await task.promise)
        this.numPages = this.pdfDoc.numPages
        this.$emit('loaded', this.numPages)
        this.loading = false
        await this.renderPage(this.currentPage)
        if (this.currentPage + 1 <= this.numPages) this.renderPage(this.currentPage + 1)
      } catch (e) {
        this.error = e
        this.loading = false
        this.$emit('error', e)
        console.error(e)
      }
    },
    async renderPage(pageNum) {
      if (!this.pdfDoc || pageNum < 1 || pageNum > this.numPages) return
      if (!this.renderedPages.includes(pageNum)) {
        this.renderedPages.push(pageNum)
        await this.$nextTick()
      }
      const canvas = this.canvasRefs[pageNum]
      if (!canvas) return
      const page = await this.pdfDoc.getPage(pageNum)
      let viewport = page.getViewport({ scale: this.scale })

      if (this.fitParentWidth && !this.initialFitDone && this.$refs.scrollContainer) {
        const width = this.$refs.scrollContainer.clientWidth - 32
        const newScale = Math.min(Math.max(width / viewport.width, this.minScale), this.maxScale)
        if (Math.abs(newScale - this.scale) > 0.01) {
          this.scale = parseFloat(newScale.toFixed(2))
          this.$emit('scale-change', this.scale)
          viewport = page.getViewport({ scale: this.scale })
        }
        this.initialFitDone = true
      }

      this.pageViewports[pageNum] = viewport
      canvas.width = viewport.width
      canvas.height = viewport.height
      const ctx = canvas.getContext('2d', { alpha: false })
      await page.render({ canvasContext: ctx, viewport }).promise
    },
    prevPage() {
      if (this.currentPage <= 1) return
      this.currentPage--
      this.$emit('page-change', this.currentPage)
      this.renderPage(this.currentPage)
      this.scrollTo(this.currentPage)
    },
    nextPage() {
      if (this.currentPage >= this.numPages) return
      this.currentPage++
      this.$emit('page-change', this.currentPage)
      this.renderPage(this.currentPage)
      this.scrollTo(this.currentPage)
    },
    scrollTo(pageNum) {
      const el = this.canvasRefs[pageNum]?.parentElement
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' })
    },
    zoomIn() {
      if (this.scale >= this.maxScale) return
      this.scale = parseFloat((this.scale + 0.1).toFixed(2))
      this.$emit('scale-change', this.scale)
      this.redrawVisible()
    },
    zoomOut() {
      if (this.scale <= this.minScale) return
      this.scale = parseFloat((this.scale - 0.1).toFixed(2))
      this.$emit('scale-change', this.scale)
      this.redrawVisible()
    },
    resetZoom() {
      this.scale = this.initialScale
      this.$emit('scale-change', this.scale)
      this.initialFitDone = false
      this.redrawVisible()
    },
    fitWidth() {
      if (!this.$refs.scrollContainer || !this.pdfDoc) return
      const vp = this.pageViewports[this.currentPage]
      if (!vp) return
      const width = this.$refs.scrollContainer.clientWidth - 32
      const newScale = Math.min(Math.max(width / vp.width, this.minScale), this.maxScale)
      this.scale = parseFloat(newScale.toFixed(2))
      this.$emit('scale-change', this.scale)
      this.initialFitDone = true
      this.redrawVisible()
    },
    async redrawVisible() {
      for (const p of this.renderedPages) {
        const canvas = this.canvasRefs[p]
        if (!canvas) continue
        const page = await this.pdfDoc.getPage(p)
        const viewport = page.getViewport({ scale: this.scale })
        this.pageViewports[p] = viewport
        canvas.width = viewport.width
        canvas.height = viewport.height
        const ctx = canvas.getContext('2d', { alpha: false })
        await page.render({ canvasContext: ctx, viewport }).promise
      }
    },
    onScroll() {
      if (!this.$refs.scrollContainer) return
      if (this.scrollTimeout) clearTimeout(this.scrollTimeout)
      this.scrollTimeout = setTimeout(() => {
        let closest = this.currentPage
        let minDist = Infinity
        this.renderedPages.forEach(p => {
          const rect = this.canvasRefs[p]?.parentElement?.getBoundingClientRect()
          if (!rect) return
          const dist = Math.abs(rect.top - 80)
          if (dist < minDist) { minDist = dist; closest = p }
        })
        if (closest !== this.currentPage) {
          this.currentPage = closest
          this.$emit('page-change', this.currentPage)
        }
        const c = this.$refs.scrollContainer
        const bottom = c.scrollHeight - (c.scrollTop + c.clientHeight)
        if (bottom < this.lazyOffset && this.currentPage + 1 <= this.numPages) {
          this.renderPage(this.currentPage + 1)
        }
      }, 80)
    },
    downloadPdf() {
      const a = document.createElement('a')
      a.href = this.src
      a.download = 'documento.pdf'
      a.target = '_blank'
      document.body.appendChild(a)
      a.click()
      document.body.removeChild(a)
    },
    resetState() {
      this.pdfDoc = null
      this.numPages = 0
      this.currentPage = this.initialPage
      this.scale = this.initialScale
      this.loading = true
      this.error = null
      this.renderedPages = []
      this.canvasRefs = {}
      this.pageViewports = {}
      this.initialFitDone = false
    }
  },
  watch: {
    src: {
      immediate: true,
      async handler() {
        this.resetState()
        await this.loadPdf()
      }
    }
  },
  beforeUnmount() {
    if (this.scrollTimeout) clearTimeout(this.scrollTimeout)
  }
}
</script>

<style scoped>
.bg-wine { background-color:#6b0f28; }
.bg-wine\/80 { background-color:rgba(107,15,40,.8); }
</style>
