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

          // commiting a mutation to update 'authorized' state
          this.$store.commit('LOGIN');

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
  <div>
    <form @submit.prevent="login">
      <input type="email" v-model="email" placeholder="E-pasta adrese" required />
      <input type="password" v-model="password" placeholder="Parole" required />
      <button type="submit" class="btn-outline-primary">Ienākt</button>
    </form>
  </div>
</template>

<style scoped>

</style>