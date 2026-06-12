<!-- QR Modal -->
<div id="qr-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4 opacity-0 transition-opacity duration-300" role="dialog" aria-modal="true" aria-labelledby="qr-modal-title">
    <div id="qr-modal-content" class="bg-white rounded-3xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden transform scale-95 transition-transform duration-300" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="min-w-0 pr-4">
                <h3 id="qr-modal-title" class="text-lg font-extrabold text-slate-900 truncate">QR Code</h3>
                <p class="text-xs font-medium text-slate-500 mt-0.5">Scan to open the link</p>
            </div>
            <button type="button" id="qr-modal-close" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition-colors shrink-0" aria-label="Close">
                <i class="ph ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-6 flex flex-col items-center gap-4">
            <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-sm">
                <canvas id="qr-canvas"></canvas>
            </div>
            <p id="qr-url-text" class="text-xs font-medium text-slate-500 break-all text-center px-2"></p>
            <div class="flex flex-col sm:flex-row gap-3 w-full">
                <button type="button" id="qr-copy-link" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-sm transition-colors">
                    <i class="ph ph-copy text-lg"></i> Copy link
                </button>
                <button type="button" id="qr-download" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-primary hover:opacity-95 text-white rounded-xl font-bold text-sm transition-all shadow-lg shadow-indigo-500/25">
                    <i class="ph ph-download-simple text-lg"></i> Download PNG
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/build/qrcode.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const qrModal = document.getElementById('qr-modal');
        const qrModalContent = document.getElementById('qr-modal-content');
        const qrModalClose = document.getElementById('qr-modal-close');
        const qrCanvas = document.getElementById('qr-canvas');
        const qrUrlText = document.getElementById('qr-url-text');
        const qrModalTitle = document.getElementById('qr-modal-title');
        const qrCopyLink = document.getElementById('qr-copy-link');
        const qrDownload = document.getElementById('qr-download');
        let currentQrUrl = '';
        let currentEventId = '';

        function closeQrModal() {
            if (qrModal.classList.contains('hidden')) return;

            qrModal.classList.add('opacity-0');
            qrModalContent.classList.add('scale-95');

            setTimeout(function() {
                qrModal.classList.add('hidden');
                qrModal.classList.remove('flex');
                const ctx = qrCanvas.getContext('2d');
                ctx.clearRect(0, 0, qrCanvas.width, qrCanvas.height);
                qrUrlText.textContent = '';
                currentQrUrl = '';
                currentEventId = '';
            }, 300);
        }

        function openQrModal(url, title, eventId) {
            currentQrUrl = url;
            currentEventId = eventId;
            qrModalTitle.textContent = title;
            qrUrlText.textContent = url;

            QRCode.toCanvas(qrCanvas, url, { width: 256, margin: 2 }, function(error) {
                if (error) {
                    if (typeof Toast !== 'undefined') {
                        Toast.fire({ icon: 'error', title: 'Failed to generate QR code' });
                    } else {
                        alert('Failed to generate QR code');
                    }
                    return;
                }

                qrModal.classList.remove('hidden');
                qrModal.classList.add('flex');
                void qrModal.offsetWidth;
                qrModal.classList.remove('opacity-0');
                qrModalContent.classList.remove('scale-95');
            });
        }

        document.querySelectorAll('.qr-trigger').forEach(function(trigger) {
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                // Check if closePopover function exists and call it (useful if shared with other modals)
                if (typeof closePopover === 'function') {
                    closePopover();
                }
                openQrModal(
                    trigger.dataset.qrUrl,
                    trigger.dataset.eventTitle,
                    trigger.dataset.eventId
                );
            });
        });

        qrModalClose.addEventListener('click', closeQrModal);

        qrModal.addEventListener('click', function(e) {
            if (e.target === qrModal) {
                closeQrModal();
            }
        });

        qrCopyLink.addEventListener('click', function() {
            if (!currentQrUrl) return;

            navigator.clipboard.writeText(currentQrUrl).then(function() {
                if (typeof Toast !== 'undefined') {
                    Toast.fire({ icon: 'success', title: 'Link copied to clipboard' });
                } else {
                    alert('Link copied to clipboard');
                }
            }).catch(function() {
                const range = document.createRange();
                range.selectNodeContents(qrUrlText);
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
                if (typeof Toast !== 'undefined') {
                    Toast.fire({ icon: 'info', title: 'Select the link and copy manually' });
                }
            });
        });

        qrDownload.addEventListener('click', function() {
            if (!qrCanvas.width) return;
            const link = document.createElement('a');
            link.download = 'qrcode-' + currentEventId + '.png';
            link.href = qrCanvas.toDataURL('image/png');
            link.click();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeQrModal();
            }
        });
    });
</script>
