<template>
  <div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">
          Tagihan Member
        </h1>

        <p class="text-gray-500">
          Daftar tagihan member bulanan
        </p>
      </div>

      <NuxtLink
        to="/petugas/member/select"
        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded"
      >
        Kembali
      </NuxtLink>
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
      <table class="min-w-full border">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="p-3">Kode</th>
            <th class="p-3">Nama</th>
            <th class="p-3">Perusahaan</th>
            <th class="p-3">Tagihan</th>
            <th class="p-3">Dibayar</th>
            <th class="p-3">Kembalian</th>
            <th class="p-3">Tanggal Tagihan</th>
            <th class="p-3">Status</th>
            <th class="p-3">Tanggal Bayar</th>
            <th class="p-3">Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="item in tagihan"
            :key="item.id"
            class="border-b hover:bg-gray-100"
          >
            <!-- Kode -->
            <td class="p-3">
              {{ item.member?.kode }}
            </td>

            <!-- Nama -->
            <td class="p-3">
              {{ item.member?.nama }}
            </td>

            <!-- Perusahaan -->
            <td class="p-3">
              {{ item.member?.perusahaan }}
            </td>

            <!-- Tagihan -->
            <td class="p-3">
              Rp {{ formatHarga(item.jumlah) }}
            </td>

            <!-- Dibayar (sama dengan input "Jumlah Dibayar") -->
            <td class="p-3">
              Rp {{ formatHarga(getDibayar(item)) }}
            </td>

            <!-- Kembalian -->
            <td class="p-3">
              Rp {{ formatHarga(getKembalian(item)) }}
            </td>

            <!-- Tanggal Tagihan -->
            <td class="p-3">
              {{ formatTanggal(item.tanggal_tagihan) }}
            </td>

            <!-- Status -->
            <td class="p-3">
              <span
                class="px-3 py-1 rounded-full text-sm font-semibold"
                :class="
                  isLunas(item)
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-700'
                "
              >
                {{ String(item.status).toUpperCase() }}
              </span>
            </td>

            <!-- Tanggal Bayar -->
            <td class="p-3">
              {{ formatTanggal(item.tanggal_bayar) }}
            </td>

            <!-- Aksi -->
            <td class="p-3 text-center">
              <!-- Kalau belum lunas -->
              <button
                v-if="!isLunas(item)"
                @click="bayar(item.id)"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium"
              >
                Bayar
              </button>

              <!-- Kalau sudah lunas -->
              <span
                v-else
                class="text-green-600 font-semibold"
              >
                ✓ Lunas
              </span>
            </td>
          </tr>

          <!-- Kalau data kosong -->
          <tr v-if="tagihan.length === 0">
            <td
              colspan="10"
              class="text-center p-6 text-gray-500"
            >
              Belum ada tagihan.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const { $api } = useNuxtApp()

const tagihan = ref([])

const fetchTagihan = async () => {
  try {
    const res = await $api.get('/tagihan')

    // Menyesuaikan response dari API
    tagihan.value = Array.isArray(res) ? res : res.data || []
  } catch (error) {
    console.error('Gagal mengambil tagihan:', error)
    tagihan.value = []
  }
}

onMounted(fetchTagihan)

// Cek status lunas
const isLunas = (item) => {
  return String(item.status).toLowerCase().trim() === 'lunas'
}

// Jumlah dibayar: pakai data dari input form (jumlah_dibayar).
// Kalau tagihan lama belum punya datanya, pakai jumlah tagihan
// untuk yang sudah lunas, dan 0 untuk yang belum.
const getDibayar = (item) => {
  if (item.jumlah_dibayar !== null && item.jumlah_dibayar !== undefined) {
    return item.jumlah_dibayar
  }

  return isLunas(item) ? item.jumlah : 0
}

// Kembalian: pakai data dari backend, atau hitung dari dibayar - tagihan
const getKembalian = (item) => {
  if (item.kembalian !== null && item.kembalian !== undefined) {
    return item.kembalian
  }

  return Math.max(Number(getDibayar(item)) - Number(item.jumlah), 0)
}

// Format harga
const formatHarga = (harga) => {
  return Number(harga || 0).toLocaleString("id-ID");
};

// Format tanggal
const formatTanggal = (tanggal) => {
  if (!tanggal) {
    return "-";
  }

  const date = new Date(tanggal);

  return date.toLocaleDateString("id-ID", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
  });
};

// Bayar tagihan
const bayar = async (id) => {
  const yakin = confirm(
    "Apakah tagihan ini sudah dibayar?"
  );

  if (!yakin) {
    return;
  }

  try {
    await $api.post(`/tagihan/${id}/bayar`);

    alert("Tagihan berhasil dibayar.");

    // Refresh data
    await fetchTagihan();
  } catch (error) {
    console.error(error);

    alert(
      error?.response?.data?.message ||
      "Gagal melakukan pembayaran."
    );
  }
};
</script>