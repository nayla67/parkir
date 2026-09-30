<template>
  <div
    class="relative min-h-screen bg-gradient-to-br from-pink-100 via-purple-100 to-blue-100 p-6 sm:p-10 font-sans"
  >
    <!-- Background decoration -->
    <div
      class="absolute -top-24 -left-24 h-96 w-96 rounded-full bg-pink-300/30 pointer-events-none"
    ></div>

    <div
      class="absolute top-1/3 -right-24 h-96 w-96 rounded-full bg-purple-300/30 pointer-events-none"
    ></div>

    <div class="relative z-10 mx-auto max-w-7xl">

      <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-black text-purple-900 tracking-tight">
            Data Member
          </h1>

          <p class="text-purple-600 text-sm font-medium mt-1">
            Daftar member parkir
          </p>
        </div>

        <div class="flex items-center gap-2">
          <NuxtLink
            to="/petugas"
            class="inline-flex items-center gap-2 bg-white/80 hover:bg-pink-100 text-purple-800 font-bold px-5 py-2.5 rounded-2xl shadow-lg transition-all active:scale-95"
          >
            ← Kembali
          </NuxtLink>

          <NuxtLink
            to="/petugas/member/tambah"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-400 to-purple-400 hover:from-pink-500 hover:to-purple-500 text-white font-bold px-5 py-2.5 rounded-2xl shadow-lg transition-all active:scale-95"
          >
            <span class="text-lg leading-none">+</span>
            Tambah Member
          </NuxtLink>
        </div>
      </div>

      <!-- TABLE CARD -->
      <div
        class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl border border-white/60 overflow-hidden p-6"
      >
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse min-w-[900px]">

            <!-- TABLE HEADER -->
            <thead>
              <tr class="border-b border-slate-200">
                <th
                  class="py-4 px-4 text-xs font-bold uppercase tracking-wider text-slate-500"
                >
                  Kode
                </th>

                <th
                  class="py-4 px-4 text-xs font-bold uppercase tracking-wider text-slate-500"
                >
                  Nama Member
                </th>

                <th
                  class="py-4 px-4 text-xs font-bold uppercase tracking-wider text-slate-500"
                >
                  Perusahaan
                </th>

                <th
                  class="py-4 px-4 text-xs font-bold uppercase tracking-wider text-slate-500"
                >
                  Harga
                </th>

                <th
                  class="py-4 px-4 text-xs font-bold uppercase tracking-wider text-slate-500"
                >
                  Status
                </th>

                <th
                  class="py-4 px-4 text-xs font-bold uppercase tracking-wider text-slate-500"
                >
                  Expired
                </th>

                <th
                  class="py-4 px-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center"
                >
                  Aksi
                </th>
              </tr>
            </thead>

            <!-- TABLE BODY -->
            <tbody class="divide-y divide-slate-100">

              <!-- LOADING -->
              <tr v-if="loading">
                <td
                  colspan="7"
                  class="text-center py-12 text-purple-500 font-semibold"
                >
                  <div class="flex justify-center items-center gap-3">
                    <div
                      class="w-5 h-5 border-2 border-purple-400 border-t-transparent rounded-full animate-spin"
                    ></div>

                    Memuat data member...
                  </div>
                </td>
              </tr>

              <!-- ERROR -->
              <tr v-else-if="errorMessage">
                <td colspan="7" class="text-center py-12">
                  <div
                    class="mx-auto w-16 h-16 rounded-full bg-rose-100 flex items-center justify-center mb-4"
                  >
                    <svg
                      class="w-8 h-8 text-rose-500"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 3h15.58a2 2 0 001.74-3l-7.82-14a2 2 0 00-3.42 0z"
                      />
                    </svg>
                  </div>

                  <p class="text-pink-600 font-bold text-lg">
                    {{ errorMessage }}
                  </p>

                  <button
                    @click="ambilMember"
                    class="mt-4 bg-purple-400 hover:bg-purple-500 text-white font-bold px-6 py-2.5 rounded-xl transition"
                  >
                    Coba Lagi
                  </button>
                </td>
              </tr>

              <!-- DATA -->
              <template v-else>
                <tr
                  v-for="item in members"
                  :key="item.id"
                  class="hover:bg-pink-50/70 transition-colors"
                >
                  <!-- KODE -->
                  <td class="py-4 px-4 text-sm font-semibold text-slate-800">
                    {{ item.kode || '-' }}
                  </td>

                  <!-- NAMA -->
                  <td class="py-4 px-4 text-sm font-medium text-slate-700">
                    {{ item.nama || '-' }}
                  </td>

                  <!-- PERUSAHAAN -->
                  <td class="py-4 px-4 text-sm text-slate-500">
                    {{ item.perusahaan || '-' }}
                  </td>

                  <!-- HARGA -->
                  <td class="py-4 px-4 text-sm text-purple-600 font-bold">
                    Rp {{ formatHarga(item.harga) }}
                  </td>

                  <!-- STATUS -->
                  <td class="py-4 px-4">
                    <span
                      class="px-3 py-1 rounded-full text-xs font-extrabold tracking-wider"
                      :class="
                        item.status === 'lunas'
                          ? 'bg-emerald-100 text-emerald-700 border border-emerald-300'
                          : 'bg-rose-100 text-rose-700 border border-rose-300'
                      "
                    >
                      {{ (item.status || 'belum lunas').toUpperCase() }}
                    </span>
                  </td>

                  <!-- EXPIRED -->
                  <td class="py-4 px-4 text-sm text-slate-500">
                    {{ formatTanggal(item.expired_at) }}
                  </td>

                  <!-- AKSI -->
                  <td class="py-4 px-4">
                    <div class="flex justify-center items-center space-x-2">

                      <!-- BAYAR -->
                      <NuxtLink
                        v-if="item.status !== 'lunas'"
                        :to="{
                          path: '/transaksi/payment',
                          query: {
                            member_id: item.id
                          }
                        }"
                        class="bg-pink-400 hover:bg-pink-500 text-white p-2.5 rounded-xl shadow-sm transition-all hover:scale-105 inline-flex items-center justify-center"
                        title="Bayar"
                      >
                        <svg
                          class="w-4 h-4"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 2 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                          />
                        </svg>
                      </NuxtLink>

                      <!-- BAYAR DISABLED -->
                      <button
                        v-else
                        disabled
                        class="bg-slate-100 text-slate-300 p-2.5 rounded-xl cursor-not-allowed"
                        title="Member sudah lunas"
                      >
                        <svg
                          class="w-4 h-4"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 2 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                          />
                        </svg>
                      </button>

                      <!-- KARTU -->
                      <NuxtLink
                        :to="`/petugas/member/kartu/${item.id}`"
                        class="bg-purple-400 hover:bg-purple-500 text-white p-2.5 rounded-xl shadow-sm transition-all hover:scale-105 inline-flex items-center justify-center"
                        title="Kartu"
                      >
                        <svg
                          class="w-4 h-4"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                          />

                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                          />
                        </svg>
                      </NuxtLink>

                      <!-- EDIT (selalu aktif, walau sudah lunas) -->
                      <NuxtLink
                        :to="`/petugas/member/edit/${item.id}`"
                        class="bg-amber-400 hover:bg-amber-500 text-white p-2.5 rounded-xl shadow-sm transition-all hover:scale-105 inline-flex items-center justify-center"
                        title="Edit"
                      >
                        <svg
                          class="w-4 h-4"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                          />
                        </svg>
                      </NuxtLink>

                      <!-- HAPUS -->
                      <button
                        v-if="item.status !== 'lunas'"
                        @click="hapusMember(item.id)"
                        class="bg-rose-400 hover:bg-rose-500 text-white p-2.5 rounded-xl shadow-sm transition-all hover:scale-105 inline-flex items-center justify-center"
                        title="Hapus"
                      >
                        <svg
                          class="w-4 h-4"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                          />
                        </svg>
                      </button>

                      <!-- HAPUS DISABLED -->
                      <button
                        v-else
                        disabled
                        class="bg-slate-100 text-slate-300 p-2.5 rounded-xl cursor-not-allowed"
                        title="Member sudah lunas"
                      >
                        <svg
                          class="w-4 h-4"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                          />
                        </svg>
                      </button>

                    </div>
                  </td>
                </tr>

                <!-- EMPTY -->
                <tr v-if="members.length === 0">
                  <td
                    colspan="7"
                    class="text-center py-12 text-slate-400 font-medium"
                  >
                    Belum ada data member.
                  </td>
                </tr>
              </template>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const { $api } = useNuxtApp()

const members = ref([])
const loading = ref(true)
const errorMessage = ref('')

const getToken = () =>
  typeof window !== 'undefined'
    ? localStorage.getItem('token') || localStorage.getItem('auth_token')
    : null

const cekSesi = () => {
  if (import.meta.server || typeof window === 'undefined') return true

  const token = getToken()

  if (!token) {
    alert('Sesi Anda telah berakhir, silakan login kembali.')
    navigateTo('/login')
    return false
  }

  return true
}

/**
 * Format harga
 */
const formatHarga = (harga) => {
  return Number(harga || 0).toLocaleString('id-ID')
}

/**
 * Format tanggal
 */
const formatTanggal = (tanggal) => {
  if (!tanggal) return '-'

  const date = new Date(tanggal)

  if (isNaN(date.getTime())) {
    return '-'
  }

  return date.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

/**
 * Ambil data member
 */
const ambilMember = async () => {
  if (!cekSesi()) return

  loading.value = true
  errorMessage.value = ''

  try {
    const response = await $api.get('/members')

    const data = response?.data

    if (Array.isArray(data)) {
      members.value = data
    } else if (Array.isArray(data?.data)) {
      members.value = data.data
    } else if (Array.isArray(data?.members)) {
      members.value = data.members
    } else {
      members.value = []
    }
  } catch (error) {
    console.error('Gagal mengambil member:', error)

    if (error?.response?.status === 401 || error?.status === 401) {
      errorMessage.value =
        'Sesi login sudah tidak valid. Silakan login kembali.'

      navigateTo('/login')
    } else {
      errorMessage.value =
        error?.response?.data?.message ||
        error?.data?.message ||
        error?.message ||
        'Gagal mengambil data member.'
    }
  } finally {
    loading.value = false
  }
}

/**
 * Hapus member
 */
const hapusMember = async (id) => {
  if (!cekSesi()) return

  const yakin = confirm(
    'Apakah kamu yakin ingin menghapus member ini?'
  )

  if (!yakin) return

  try {
    await $api.delete(`/members/${id}`)

    alert('Member berhasil dihapus.')

    await ambilMember()
  } catch (error) {
    console.error('Gagal menghapus member:', error)

    if (error?.response?.status === 401 || error?.status === 401) {
      navigateTo('/login')
    } else {
      alert(
        error?.response?.data?.message ||
        error?.data?.message ||
        'Gagal menghapus member.'
      )
    }
  }
}

/**
 * Jalankan setelah browser siap
 */
onMounted(() => {
  ambilMember()
})
</script>