<template>
  <div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">
          Tiket Parkir
        </h1>

        <p class="text-gray-500">
          Daftar tiket kendaraan yang masuk
        </p>
      </div>

      <NuxtLink
        to="/petugas/scan"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
      >
        + Buat Tiket
      </NuxtLink>
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
      <table class="min-w-full border">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="p-3">Kode Tiket</th>
            <th class="p-3">QR Code</th>
            <th class="p-3">Status</th>
            <th class="p-3">Waktu Masuk</th>
            <th class="p-3">Waktu Keluar</th>
            <th class="p-3">Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="item in tiket"
            :key="item.id"
            class="border-b hover:bg-gray-100"
          >
            <!-- Kode -->
            <td class="p-3 font-semibold">
              {{ item.kode_tiket }}
            </td>

            <!-- QR -->
            <td class="p-3">
              <img
                v-if="qrImages[item.id]"
                :src="qrImages[item.id]"
                :alt="`QR ${item.kode_tiket}`"
                class="w-28 h-28 object-contain"
              />

              <span
                v-else
                class="text-gray-400"
              >
                Membuat QR...
              </span>
            </td>

            <!-- Status -->
            <td class="p-3">
              <span
                class="px-3 py-1 rounded-full text-sm font-semibold"
                :class="
                  item.status === 'masuk'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-gray-100 text-gray-700'
                "
              >
                {{ item.status.toUpperCase() }}
              </span>
            </td>

            <!-- Waktu masuk -->
            <td class="p-3">
              {{ formatTanggal(item.waktu_masuk) }}
            </td>

            <!-- Waktu keluar -->
            <td class="p-3">
              {{ formatTanggal(item.waktu_keluar) }}
            </td>

            <!-- Aksi -->
            <td class="p-3">
              <NuxtLink
                v-if="item.status === 'masuk'"
                to="/petugas/keluar"
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded"
              >
                Keluar
              </NuxtLink>

              <span
                v-else
                class="text-gray-500"
              >
                Selesai
              </span>
            </td>
          </tr>

          <!-- Kosong -->
          <tr v-if="tiket.length === 0">
            <td
              colspan="6"
              class="text-center p-6 text-gray-500"
            >
              Belum ada tiket.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import QRCode from "qrcode";

const config = useRuntimeConfig();

const tiket = ref([]);
const qrImages = ref({});

const formatTanggal = (tanggal) => {
  if (!tanggal) {
    return "-";
  }

  return new Date(tanggal).toLocaleString("id-ID");
};

const buatQR = async (data) => {
  const hasil = {};

  for (const item of data) {
    if (!item.qr_code) {
      continue;
    }

    try {
      hasil[item.id] = await QRCode.toDataURL(item.qr_code, {
        width: 180,
        margin: 2,
      });
    } catch (error) {
      console.error(
        `Gagal membuat QR ${item.kode_tiket}:`,
        error
      );
    }
  }

  qrImages.value = hasil;
};

const ambilTiket = async () => {
  try {
    const response = await $fetch(
      `${config.public.apiBase}/tiket`
    );

    tiket.value = response;

    await buatQR(response);
  } catch (error) {
    console.error(
      "Gagal mengambil data tiket:",
      error
    );
  }
};

onMounted(() => {
  ambilTiket();
});
</script>