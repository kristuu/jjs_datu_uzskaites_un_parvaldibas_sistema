<template>
  <div ref="modalRef" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ title }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Modal } from 'bootstrap';

export default {
  props: ['modalId', 'title'],

  data() {
    return {
      bsModal: null
    }
  },

  mounted() {
    this.$nextTick(() => {
      this.bsModal = new Modal(this.$refs.modalRef);
    });
  },

  beforeUnmount() {
    if (this.bsModal !== null) {
      this.bsModal.dispose();
    }
  },

  methods: {
    openModal() {
      this.bsModal.show();
    },

    closeModal() {
      this.bsModal.hide();
    }
  }
}
</script>