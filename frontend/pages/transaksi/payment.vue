<template>
  <div class="relative min-h-screen bg-gradient-to-br from-pink-100 via-purple-100 to-sky-100 p-4 sm:p-6 lg:p-8 font-sans overflow-hidden">
    
    <div class="absolute -top-24 -left-24 h-96 w-96 rounded-full bg-pink-300/30 pointer-events-none"></div>
    <div class="absolute top-1/3 -right-24 h-96 w-96 rounded-full bg-sky-300/30 pointer-events-none"></div>

    <div class="relative z-10 mx-auto max-w-xl space-y-6">
      
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl sm:text-3xl font-black text-neutral-700 tracking-tight">Pembayaran Member</h1>
          <p class="text-xs sm:text-sm text-neutral-500 font-medium mt-0.5">Selesaikan transaksi pembayaran langganan parkir</p>
        </div>
        <button
          @click="kembali"
          class="inline-flex items-center gap-2 rounded-2xl border border-white/70 bg-white/70 backdrop-blur-sm px-4 py-2 text-xs font-bold text-neutral-600 shadow-lg transition-all hover:bg-white active:scale-95"
        >
          <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Kembali
        </button>
      </div>

      <div class="overflow-hidden rounded-3xl border border-white/70 bg-white shadow-2xl">
        
        <div class="bg-gradient-to-r from-pink-300 via-purple-300 to-sky-300 p-6 text-neutral-800">
          <div class="flex items-center justify-between mb-4">
            <span class="rounded-lg bg-white/40 px-2.5 py-1 font-mono text-xs font-bold tracking-wider border border-white/60">
              {{ memberData.kode || 'KODE-MEMBER' }}
            </span>
            <span class="text-xs font-semibold text-rose-700 bg-white/50 px-2.5 py-1 rounded-full border border-white/60">
              Status: Belum Lunas
            </span>
          </div>

          <div class="space-y-1">
            <h2 class="text-xl font-bold tracking-wide text-neutral-800">{{ memberData.nama || '-' }}</h2>
            <p class="text-xs text-neutral-600 flex items-center gap-1.5">
              <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              {{ memberData.perusahaan || 'Perusahaan Tidak Diisi' }}
            </p>
          </div>
        </div>

        <div class="p-6 space-y-5">
          
          <div class="flex items-center justify-between rounded-2xl bg-purple-50 p-4 border border-purple-100">
            <div>
              <p class="text-xs font-bold uppercase tracking-wider text-purple-500">Total Tagihan</p>
              <p class="text-2xl font-black text-neutral-800">Rp {{ formatHarga(memberData.tagihan) }}</p>
            </div>
            <div class="h-10 w-10 rounded-xl bg-purple-200/60 flex items-center justify-center text-purple-500">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-neutral-600 uppercase tracking-wider mb-2">Metode Pembayaran</label>
            <div class="grid grid-cols-2 gap-3">
              <button
                type="button"
                @click="metodeBayar = 'Tunai'"
                class="flex items-center justify-center gap-2 rounded-xl p-3 border text-xs font-bold transition-all"
                :class="metodeBayar === 'Tunai' ? 'border-purple-300 bg-purple-50 text-purple-600 ring-2 ring-purple-200' : 'border-neutral-200 bg-white text-neutral-500 hover:bg-neutral-50'"
              >
                💵 Tunai (Cash)
              </button>
              <button
                type="button"
                @click="metodeBayar = 'QRIS'"
                class="flex items-center justify-center gap-2 rounded-xl p-3 border text-xs font-bold transition-all"
                :class="metodeBayar === 'QRIS' ? 'border-purple-300 bg-purple-50 text-purple-600 ring-2 ring-purple-200' : 'border-neutral-200 bg-white text-neutral-500 hover:bg-neutral-50'"
              >
                📱 QRIS / Non-Tunai
              </button>
            </div>
          </div>

          <div v-if="metodeBayar === 'Tunai'" class="space-y-3">
            <div>
              <label class="block text-xs font-bold text-neutral-600 uppercase tracking-wider mb-1.5">Jumlah Uang Dibayar</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-neutral-400">Rp</span>
                <input
                  v-model.number="jumlahBayar"
                  type="number"
                  placeholder="0"
                  class="w-full rounded-xl border border-neutral-200 bg-white pl-11 pr-4 py-3 text-base font-bold text-neutral-800 shadow-sm focus:border-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-200"
                />
              </div>
            </div>

            <div class="flex flex-wrap gap-2">
              <button
                @click="jumlahBayar = Number(memberData.tagihan)"
                type="button"
                class="rounded-lg bg-purple-50 px-2.5 py-1 text-xs font-semibold text-purple-500 hover:bg-purple-100 transition-colors"
              >
                Uang Pas
              </button>
              <button
                v-for="nominal in shortcutNominal"
                :key="nominal"
                @click="jumlahBayar = nominal"
                type="button"
                class="rounded-lg bg-purple-50 px-2.5 py-1 text-xs font-semibold text-purple-500 hover:bg-purple-100 transition-colors"
              >
                Rp {{ formatHarga(nominal) }}
              </button>
            </div>

            <div 
              class="flex items-center justify-between rounded-xl p-4 border transition-all"
              :class="isKurang 
                ? 'bg-rose-50 border-rose-200 text-rose-600' 
                : 'bg-teal-50 border-teal-200 text-teal-700'"
            >
              <div>
                <p class="text-xs font-bold uppercase tracking-wider">
                  {{ isKurang ? 'Kurang Pembayaran' : 'Kembalian' }}
                </p>
                <p class="text-xl font-bold">
                  Rp {{ formatHarga(Math.abs(kembalian)) }}
                </p>
              </div>
              <span v-if="isKurang" class="text-xs font-bold bg-rose-200/60 px-2.5 py-1 rounded-md text-rose-700">
                Uang Belum Cukup
              </span>
              <span v-else class="text-xs font-bold bg-teal-200/60 px-2.5 py-1 rounded-md text-teal-700">
                Siap Transaksi
              </span>
            </div>
          </div>

          <button
            @click="prosesKonfirmasi"
            :disabled="isLoading"
            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-pink-300 via-purple-300 to-sky-300 hover:from-pink-400 hover:via-purple-400 hover:to-sky-400 py-3.5 text-sm font-bold text-neutral-800 shadow-lg transition-all active:scale-95 disabled:bg-neutral-200 disabled:text-neutral-400 disabled:cursor-not-allowed disabled:shadow-none"
          >
            <svg v-if="!isLoading" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span v-if="isLoading">Memproses...</span>
            <span v-else>Konfirmasi Pembayaran</span>
          </button>

        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const { $api } = useNuxtApp()

const memberData = ref({
  id: '',
  kode: '',
  nama: '',
  perusahaan: '',
  tagihan: 0,
  dibayar: 0
})

const metodeBayar = ref('Tunai')
const jumlahBayar = ref(0)
const isLoading = ref(false)

// SYNC ROUTE QUERY KE STATE
watch(() => route.query, (newQuery) => {
  const memberId = Number(newQuery.member_id || newQuery.id) || ''
  const nominalTagihan = Number(newQuery.tagihan) || 0

  memberData.value = {
    id: memberId,
    kode: newQuery.kode || '',
    nama: newQuery.nama || '',
    perusahaan: newQuery.perusahaan || '',
    tagihan: nominalTagihan,
    dibayar: Number(newQuery.dibayar) || 0
  }
  jumlahBayar.value = nominalTagihan
}, { immediate: true })

// Ambil detail member (id query) di sisi client agar tagihan & nama akurat
onMounted(async () => {
  const memberId = Number(route.query.member_id || route.query.id)

  if (!memberId) return

  try {
    const response = await $api.get(`/members/${memberId}`)
    const data = response?.data

    if (data && data.id) {
      const tagihan = Number(data.harga) || 0

      memberData.value = {
        id: Number(data.id),
        kode: data.kode || '',
        nama: data.nama || '',
        perusahaan: data.perusahaan || '',
        tagihan,
        dibayar: 0
      }
      jumlahBayar.value = tagihan
    }
  } catch (error) {
    console.error('Gagal memuat detail member:', error)
  }
})

const shortcutNominal = computed(() => {
  const t = memberData.value.tagihan
  if (t <= 50000) return [50000, 100000]
  if (t <= 100000) return [100000, 200000]
  return [t + 50000, t + 100000]
})

const formatHarga = (val) => Number(val || 0).toLocaleString('id-ID')

const kembalian = computed(() => {
  if (metodeBayar.value === 'QRIS') return 0
  const bayar = Number(jumlahBayar.value) || 0
  const tagihan = Number(memberData.value.tagihan) || 0
  return bayar - tagihan
})

const isKurang = computed(() => {
  if (metodeBayar.value === 'QRIS') return false
  const bayar = Number(jumlahBayar.value) || 0
  const tagihan = Number(memberData.value.tagihan) || 0
  return bayar < tagihan
})

const kembali = () => {
  navigateTo('/petugas/member/select')
}

// PROSES SIMPAN KE BACKEND
const prosesKonfirmasi = async () => {
  const memberId = Number(memberData.value.id)

  if (!memberId) {
    alert('ID member tidak ditemukan di URL. Silakan kembali ke halaman member dan pilih kembali member yang akan dibayar.')
    return
  }

  if (isKurang.value && metodeBayar.value !== 'QRIS') {
    alert('Uang dibayar belum cukup untuk melunasi tagihan.')
    return
  }

  isLoading.value = true

  try {
    const nominalSekarang = metodeBayar.value === 'QRIS'
      ? Number(memberData.value.tagihan) || 0
      : Number(jumlahBayar.value) || 0

    const sudahDibayar = Number(memberData.value.dibayar) || 0
    const dibayarTotal = sudahDibayar + nominalSekarang
    const tagihan = Number(memberData.value.tagihan) || 0

    const isLunas = dibayarTotal >= tagihan

    // Payload: angka murni (tanpa "Rp"), member_id bertipe number,
    // metode pembayaran lowercase sesuai backend ('cash' / 'qris')
    const payload = {
      member_id: memberId,
      jumlah_bayar: nominalSekarang,
      dibayar: dibayarTotal,
      total_tagihan: tagihan,
      status: isLunas ? 'lunas' : 'belum',
      metode_pembayaran: metodeBayar.value === 'QRIS' ? 'qris' : 'cash'
    }

    await $api.put(`/members/${memberId}/lunas`, payload)

    alert(isLunas
      ? `Pembayaran member ${memberData.value.nama || `ID ${memberId}`} LUNAS!`
      : `Pembayaran sebagian tercatat. Status ${memberData.value.nama || `ID ${memberId}`} masih BELUM LUNAS.`)

    navigateTo('/petugas/member/select')
  } catch (error) {
    console.error('Error bayar:', error)

    const status = error?.response?.status || error?.status

    if (status === 401) {
      navigateTo('/login')
      return
    }

    const pesan =
      error?.response?.data?.message ||
      error?.data?.message ||
      'Gagal memproses pembayaran. Periksa koneksi ke Server Backend!'

    alert(pesan)
  } finally {
    isLoading.value = false
  }
}
</script>