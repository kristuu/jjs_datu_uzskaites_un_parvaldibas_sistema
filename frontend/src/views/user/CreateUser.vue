<script>
import axios from '@/services/axios';

export default {
  data() {
    return {
      errorList: '',
      user: {
        name: '',
        surname: '',
        person_code: '',
        birthdate: '',
        email: '',
        password: '',
        phone: '',
        googleplaces_address_code: '',
        iban_code: ''
      },
    };
  },
  methods: {
    createUser() {
      axios.post('/users', this.user)
          .then(response => {
            alert(response.data);

            /*this.user = {
              name: '',
              surname: '',
              person_code: '',
              birthdate: '',
              email: '',
              password: '',
              phone: '',
              googleplaces_address_code: '',
              iban_code: ''
            };*/
            this.errorList = '';

            // this.$router.push({ name: 'UserList' }); // redirect to User List
          })
          .catch(error => {
            if (error.response) {
              if (error.response.status === 422) {
                this.errorList = error.response.data.errors;
              }
            } else if (error.request) {
              console.log(error.request);
            } else {
              console.log('Kļūda: ', error.message);
            }
          });
    },
  },
};
</script>

<template>
  <div>
    <form @submit.prevent="createUser">
      <div class="mb-3">
        <label for="name" class="form-label">Vārds (-i)</label>
        <input v-model="user.name" type="text" class="form-control" id="name">
      </div>
      <div class="mb-3">
        <label for="surname" class="form-label">Uzvārds (-i)</label>
        <input v-model="user.surname" type="text" class="form-control" id="surname">
      </div>
      <div class="mb-3">
        <label for="person_code" class="form-label">Personas kods</label>
        <input v-model="user.person_code" type="text" class="form-control" id="person_code">
      </div>
      <div class="mb-3">
        <label for="birthdate" class="form-label">Dzimšanas datums</label>
        <input v-model="user.birthdate" type="date" class="form-control" id="birthdate">
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">E-pasta adrese</label>
        <input v-model="user.email" type="email" class="form-control" id="email">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input v-model="user.password" type="password" class="form-control" id="password">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</template>