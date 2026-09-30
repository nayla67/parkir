<template>
  <!-- BACKGROUND -->
  <div
    class="relative min-h-screen overflow-hidden bg-gradient-to-br from-pink-100 via-purple-100 to-blue-100 p-4 font-sans sm:p-6 lg:p-8"
  >

    <!-- ORNAMEN BACKGROUND -->
    <div
      class="pointer-events-none absolute -left-24 -top-24 h-96 w-96 rounded-full bg-pink-300/30"
    ></div>

    <div
      class="pointer-events-none absolute -right-24 top-1/3 h-96 w-96 rounded-full bg-blue-300/30"
    ></div>

    <!-- CONTAINER -->
    <div class="relative z-10 mx-auto max-w-md">

      <!-- CARD FORM -->
      <div
        class="overflow-hidden rounded-3xl border border-white/70 bg-white p-6 shadow-2xl sm:p-8"
      >

        <!-- HEADER & KEMBALI -->
        <div class="mb-6 flex items-center justify-between">

          <button
            type="button"
            @click="goBack"
            class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 transition-colors hover:text-purple-600"
          >
            <svg
              class="h-4 w-4"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M10 19l-7-7m0 0l7-7m-7 7h18"
              />
            </svg>

            Kembali
          </button>

        </div>

        <!-- TITLE -->
        <div class="mb-6">
          <h1
            class="text-2xl font-black tracking-tight text-slate-900"
          >
            Edit Data Member
          </h1>

          <p class="mt-1 text-xs text-slate-500">
            Perbarui informasi data member parkir
          </p>
        </div>

        <!-- FORM -->
        <form
          @submit.prevent="updateMember"
          class="space-y-4"
        >

          <!-- NAMA -->
          <div>
            <label
              class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700"
            >
              Nama Member
            </label>

            <input
              v-model="form.nama"
              type="text"
              class="w-full rounded-xl border border-purple-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20"
              required
            />
          </div>

          <!-- PERUSAHAAN -->
          <div>
            <label
              class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700"
            >
              Nama Perusahaan
            </label>

            <input
              v-model="form.perusahaan"
              type="text"
              class="w-full rounded-xl border border-purple-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20"
              required
            />
          </div>

          <!-- TOMBOL SIMPAN -->
          <button
            type="submit"
            :disabled="saving"
            class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-3.5 text-sm font-bold text-white shadow-lg transition-all hover:bg-emerald-600 active:scale-95 disabled:cursor-not-allowed disabled:opacity-60"
          >
            <svg
              v-if="saving"
              class="h-4 w-4 animate-spin"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
              ></circle>

              <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
              ></path>
            </svg>

            {{ saving ? 'Menyimpan...' : 'Simpan Perubahan' }}
          </button>

        </form>

      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

// ======================================================
// TANPA SIDEBAR / LAYOUT
// ======================================================

definePageMeta({
  layout: false,
})

// ======================================================
// ROUTER & API
// ======================================================

const route = useRoute()
const router = useRouter()
const { $api } = useNuxtApp()

// ======================================================
// ID MEMBER
// ======================================================

const memberId = route.params.id

// ======================================================
// STATE
// ======================================================

const loading = ref(false)
const saving = ref(false)

// ======================================================
// TOKEN
// ======================================================

const getToken = () => {
  if (typeof window === 'undefined') {
    return null
  }

  return (
    localStorage.getItem('token') ||
    localStorage.getItem('auth_token')
  )
}

// ======================================================
// AUTH HEADERS
// ======================================================

const authHeaders = () => {
  const token = getToken()

  return token
    ? {
        Authorization: `Bearer ${token}`,
      }
    : {}
}

// ======================================================
// CEK SESI
// ======================================================

const cekSesi = () => {
  if (!getToken()) {
    alert(
      'Sesi Anda telah berakhir, silakan login kembali'
    )

    navigateTo('/login')

    return false
  }

  return true
}

// ======================================================
// FORM
// ======================================================

const form = ref({
  kode: '',
  nama: '',
  perusahaan: '',
  status: 'belum',
  expired_at: '',
})

// ======================================================
// AMBIL DATA MEMBER
// ======================================================

const fetchMemberData = async () => {
  if (!memberId) {
    alert('ID member tidak ditemukan')
    return
  }

  if (!cekSesi()) {
    return
  }

  loading.value = true

  try {
    const response = await $api.get(
      `/members/${memberId}`,
      {
        headers: authHeaders(),
      }
    )

    const data =
      response.data?.data ??
      response.data

    form.value = {
      kode: data?.kode ?? '',
      nama: data?.nama ?? '',
      perusahaan: data?.perusahaan ?? '',
      status: data?.status ?? 'belum',
      expired_at: data?.expired_at ?? '',
    }

  } catch (error) {
    console.error(
      'Gagal mengambil data detail member:',
      error
    )

    if (
      error?.response?.status === 401 ||
      error?.status === 401
    ) {
      navigateTo('/login')
      return
    }

    alert(
      error?.response?.data?.message ||
      'Gagal mengambil data member.'
    )

  } finally {
    loading.value = false
  }
}

// ======================================================
// KEMBALI
// ======================================================

const goBack = () => {
  if (
    typeof window !== 'undefined' &&
    window.history.length > 1
  ) {
    router.back()
    return
  }

  // Fallback
  router.push('/petugas/member/select')
}

// ======================================================
// UPDATE MEMBER
// ======================================================

const updateMember = async () => {
  if (!cekSesi()) {
    return
  }

  if (!memberId) {
    alert('ID member tidak ditemukan')
    return
  }

  saving.value = true

  try {
    await $api.put(
      `/members/${memberId}`,
      form.value,
      {
        headers: authHeaders(),
      }
    )

    alert(
      'Data member berhasil diperbarui'
    )

    // Kembali ke halaman sebelumnya
    goBack()

  } catch (error) {
    console.error(
      'Error update:',
      error?.response?.data
    )

    if (
      error?.response?.status === 401 ||
      error?.status === 401
    ) {
      navigateTo('/login')
      return
    }

    const errData =
      error?.response?.data

    if (errData?.message) {
      alert(
        `Gagal: ${errData.message}`
      )

    } else if (errData?.errors) {
      alert(
        `Gagal: ${Object.values(errData.errors)
          .flat()
          .join(', ')}`
      )

    } else {
      alert(
        'Gagal memperbarui data! Periksa koneksi API.'
      )
    }

  } finally {
    saving.value = false
  }
}

// ======================================================
// MOUNT
// ======================================================

onMounted(() => {
  fetchMemberData()
})
</script>