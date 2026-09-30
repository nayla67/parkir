<template>
  <div class="min-h-screen flex items-center justify-center relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-green-700 to-green-900"></div>
    <div class="absolute inset-0 bg-blue-900/40"></div>

    <div
      class="
        relative z-10
        w-[420px]
        min-h-[650px]
        bg-white/40
        backdrop-blur-md
        rounded-3x1
        shadow-2x1
        flex
        flex-col
        items-center
        pt-14
      "
    >
      <h1
        class="
          text-white
          text-3x1
          font-bold
          text-center
          drop-shadow-lg
        "
      >
        PARKIR<br />
        PLAZA ANDALAS
      </h1>

      <p
        class="
          mt-8
          text-gray-700
          text-sm
          text-center
          font-semibold
        "
      >
        Pencet Tombol PRINT untuk<br />
        mengambil tiket
      </p>

      <button
        @click="printTicket"
        :disabled="isPrinting"
        class="
          mt-4
          w-36
          h-11
          rounded-full
          bg-lime-900
          text-white
          font-bold
          shadow-lg
          hover:bg-lime-700
          transition
          disabled:opacity-60
          disabled:cursor-not-allowed
        "
      >
        {{ isPrinting ? 'MEMPROSES...' : 'PRINT' }}
      </button>

      <div class="w-44 border-t-2 border-black mt-8"></div>

      <p
        class="
          mt-8
          text-gray-700
          text-sm
          text-center
          font-semibold
        "
      >
        Scan kartu Member Untuk<br />
        Buka Pintu
      </p>

      <input
        v-model="scanCard"
        autofocus
        type="text"
        :disabled="isScanning"
        class="
          mt-5
          w-44
          h-14
          rounded-xl
          bg-white
          text-center
          text-xl
          shadow-lg
          disabled:opacity-60
        "
        @keyup.enter="openGate"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useNuxtApp } from "#app";
import jsPDF from "jspdf";
import QRCode from "qrcode";

const { $api } = useNuxtApp();

const scanCard = ref("");
const tiket = ref<any>(null);
const isPrinting = ref(false);
const isScanning = ref(false);

const buatTiket = async () => {
  try {
    const response = await $api.post("/tiket");
    tiket.value = response.data.data;
    return tiket.value;
  } catch (error: any) {
    const pesan =
      error?.response?.data?.message ||
      error?.response?.statusText ||
      error?.message ||
      "Gagal membuat tiket";

    console.error("Error buat tiket:", error);
    alert(`Gagal membuat tiket: ${pesan}`);
    return null;
  }
};

const printTicket = async () => {
  if (isPrinting.value) return;

  isPrinting.value = true;

  try {
    const data = await buatTiket();

    if (!data) return;

    const nomorTiket = data.kode_tiket;

    // PENTING: QR harus encode data.qr_code APA ADANYA,
    // bukan teks/template lain. Ini yang dipakai backend
    // untuk mencari tiket saat scan keluar.
    const qrData = data.qr_code;

    const qrimage = await QRCode.toDataURL(qrData);

    const pdf = new jsPDF({
      orientation: "portrait",
      unit: "mm",
      format: [80, 140],
    });

    pdf.setFontSize(12);
    pdf.text("PARKIR PLAZA ANDALAS", 40, 10, { align: "center" });

    pdf.setFontSize(7);
    pdf.text("===============================", 40, 20, { align: "center" });
    pdf.text("Jl. Dr KRT RADJIMAN WIDYODININGRAT", 40, 25, { align: "center" });
    pdf.text("jAKARTA TIMUR", 40, 30, { align: "center" });

    pdf.addImage(qrimage, "PNG", 25, 35, 40, 40);

    pdf.setFontSize(10);
    pdf.text(nomorTiket, 40, 90, { align: "center" });

    pdf.setFontSize(8);
    pdf.text(
      `informasi :

1. Non Member 
Parkir Rp.3000

2. Member 
Parkir Rp.150.000/bulan 

Terima kasih`,
      40,
      105,
      { align: "center" }
    );

    pdf.save(`tiket-${nomorTiket}.pdf`);
  } finally {
    isPrinting.value = false;
  }
};

const openGate = async () => {
  const kode = scanCard.value.trim();

  if (!kode || isScanning.value) return;

  isScanning.value = true;

  try {
    const response = await $api.post("/scan", {
      qr_code: kode,
    });

    const body = response?.data;

    alert(body?.message || "Member berhasil masuk. Pintu terbuka.");

  } catch (error: any) {
    const pesan =
      error?.response?.data?.message ||
      error?.response?.statusText ||
      error?.message ||
      "Gagal memproses kartu member.";

    alert(pesan);

  } finally {
    isScanning.value = false;
    scanCard.value = "";
  }
};
</script>