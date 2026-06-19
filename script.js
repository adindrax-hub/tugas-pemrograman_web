document.addEventListener("DOMContentLoaded", function () {
  // =========================================================
  // 1. LOGIKA ANIMASI HURUF MENYEBAR
  // =========================================================
  const bgContainer = document.getElementById("interactive-bg");
  if (bgContainer) {
    // Ganti huruf-huruf di bawah ini sesuai keinginan Anda
    const letters = ["I", "N", "D", "A", "T", "A", "O", "R", "G"];
    const maxLetters = 45;

    for (let i = 0; i < maxLetters; i++) {
      const span = document.createElement("span");
      span.classList.add("floating-letter");
      span.innerText = letters[Math.floor(Math.random() * letters.length)];

      span.style.left = Math.floor(Math.random() * 100) + "%";
      span.style.top = Math.floor(Math.random() * 100) + "%";
      span.style.fontSize =
        Math.floor(Math.random() * (35 - 15 + 1)) + 15 + "px";
      span.style.animationDuration =
        Math.floor(Math.random() * (18 - 8 + 1)) + 8 + "s";
      span.style.animationDelay = Math.random() * 10 + "s";

      bgContainer.appendChild(span);
    }
  }

  // =========================================================
  // 2. KANVAS TANDA TANGAN DIGITAL (DIPERBAIKI)
  // =========================================================
  const canvas = document.getElementById("signatureCanvas");
  if (canvas) {
    const signaturePad = new SignaturePad(canvas, {
      backgroundColor: "rgba(255, 255, 255, 0)",
      penColor: "rgb(0, 0, 0)",
    });

    function resizeCanvas() {
      // Cegah error jika modal sedang tertutup (ukuran 0)
      if (canvas.offsetWidth === 0) return;

      const ratio = Math.max(window.devicePixelRatio || 1, 1);
      canvas.width = canvas.offsetWidth * ratio;
      canvas.height = canvas.offsetHeight * ratio;
      canvas.getContext("2d").scale(ratio, ratio);
      signaturePad.clear();
    }

    window.addEventListener("resize", resizeCanvas);

    // KUNCI PERBAIKAN: Jalankan resizeCanvas HANYA saat modal selesai dibuka
    const crudModalEl = document.getElementById("crudModal");
    if (crudModalEl) {
      crudModalEl.addEventListener("shown.bs.modal", function () {
        resizeCanvas();
      });
    }

    document
      .getElementById("clearSignature")
      .addEventListener("click", function () {
        signaturePad.clear();
      });

    document
      .getElementById("dataForm")
      .addEventListener("submit", function (e) {
        if (!signaturePad.isEmpty()) {
          document.getElementById("tandaTanganBase64").value =
            signaturePad.toDataURL("image/png");
        }
      });
  }
});

// =========================================================
// 3. JQUERY UNTUK DATATABLES & MODAL
// =========================================================
if (typeof jQuery !== "undefined") {
  $(document).ready(function () {
    if ($("#dataTable").length) {
      $("#dataTable").DataTable({
        dom: "Bfrtip",
        buttons: [
          {
            extend: "excelHtml5",
            className: "btn btn-dark btn-sm mb-3",
            text: '<i class="fa-solid fa-file-excel"></i> Excel',
          },
          {
            extend: "pdfHtml5",
            className: "btn btn-dark btn-sm mb-3 ms-1",
            text: '<i class="fa-solid fa-file-pdf"></i> PDF',
          },
          {
            extend: "print",
            className: "btn btn-outline-dark btn-sm mb-3 ms-1",
            text: '<i class="fa-solid fa-print"></i> Print',
          },
        ],
      });
    }

    $(".btn-edit").on("click", function () {
      const id = $(this).data("id");
      const nama = $(this).data("nama");

      $("#idData").val(id);
      $("#namaLengkap").val(nama);
      $("#dataForm").attr("action", "proses.php?aksi=edit");
      $("#modalLabel").text("Edit Data Workspace");
    });

    $("#crudModal").on("hidden.bs.modal", function () {
      $("#idData").val("");
      $("#namaLengkap").val("");
      $("#dataForm").attr("action", "proses.php?aksi=tambah");
      $("#modalLabel").text("Kelola Data InData");
    });
  });
}
