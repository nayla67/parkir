<template>
  <div
    class="relative min-h-screen bg-gradient-to-br from-pink-100 via-purple-100 to-blue-100 p-4 sm:p-6 lg:p-10 font-sans overflow-hidden flex items-center justify-center"
  >
    <!-- BACKGROUND DECORATION -->
    <div
      class="absolute -top-32 -left-32 h-[500px] w-[500px] rounded-full bg-pink-300/30 blur-[120px] pointer-events-none"
    ></div>

    <div
      class="absolute -bottom-32 -right-32 h-[500px] w-[500px] rounded-full bg-blue-300/30 blur-[120px] pointer-events-none"
    ></div>

    <div class="relative z-10 w-full max-w-6xl">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- FORM -->
        <div
          class="lg:col-span-7 bg-white/80 backdrop-blur-2xl rounded-3xl shadow-xl border border-white/60 p-6 sm:p-9 transition-all"
        >

          <!-- HEADER -->
          <div
            class="flex items-center justify-between mb-8 pb-5 border-b border-purple-100"
          >
            <div class="flex items-center gap-3.5">
              <div
                class="h-12 w-12 rounded-2xl bg-gradient-to-tr from-pink-400 to-purple-400 flex items-center justify-center text-white shadow-lg shadow-pink-300/30"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="22"
                  height="22"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                  <circle cx="9" cy="7" r="4" />
                  <line x1="19" y1="8" x2="19" y2="14" />
                  <line x1="22" y1="11" x2="16" y2="11" />
                </svg>
              </div>

              <div>
                <h1 class="text-2xl font-black text-purple-900 tracking-tight">
                  Tambah Member
                </h1>

                <p class="text-xs text-purple-500 font-medium">
                  Registrasi hak akses & langganan parkir baru
                </p>
              </div>
            </div>

            <NuxtLink
              to="/petugas/member/select"
              class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-pink-100 hover:bg-pink-200 text-purple-700 text-xs font-bold transition-all active:scale-95"
            >
              ✕ Batal
            </NuxtLink>
          </div>

          <!-- FORM INPUT -->
          <form @submit.prevent="handleSubmit" class="space-y-5">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

              <!-- KODE MEMBER -->
              <div>
                <label
                  class="block text-xs font-extrabold text-purple-700 uppercase tracking-wider mb-2"
                >
                  Kode Member Otomatis
                </label>

                <div class="relative group">
                  <span
                    class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-black text-purple-500"
                  >
                    #
                  </span>

                  <input
                    type="text"
                    v-model="formData.kode"
                    readonly
                    class="w-full rounded-2xl border border-purple-200 bg-purple-100/70 pl-9 pr-4 py-3 text-sm font-bold text-purple-900 shadow-sm cursor-not-allowed focus:outline-none"
                  />
                </div>

                <p class="mt-1.5 text-xs font-medium text-purple-500">
                  ✓ Kode member dibuat otomatis oleh sistem
                </p>
              </div>

              <!-- NAMA -->
              <div>
                <label
                  class="block text-xs font-extrabold text-purple-700 uppercase tracking-wider mb-2"
                >
                  Nama Lengkap
                </label>

                <div class="relative group">
                  <span
                    class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-purple-400"
                  >
                    👤
                  </span>

                  <input
                    type="text"
                    v-model="formData.nama"
                    placeholder="Masukkan nama member"
                    autocomplete="off"
                    class="w-full rounded-2xl border border-purple-200 bg-purple-50/40 pl-11 pr-4 py-3 text-sm font-bold text-purple-900 shadow-sm transition-all focus:bg-white focus:border-purple-400 focus:ring-4 focus:ring-purple-400/20 focus:outline-none"
                    required
                  />
                </div>
              </div>

              <!-- PERUSAHAAN -->
              <div class="sm:col-span-2">
                <label
                  class="block text-xs font-extrabold text-purple-700 uppercase tracking-wider mb-2"
                >
                  Perusahaan / Instansi
                </label>

                <div class="relative group">
                  <span
                    class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-purple-400"
                  >
                    🏢
                  </span>

                  <input
                    type="text"
                    v-model="formData.perusahaan"
                    placeholder="PT / Instansi kerja"
                    autocomplete="off"
                    class="w-full rounded-2xl border border-purple-200 bg-purple-50/40 pl-11 pr-4 py-3 text-sm font-bold text-purple-900 shadow-sm transition-all focus:bg-white focus:border-purple-400 focus:ring-4 focus:ring-purple-400/20 focus:outline-none"
                    required
                  />
                </div>
              </div>

              <!-- JUMLAH DIBAYAR -->
              <div class="sm:col-span-2">
                <label
                  class="block text-xs font-extrabold text-purple-700 uppercase tracking-wider mb-2"
                >
                  Jumlah Dibayar
                </label>

                <div class="relative group">
                  <span
                    class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-black text-purple-500"
                  >
                    Rp
                  </span>

                  <input
                    type="text"
                    inputmode="numeric"
                    :value="jumlahDibayarTampil"
                    @input="onInputUang"
                    placeholder="150.000"
                    autocomplete="off"
                    class="w-full rounded-2xl border border-purple-200 bg-purple-50/40 pl-11 pr-4 py-3 text-sm font-bold text-purple-900 shadow-sm transition-all focus:bg-white focus:border-purple-400 focus:ring-4 focus:ring-purple-400/20 focus:outline-none"
                    required
                  />
                </div>

                <!-- NOMINAL CEPAT -->
                <div class="mt-2 flex flex-wrap gap-2">
                  <button
                    v-for="nominal in nominalCepat"
                    :key="nominal"
                    type="button"
                    @click="setUang(nominal)"
                    class="px-3 py-1.5 rounded-xl bg-purple-100 hover:bg-purple-200 text-purple-700 text-xs font-bold transition-all active:scale-95"
                  >
                    {{ formatRupiah(nominal) }}
                  </button>
                </div>

                <p
                  v-if="jumlahDibayar > 0 && jumlahDibayar < formData.harga"
                  class="mt-2 text-xs font-semibold text-rose-500"
                >
                  Jumlah dibayar kurang Rp {{ formatRupiah(kurang) }}
                </p>

                <div
                  v-else-if="jumlahDibayar >= formData.harga"
                  class="mt-2 rounded-2xl border border-emerald-200 bg-emerald-50 p-3 flex items-center justify-between gap-3"
                >
                  <span class="text-xs font-semibold text-emerald-700">
                    ✓ Pembayaran cukup — status LUNAS
                  </span>

                  <span class="text-sm font-black text-emerald-800 whitespace-nowrap">
                    Kembalian: Rp {{ formatRupiah(kembalian) || 0 }}
                  </span>
                </div>
              </div>

              <!-- INFORMASI -->
              <div
                class="sm:col-span-2 rounded-2xl border border-purple-200 bg-purple-50/70 p-4 text-xs font-semibold text-purple-700"
              >
                ℹ️ Biaya langganan (Rp 150.000/bulan) dan masa berlaku
                (1 bulan dari sekarang) akan diisi otomatis oleh sistem.
                Status akan otomatis LUNAS jika jumlah dibayar mencukupi.
              </div>

            </div>

            <!-- SUBMIT -->
            <div class="pt-4 mt-8 border-t border-purple-100">
              <button
                type="submit"
                :disabled="isSubmitting || jumlahDibayar < formData.harga"
                class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-pink-400 via-purple-400 to-blue-400 hover:from-pink-500 hover:to-purple-500 text-white font-extrabold text-sm shadow-xl shadow-purple-300/30 transition-all hover:scale-[1.01] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{
                  isSubmitting
                    ? 'Mendaftarkan Member...'
                    : 'Simpan & Terbitkan Kartu'
                }}
              </button>
            </div>

          </form>
        </div>

        <!-- PREVIEW -->
        <div class="lg:col-span-5 lg:sticky lg:top-10 space-y-4">

          <div
            class="text-xs font-extrabold text-purple-700 uppercase tracking-widest flex items-center gap-2"
          >
            <span
              class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"
            ></span>

            Realtime Preview Pass
          </div>

          <!-- CARD PREVIEW -->
          <div
            class="relative w-full aspect-[1.58/1] rounded-3xl p-6 sm:p-7 text-white shadow-xl overflow-hidden border border-white/60 bg-gradient-to-tr from-purple-200 via-pink-100 to-blue-200 flex flex-col justify-between"
          >

            <div
              class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-white/30 blur-2xl pointer-events-none"
            ></div>

            <!-- PREVIEW HEADER -->
            <div class="flex items-start justify-between relative z-10">
              <div class="flex items-center gap-2">
                <div
                  class="h-7 w-7 rounded-lg bg-white/40 border border-white/60 backdrop-blur-md flex items-center justify-center text-xs font-black text-purple-800"
                >
                  P
                </div>

                <span class="text-xs font-bold tracking-wider text-purple-800">
                  MEMBER PASS
                </span>
              </div>

              <span
                class="font-mono text-xs font-black tracking-widest px-3 py-1 rounded-full bg-white/40 backdrop-blur-md border border-white/60 text-purple-800 shadow-inner"
              >
                {{ formData.kode || 'MBR-000' }}
              </span>
            </div>

            <!-- PREVIEW DATA -->
            <div class="relative z-10 space-y-1 my-auto pt-2">
              <h2
                class="text-xl sm:text-2xl font-black tracking-tight text-purple-900 drop-shadow-sm truncate"
              >
                {{ formData.nama || 'Nama Member' }}
              </h2>

              <p class="text-xs font-medium text-purple-700 truncate">
                🏢 {{ formData.perusahaan || 'Nama Perusahaan / Instansi' }}
              </p>
            </div>

            <!-- PREVIEW FOOTER -->
            <div
              class="relative z-10 pt-3 border-t border-white/50 grid grid-cols-2 gap-2 items-end text-xs"
            >

              <div>
                <span
                  class="text-[10px] uppercase tracking-wider text-purple-700/80 font-semibold block"
                >
                  Total Tagihan
                </span>

                <span class="font-extrabold text-sm text-purple-900">
                  Rp {{ formatRupiah(formData.harga) }}
                </span>

                <span
                  v-if="kembalian > 0"
                  class="block mt-1 text-[10px] font-bold text-emerald-700"
                >
                  Kembalian Rp {{ formatRupiah(kembalian) }}
                </span>
              </div>

              <div class="text-right">
                <span
                  class="text-[10px] uppercase tracking-wider text-purple-700/80 font-semibold block"
                >
                  Status / Expired
                </span>

                <div class="flex items-center justify-end gap-1.5 mt-0.5">

                  <span
                    class="px-2 py-0.5 rounded-md text-[9px] font-black tracking-wider border"
                    :class="
                      jumlahDibayar >= formData.harga
                        ? 'bg-emerald-200/70 text-emerald-900 border-emerald-300/70'
                        : 'bg-pink-200/70 text-pink-900 border-pink-300/70'
                    "
                  >
                    {{
                      jumlahDibayar >= formData.harga
                        ? 'LUNAS'
                        : 'BELUM'
                    }}
                  </span>

                  <span
                    class="font-mono text-[10px] font-bold text-purple-800"
                  >
                    1 bulan
                  </span>

                </div>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isSubmitting: false,
      jumlahDibayar: 0,
      jumlahDibayarTampil: '',
      nominalCepat: [150000, 200000, 250000, 300000],

      formData: {
        kode: '',
        nama: '',
        perusahaan: '',
        harga: 150000,
        status: 'belum',
        expired_at: ''
      }
    }
  },

  computed: {
    kurang() {
      return Math.max(this.formData.harga - this.jumlahDibayar, 0)
    },

    kembalian() {
      return Math.max(this.jumlahDibayar - this.formData.harga, 0)
    }
  },

  async mounted() {
    await this.generateKodeMember()
  },

  methods: {
    // FORMAT ANGKA KE RIBUAN (150000 -> 150.000)
    formatRupiah(angka) {
      return angka ? Number(angka).toLocaleString('id-ID') : ''
    },

    // INPUT UANG: HANYA ANGKA + OTOMATIS TITIK RIBUAN
    onInputUang(e) {
      const angka = e.target.value.replace(/\D/g, '')

      this.jumlahDibayar = angka ? parseInt(angka, 10) : 0
      this.jumlahDibayarTampil = this.formatRupiah(this.jumlahDibayar)

      // paksa tampilan input ikut ter-format
      e.target.value = this.jumlahDibayarTampil
    },

    // ISI CEPAT DARI TOMBOL NOMINAL
    setUang(nominal) {
      this.jumlahDibayar = nominal
      this.jumlahDibayarTampil = this.formatRupiah(nominal)
    },

    // GENERATE KODE MEMBER BERURUTAN
    async generateKodeMember() {
      try {
        const { $api } = useNuxtApp()

        const response = await $api.get('/members')

        // Menyesuaikan response dari API
        const members = Array.isArray(response)
          ? response
          : response.data || []

        let nomorTerakhir = 0

        members.forEach((member) => {
          const kode = member.kode || ''

          if (kode.startsWith('MBR-')) {
            const nomor = parseInt(
              kode.replace('MBR-', ''),
              10
            )

            if (!isNaN(nomor) && nomor > nomorTerakhir) {
              nomorTerakhir = nomor
            }
          }
        })

        const nomorBaru = nomorTerakhir + 1

        this.formData.kode =
          'MBR-' + String(nomorBaru).padStart(3, '0')

      } catch (err) {
        console.error(
          'Gagal mengambil kode member:',
          err
        )

        this.formData.kode = 'MBR-001'
      }
    },

    async handleSubmit() {
      if (this.jumlahDibayar < this.formData.harga) {
        alert(
          'Jumlah dibayar belum mencukupi harga langganan.'
        )

        return
      }

      this.isSubmitting = true

      const { $api } = useNuxtApp()

      this.formData.status =
        this.jumlahDibayar >= this.formData.harga
          ? 'lunas'
          : 'belum'

      const expired = new Date()

      expired.setMonth(
        expired.getMonth() + 1
      )

      this.formData.expired_at =
        expired.toISOString().split('T')[0]

      try {
        await $api.post('/members', {
          ...this.formData,
          jumlah_dibayar: this.jumlahDibayar,
          kembalian: this.kembalian
        })

        alert(
          `Berhasil! Member baru telah ditambahkan.\nKembalian: Rp ${this.formatRupiah(this.kembalian) || 0}`
        )

        this.$router.push(
          '/petugas/member/select'
        )

      } catch (err) {
        console.error(err)

        if (err.response?.status === 401) {
          this.$router.push('/login')
          return
        }

        alert(
          err?.response?.data?.message ||
          'Gagal menyimpan data.'
        )

      } finally {
        this.isSubmitting = false
      }
    }
  }
}
</script>