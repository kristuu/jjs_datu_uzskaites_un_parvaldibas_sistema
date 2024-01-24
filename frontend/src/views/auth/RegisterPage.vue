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
      password: "",
      password_confirmation: ""
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
          password: this.password,
          password_confirmation: this.password_confirmation
        });

        if (response.status === 200) {
          // Registration is successful
          console.log(response.data);
          this.$router.push({ name: 'LoginPage' }); // Or name of your login route
        }
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
  <div class="main-frame w-100 m-auto text-start">
    <form @submit.prevent="register" class="register-form">
      <img class="mb-4" src="../../assets/logo-red.svg" alt width="72"/>
      <h1 class="h3 mb-3 fw-normal">Reģistrācija</h1>
      <div class="form-floating">
        <input type="text" v-model="name" id="name" class="form-control" placeholder="Vārds (-i)" maxlength="60" required />
        <label for="name" class="fw-normal">Vārds (-i)</label>
      </div>
      <div class="form-floating">
        <input type="text" v-model="surname" id="surname" class="form-control" placeholder="Uzvārds (-i)" maxlength="60" required />
        <label for="surname" class="fw-normal">Uzvārds (-i)</label>
      </div>
      <div class="form-floating">
        <input type="text" v-model="person_code" id="person_code" class="form-control" placeholder="Personas kods (11 rakstzīmes, bez domuzīmes)" minlength="11" maxlength="11" required />
        <label for="person_code" class="fw-normal">Personas kods (11 rakstz. bez "-")</label>
      </div>
      <div class="form-floating">
        <input type="date" v-model="birthdate" id="birthdate" class="form-control" placeholder="Dzimšanas datums" required />
        <label for="birthdate" class="fw-normal">Dzimšanas datums</label>
      </div>
      <div class="form-floating">
        <input type="email" v-model="email" id="email" class="form-control" placeholder="E-pasta adrese" required />
        <label for="email" class="fw-normal">E-pasta adrese</label>
      </div>
      <div class="form-floating">
        <input type="password" v-model="password" id="password" class="form-control" placeholder="Parole" minlength="8" maxlength="255" required />
        <label for="password" class="fw-normal">Parole</label>
      </div>
      <div class="form-floating">
        <input type="password" v-model="password_confirmation" id="password_confirmation" class="form-control" placeholder="Parole" minlength="8" maxlength="255" required />
        <label for="password_confirmation" class="fw-normal">Parole (atkārtoti)</label>
      </div>
      <button type="submit" class="btn btn-primary w-100 py-2 mt-5">Reģistrēties</button>
    </form>
  </div>
</template>

<style scoped>
  .main-frame {
    max-width: 330px;
    padding: 1rem;
  }
</style>