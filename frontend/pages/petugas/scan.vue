<template>
  <div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">
          Scan QR Member
        </h1>

        <p class="text-gray-500">
          Arahkan QR Code member ke kamera
        </p>
      </div>

      <NuxtLink
        to="/petugas"
        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg"
      >
        Kembali
      </NuxtLink>
    </div>

    <div class="max-w-xl mx-auto">
      <!-- Scanner -->
      <div class="bg-white rounded-xl shadow p-4">
        <div
          id="reader"
          class="w-full"
        ></div>
      </div>

      <!-- Status -->
      <div
        v-if="message"
        class="mt-4 p-4 rounded-lg text-center font-semibold"
        :class="
          success
            ? 'bg-green-100 text-green-700'
            : 'bg-red-100 text-red-700'
        "
      >
        {{ message }}
      </div>

      <!-- Data Member -->
      <div
        v-if="hasil"
        class="mt-4 bg-white rounded-xl shadow p-5"
      >
        <h2 class="text-lg font-bold mb-4">
          Member
        </h2>

        <div class="space-y-2">
          <p>
            <span class="text-gray-500">Kode:</span>
            {{ hasil.member?.kode }}
          </p>

          <p>
            <span class="text-gray-500">Nama:</span>
            {{ hasil.member?.nama }}
          </p>

          <p>
            <span class="text-gray-500">Perusahaan:</span>
            {{ hasil.member?.perusahaan }}
          </p>

          <p>
            <span class="text-gray-500">Tiket:</span>
            {{ hasil.tiket?.kode_tiket }}
          </p>

          <p>
            <span class="text-gray-500">Status:</span>
            {{ hasil.tiket?.status }}
          </p>

          <p>
            <span class="text-gray-500">Waktu Masuk:</span>
            {{ hasil.tiket?.waktu_masuk }}
          </p>
        </div>
      </div>

      <!-- Scan Lagi -->
      <button
        v-if="hasil"
        @click="scanLagi"
        class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold"
      >
        Scan Lagi
      </button>
    </div>
  </div>
</template>

<script setup>
import { Html5Qrcode } from "html5-qrcode";

const config = useRuntimeConfig();

const scanner = ref(null);
const scanning = ref(false);

const message = ref("");
const success = ref(false);
const hasil = ref(null);

const mulaiScan = async () => {
  if (scanning.value) {
    return;
  }

  scanning.value = true;

  scanner.value = new Html5Qrcode("reader");

  try {
    await scanner.value.start(
      {
        facingMode: "environment",
      },
      {
        fps: 10,
        qrbox: {
          width: 250,
          height: 250,
        },
      },
      async (decodedText) => {
        await prosesScan(decodedText);
      },
      () => {
        // Abaikan error ketika kamera belum menemukan QR
      }
    );
  } catch (error) {
    console.error("Kamera gagal:", error);

    message.value =
      "Kamera tidak dapat digunakan. Pastikan izin kamera diberikan.";

    success.value = false;
    scanning.value = false;
  }
};

const hentikanScan = async () => {
  if (!scanner.value) {
    return;
  }

  try {
    if (scanner.value.isScanning) {
      await scanner.value.stop();
    }

    await scanner.value.clear();
  } catch (error) {
    console.error(error);
  }

  scanning.value = false;
};

const prosesScan = async (qrCode) => {
  // Hentikan kamera supaya QR tidak terbaca berkali-kali
  await hentikanScan();

  try {
    const response = await $fetch(
      `${config.public.apiBase}/scan`,
      {
        method: "POST",
        body: {
          qr_code: qrCode,
        },
      }
    );

    hasil.value = response;

    message.value =
      response.message || "Member berhasil masuk.";

    success.value = true;
  } catch (error) {
    console.error("Scan gagal:", error);

    hasil.value = null;

    message.value =
      error?.data?.message ||
      "QR Code tidak dapat diproses.";

    success.value = false;
  }
};

const scanLagi = async () => {
  message.value = "";
  success.value = false;
  hasil.value = null;

  await nextTick();

  mulaiScan();
};

onMounted(() => {
  mulaiScan();
});

onBeforeUnmount(() => {
  hentikanScan();
});
</script>

<style>
/* html5-qrcode menyisipkan <video> secara dinamis ke dalam #reader.
   Beberapa perangkat/browser menampilkan preview yang mirror (terbalik
   horizontal). scaleX(-1) mem-flip video kembali ke orientasi normal
   supaya QR / tulisan pada tiket mudah dibaca. */
#reader video {
  transform: scaleX(-1) !important;
}
</style>