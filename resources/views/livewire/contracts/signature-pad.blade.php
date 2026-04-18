<div x-data="signaturePad()" x-show="open" @open-signature-pad.window="openFor($event.detail.contractId)"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display:none">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md space-y-4" @click.stop>
        <h3 class="text-lg font-semibold text-gray-900">Sign Contract</h3>
        <p class="text-sm text-gray-500">Draw your signature below. It will be stored as your electronic signature.</p>

        <canvas id="sig-canvas" width="420" height="150"
                class="border-2 border-gray-300 rounded-md w-full touch-none cursor-crosshair"
                @mousedown="startDraw($event)" @mousemove="draw($event)" @mouseup="stopDraw()"
                @touchstart.prevent="startDraw($event.touches[0])" @touchmove.prevent="draw($event.touches[0])" @touchend="stopDraw()">
        </canvas>

        <div class="flex justify-between">
            <button @click="clear()" class="text-sm text-gray-500 hover:text-gray-700">Clear</button>
            <div class="flex gap-3">
                <button @click="open = false" class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                <button @click="submit()" class="px-4 py-2 text-sm text-white bg-green-600 rounded-md hover:bg-green-700">Submit Signature</button>
            </div>
        </div>
    </div>
</div>

<script>
function signaturePad() {
    return {
        open: false, drawing: false, contractId: null, ctx: null, canvas: null,
        openFor(contractId) { this.contractId = contractId; this.open = true; this.$nextTick(() => { this.initCanvas(); }); },
        initCanvas() { this.canvas = document.getElementById('sig-canvas'); this.ctx = this.canvas.getContext('2d'); this.ctx.strokeStyle = '#1e40af'; this.ctx.lineWidth = 2; this.ctx.lineCap = 'round'; },
        startDraw(e) { this.drawing = true; const r = this.canvas.getBoundingClientRect(); this.ctx.beginPath(); this.ctx.moveTo(e.clientX - r.left, e.clientY - r.top); },
        draw(e) { if (!this.drawing) return; const r = this.canvas.getBoundingClientRect(); this.ctx.lineTo(e.clientX - r.left, e.clientY - r.top); this.ctx.stroke(); },
        stopDraw() { this.drawing = false; },
        clear() { this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height); },
        submit() {
            const data = this.canvas.toDataURL('image/png');
            @this.call('saveSignature', this.contractId, data);
            this.open = false;
        }
    }
}
</script>
