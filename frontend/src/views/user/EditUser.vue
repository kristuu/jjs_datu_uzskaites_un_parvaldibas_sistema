<script>
import VueGoogleAutocomplete from 'vue-google-autocomplete';
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
  components: {
    VueGoogleAutocomplete,
  },
  methods: {
    async fetchUser() {
      try {
        const response = await axios.get(`/user/${this.$route.params.person_code}`);
        this.user = response.data.message;
        console.log(this.user);
      } catch (e) {
        console.log(e);
      }
    },
    async updateUser() {
      try {
        await axios.put(`/user/${this.$route.params.person_code}`, this.user);
        this.$router.push({ name: 'UserList' });
      } catch (e) {
        this.errorList = e.response.data.errors;
      }
    },
    getAddressData(addressData, placeResultData) {
      this.user.googleplaces_address_code = placeResultData.place_id;
    },
  },
  mounted() {
    this.fetchUser().catch(error => console.error(error));
  }
};
</script>

<template>
  <div class="container-fluid">
    <form @submit.prevent="updateUser" class="card">
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
        <label for="googleplaces_address_code" class="form-label">Address</label>
        <vue-google-autocomplete
            id="googleplaces_address_code"
            classname="form-control"
            placeholder="Sāciet rakstīt adresi..."
            v-on:placechanged="getAddressData"
            autocomplete="off">
        </vue-google-autocomplete>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</template>