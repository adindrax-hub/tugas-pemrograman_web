document.addEventListener("DOMContentLoaded", function () {
  // 1. Inisialisasi DataTables (Pastikan tabelnya ada)
  if ($("#dataTable").length) {
    $("#dataTable").DataTable({
      dom: "Bfrtip",
      buttons: [
        {
          extend: "excelHtml5",
          className: "btn btn-success btn-sm mb-3 me-2",
          text: "Ekspor Excel",
        },
        {
          extend: "pdfHtml5",
          className: "btn btn-danger btn-sm mb-3 me-2",
          text: "Ekspor PDF",
        },
        {
          extend: "print",
          className: "btn btn-info btn-sm mb-3",
          text: "Cetak Data",
        },
      ],
      language: {
        search: "Cari Data:",
        lengthMenu: "Tampilkan _MENU_ data",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        paginate: {
          first: "Pertama",
          last: "Terakhir",
          next: "Selanjutnya",
          previous: "Sebelumnya",
        },
      },
    });
  }

  // 2. Inisialisasi Canvas Signature Pad
  const canvas = document.getElementById("signatureCanvas");
  let signaturePad;

  function resizeCanvas() {
    if (!canvas) return;
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
    if (signaturePad) {
      signaturePad.clear();
    }
  }

  const crudModal = document.getElementById("crudModal");
  if (crudModal) {
    crudModal.addEventListener("shown.bs.modal", function () {
      resizeCanvas();
      if (!signaturePad && canvas) {
        signaturePad = new SignaturePad(canvas, {
          penColor: "rgb(0, 0, 0)",
          backgroundColor: "rgb(255, 255, 255)",
        });
      }
    });
  }

  const clearBtn = document.getElementById("clearSignature");
  if (clearBtn) {
    clearBtn.addEventListener("click", function () {
      if (signaturePad) {
        signaturePad.clear();
      }
    });
  }

  // 3. LOGIKA DINAMIS MODAL (TAMBAH vs EDIT)
  const btnTambahBaru = document.getElementById("btnTambahBaru");
  if (btnTambahBaru) {
    btnTambahBaru.addEventListener("click", function () {
      if (document.getElementById("modalLabel"))
        document.getElementById("modalLabel").innerText = "Form Input Data";
      if (document.getElementById("dataForm"))
        document.getElementById("dataForm").action = "proses.php?aksi=tambah";
      if (document.getElementById("idData"))
        document.getElementById("idData").value = "";
      if (document.getElementById("dataForm"))
        document.getElementById("dataForm").reset();

      const uploadInput = document.getElementById("uploadDokumen");
      if (uploadInput) uploadInput.setAttribute("required", "required");
      if (document.getElementById("fileHelp"))
        document.getElementById("fileHelp").innerText =
          "Tahan tombol CTRL untuk memilih lebih dari satu file.";
      if (document.getElementById("sigHelp"))
        document.getElementById("sigHelp").innerText =
          "*Wajib mengisi tanda tangan.";
      if (signaturePad) signaturePad.clear();
    });
  }

  // Edit Event Delegation (Aman untuk element dinamis DataTables)
  document.addEventListener("click", function (e) {
    if (e.target && e.target.classList.contains("btn-edit")) {
      const id = e.target.getAttribute("data-id");
      const nama = e.target.getAttribute("data-nama");

      if (document.getElementById("modalLabel"))
        document.getElementById("modalLabel").innerText = "Form Edit Data";
      if (document.getElementById("dataForm"))
        document.getElementById("dataForm").action = "proses.php?aksi=edit";
      if (document.getElementById("idData"))
        document.getElementById("idData").value = id;
      if (document.getElementById("namaLengkap"))
        document.getElementById("namaLengkap").value = nama;

      const uploadInput = document.getElementById("uploadDokumen");
      if (uploadInput) uploadInput.removeAttribute("required");
      if (document.getElementById("fileHelp"))
        document.getElementById("fileHelp").innerText =
          "Kosongkan jika tidak ingin menambah/mengubah file dokumen.";
      if (document.getElementById("sigHelp"))
        document.getElementById("sigHelp").innerText =
          "*Biarkan kosong jika tidak ingin mengubah tanda tangan lama.";
      if (signaturePad) signaturePad.clear();
    }
  });

  // 4. PROSES TRANSFER DATA CANVAS KE INPUT HIDDEN SAAT SUBMIT
  const dataForm = document.getElementById("dataForm");
  if (dataForm) {
    dataForm.addEventListener("submit", function (e) {
      const action = dataForm.getAttribute("action");
      const isTambah = action ? action.includes("aksi=tambah") : false;

      if (isTambah && signaturePad && signaturePad.isEmpty()) {
        e.preventDefault();
        alert("Silakan isi tanda tangan terlebih dahulu sebelum menyimpan.");
      } else {
        const hiddenInput = document.getElementById("tandaTanganBase64");
        if (hiddenInput) {
          if (signaturePad && !signaturePad.isEmpty()) {
            // Mengubah gambar canvas menjadi string teks Base64 untuk dikirim ke PHP
            hiddenInput.value = signaturePad.toDataURL();
          } else {
            hiddenInput.value = "";
          }
        }
      }
    });
  }
});
