<script>
import {Modal} from "bootstrap";
import axios from "@/services/axios";

export default {
  name: "UserEditModal",
  props: ['selectedUser'],
  data() {
    return {
      localUser: { ...this.selectedUser } // a copy of the prop into local data
    }
  },
  methods: {
    openEditModal(user) {
      this.localUser = { ...user };

      if (!this.editModal) {
        this.$nextTick(() => {
          this.editModal = new Modal(document.getElementById('editUserModal'));
          this.editModal.show();
        })
      } else {
        this.editModal.show();
      }
    },
    async updateUser() {
      try {
        await axios.put(`/user/${this.localUser.person_code}`, this.localUser);
        this.editModal.hide();
        this.$emit('fetchUsers', this.perPage);
      } catch (e) {
        this.errorList = e.response.data.errors;
      }
    },
  }
}
</script>

<template>

  <!--  Pop-up modal for editing users -->
  <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <a class="navbar-brand" href="/"><img src="@/assets/logo-red.svg" height="45" /></a>
          <h5 class="modal-title mx-auto" id="exampleModalLabel">Lietotāja rediģēšana</h5>
          <button type="button" class="btn-close" data-dismiss="modal" @click="this.editModal.hide()" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="updateUser" class="row g-3 text-start">
            <div class="col-lg-4">
              <label for="name" class="form-label">Vārds (-i)</label>
              <input v-model="localUser.name" type="text" class="form-control" id="name">
            </div>
            <div class="col-lg-4">
              <label for="surname" class="form-label">Uzvārds (-i)</label>
              <input v-model="localUser.surname" type="text" class="form-control" id="surname">
            </div>
            <div class="col-lg-4">
              <label for="person_code" class="form-label">Personas kods</label>
              <input v-model="localUser.person_code" type="text" class="form-control" id="person_code">
            </div>
            <div class="col-lg-4">
              <label for="birthdate" class="form-label">Dzimšanas datums</label>
              <input v-model="localUser.birthdate" type="date" class="form-control" id="birthdate">
            </div>
            <div class="col-lg-4">
              <label for="email" class="form-label">E-pasta adrese</label>
              <input v-model="localUser.email" type="email" class="form-control" id="email">
            </div>
            <div class="col-lg-4">
              <label for="phone" class="form-label">Telefona nr.</label>
              <input v-model="localUser.phone" type="text" class="form-control" id="phone">
            </div>
            <div class="col-lg-6">
              <label for="iban" class="form-label">Bankas konta nr.</label>
              <input v-model="localUser.iban_code" type="text" class="form-control" id="iban">
            </div>
            <button type="submit" class="btn btn-primary col-6">Saglabāt</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</template>

<style scoped>

</style>