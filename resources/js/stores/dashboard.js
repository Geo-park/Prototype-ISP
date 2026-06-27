// Store dashboard — akan diisi oleh Dev A
import { defineStore } from 'pinia';

export const useDashboardStore = defineStore('dashboard', {
  state: () => ({
    stats: null,
    revenueChart: [],
    pelangganChart: [],
    statusPembayaran: null,
    invoiceTerbaru: [],
    aktivitasLog: [],
    statusJaringan: null,
    loading: false,
    error: null,
  }),

  actions: {
    // Placeholder — Dev A akan mengisi fetch data dari API
    setLoading(val) {
      this.loading = val;
    },
    setError(err) {
      this.error = err;
    },
  },
});
