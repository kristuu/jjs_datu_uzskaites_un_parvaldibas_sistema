<script>
import axios from '@/services/axios'

export default {
  data() {
    return {
      name: "",
      surname: "",
      person_code: "",
      birthdate: "",
      email: "",
      password: ""
    };
  },
  methods: {
    async register() {
      try {
        const response = await axios.post("/register", {
          name: this.name,
          surname: this.surname,
          person_code: this.person_code,
          birthdate: this.birthdate,
          email: this.email,
          password: this.password
        });
      } catch (e) {
        console.error("Radusies kļūda: ", e);

        if (e.response) {
          console.error("Kļūdas detalizēts apraksts: ", e.response.data);
        }
      }
    }
  }
};
</script>

<template>
  <div>
    <form @submit.prevent="register">
      <input type="text" v-model="name" class="form-control" placeholder="Vārds (-i)" maxlength="60" required />
      <input type="text" v-model="surname" class="form-control" placeholder="Uzvārds (-i)" maxlength="60" required />
      <input type="text" v-model="person_code" class="form-control" placeholder="Personas kods (11 rakstzīmes, bez domuzīmes)" minlength="11" maxlength="11" required />
      <input type="date" v-model="birthdate" class="form-control" placeholder="Dzimšanas datums" required />
      <input type="email" v-model="email" class="form-control" placeholder="E-pasta adrese" required />
      <input type="password" v-model="password" class="form-control" placeholder="Parole" minlength="8" maxlength="255" required />
      <button type="submit" class="btn-outline-primary">Reģistrēties</button>
    </form>
  </div>
</template>

<style scoped>

</style>