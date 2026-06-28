// Store invoice — akan diisi oleh Dev A
import { defineStore } from 'pinia';

export const useInvoiceStore = defineStore('invoice', {
  state: () => ({
    invoices: [],
    currentInvoice: null,
    loading: false,
    error: null,
  }),

  actions: {
    // Placeholder — Dev A akan mengisi fetch/payment logic
    setLoading(val) {
      this.loading = val;
    },
    setError(err) {
      this.error = err;
    },
  },
});
