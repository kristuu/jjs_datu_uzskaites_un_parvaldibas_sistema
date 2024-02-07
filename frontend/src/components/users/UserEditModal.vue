<script>
import {Modal} from "bootstrap";
import axios from "@/services/axios";

import { format } from 'date-fns';
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
      this.localUser.phone = this.localUser.phone ?? '';
      this.oldPersonCode = user.person_code;
      const form = document.getElementById("editUserForm");
      this.errorList = {};
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
    },
    async updateUser() {
      try {
        this.localUser.person_code = this.unmaskedData.person_code;
        this.localUser.birthdate = format(new Date(this.localUser.birthdate), 'yyyy-MM-dd HH:mm:ss'); // format date from VueDatePicker format to MySQL database format
        await axios.put(`/user/${this.oldPersonCode}`, this.localUser);
        this.editModal.hide();
        this.$emit('fetchUsers', this.perPage);
      } catch (e) {
        this.errorList = e.response.data;

        console.log(e);
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
                       maxlength="60"
                       v-maska data-maska="A A" data-maska-tokens="A:[A-ž\s\-]:multiple"
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
                       maxlength="60"
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
              <VueDatePicker v-model="localUser.birthdate"
                             locale="lv"
                             position="left"
                             cancelText="Atcelt"
                             selectText="Saglabāt"
                             :enable-time-picker="false"
                             :format="'dd.MM.yyyy'"
                             auto-apply
                             @internal-model-change="closeMenu">
                <template #dp-input="{ value }">
                  <div class="form-floating">
                    <input id="birthdate"
                           type="text"
                           :value="value"
                           class="form-control"
                           :class="{ 'is-invalid' : errorList.birthdate?.length > 0 }"
                           placeholder="Dzimšanas datums"
                           autocomplete="off" readonly />
                    <label for="birthdate">Dzimšanas datums</label>
                    <template v-for="(error, index) in errorList.birthdate">
                      <div v-if="errorList.birthdate && errorList.birthdate.length > 0"
                           class="invalid-feedback"
                           :key="index">
                        {{ error }}
                      </div>
                    </template>
                  </div>
                </template>
              </VueDatePicker>
            </div>
            <div class="col-lg-4">
              <div class="form-floating">
                <input v-model="localUser.email"
                       inputmode="email" type="email"
                       class="form-control"
                       :class="{ 'is-invalid' : errorList.email?.length > 0 }"
                       id="email"
                       placeholder="E-pasta adrese">
                <label for="email">E-pasta adrese</label>
                <template v-for="(error, index) in errorList.email">
                  <div v-if="errorList.email && errorList.email.length > 0"
                       class="invalid-feedback"
                       :key="index">
                    {{ error }}
                  </div>
                </template>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group form-floating">
                <vue-tel-input v-model="localUser.phone"
                               class="form-control"
                               :class="{ 'is-invalid' : errorList.phone?.length > 0 }"
                               :auto-format="true"
                               autocomplete="off"
                               mode="international">
                  <template #input="{ props, actions, value, update }">
                    <input ref="phone" v-on="{ ...actions }" v-bind="props"
                           :value="value" @input="update($event.target.value)"
                           class="form-control" placeholder=" "/>
                  </template>
                </vue-tel-input>
                <label for="phone">Telefona nr.</label>
              </div>
              <template v-for="(error, index) in errorList.phone">
                <div v-if="errorList.phone && errorList.phone.length > 0"
                     class="invalid-feedback"
                     :key="index">
                  {{ error }}
                </div>
              </template>
            </div>
            <div class="col-lg-6">
              <div class="form-floating">
                <input v-model="localUser.iban_code"
                       type="text"
                       class="form-control"
                       :class="{ 'is-invalid' : errorList.iban_code?.length > 0 }"
                       id="iban_code"
                       placeholder="IBAN (starptautiskais bankas konta numurs)">
                <label for="iban_code">IBAN (starptautiskais bankas konta numurs)</label>
                <template v-for="(error, index) in errorList.iban_code">
                  <div v-if="errorList.iban_code && errorList.iban_code.length > 0"
                       class="invalid-feedback"
                       :key="index">
                    {{ error }}
                  </div>
                </template>
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
}

.invalid-feedback {
  display: block;
}
</style>