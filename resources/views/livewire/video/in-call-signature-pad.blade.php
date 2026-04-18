<div x-data="inCallSigPad()" x-show="open" @open-signature-pad.window="open = true"
     class="border-t border-gray-200 bg-white p-4 space-y-3" style="display:none">
    <h4 class="text-sm font-semibold text-gray-700">Sign while on call</h4>
    <canvas id="incall-sig-canvas" width="400" height="120"
            class="border border-gray-300 rounded w-full touch-none cursor-crosshair"
            @mousedown="startDraw($event)" @mousemove="draw($event)" @mouseup="stopDraw()"
            @touchstart.prevent="startDraw($event.touches[0])" @touchmove.prevent="draw($event.touches[0])" @touchend="stopDraw()">
    </canvas>
    <div class="flex justify-between items-center">
        <button @click="clear()" class="text-xs text-gray-500 hover:text-gray-700">Clear</button>
        <div class="flex gap-2">
            <button @click="open = false" class="px-3 py-1 text-xs text-gray-600 border border-gray-300 rounded-md">Cancel</button>
            <button @click="submit()" class="px-3 py-1 text-xs text-white bg-green-600 rounded-md hover:bg-green-700">Submit</button>
        </div>
    </div>
</div>
<script>
function inCallSigPad() {
    return {
        open: false, drawing: false, ctx: null, canvas: null,
        init() { this.$watch('open', v => { if (v) this.$nextTick(() => { this.canvas = document.getElementById('incall-sig-canvas'); this.ctx = this.canvas.getContext('2d'); this.ctx.strokeStyle = '#1e40af'; this.ctx.lineWidth = 2; this.ctx.lineCap = 'round'; }); }); },
        startDraw(e) { this.drawing = true; const r = this.canvas.getBoundingClientRect(); this.ctx.beginPath(); this.ctx.moveTo(e.clientX-r.left, e.clientY-r.top); },
        draw(e) { if (!this.drawing) return; const r = this.canvas.getBoundingClientRect(); this.ctx.lineTo(e.clientX-r.left, e.clientY-r.top); this.ctx.stroke(); },
        stopDraw() { this.drawing = false; },
        clear() { this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height); },
        submit() { @this.call('sign', this.canvas.toDataURL('image/png')); this.open = false; }
    }
}
</script>
