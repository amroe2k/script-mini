<style>
/* Custom SweetAlert2 Dark/Light Mode Integration */
.swal2-popup.custom-swal {
  background: var(--card-bg, var(--bg, #111827)) !important;
  color: var(--text, #f1f5f9) !important;
  border: 1px solid var(--border, rgba(255,255,255,0.06)) !important;
  border-radius: 16px !important;
  box-shadow: 0 20px 40px rgba(0,0,0,0.3) !important;
  font-family: var(--font-body, 'Poppins', sans-serif) !important;
}

[data-theme="light"] .swal2-popup.custom-swal {
  box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
}

.swal2-title, .swal2-html-container {
  color: var(--text, #f1f5f9) !important;
}

.swal2-actions {
  gap: 12px !important;
}

/* Confirm Button styling */
.swal2-confirm.custom-swal-confirm {
  background: var(--primary, #3b82f6) !important;
  color: #ffffff !important; /* Fix light mode text contrast */
  border-radius: 12px !important;
  padding: 12px 26px !important;
  font-weight: 600 !important;
  border: none !important;
  cursor: pointer !important;
  box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2) !important;
  transition: all 0.2s ease !important;
}

.swal2-confirm.custom-swal-confirm:hover {
  background: var(--primary-hover, #2563eb) !important;
  transform: translateY(-1px);
  box-shadow: 0 6px 15px rgba(59, 130, 246, 0.3) !important;
}

[data-theme="light"] .swal2-confirm.custom-swal-confirm {
  background: var(--primary, #2563eb) !important;
  box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15) !important;
}

[data-theme="light"] .swal2-confirm.custom-swal-confirm:hover {
  background: #1d4ed8 !important;
  box-shadow: 0 6px 15px rgba(37, 99, 235, 0.25) !important;
}

.swal2-confirm.custom-swal-confirm:active {
  transform: translateY(1px);
}

/* Cancel Button styling */
.swal2-cancel.custom-swal-cancel {
  background: transparent !important;
  color: var(--text, #f1f5f9) !important;
  border: 1px solid var(--border-mid, rgba(255,255,255,0.15)) !important;
  border-radius: 12px !important;
  padding: 12px 26px !important;
  font-weight: 600 !important;
  cursor: pointer !important;
  transition: all 0.2s ease !important;
}

.swal2-cancel.custom-swal-cancel:hover {
  background: rgba(255, 255, 255, 0.05) !important;
}

[data-theme="light"] .swal2-cancel.custom-swal-cancel {
  color: var(--text, #0f172a) !important;
  border: 1px solid var(--border-mid, rgba(0, 0, 0, 0.13)) !important;
}

[data-theme="light"] .swal2-cancel.custom-swal-cancel:hover {
  background: rgba(0, 0, 0, 0.04) !important;
}

.swal2-cancel.custom-swal-cancel:active {
  transform: translateY(1px);
}

/* Toast specifically */
.swal2-toast.custom-swal {
  border-radius: 12px !important;
  padding: 12px 16px !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Global SweetAlert mixins for default styling
  const SwalCustom = Swal.mixin({
    customClass: {
      popup: 'custom-swal',
      confirmButton: 'custom-swal-confirm',
      cancelButton: 'custom-swal-cancel'
    },
    buttonsStyling: false
  });

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: true,
    customClass: {
      popup: 'custom-swal',
    },
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  });

  // Make SwalCustom and Toast globally available
  window.SwalCustom = SwalCustom;
  window.Toast = Toast;

  // Check for session flashdata messages
  <?php if (session()->getFlashdata('msg')): ?>
    <?php 
      $msg = session()->getFlashdata('msg');
      $text = 'Berhasil';
      if ($msg === 'created') $text = 'Script berhasil ditambahkan!';
      if ($msg === 'updated') $text = 'Script berhasil diperbarui!';
      if ($msg === 'deleted') $text = 'Script berhasil dihapus!';
    ?>
    Toast.fire({
      icon: 'success',
      title: '<?= $text ?>'
    });
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
    SwalCustom.fire({
      icon: 'error',
      title: 'Terjadi Kesalahan',
      text: '<?= esc(session()->getFlashdata('error')) ?>',
      confirmButtonText: 'Tutup'
    });
  <?php endif; ?>
});

// Helper for Confirm Dialogs (e.g. for deletes)
function confirmDelete(formElement, message = 'Hapus script ini permanen?') {
  window.SwalCustom.fire({
    title: 'Konfirmasi',
    text: message,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      formElement.submit();
    }
  });
  return false;
}
</script>
