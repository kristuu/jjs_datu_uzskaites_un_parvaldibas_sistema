<script>
import { mapState, mapActions } from "vuex";

export default {
  computed: {
    // using Vuex helpers to map 'authorized' state to a computed property
    ...mapState(['authorized'])
  },
  methods: {
    // Vuex helper usage to map 'logout' action to a method
    ...mapActions(['logout']),
  }
}
</script>

<template>
  <nav class="navbar navbar-expand-lg fixed-top navbar-light yrs-bg-primary" aria-label="Galvenā navigācija">
    <div class="container bg-white rounded-3">
      <a class="navbar-brand" href="/"><img src="../assets/logo.svg" height="45" /></a>
      <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-label="Navigācijas sadaļas atvēršana/aizvēršana">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse collapse" id="navbarMain">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <router-link class="nav-link" aria-current="page" to="/">Sākums</router-link>
          </li>
          <li class="nav-item" v-if="authorized">
            <router-link class="nav-link" to="/users">Lietotāji</router-link>
          </li>
        </ul>
        <ul class="navbar-nav mb-2 lb-lg-0">
          <li class="nav-item">
            <router-link v-if="!authorized" class="nav-link" to="/login">Autorizēties</router-link>
          </li>
          <li class="nav-item">
            <router-link v-if="!authorized" class="nav-link" to="/register">Reģistrēties</router-link>
          </li>
          <span class="nav-item" @click="logout" v-if="authorized">Beigt sesiju</span>
        </ul>
      </div>
    </div>
  </nav>
</template>

<style scoped>

</style>