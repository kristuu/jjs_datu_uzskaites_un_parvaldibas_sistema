<script>
import {Modal} from "bootstrap";
import axios from "@/services/axios";
import { vMaska } from 'maska';

export default {
  name: "UserEditModal",
  props: ['selectedUser'],
  directives: { maska: vMaska },
  data() {
    return {
      localUser: {...this.selectedUser}, // a copy of the prop into local data
      oldPersonCode: '',  // used to edit a user in case if person code has been changed too (should happen rarely)
      unmaskedData: { 'person_code': '', },
      errorList: {},
    };
  },
  methods: {
    openEditModal(user) {
      this.localUser = {...user};
      this.oldPersonCode = user.person_code;
      const form = document.getElementById("editUserForm");
      form.classList.remove('was-validated');

      if (!this.editModal) {
        this.$nextTick(() => {
          this.editModal = new Modal(document.getElementById('editUserModal'));
          this.editModal.show();
        })
      } else {
        this.editModal.show();
      }
    },
    onMaska(caller, field) {
      this.unmaskedData[field] = caller.detail.unmasked;
      console.log(this.unmaskedData);
    },
    async updateUser() {
      try {
        this.localUser.person_code = this.unmaskedData.person_code;
        await axios.put(`/user/${this.oldPersonCode}`, this.localUser);
        this.editModal.hide();
        this.$emit('fetchUsers', this.perPage);
      } catch (e) {
        this.errorList = e.response.data;
        console.log(this.errorList.name);
      }
    },
  },
  mounted() {
    // Your validation script goes here
    'use strict'

    const forms = document.querySelectorAll('.needs-validation')

    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
  },
}
</script>

<template>

  <!--  Pop-up modal for editing users -->
  <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
       aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <a class="navbar-brand" href="/"><img src="@/assets/logo-red.svg" height="45"/></a>
          <h5 class="modal-title mx-auto" id="exampleModalLabel">Lietotāja rediģēšana</h5>
          <button type="button" class="btn-close" data-dismiss="modal" @click="this.editModal.hide()"
                  aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
          <form id="editUserForm" @submit.prevent="updateUser" class="row g-3 text-start">
            <div class="col-lg-4">
              <div class="form-floating">
                <input v-model="localUser.name"
                       type="text"
                       v-maska data-maska="A A" data-maska-tokens="A:[A-ž]:multiple"
                       class="form-control"
                       :class="{ 'is-invalid' : errorList.name?.length > 0 }"
                       id="name"
                       placeholder="Vārds (-i)">
                <label for="name">Vārds (-i)</label>
                <template v-for="(error, index) in errorList.name">
                  <div v-if="errorList.name && errorList.name.length > 0"
                       class="invalid-feedback"
                       :key="index">
                    {{ error }}
                  </div>
                </template>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-floating">
                <input v-model="localUser.surname"
                       type="text"
                       v-maska data-maska="A A" data-maska-tokens="A:[A-ž]:multiple"
                       class="form-control"
                       :class="{ 'is-invalid' : errorList.surname?.length > 0 }"
                       id="surname"
                       placeholder="Uzvārds (-i)">
                <label for="surname">Uzvārds (-i)</label>
                <template v-for="(error, index) in errorList.surname">
                  <div v-if="errorList.surname && errorList.surname.length > 0"
                       class="invalid-feedback"
                       :key="index">
                    {{ error }}
                  </div>
                </template>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-floating">
                <input v-model="localUser.person_code"
                       inputmode="numeric" type="text"
                       v-maska data-maska-eager data-maska="######-#####"
                       @maska="caller => onMaska(caller, 'person_code')"
                       class="form-control"
                       :class="{ 'is-invalid' : errorList.person_code?.length > 0 }"
                       id="person_code"
                       placeholder="Personas kods">
                <label for="person_code">Personas kods</label>
                <template v-for="(error, index) in errorList.person_code">
                  <div v-if="errorList.person_code && errorList.person_code.length > 0"
                       class="invalid-feedback"
                       :key="index">
                    {{ error }}
                  </div>
                </template>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-floating">
                <input v-model="localUser.birthdate"
                       type="date"
                       class="form-control"
                       id="birthdate"
                       placeholder="Dzimšanas datums">
                <label for="birthdate">Dzimšanas datums</label>
              </div>
            </div>
            <div class="col-lg-4">
            <div class="form-floating">
                <input v-model="localUser.email"
                       type="email"
                       class="form-control"
                       id="email"
                       placeholder="E-pasta adrese">
                <label for="email">E-pasta adrese</label>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-floating">
                <input v-model="localUser.phone" type="text" class="form-control" id="phone" placeholder="Telefona nr.">
                <label for="phone">Telefona nr.</label>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-floating">
                <input v-model="localUser.iban_code" type="text" class="form-control" id="iban" placeholder="Bankas konta nr.">
                <label for="iban">Bankas konta nr.</label>
              </div>
            </div>
            <button type="submit" class="btn btn-primary col-6">Saglabāt</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</template>

<style scoped>
.is-invalid {
  border-color: #dc3545 !important;
  /* ... your other styles, with !important */
}
</style>