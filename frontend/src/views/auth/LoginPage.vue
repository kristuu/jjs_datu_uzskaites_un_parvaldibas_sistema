<script>
import axios from '@/services/axios'

export default {
  data() {
    return {
      email: "",
      password: ""
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post("/login", {
          email: this.email,
          password: this.password
        });

        // if response delivers a token, it gets stored in local storage
        if (response.data.token) {
          localStorage.setItem('token', response.data.token);
          axios.defaults.headers.authorization = `Bearer ${response.data.token}`;

          // commiting a mutation to update 'authorized' state
          this.$store.commit('LOGIN');

          console.log(response.data.user);
          // redirecting to home page
          this.$router.push('/');
        }
      } catch (e) {
        console.error("Radusies kļūda: ", e);
      }
    }
  }
};
</script>

<template>
  <div class="main-frame w-100 m-auto text-start">
    <form @submit.prevent="login">
      <img class="mb-4" src="../../assets/logo-red.svg" alt width="72"/>
      <h1 class="h3 mb-3 fw-normal">Autorizācija</h1>
      <div class="form-floating">
        <input type="email" class="form-control" id="email" v-model="email" placeholder="E-pasta adrese" required />
        <label for="email" class="fw-normal">E-pasta adrese</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="password" v-model="password" placeholder="Parole" required />
        <label for="password" class="fw-normal">Parole</label>
      </div>
      <button type="submit" class="btn btn-primary w-100 py-2 mt-5">Ienākt</button>
    </form>
  </div>
</template>

<style scoped>
  .main-frame {
    max-width: 330px;
    padding: 1rem;
  }
</style>